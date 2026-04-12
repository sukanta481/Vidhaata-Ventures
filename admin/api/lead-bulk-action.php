<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
    exit;
}

$action  = $_POST['action']  ?? '';
$ids_raw = $_POST['ids']     ?? '[]';
$ids     = json_decode($ids_raw, true);

if (!is_array($ids) || empty($ids)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'No lead IDs provided.']);
    exit;
}

$ids          = array_map('intval', $ids);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$valid_statuses = ['new', 'contacted', 'qualified', 'site_visit', 'negotiation', 'closed'];

try {
    // ── UPDATE STATUS ──────────────────────────────────────────────────────────
    if ($action === 'update_status') {
        $new_status  = strtolower(trim($_POST['status'] ?? ''));
        $notes       = trim($_POST['notes'] ?? '');
        $listing_id  = !empty($_POST['listing_id']) ? intval($_POST['listing_id']) : null;
        $followup_at = trim($_POST['followup_at'] ?? '');

        if (!in_array($new_status, $valid_statuses, true)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Invalid status: ' . $new_status]);
            exit;
        }

        // Validate follow-up datetime format if provided
        $followup_value = null;
        if (!empty($followup_at)) {
            $dt = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $followup_at);
            if ($dt !== false) {
                $followup_value = $dt->format('Y-m-d H:i:s');
            }
        }
        // Default to NOW() if no valid follow-up provided
        if ($followup_value === null) {
            $followup_value = date('Y-m-d H:i:s');
        }

        // Fetch current statuses for each lead (to record from_status)
        $cur_stmt = $pdo->prepare("SELECT id, status FROM leads WHERE id IN ({$placeholders})");
        $cur_stmt->execute($ids);
        $current_leads = $cur_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Update leads table with status and follow-up date
        $upd_stmt = $pdo->prepare("UPDATE leads SET status = ?, followup_at = ? WHERE id IN ({$placeholders})");
        $upd_stmt->execute(array_merge([$new_status, $followup_value], $ids));

        // Build activity note — for site_visit, prepend property name if given
        $activity_note = $notes ?: null;
        $visited_title = null;
        if ($new_status === 'site_visit' && $listing_id) {
            try {
                $ls = $pdo->prepare("SELECT title, location FROM listings WHERE id = ?");
                $ls->execute([$listing_id]);
                $lrow = $ls->fetch(PDO::FETCH_ASSOC);
                if ($lrow) {
                    $visited_title = $lrow['title'] . ($lrow['location'] ? ' — ' . $lrow['location'] : '');
                    $activity_note = 'Visited: ' . $visited_title . ($notes ? '. ' . $notes : '');
                }
            } catch (PDOException $ignored) {}
        }

        // Log activity for each lead
        $act_stmt = $pdo->prepare(
            "INSERT INTO lead_activities (lead_id, action_type, from_status, to_status, notes, listing_id, created_at)
             VALUES (:lead_id, 'status_change', :from_status, :to_status, :notes, :listing_id, NOW())"
        );

        foreach ($current_leads as $lead) {
            if ($lead['status'] !== $new_status) {
                $act_stmt->execute([
                    ':lead_id'     => $lead['id'],
                    ':from_status' => $lead['status'],
                    ':to_status'   => $new_status,
                    ':notes'       => $activity_note,
                    ':listing_id'  => ($new_status === 'site_visit') ? $listing_id : null,
                ]);
            }
        }

        echo json_encode(['success' => true, 'visited_title' => $visited_title]);
        exit;
    }

    // ── ADD NOTE ──────────────────────────────────────────────────────────────
    if ($action === 'add_note') {
        $notes = trim($_POST['notes'] ?? '');
        if (empty($notes)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Note cannot be empty.']);
            exit;
        }

        $act_stmt = $pdo->prepare(
            "INSERT INTO lead_activities (lead_id, action_type, from_status, to_status, notes, created_at)
             VALUES (:lead_id, 'note', NULL, NULL, :notes, NOW())"
        );
        foreach ($ids as $lid) {
            $act_stmt->execute([':lead_id' => $lid, ':notes' => $notes]);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    // ── PROPOSE PROPERTY ──────────────────────────────────────────────────────
    if ($action === 'propose_property') {
        $listing_id = intval($_POST['listing_id'] ?? 0);
        $notes      = trim($_POST['notes'] ?? '');

        if ($listing_id <= 0) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'No property selected.']);
            exit;
        }

        // Get property title for the activity log
        $ls = $pdo->prepare("SELECT title, location, bedrooms, price FROM listings WHERE id = ?");
        $ls->execute([$listing_id]);
        $listing = $ls->fetch(PDO::FETCH_ASSOC);
        if (!$listing) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Property not found.']);
            exit;
        }
        $prop_label = $listing['title'] . ($listing['location'] ? ' — ' . $listing['location'] : '');

        $prop_stmt = $pdo->prepare(
            "INSERT INTO lead_proposals (lead_id, listing_id, notes, proposed_at) VALUES (:lead_id, :listing_id, :notes, NOW())"
        );
        $act_stmt = $pdo->prepare(
            "INSERT INTO lead_activities (lead_id, action_type, from_status, to_status, notes, listing_id, created_at)
             VALUES (:lead_id, 'proposal', NULL, NULL, :notes, :listing_id, NOW())"
        );

        foreach ($ids as $lid) {
            // Avoid re-proposing the same property
            $exists = $pdo->prepare("SELECT id FROM lead_proposals WHERE lead_id=? AND listing_id=?");
            $exists->execute([$lid, $listing_id]);
            if ($exists->fetch()) continue;

            $prop_stmt->execute([':lead_id' => $lid, ':listing_id' => $listing_id, ':notes' => $notes ?: null]);
            $act_stmt->execute([
                ':lead_id'    => $lid,
                ':notes'      => 'Proposed: ' . $prop_label . ($notes ? '. ' . $notes : ''),
                ':listing_id' => $listing_id,
            ]);
        }

        echo json_encode(['success' => true, 'listing' => $listing, 'prop_label' => $prop_label]);
        exit;
    }

    // ── DELETE ────────────────────────────────────────────────────────────────
    if ($action === 'delete') {
        // Delete activities + proposals first
        $pdo->prepare("DELETE FROM lead_activities WHERE lead_id IN ({$placeholders})")->execute($ids);
        $pdo->prepare("DELETE FROM lead_proposals   WHERE lead_id IN ({$placeholders})")->execute($ids);
        $pdo->prepare("DELETE FROM leads             WHERE id IN ({$placeholders})")->execute($ids);
        echo json_encode(['success' => true]);
        exit;
    }

    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

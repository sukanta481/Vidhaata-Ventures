<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$lead_id = intval($_GET['lead_id'] ?? 0);
if ($lead_id <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid lead ID.']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "SELECT lp.id, lp.listing_id, lp.notes, lp.proposed_at,
                l.title, l.location, l.bedrooms, l.price, l.type, l.status as listing_status
         FROM lead_proposals lp
         LEFT JOIN listings l ON l.id = lp.listing_id
         WHERE lp.lead_id = ?
         ORDER BY lp.proposed_at DESC"
    );
    $stmt->execute([$lead_id]);
    $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'proposals' => $proposals]);
} catch (PDOException $e) {
    echo json_encode(['success' => true, 'proposals' => []]);
}

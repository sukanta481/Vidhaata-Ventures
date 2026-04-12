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

$name               = trim($_POST['name']               ?? '');
$phone              = trim($_POST['phone']              ?? '');
$email              = trim($_POST['email']              ?? '');
$message            = trim($_POST['message']            ?? '');
$source_page        = trim($_POST['source_page']        ?? '');
$whatsapp_available = (isset($_POST['whatsapp_available']) && $_POST['whatsapp_available'] == 1) ? 1 : 0;
$lead_type          = trim($_POST['lead_type']          ?? 'buyer');
$budget_min         = !empty($_POST['budget_min'])  ? intval($_POST['budget_min'])  : null;
$budget_max         = !empty($_POST['budget_max'])  ? intval($_POST['budget_max'])  : null;
$preferred_locations= trim($_POST['preferred_locations'] ?? '');
$cfg_raw            = $_POST['configuration'] ?? [];
$configuration      = is_array($cfg_raw) ? implode(', ', array_map('trim', $cfg_raw)) : trim($cfg_raw);

if (empty($name) || empty($phone)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Name and Phone are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO leads (name, phone, email, message, source_page, status, whatsapp_available, lead_type, budget_min, budget_max, preferred_locations, configuration)
         VALUES (:name, :phone, :email, :message, :source_page, 'new', :whatsapp_available, :lead_type, :budget_min, :budget_max, :preferred_locations, :configuration)"
    );
    $stmt->execute([
        ':name'               => $name,
        ':phone'              => $phone,
        ':email'              => $email,
        ':message'            => $message,
        ':source_page'        => $source_page,
        ':whatsapp_available' => $whatsapp_available,
        ':lead_type'          => $lead_type,
        ':budget_min'         => $budget_min,
        ':budget_max'         => $budget_max,
        ':preferred_locations'=> $preferred_locations,
        ':configuration'      => $configuration,
    ]);

    $new_lead_id = $pdo->lastInsertId();

    // Seed initial activity in timeline
    try {
        $act = $pdo->prepare(
            "INSERT INTO lead_activities (lead_id, action_type, from_status, to_status, notes, created_at)
             VALUES (:lead_id, 'created', NULL, 'new', :notes, NOW())"
        );
        $act->execute([
            ':lead_id' => $new_lead_id,
            ':notes'   => $message ?: null,
        ]);
    } catch (PDOException $ignored) {
        // Table may not exist yet — silently ignore
    }

    echo json_encode(['success' => true, 'lead_id' => $new_lead_id]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

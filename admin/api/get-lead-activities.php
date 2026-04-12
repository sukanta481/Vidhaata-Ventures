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
        "SELECT id, action_type, from_status, to_status, notes, created_at
         FROM lead_activities
         WHERE lead_id = ?
         ORDER BY created_at DESC
         LIMIT 50"
    );
    $stmt->execute([$lead_id]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'activities' => $activities]);
} catch (PDOException $e) {
    // If table doesn't exist yet, return empty gracefully
    http_response_code(200);
    echo json_encode(['success' => true, 'activities' => []]);
}

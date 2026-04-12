<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

try {
    $stmt = $pdo->query(
        "SELECT id, title, location, bedrooms, price, type, status
         FROM listings
         WHERE status = 'active'
         ORDER BY title ASC
         LIMIT 200"
    );
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format price
    foreach ($listings as &$l) {
        $l['price_fmt'] = $l['price'] ? '₹' . number_format($l['price']) : 'Price on Request';
        $l['label']     = $l['title'] . ($l['location'] ? ' — ' . $l['location'] : '') . ($l['bedrooms'] ? ' · ' . $l['bedrooms'] . ' BHK' : '');
    }

    echo json_encode(['success' => true, 'listings' => $listings]);
} catch (PDOException $e) {
    echo json_encode(['success' => true, 'listings' => []]);
}

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

try {
    $id = !empty($_POST['id']) ? intval($_POST['id']) : null;

    if (!$id) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Listing ID is required.']);
        exit;
    }

    // Check if listing exists
    $check_stmt = $pdo->prepare('SELECT id, title, image_filename FROM listings WHERE id = :id');
    $check_stmt->execute([':id' => $id]);
    $listing = $check_stmt->fetch();

    if (!$listing) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Listing not found.']);
        exit;
    }

    // Delete associated images from filesystem
    if (!empty($listing['image_filename'])) {
        $images = array_filter(array_map('trim', explode(',', $listing['image_filename'])));
        foreach ($images as $img) {
            $image_path = __DIR__ . '/../../assets/images/' . $img;
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
        }
    }

    // Delete the listing
    $delete_stmt = $pdo->prepare('DELETE FROM listings WHERE id = :id');
    $delete_stmt->execute([':id' => $id]);

    echo json_encode(['success' => true, 'message' => 'Listing deleted successfully.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
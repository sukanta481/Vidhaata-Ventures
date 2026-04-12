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
    $id          = !empty($_POST['id']) ? intval($_POST['id']) : null;
    $title       = trim($_POST['title'] ?? '');
    $type        = $_POST['type'] ?? 'residential';
    $sub_type    = $_POST['sub_type'] ?? null;
    $listing_purpose = $_POST['listing_purpose'] ?? 'sale';
    $description = trim($_POST['description'] ?? '');
    $price       = !empty($_POST['price']) ? floatval($_POST['price']) : null;
    $location    = trim($_POST['location'] ?? '');
    $bedrooms    = !empty($_POST['bedrooms']) ? intval($_POST['bedrooms']) : null;
    $area_sqft   = !empty($_POST['area_sqft']) ? intval($_POST['area_sqft']) : null;
    $status      = $_POST['status'] ?? 'active';
    $possession_status = $_POST['possession_status'] ?? null;
    $furnishing  = $_POST['furnishing'] ?? null;
    $parking     = $_POST['parking'] ?? null;
    $rera_id     = trim($_POST['rera_id'] ?? '');
    $is_rera     = !empty($_POST['is_rera']) ? 1 : 0;
    $amenities   = !empty($_POST['amenities']) && is_array($_POST['amenities']) ? implode(',', $_POST['amenities']) : null;

    if (empty($title)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Title is required.']);
        exit;
    }

    // Handle image upload
    $image_filename = null;
    if (!empty($_FILES['gallery']['name'][0])) {
        $upload_dir = __DIR__ . '/../../assets/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $ext = pathinfo($_FILES['gallery']['name'][0], PATHINFO_EXTENSION);
        $image_filename = 'uploads/' . uniqid('listing_') . '.' . $ext;
        $dest = __DIR__ . '/../../assets/images/' . $image_filename;
        if (!move_uploaded_file($_FILES['gallery']['tmp_name'][0], $dest)) {
            $image_filename = null; // fallback
        }
    }

    if ($id) {
        // UPDATE existing listing
        $sql = "UPDATE listings SET
            title = :title,
            type = :type,
            sub_type = :sub_type,
            listing_purpose = :listing_purpose,
            description = :description,
            price = :price,
            location = :location,
            bedrooms = :bedrooms,
            area_sqft = :area_sqft,
            status = :status,
            possession_status = :possession_status,
            furnishing = :furnishing,
            parking = :parking,
            rera_id = :rera_id,
            is_rera = :is_rera,
            amenities = :amenities
        ";
        if ($image_filename) {
            $sql .= ", image_filename = :image_filename";
        }
        $sql .= " WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':title'             => $title,
            ':type'              => $type,
            ':sub_type'          => $sub_type,
            ':listing_purpose'   => $listing_purpose,
            ':description'       => $description,
            ':price'             => $price,
            ':location'          => $location,
            ':bedrooms'          => $bedrooms,
            ':area_sqft'         => $area_sqft,
            ':status'            => $status,
            ':possession_status' => $possession_status,
            ':furnishing'        => $furnishing,
            ':parking'           => $parking,
            ':rera_id'           => $rera_id,
            ':is_rera'           => $is_rera,
            ':amenities'         => $amenities,
            ':id'                => $id,
        ];
        if ($image_filename) {
            $params[':image_filename'] = $image_filename;
        }
        $stmt->execute($params);
    } else {
        // INSERT new listing
        $sql = "INSERT INTO listings (
            title, type, sub_type, listing_purpose, description, price, location,
            bedrooms, area_sqft, status, possession_status, furnishing, parking,
            rera_id, is_rera, amenities, image_filename
        ) VALUES (
            :title, :type, :sub_type, :listing_purpose, :description, :price, :location,
            :bedrooms, :area_sqft, :status, :possession_status, :furnishing, :parking,
            :rera_id, :is_rera, :amenities, :image_filename
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title'             => $title,
            ':type'              => $type,
            ':sub_type'          => $sub_type,
            ':listing_purpose'   => $listing_purpose,
            ':description'       => $description,
            ':price'             => $price,
            ':location'          => $location,
            ':bedrooms'          => $bedrooms,
            ':area_sqft'         => $area_sqft,
            ':status'            => $status,
            ':possession_status' => $possession_status,
            ':furnishing'        => $furnishing,
            ':parking'           => $parking,
            ':rera_id'           => $rera_id,
            ':is_rera'           => $is_rera,
            ':amenities'         => $amenities,
            ':image_filename'    => $image_filename,
        ]);
        $id = $pdo->lastInsertId();
    }

    echo json_encode(['success' => true, 'id' => $id]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
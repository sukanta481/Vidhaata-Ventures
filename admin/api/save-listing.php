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
    $id             = !empty($_POST['id']) ? intval($_POST['id']) : null;
    $title          = trim($_POST['title'] ?? '');
    $type           = $_POST['type'] ?? 'residential';
    $sub_type       = $_POST['sub_type'] ?? null;
    $listing_purpose = $_POST['listing_purpose'] ?? 'sale';
    $transaction    = trim($_POST['transaction'] ?? '');
    $description    = trim($_POST['description'] ?? '');
    $price          = !empty($_POST['price']) ? floatval($_POST['price']) : null;
    $location       = trim($_POST['location'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $pincode        = trim($_POST['pincode'] ?? '');
    $bedrooms       = !empty($_POST['bedrooms']) ? intval($_POST['bedrooms']) : null;
    $bathrooms      = !empty($_POST['bathrooms']) ? intval($_POST['bathrooms']) : null;
    $balconies      = !empty($_POST['balconies']) ? intval($_POST['balconies']) : null;
    $floor_number   = !empty($_POST['floor_number']) ? intval($_POST['floor_number']) : null;
    $total_floors   = !empty($_POST['total_floors']) ? intval($_POST['total_floors']) : null;
    $area_sqft      = !empty($_POST['area_sqft']) ? floatval($_POST['area_sqft']) : null;
    $carpet_area    = !empty($_POST['carpet_area']) ? floatval($_POST['carpet_area']) : null;
    $status         = $_POST['status'] ?? 'active';
    $possession_status = $_POST['possession_status'] ?? null;
    $furnishing     = $_POST['furnishing'] ?? null;
    $parking        = $_POST['parking'] ?? null;
    $rera_id        = trim($_POST['rera_id'] ?? '');
    $is_rera        = !empty($_POST['is_rera']) ? 1 : 0;
    $is_negotiable  = !empty($_POST['is_negotiable']) ? 1 : 0;
    $amenities      = !empty($_POST['amenities']) && is_array($_POST['amenities']) ? implode(',', $_POST['amenities']) : null;
    $video_url      = trim($_POST['video_url'] ?? '');

    // Residential: Building type & society fields
    $building_type      = $_POST['building_type'] ?? null;
    $society_name       = trim($_POST['society_name'] ?? '');
    $society_scale      = $_POST['society_scale'] ?? null;
    $society_amenities  = !empty($_POST['society_amenities']) && is_array($_POST['society_amenities']) ? json_encode($_POST['society_amenities']) : null;
    $water_source       = $_POST['water_source'] ?? null;

    // Residential: House/Villa
    $plot_area          = !empty($_POST['plot_area']) ? floatval($_POST['plot_area']) : null;
    $plot_area_unit     = $_POST['plot_area_unit'] ?? null;
    $private_garden     = !empty($_POST['private_garden']) ? 1 : 0;
    $terrace            = !empty($_POST['terrace']) ? 1 : 0;

    // Residential: Rent
    $monthly_rent       = !empty($_POST['monthly_rent']) ? floatval($_POST['monthly_rent']) : null;
    $security_deposit   = !empty($_POST['security_deposit']) ? floatval($_POST['security_deposit']) : null;
    $tenant_preference  = $_POST['tenant_preference'] ?? null;

    // Commercial: Washrooms & amenities
    $washrooms_type     = $_POST['washrooms_type'] ?? null;
    $central_ac         = !empty($_POST['central_ac']) ? 1 : 0;
    $dg_power_backup    = !empty($_POST['dg_power_backup']) ? 1 : 0;
    $cafeteria_pantry   = !empty($_POST['cafeteria_pantry']) ? 1 : 0;
    $visitor_parking    = !empty($_POST['visitor_parking']) ? 1 : 0;

    // Commercial: Pre-leased
    $pre_leased             = !empty($_POST['pre_leased']) ? 1 : 0;
    $current_tenant         = trim($_POST['current_tenant'] ?? '');
    $monthly_rent_received  = !empty($_POST['monthly_rent_received']) ? floatval($_POST['monthly_rent_received']) : null;
    $lease_expiry_date      = !empty($_POST['lease_expiry_date']) ? $_POST['lease_expiry_date'] : null;

    // Commercial: Rent
    $lockin_period_months   = !empty($_POST['lockin_period_months']) ? intval($_POST['lockin_period_months']) : null;
    $revenue_share_model    = !empty($_POST['revenue_share_model']) ? 1 : 0;

    // Land/Plot
    $area_unit      = $_POST['area_unit'] ?? null;
    $plot_length    = !empty($_POST['plot_length']) ? floatval($_POST['plot_length']) : null;
    $plot_width     = !empty($_POST['plot_width']) ? floatval($_POST['plot_width']) : null;
    $road_width     = !empty($_POST['road_width']) ? floatval($_POST['road_width']) : null;
    $boundary_wall  = !empty($_POST['boundary_wall']) ? 1 : 0;
    $corner_plot    = !empty($_POST['corner_plot']) ? 1 : 0;
    $na_approved    = !empty($_POST['na_approved']) ? 1 : 0;
    $na_type        = $_POST['na_type'] ?? null;
    $area_unit_residential = $_POST['area_unit_residential'] ?? 'sqft';

    if (empty($title)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Title is required.']);
        exit;
    }

    // Map type back to original values for database
    $db_type = $type;
    if ($type === 'land_plot') {
        $db_type = 'plot';
    }

    // Discover current listings schema and persist only supported columns.
    $schema_stmt = $pdo->query('SHOW COLUMNS FROM listings');
    $schema_rows = $schema_stmt->fetchAll(PDO::FETCH_ASSOC);
    $existing_columns = [];
    $column_meta = [];
    foreach ($schema_rows as $row) {
        $col = (string)($row['Field'] ?? '');
        if ($col === '') {
            continue;
        }
        $existing_columns[] = $col;
        $column_meta[$col] = (string)($row['Type'] ?? '');
    }
    $existing_set = array_flip($existing_columns);

    // Keep legacy schema compatible when type enum does not include plot.
    if ($db_type === 'plot' && isset($column_meta['type']) && stripos($column_meta['type'], 'plot') === false) {
        $db_type = 'residential';
    }

    $filter_by_schema = function (array $fields) use ($existing_set): array {
        $safe = [];
        foreach ($fields as $field => $value) {
            if (isset($existing_set[$field])) {
                $safe[$field] = $value;
            }
        }
        return $safe;
    };

    // Debug: Log the ID being received
    error_log("Save listing - ID received: " . ($id ?? 'null') . ", Type: " . gettype($_POST['id'] ?? null));

    // Handle multiple image uploads
    $image_filename = null;
    $new_images = [];
    $upload_errors = [];
    
    if (!empty($_FILES['gallery']['name']) && is_array($_FILES['gallery']['name'])) {
        $upload_dir = __DIR__ . '/../../assets/images/uploads/';
        
        // Ensure upload directory exists and is writable
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                $upload_errors[] = "Failed to create upload directory: $upload_dir";
            }
        }
        
        // Check if directory is writable
        if (is_dir($upload_dir) && !is_writable($upload_dir)) {
            $upload_errors[] = "Upload directory is not writable: $upload_dir";
        }

        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $total_files = count($_FILES['gallery']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            $original_name = $_FILES['gallery']['name'][$i] ?? '';
            $tmp_name = $_FILES['gallery']['tmp_name'][$i] ?? '';
            $error_code = $_FILES['gallery']['error'][$i] ?? UPLOAD_ERR_NO_FILE;

            // Check for upload errors
            if ($error_code !== UPLOAD_ERR_OK) {
                $error_messages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
                ];
                $upload_errors[] = "Upload error for '$original_name': " . ($error_messages[$error_code] ?? 'Unknown error');
                continue;
            }
            
            if (empty($original_name) || empty($tmp_name)) {
                $upload_errors[] = "Empty file name or temp name for file index $i";
                continue;
            }

            $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_ext, true)) {
                $upload_errors[] = "Invalid file type for '$original_name': .$ext (allowed: jpg, jpeg, png, webp, gif)";
                continue;
            }

            $relative_path = 'uploads/' . uniqid('listing_') . '.' . $ext;
            $dest = __DIR__ . '/../../assets/images/' . $relative_path;
            
            if (move_uploaded_file($tmp_name, $dest)) {
                $new_images[] = $relative_path;
            } else {
                $upload_errors[] = "Failed to move uploaded file '$original_name' to destination";
            }
        }
    }

    if (!empty($new_images)) {
        $image_filename = implode(',', $new_images);
    }
    
    // Store upload errors for debugging (only if there are errors)
    if (!empty($upload_errors)) {
        error_log("Image upload errors: " . implode('; ', $upload_errors));
    }

    if ($id) {
        // If editing and new photos were uploaded, append them to existing gallery.
        if (!empty($new_images)) {
            $existing_stmt = $pdo->prepare('SELECT image_filename FROM listings WHERE id = :id');
            $existing_stmt->execute([':id' => $id]);
            $existing_image_value = (string) ($existing_stmt->fetchColumn() ?: '');
            $existing_images = array_values(array_filter(array_map('trim', explode(',', $existing_image_value))));
            $image_filename = implode(',', array_merge($existing_images, $new_images));
        }

        // Build UPDATE query with all fields
        $fields = [
            'title' => $title,
            'type' => $db_type,
            'sub_type' => $sub_type,
            'listing_purpose' => $listing_purpose,
            'transaction' => $transaction,
            'description' => $description,
            'price' => $price,
            'location' => $location,
            'city' => $city,
            'pincode' => $pincode,
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'balconies' => $balconies,
            'floor_number' => $floor_number,
            'total_floors' => $total_floors,
            'area_sqft' => $area_sqft,
            'carpet_area' => $carpet_area,
            'status' => $status,
            'possession_status' => $possession_status,
            'furnishing' => $furnishing,
            'parking' => $parking,
            'rera_id' => $rera_id,
            'is_rera' => $is_rera,
            'is_negotiable' => $is_negotiable,
            'amenities' => $amenities,
            'video_url' => $video_url,
            // Residential specific
            'building_type' => $building_type,
            'society_name' => $society_name,
            'society_scale' => $society_scale,
            'society_amenities' => $society_amenities,
            'water_source' => $water_source,
            'plot_area_sqft' => $plot_area,
            'area_unit' => !empty($area_unit) ? $area_unit : $plot_area_unit,
            'private_garden' => $private_garden,
            'terrace' => $terrace,
            'monthly_rent' => $monthly_rent,
            'security_deposit' => $security_deposit,
            'tenant_preference' => $tenant_preference,
            // Commercial specific
            'washrooms_type' => $washrooms_type,
            'central_ac' => $central_ac,
            'dg_power_backup' => $dg_power_backup,
            'cafeteria_pantry' => $cafeteria_pantry,
            'visitor_parking' => $visitor_parking,
            'pre_leased' => $pre_leased,
            'current_tenant' => $current_tenant,
            'monthly_rent_received' => $monthly_rent_received,
            'lease_expiry_date' => $lease_expiry_date,
            'lockin_period_months' => $lockin_period_months,
            'revenue_share_model' => $revenue_share_model,
            // Land specific
            'plot_length' => $plot_length,
            'plot_width' => $plot_width,
            'road_width' => $road_width,
            'boundary_wall' => $boundary_wall,
            'corner_plot' => $corner_plot,
            'na_approved' => $na_approved,
            'na_type' => $na_type,
            'area_unit_residential' => $area_unit_residential,
        ];

        $fields = $filter_by_schema($fields);

        $set_clauses = [];
        $params = [];
        foreach ($fields as $field => $value) {
            $set_clauses[] = "$field = :$field";
            $params[":$field"] = $value;
        }

        if ($image_filename && isset($existing_set['image_filename'])) {
            $set_clauses[] = "image_filename = :image_filename";
            $params[':image_filename'] = $image_filename;
        }

        if (empty($set_clauses)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No compatible listing columns were found in database schema.']);
            exit;
        }

        $sql = "UPDATE listings SET " . implode(', ', $set_clauses) . " WHERE id = :id";
        $params[':id'] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } else {
        // INSERT new listing
        $fields = [
            'title' => $title,
            'type' => $db_type,
            'sub_type' => $sub_type,
            'listing_purpose' => $listing_purpose,
            'transaction' => $transaction,
            'description' => $description,
            'price' => $price,
            'location' => $location,
            'city' => $city,
            'pincode' => $pincode,
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'balconies' => $balconies,
            'floor_number' => $floor_number,
            'total_floors' => $total_floors,
            'area_sqft' => $area_sqft,
            'carpet_area' => $carpet_area,
            'status' => $status,
            'possession_status' => $possession_status,
            'furnishing' => $furnishing,
            'parking' => $parking,
            'rera_id' => $rera_id,
            'is_rera' => $is_rera,
            'is_negotiable' => $is_negotiable,
            'amenities' => $amenities,
            'video_url' => $video_url,
            'image_filename' => $image_filename,
            // Residential specific
            'building_type' => $building_type,
            'society_name' => $society_name,
            'society_scale' => $society_scale,
            'society_amenities' => $society_amenities,
            'water_source' => $water_source,
            'plot_area_sqft' => $plot_area,
            'area_unit' => !empty($area_unit) ? $area_unit : $plot_area_unit,
            'private_garden' => $private_garden,
            'terrace' => $terrace,
            'monthly_rent' => $monthly_rent,
            'security_deposit' => $security_deposit,
            'tenant_preference' => $tenant_preference,
            // Commercial specific
            'washrooms_type' => $washrooms_type,
            'central_ac' => $central_ac,
            'dg_power_backup' => $dg_power_backup,
            'cafeteria_pantry' => $cafeteria_pantry,
            'visitor_parking' => $visitor_parking,
            'pre_leased' => $pre_leased,
            'current_tenant' => $current_tenant,
            'monthly_rent_received' => $monthly_rent_received,
            'lease_expiry_date' => $lease_expiry_date,
            'lockin_period_months' => $lockin_period_months,
            'revenue_share_model' => $revenue_share_model,
            // Land specific
            'plot_length' => $plot_length,
            'plot_width' => $plot_width,
            'road_width' => $road_width,
            'boundary_wall' => $boundary_wall,
            'corner_plot' => $corner_plot,
            'na_approved' => $na_approved,
            'na_type' => $na_type,
            'area_unit_residential' => $area_unit_residential,
        ];

        $fields = $filter_by_schema($fields);

        if (empty($fields)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No compatible listing columns were found in database schema.']);
            exit;
        }

        $columns = array_keys($fields);
        $placeholders = array_map(fn($col) => ":$col", $columns);

        $sql = "INSERT INTO listings (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($fields);
        $id = $pdo->lastInsertId();
    }

    $response = ['success' => true, 'id' => $id, 'uploaded_count' => count($new_images)];
    if (!empty($upload_errors)) {
        $response['upload_warnings'] = $upload_errors;
    }
    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
<?php
require_once __DIR__ . '/../admin/includes/auth-check.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listing Debug Diagnostics</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1a1a1a; color: #e0e0e0; }
        .section { background: #2a2a2a; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .ok { color: #4caf50; }
        .error { color: #f44336; }
        .warn { color: #ff9800; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #444; }
        th { background: #333; }
    </style>
</head>
<body>
    <h1>Listing Debug Diagnostics</h1>

    <?php
    // 1. Check upload directory
    echo '<div class="section"><h2>1. Upload Directory Check</h2>';
    $upload_dir = __DIR__ . '/../assets/images/uploads/';
    echo "<p>Path: <code>$upload_dir</code></p>";
    if (is_dir($upload_dir)) {
        echo '<p class="ok">✓ Directory exists</p>';
        if (is_writable($upload_dir)) {
            echo '<p class="ok">✓ Directory is writable</p>';
        } else {
            echo '<p class="error">✗ Directory is NOT writable</p>';
        }
    } else {
        echo '<p class="error">✗ Directory does NOT exist</p>';
        if (mkdir($upload_dir, 0755, true)) {
            echo '<p class="ok">✓ Directory created successfully</p>';
        } else {
            echo '<p class="error">✗ Failed to create directory</p>';
        }
    }
    echo '</div>';

    // 2. Check database columns
    echo '<div class="section"><h2>2. Database Schema Check</h2>';
    try {
        $stmt = $pdo->query('SHOW COLUMNS FROM listings');
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<table><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>';
        foreach ($columns as $col) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($col['Field']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Type']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Null']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Key']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Default'] ?? 'NULL') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } catch (Exception $e) {
        echo '<p class="error">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    echo '</div>';

    // 3. Check recent listings
    echo '<div class="section"><h2>3. Recent Listings (Last 10)</h2>';
    try {
        $stmt = $pdo->query('SELECT id, title, type, listing_purpose, image_filename, created_at FROM listings ORDER BY id DESC LIMIT 10');
        $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<table><tr><th>ID</th><th>Title</th><th>Type</th><th>Purpose</th><th>Images</th><th>Created</th></tr>';
        foreach ($listings as $l) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($l['id']) . '</td>';
            echo '<td>' . htmlspecialchars($l['title']) . '</td>';
            echo '<td>' . htmlspecialchars($l['type']) . '</td>';
            echo '<td>' . htmlspecialchars($l['listing_purpose']) . '</td>';
            echo '<td>' . htmlspecialchars($l['image_filename'] ?? 'none') . '</td>';
            echo '<td>' . htmlspecialchars($l['created_at'] ?? 'N/A') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } catch (Exception $e) {
        echo '<p class="error">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    echo '</div>';

    // 4. Test image upload
    echo '<div class="section"><h2>4. Test Image Upload</h2>';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
        $file = $_FILES['test_image'];
        echo '<p>File name: ' . htmlspecialchars($file['name']) . '</p>';
        echo '<p>Error code: ' . $file['error'] . '</p>';
        echo '<p>Temp path: ' . htmlspecialchars($file['tmp_name']) . '</p>';
        echo '<p>Size: ' . $file['size'] . ' bytes</p>';
        
        if ($file['error'] === UPLOAD_ERR_OK) {
            $dest = $upload_dir . 'test_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                echo '<p class="ok">✓ File uploaded successfully to: ' . htmlspecialchars($dest) . '</p>';
            } else {
                echo '<p class="error">✗ Failed to move uploaded file</p>';
            }
        } else {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'PHP extension stopped the upload'
            ];
            echo '<p class="error">Upload error: ' . ($errors[$file['error']] ?? 'Unknown') . '</p>';
        }
    }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="test_image" accept="image/*" required>
        <button type="submit">Test Upload</button>
    </form>
    </div>

    <div class="section">
        <h2>5. PHP Configuration</h2>
        <table>
            <tr><th>Setting</th><th>Value</th></tr>
            <tr><td>upload_max_filesize</td><td><?php echo ini_get('upload_max_filesize'); ?></td></tr>
            <tr><td>post_max_size</td><td><?php echo ini_get('post_max_size'); ?></td></tr>
            <tr><td>max_file_uploads</td><td><?php echo ini_get('max_file_uploads'); ?></td></tr>
            <tr><td>file_uploads</td><td><?php echo ini_get('file_uploads') ? 'On' : 'Off'; ?></td></tr>
            <tr><td>upload_tmp_dir</td><td><?php echo ini_get('upload_tmp_dir') ?: '(system default)'; ?></td></tr>
        </table>
    </div>
</body>
</html>
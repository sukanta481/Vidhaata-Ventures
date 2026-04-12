<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$stmt = $pdo->query("DESCRIBE listings");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

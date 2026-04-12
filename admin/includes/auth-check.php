<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: ' . (isset($_SERVER['SCRIPT_NAME']) && strpos($_SERVER['SCRIPT_NAME'], 'admin_v2') !== false ? '../admin_v2/login.php' : 'login.php'));
    exit;
}

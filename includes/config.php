<?php
// ─── Environment Detection ────────────────────────────────────────────────────
// Detect if running on live server (Hostinger) or local (XAMPP)
$is_live = isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vidhaata') !== false && strpos($_SERVER['HTTP_HOST'], 'localhost') === false;

// Site settings
define('SITE_NAME', 'Vidhaata Ventures');
define('SITE_TAGLINE', 'Space, Curated.');

if ($is_live) {
    // ── Live Server (Hostinger) ──────────────────────────────────────────────
    define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u286257250_vidhaataV');
    define('DB_USER', 'u286257250_Vidhaatav');
    define('DB_PASS', 'Sukanta@0050');
} else {
    // ── Local Server (XAMPP) ─────────────────────────────────────────────────
    define('SITE_URL', 'http://localhost/VidhaataV');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'vidhaata_ventures');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

// Admin credentials
define('ADMIN_USER', 'admin');
define('ADMIN_PASS_HASH', password_hash('changeme', PASSWORD_DEFAULT));

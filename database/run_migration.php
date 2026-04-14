<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "Running Dynamic Listing Fields Migration...\n\n";

$columns = [
    ['name' => 'project_name', 'sql' => 'ALTER TABLE listings ADD COLUMN project_name VARCHAR(255) DEFAULT NULL AFTER society_name'],
    ['name' => 'total_floors', 'sql' => 'ALTER TABLE listings ADD COLUMN total_floors INT DEFAULT NULL AFTER floor_number'],
    ['name' => 'building_type', 'sql' => 'ALTER TABLE listings ADD COLUMN building_type VARCHAR(50) DEFAULT NULL AFTER sub_type'],
    ['name' => 'plot_area_sqft', 'sql' => 'ALTER TABLE listings ADD COLUMN plot_area_sqft DECIMAL(12,2) DEFAULT NULL AFTER carpet_area'],
    ['name' => 'private_garden', 'sql' => 'ALTER TABLE listings ADD COLUMN private_garden TINYINT(1) DEFAULT 0 AFTER plot_area_sqft'],
    ['name' => 'terrace', 'sql' => 'ALTER TABLE listings ADD COLUMN terrace TINYINT(1) DEFAULT 0 AFTER private_garden'],
    ['name' => 'monthly_rent', 'sql' => 'ALTER TABLE listings ADD COLUMN monthly_rent DECIMAL(12,2) DEFAULT NULL AFTER price'],
    ['name' => 'security_deposit', 'sql' => 'ALTER TABLE listings ADD COLUMN security_deposit DECIMAL(12,2) DEFAULT NULL AFTER monthly_rent'],
    ['name' => 'tenant_preference', 'sql' => 'ALTER TABLE listings ADD COLUMN tenant_preference VARCHAR(50) DEFAULT NULL AFTER security_deposit'],
    ['name' => 'washrooms_type', 'sql' => 'ALTER TABLE listings ADD COLUMN washrooms_type VARCHAR(50) DEFAULT NULL AFTER furnishing'],
    ['name' => 'central_ac', 'sql' => 'ALTER TABLE listings ADD COLUMN central_ac TINYINT(1) DEFAULT 0 AFTER washrooms_type'],
    ['name' => 'dg_power_backup', 'sql' => 'ALTER TABLE listings ADD COLUMN dg_power_backup TINYINT(1) DEFAULT 0 AFTER central_ac'],
    ['name' => 'cafeteria_pantry', 'sql' => 'ALTER TABLE listings ADD COLUMN cafeteria_pantry TINYINT(1) DEFAULT 0 AFTER dg_power_backup'],
    ['name' => 'visitor_parking', 'sql' => 'ALTER TABLE listings ADD COLUMN visitor_parking TINYINT(1) DEFAULT 0 AFTER cafeteria_pantry'],
    ['name' => 'pre_leased', 'sql' => 'ALTER TABLE listings ADD COLUMN pre_leased TINYINT(1) DEFAULT 0 AFTER is_negotiable'],
    ['name' => 'current_tenant', 'sql' => 'ALTER TABLE listings ADD COLUMN current_tenant VARCHAR(255) DEFAULT NULL AFTER pre_leased'],
    ['name' => 'monthly_rent_received', 'sql' => 'ALTER TABLE listings ADD COLUMN monthly_rent_received DECIMAL(12,2) DEFAULT NULL AFTER current_tenant'],
    ['name' => 'lease_expiry_date', 'sql' => 'ALTER TABLE listings ADD COLUMN lease_expiry_date DATE DEFAULT NULL AFTER monthly_rent_received'],
    ['name' => 'lockin_period_months', 'sql' => 'ALTER TABLE listings ADD COLUMN lockin_period_months INT DEFAULT NULL AFTER lease_expiry_date'],
    ['name' => 'revenue_share_model', 'sql' => 'ALTER TABLE listings ADD COLUMN revenue_share_model TINYINT(1) DEFAULT 0 AFTER lockin_period_months'],
    ['name' => 'area_unit', 'sql' => 'ALTER TABLE listings ADD COLUMN area_unit VARCHAR(20) DEFAULT "sqft" AFTER area_sqft'],
    ['name' => 'plot_length', 'sql' => 'ALTER TABLE listings ADD COLUMN plot_length DECIMAL(10,2) DEFAULT NULL'],
    ['name' => 'plot_width', 'sql' => 'ALTER TABLE listings ADD COLUMN plot_width DECIMAL(10,2) DEFAULT NULL'],
    ['name' => 'road_width', 'sql' => 'ALTER TABLE listings MODIFY COLUMN road_width DECIMAL(10,2) DEFAULT NULL'],
    ['name' => 'boundary_wall', 'sql' => 'ALTER TABLE listings ADD COLUMN boundary_wall TINYINT(1) DEFAULT 0'],
    ['name' => 'corner_plot', 'sql' => 'ALTER TABLE listings ADD COLUMN corner_plot TINYINT(1) DEFAULT 0'],
    ['name' => 'na_approved', 'sql' => 'ALTER TABLE listings ADD COLUMN na_approved TINYINT(1) DEFAULT 0'],
    ['name' => 'na_type', 'sql' => 'ALTER TABLE listings ADD COLUMN na_type VARCHAR(50) DEFAULT NULL'],
    ['name' => 'society_amenities', 'sql' => 'ALTER TABLE listings ADD COLUMN society_amenities TEXT DEFAULT NULL'],
    ['name' => 'water_source', 'sql' => 'ALTER TABLE listings ADD COLUMN water_source VARCHAR(50) DEFAULT NULL'],
    ['name' => 'area_unit_residential', 'sql' => 'ALTER TABLE listings ADD COLUMN area_unit_residential VARCHAR(20) DEFAULT "sqft"'],
    ['name' => 'city', 'sql' => 'ALTER TABLE listings ADD COLUMN city VARCHAR(100) DEFAULT NULL'],
    ['name' => 'pincode', 'sql' => 'ALTER TABLE listings ADD COLUMN pincode VARCHAR(20) DEFAULT NULL'],
];

$pdo->exec('SET sql_mode = ""'); // Silence strict mode warnings for duplicate columns

$success = 0;
$skipped = 0;
$errors = 0;

foreach ($columns as $col) {
    try {
        // Check if column exists
        $check = $pdo->query("SHOW COLUMNS FROM listings LIKE '{$col['name']}'")->fetch();
        if ($check) {
            echo "[SKIP] {$col['name']} - already exists\n";
            $skipped++;
            continue;
        }
        $pdo->exec($col['sql']);
        echo "[OK] {$col['name']} - added\n";
        $success++;
    } catch (PDOException $e) {
        echo "[ERROR] {$col['name']} - " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n========================================\n";
echo "Migration Complete!\n";
echo "Added: $success | Skipped: $skipped | Errors: $errors\n";
echo "========================================\n";
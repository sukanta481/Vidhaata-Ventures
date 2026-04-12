<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

// ─── 1. Extend leads table with 5-stage pipeline support ─────────────────────
try {
    $pdo->exec("ALTER TABLE leads MODIFY COLUMN status ENUM('new','contacted','qualified','site_visit','negotiation','closed') NOT NULL DEFAULT 'new'");
    echo "✓ leads.status column updated to 5-stage enum<br>";
} catch (PDOException $e) {
    echo "⚠ leads.status: " . $e->getMessage() . "<br>";
}

// ─── 2. Create lead_activities table ─────────────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS lead_activities (
        id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        lead_id     INT UNSIGNED NOT NULL,
        action_type VARCHAR(50)  NOT NULL DEFAULT 'status_change',
        from_status VARCHAR(50)  DEFAULT NULL,
        to_status   VARCHAR(50)  DEFAULT NULL,
        notes       TEXT         DEFAULT NULL,
        created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_lead_id (lead_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ lead_activities table created<br>";
} catch (PDOException $e) {
    echo "✗ lead_activities: " . $e->getMessage() . "<br>";
}

// ─── 3. Seed existing leads with a 'created' activity if empty ───────────────
try {
    $leads = $pdo->query("SELECT id, status, created_at FROM leads")->fetchAll(PDO::FETCH_ASSOC);
    $insert = $pdo->prepare("INSERT IGNORE INTO lead_activities (lead_id, action_type, from_status, to_status, notes, created_at)
        VALUES (:lead_id, 'created', NULL, :status, 'Lead added to pipeline.', :created_at)");
    $seeded = 0;
    foreach ($leads as $l) {
        $existing = $pdo->prepare("SELECT id FROM lead_activities WHERE lead_id=? AND action_type='created'");
        $existing->execute([$l['id']]);
        if (!$existing->fetch()) {
            $insert->execute([':lead_id' => $l['id'], ':status' => $l['status'], ':created_at' => $l['created_at']]);
            $seeded++;
        }
    }
    echo "✓ Seeded $seeded existing leads with initial activity<br>";
} catch (PDOException $e) {
    echo "✗ Seed error: " . $e->getMessage() . "<br>";
}

echo "<br><strong>Done!</strong>";

// ─── 4. Create lead_proposals table (property proposal tracking) ───────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS lead_proposals (
        id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        lead_id     INT UNSIGNED NOT NULL,
        listing_id  INT UNSIGNED NOT NULL,
        notes       TEXT         DEFAULT NULL,
        proposed_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_lp_lead    (lead_id),
        INDEX idx_lp_listing (listing_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "<br>✓ lead_proposals table created";
} catch (PDOException $e) {
    echo "<br>⚠ lead_proposals: " . $e->getMessage();
}

// ─── 5. Add listing_id to lead_activities (for site_visit property tracking) ─
try {
    $pdo->exec("ALTER TABLE lead_activities ADD COLUMN listing_id INT UNSIGNED DEFAULT NULL");
    echo "<br>✓ lead_activities.listing_id column added";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<br>✓ lead_activities.listing_id already exists";
    } else {
        echo "<br>⚠ lead_activities.listing_id: " . $e->getMessage();
    }
}

echo "<br><br><strong>All migrations complete!</strong>";


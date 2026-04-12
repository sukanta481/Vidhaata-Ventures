<?php
require_once __DIR__ . '/../admin/includes/auth-check.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/admin-layout.php';

// ─── Helper ───────────────────────────────────────────────────────────────────
function count_rows(PDO $pdo, string $table, ?string $status = null): int {
  if ($status === null) {
    return (int) $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
  }
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE status = :status");
  $stmt->execute([':status' => $status]);
  return (int) $stmt->fetchColumn();
}

// ─── Lead Metrics ─────────────────────────────────────────────────────────────
$total_leads   = count_rows($pdo, 'leads');
$new_leads     = count_rows($pdo, 'leads', 'new');
$deals_active  = (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE status != 'closed'")->fetchColumn();

// New leads trend: compare current 7-day window vs previous 7-day window
$current_week  = (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'new' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();
$previous_week = (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'new' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();
if ($previous_week > 0) {
  $new_leads_trend = round((($current_week - $previous_week) / $previous_week) * 100);
  $new_leads_trend = ($new_leads_trend >= 0 ? '+' : '') . $new_leads_trend . '%';
} else {
  $new_leads_trend = '+0%';
}

// Today's follow-ups: leads where DATE(followup_at) = CURDATE()
$today_followups = (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE DATE(followup_at) = CURDATE()")->fetchColumn();

// Overdue follow-ups: followup_at < CURDATE() AND status != 'closed'
$overdue_followups = (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE DATE(followup_at) < CURDATE() AND status != 'closed' AND followup_at IS NOT NULL")->fetchColumn();

// Upcoming site visits: status = 'site_visit' AND followup_at >= CURDATE()
$upcoming_visits = (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'site_visit' AND DATE(followup_at) >= CURDATE()")->fetchColumn();

// Next site visit time: nearest site_visit with followup_at >= NOW()
$next_visit_stmt = $pdo->query("SELECT followup_at FROM leads WHERE status = 'site_visit' AND followup_at >= NOW() ORDER BY followup_at ASC LIMIT 1");
$next_visit_row  = $next_visit_stmt->fetch(PDO::FETCH_ASSOC);
$next_visit_time = $next_visit_row ? date('g:i A', strtotime($next_visit_row['followup_at'])) : '--';

// ─── Listing Metrics ──────────────────────────────────────────────────────────
$active_listings = count_rows($pdo, 'listings', 'active');
$sold_listings   = count_rows($pdo, 'listings', 'sold');
$inactive_listings = count_rows($pdo, 'listings', 'inactive');
$total_listings  = count_rows($pdo, 'listings');

// SVG ring percentages
$active_pct  = $total_listings > 0 ? round(($active_listings / $total_listings) * 100) : 0;
$sold_pct    = $total_listings > 0 ? round(($sold_listings / $total_listings) * 100) : 0;
$inactive_pct = $total_listings > 0 ? round(($inactive_listings / $total_listings) * 100) : 0;

// ─── Compliance ───────────────────────────────────────────────────────────────
$missing_rera = (int) $pdo->query("SELECT COUNT(*) FROM listings WHERE status = 'active' AND (is_rera = 0 OR rera_id IS NULL OR rera_id = '')")->fetchColumn();
$expiring_soon = 0; // no document expiry column in schema
$critical_count = $missing_rera + $expiring_soon;

// ─── High-Demand Properties ───────────────────────────────────────────────────
$demand_stmt = $pdo->query("
  SELECT l.id, l.title,
    (SELECT COUNT(*) FROM lead_proposals lp WHERE lp.listing_id = l.id)
    + (SELECT COUNT(*) FROM lead_activities la WHERE la.listing_id = l.id AND la.action_type = 'status_change' AND la.to_status = 'site_visit')
    AS demand_count
  FROM listings l
  WHERE l.status = 'active'
  ORDER BY demand_count DESC, l.created_at DESC
  LIMIT 3
");
$high_demand = $demand_stmt->fetchAll(PDO::FETCH_ASSOC);

// If fewer than 3, fill with newest active listings
if (count($high_demand) < 3) {
  $existing_ids = array_column($high_demand, 'id');
  $placeholders = count($existing_ids) > 0 ? 'AND id NOT IN (' . implode(',', $existing_ids) . ')' : '';
  $fill_stmt = $pdo->query("SELECT id, title, 0 AS demand_count FROM listings WHERE status = 'active' {$placeholders} ORDER BY created_at DESC LIMIT " . (3 - count($high_demand)));
  $fill_rows = $fill_stmt->fetchAll(PDO::FETCH_ASSOC);
  $high_demand = array_merge($high_demand, $fill_rows);
}

// ─── Recent Leads ─────────────────────────────────────────────────────────────
$recent_leads = $pdo->query('SELECT id, name, phone, source_page, status, created_at FROM leads ORDER BY created_at DESC LIMIT 5')->fetchAll();

$active_page = 'dashboard';
$page_title = 'Dashboard';
admin_head($page_title);
?>

<?php admin_body_open(); ?>

<!-- Page Content -->
<div class="space-y-8">
  <!-- Performance Snapshot (Bento Layout) -->
  <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-surface-container-lowest p-6 rounded-xl relative overflow-hidden group border border-outline-variant/10">
      <div class="flex justify-between items-start mb-4">
        <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary">
          <span class="material-symbols-outlined" data-icon="group_add">group_add</span>
        </div>
        <span class="text-tertiary font-bold text-xs bg-tertiary-fixed-dim/20 px-2 py-1 rounded"><?php echo e($new_leads_trend); ?></span>
      </div>
      <h3 class="text-outline text-sm font-semibold font-headline">New Leads</h3>
      <p class="text-4xl font-extrabold font-headline text-on-surface mt-1"><?php echo e($new_leads); ?></p>
      <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined text-9xl" data-icon="trending_up">trending_up</span>
      </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border-l-4 border-error relative shadow-sm">
      <div class="flex justify-between items-start mb-4">
        <div class="w-10 h-10 bg-error/10 rounded-lg flex items-center justify-center text-error">
          <span class="material-symbols-outlined" data-icon="notification_important">notification_important</span>
        </div>
        <span class="font-bold text-xs bg-error-container text-error px-2 py-1 rounded"><?php echo e($overdue_followups); ?> OVERDUE</span>
      </div>
      <h3 class="text-outline text-sm font-semibold font-headline">Today's Follow-ups</h3>
      <p class="text-4xl font-extrabold font-headline text-on-surface mt-1"><?php echo e($today_followups); ?></p>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl group border border-outline-variant/10 shadow-sm">
      <div class="flex justify-between items-start mb-4">
        <div class="w-10 h-10 bg-tertiary/10 rounded-lg flex items-center justify-center text-tertiary">
          <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
        </div>
        <span class="text-outline text-xs font-medium">Next: <?php echo e($next_visit_time); ?></span>
      </div>
      <h3 class="text-outline text-sm font-semibold font-headline">Upcoming Site Visits</h3>
      <p class="text-4xl font-extrabold font-headline text-on-surface mt-1">0<?php echo e($upcoming_visits); ?></p>
    </div>
  </section>

  <!-- Property Metrics Row -->
  <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- 1. Live Inventory Status -->
    <div class="bg-surface-container-lowest p-6 rounded-xl flex items-center gap-6 border border-outline-variant/10 shadow-sm">
      <div class="relative w-24 h-24 flex-shrink-0">
        <svg class="w-full h-full -rotate-90 transform" viewbox="0 0 36 36">
          <circle class="stroke-surface-container" cx="18" cy="18" fill="none" r="16" stroke-width="4"></circle>
          <circle class="stroke-secondary" cx="18" cy="18" fill="none" r="16" stroke-dasharray="<?php echo e($active_pct); ?>, 100" stroke-width="4"></circle>
          <circle class="stroke-tertiary" cx="18" cy="18" fill="none" r="16" stroke-dasharray="<?php echo e($sold_pct); ?>, 100" stroke-dashoffset="-<?php echo e($active_pct); ?>" stroke-width="4"></circle>
          <circle class="stroke-error" cx="18" cy="18" fill="none" r="16" stroke-dasharray="<?php echo e($inactive_pct); ?>, 100" stroke-dashoffset="-<?php echo e($active_pct + $sold_pct); ?>" stroke-width="4"></circle>
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
          <span class="text-lg font-black font-headline leading-none"><?php echo e($total_listings); ?></span>
          <span class="text-[8px] uppercase font-bold text-outline">Total</span>
        </div>
      </div>
      <div class="flex-1 space-y-2">
        <h3 class="text-outline text-xs font-bold font-headline uppercase tracking-wider">Inventory Status</h3>
        <div class="grid grid-cols-1 gap-1">
          <div class="flex items-center justify-between text-[11px] font-bold">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-secondary"></span> Available</span>
            <span class="text-on-surface"><?php echo e($active_listings); ?></span>
          </div>
          <div class="flex items-center justify-between text-[11px] font-bold">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-error"></span> Sold/Rent</span>
            <span class="text-on-surface"><?php echo e($sold_listings); ?></span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 2. Compliance & Docs -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border-t-4 border-error flex flex-col justify-between shadow-sm">
      <div class="flex justify-between items-start">
        <div class="space-y-1">
          <h3 class="text-outline text-xs font-bold font-headline uppercase tracking-wider">Compliance &amp; Docs</h3>
          <p class="text-[10px] text-outline/60 font-medium">Last audit: Today, 10:45 AM</p>
        </div>
        <span class="bg-error text-white text-[10px] font-black px-2 py-1 rounded"><?php echo e($critical_count); ?> CRITICAL</span>
      </div>
      <div class="mt-4 grid grid-cols-2 gap-4">
        <div class="bg-error/5 p-3 rounded-lg border border-error/10">
          <p class="text-2xl font-black text-error leading-tight"><?php echo e($missing_rera); ?></p>
          <p class="text-[10px] font-bold text-error/70 uppercase">Missing RERA</p>
        </div>
        <div class="bg-surface-container p-3 rounded-lg border border-outline-variant/30">
          <p class="text-2xl font-black text-on-surface leading-tight"><?php echo e($expiring_soon); ?></p>
          <p class="text-[10px] font-bold text-outline uppercase">Expiring Soon</p>
        </div>
      </div>
    </div>
    
    <!-- 3. High-Demand Properties -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
      <h3 class="text-outline text-xs font-bold font-headline uppercase tracking-wider mb-4">High-Demand Properties</h3>
      <div class="space-y-3">
        <?php foreach ($high_demand as $idx => $list): ?>
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-1.5 h-1.5 rounded-full <?php echo $idx === 0 ? 'bg-primary-fixed-dim' : ($idx === 1 ? 'bg-secondary' : 'bg-tertiary'); ?>"></div>
            <span class="text-xs font-bold text-on-surface truncate w-32"><?php echo e($list['title']); ?></span>
          </div>
          <span class="text-[10px] font-black bg-surface-container-low text-secondary px-2 py-0.5 rounded"><?php echo e($list['demand_count']); ?> INQ</span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Sales Pipeline section replaced with Recent Leads for dynamic backend data -->
  <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
    <section class="xl:col-span-3 space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-extrabold font-headline tracking-tight flex items-center gap-3">
          Recent Leads Pipeline
          <span class="text-sm font-normal text-outline bg-surface-container px-3 py-1 rounded-full"><?php echo e($deals_active); ?> Deals Active</span>
        </h2>
        <a href="leads.php" class="text-secondary text-sm font-bold flex items-center gap-1 hover:underline">
          View Full Pipeline <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
        </a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($recent_leads as $lead): ?>
        <!-- Card -->
        <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border-t-2 border-secondary/20">
          <div class="flex justify-between items-start mb-3">
            <div>
              <h4 class="font-bold text-on-surface"><?php echo e($lead['name']); ?></h4>
              <p class="text-xs text-outline font-medium"><?php echo e($lead['phone']); ?></p>
              <div class="mt-2 flex items-center gap-1.5">
                <div class="flex items-center gap-1 px-1.5 py-0.5 rounded bg-tertiary-fixed-dim/10 border border-tertiary/20">
                  <span class="text-[10px] font-bold text-tertiary uppercase tracking-tight"><?php echo e(ucfirst($lead['status'])); ?></span>
                </div>
              </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-secondary font-bold text-xs uppercase">
              <?php echo e(substr($lead['name'], 0, 2)); ?>
            </div>
          </div>
          <div class="flex items-center justify-between mt-4 pt-4 border-t border-outline-variant/10">
            <div class="flex gap-2">
              <button class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-colors">
                <span class="material-symbols-outlined text-sm" data-icon="call">call</span>
              </button>
            </div>
            <span class="text-[10px] font-bold text-outline uppercase">Via <?php echo e($lead['source_page'] ?: 'Website'); ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Today's Focus (Right) -->
    <aside class="space-y-6">
      <h2 class="text-2xl font-extrabold font-headline tracking-tight">Today's Focus</h2>
      <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="p-4 bg-secondary/5 border-b border-outline-variant/10">
          <span class="text-xs font-black uppercase tracking-tighter text-secondary">High Priority Tasks</span>
        </div>
        <ul class="divide-y divide-outline-variant/10">
          <li class="p-5 hover:bg-surface-container-low transition-colors group cursor-pointer">
            <div class="flex gap-4">
              <div class="mt-1 w-5 h-5 rounded border-2 border-outline-variant flex items-center justify-center group-hover:border-secondary transition-colors"></div>
              <div>
                <p class="text-sm font-bold text-on-surface">Follow up with <?php echo e($overdue_followups); ?> overdue leads</p>
                <p class="text-[10px] text-error font-black mt-1 uppercase"><?php echo e($overdue_followups); ?> Overdue</p>
              </div>
            </div>
          </li>
          <li class="p-5 hover:bg-surface-container-low transition-colors group cursor-pointer">
            <div class="flex gap-4">
              <div class="mt-1 w-5 h-5 rounded border-2 border-outline-variant flex items-center justify-center group-hover:border-secondary transition-colors"></div>
              <div>
                <p class="text-sm font-bold text-on-surface">Update RERA compliance (<?php echo e($missing_rera); ?> missing)</p>
                <p class="text-[10px] text-tertiary font-black mt-1 uppercase"><?php echo e($critical_count); ?> Critical</p>
              </div>
            </div>
          </li>
        </ul>
        <button class="w-full py-4 text-center text-xs font-bold text-secondary bg-surface-container-low hover:bg-surface-container transition-colors uppercase tracking-widest">
          Add New Task
        </button>
      </div>
      
      <!-- Quick Stats Micro-Card -->
      <div class="bg-gradient-to-br from-[#455f88] to-[#121d1e] p-6 rounded-xl text-white relative overflow-hidden">
        <div class="relative z-10">
          <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">TOKENS COLLECTED</h4>
          <p class="text-3xl font-black font-headline mt-2">₹0</p>
          <div class="mt-4 flex items-center gap-2">
            <div class="flex-1 h-1.5 bg-white/20 rounded-full overflow-hidden">
              <div class="w-[0%] h-full bg-[#ff753d]"></div>
            </div>
            <span class="text-[10px] font-bold">No token data available</span>
          </div>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-white/10 text-8xl" data-icon="account_balance_wallet">account_balance_wallet</span>
      </div>
    </aside>
  </div>
</div>

<?php admin_footer(); ?>

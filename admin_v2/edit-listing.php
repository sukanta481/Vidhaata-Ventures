<?php
require_once __DIR__ . '/../admin/includes/auth-check.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/admin-layout.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: listings.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM listings WHERE id = :id');
$stmt->execute([':id' => $id]);
$listing = $stmt->fetch();

if (!$listing) {
    header('Location: listings.php');
    exit;
}

$active_page = 'listings';
$page_title = 'Edit Property';
admin_head($page_title);
?>
<?php admin_body_open(); ?>

<!-- Main Content Area -->
<section class="max-w-6xl mx-auto w-full space-y-8">
  <header class="mb-12">
    <a href="listings.php" class="inline-flex items-center gap-1 text-sm font-bold text-on-surface-variant transition-colors hover:text-primary mb-4">
      <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Listings
    </a>
    <h1 class="text-4xl font-headline font-extrabold text-on-background tracking-tight mb-2">Architectural Onboarding</h1>
    <p class="text-secondary font-body max-w-2xl">Update entry in the digital ledger. High-precision data entry ensures maximum visibility for your premium property listings.</p>
  </header>

  <?php require __DIR__ . '/includes/listing-form.php'; ?>
</section>

<!-- Map Component Placeholder for Location context -->
<div class="fixed right-12 top-32 w-48 h-48 rounded-2xl overflow-hidden shadow-2xl border-4 border-white hidden xl:block z-30 pointer-events-none">
  <div class="absolute inset-0 bg-surface-container-high animate-pulse"></div>
  <img class="w-full h-full object-cover" data-alt="minimalist map" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDELDQf2hksNNQVk-zbXZ7669MAM3jRkWN8GfYU0eN2imFCOkr4Br7aL-uZ98Np0MgZny2k_GT2wjyzVDKjSzYUwoNTMg4qgH4L04CDr-FqCf0Pa0RmG0KJTXSJ72ZiPY3q5coSkdWKqLgc4_QXTFPFG-n3UliYyDQxQu9PN6-U6hYrgTfBUZ7rt1RmC_CLrogkkqHRQ1fFxV0Kjw7EPXEu0zWoL9G21Hr4rJj8L3cQjrN434Uze3k5DMYiaS2WWitlBJ7jmPeKNZEK"/>
  <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
  <div class="absolute bottom-3 left-3 text-white text-[10px] font-bold uppercase tracking-widest">Neighborhood Context</div>
</div>

<?php admin_footer(); ?>

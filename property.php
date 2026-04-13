<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$listing = null;
if ($id) {
    if (isset($pdo)) {
        $stmt = $pdo->prepare('SELECT * FROM listings WHERE id = :id AND status != "deleted"');
        $stmt->execute([':id' => $id]);
        $listing = $stmt->fetch();
    }
}

// Fallback logic if property not found or ID missing
if (!$listing) {
    // If we just want to show dummy data for now
    $listing = [
        'title' => 'DTC Still Waters',
        'city' => 'Kolkata',
        'location' => 'Prime South Avenue, Waterfront District',
        'price' => 8800000,
        'image_filename' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDI7Xqe1qk5wXUbVQl3Zh36Uhj8-YWmmqWuGpI4KLaTTUxnl46dwI7Q4dfl7fKO73f-cXfm-mt5NOyyoffZc77-aV-QaS4kRNVEzOQvUJaCfdPaGNG_pt7kQwbtHUGNUkyCWE4o0AglsvRVNPjCctOUhljKt218CaOUd0zgRvcdZGwxXNyEbbZ0YS3the8Kz6kEH-Bd3y4dpb11t44lJusKEvK8DcOTgggrItVCKD0vAK8nzVm3rGB5YKp_WlJqOxAFU0BwotVQqRE',
        'is_rera' => 1,
        'bedrooms' => '2,3,4',
        'possession_status' => 'Dec 2026',
        'total_floors' => 'G+24',
        'area_sqft' => '1200 - 2400',
    ];
} else {
    // Formatting for display
    if ($listing['price'] >= 10000000) {
        $price_display = round($listing['price'] / 10000000, 2) . ' Cr';
    } else {
        $price_display = round($listing['price'] / 100000, 2) . ' Lakh';
    }
    $listing['price_formatted'] = '&#8377; ' . $price_display;
}

$page_title = $listing['title'] ?? 'Property Details';
require_once __DIR__ . '/includes/header.php';
?>

<main class="pt-24 pb-12 px-4 md:px-6 max-w-7xl mx-auto">
  <!-- Hero Section -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

    <!-- Left Column -->
    <div class="lg:col-span-7 space-y-6 md:space-y-8">

      <div class="relative group reveal">
        <div class="aspect-[3/2] overflow-hidden rounded-2xl md:rounded-[2rem] shadow-xl">
          <?php
            $imageFile = trim((string)($listing['image_filename'] ?? ''));
            if ($imageFile === '') {
              $heroImage = SITE_URL . '/assets/images/placeholder.jpg';
            } elseif (strpos($imageFile, 'http://') === 0 || strpos($imageFile, 'https://') === 0) {
              $heroImage = $imageFile;
            } elseif (strpos($imageFile, '/assets/images/') === 0) {
              $heroImage = SITE_URL . $imageFile;
            } elseif (strpos($imageFile, 'assets/images/') === 0) {
              $heroImage = SITE_URL . '/' . $imageFile;
            } elseif (strpos($imageFile, '/uploads/') === 0) {
              $heroImage = SITE_URL . '/assets/images/' . ltrim($imageFile, '/');
            } elseif (strpos($imageFile, 'uploads/') === 0) {
              $heroImage = SITE_URL . '/assets/images/' . $imageFile;
            } elseif (strpos($imageFile, '/') === false) {
              $heroImage = SITE_URL . '/assets/images/uploads/' . $imageFile;
            } else {
              $heroImage = SITE_URL . '/assets/images/' . ltrim($imageFile, '/');
            }
          ?>
          <img alt="<?php echo htmlspecialchars($listing['title']); ?>" class="w-full h-full object-cover" src="<?php echo htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8'); ?>"/>
        </div>
        <!-- Pagination Dots -->
        <div class="absolute bottom-4 md:bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-white shadow-md"></span>
          <span class="w-2.5 h-2.5 rounded-full bg-white/40 backdrop-blur-sm"></span>
          <span class="w-2.5 h-2.5 rounded-full bg-white/40 backdrop-blur-sm"></span>
        </div>
      </div>

      <!-- Branding & Title -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 reveal">
        <div class="space-y-2">
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold font-headline tracking-tighter text-primary"><?php echo htmlspecialchars($listing['title']); ?></h1>
          <?php if(!empty($listing['society_name'])): ?>
            <p class="text-on-tertiary-container italic font-medium">In <?php echo htmlspecialchars($listing['society_name']); ?></p>
          <?php endif; ?>
          <div class="flex items-center gap-2 text-on-surface-variant">
            <span class="material-symbols-outlined text-primary text-xl">location_on</span>
            <span class="text-sm font-medium tracking-tight"><?php echo htmlspecialchars($listing['location'] . ', ' . $listing['city']); ?></span>
          </div>
        </div>
        <div class="text-left md:text-right">
          <p class="text-xs text-outline uppercase tracking-widest font-bold">Price</p>
          <p class="text-2xl md:text-3xl font-extrabold text-primary font-headline"><?php echo $listing['price_formatted'] ?? '&#8377; ' . number_format((float)$listing['price']); ?></p>
        </div>
      </div>

      <!-- Tags & Action -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 md:gap-6 pt-2 md:pt-4 reveal">
        <div class="flex flex-wrap gap-2">
          <?php if(!empty($listing['is_rera'])): ?>
          <span class="px-3 md:px-4 py-1.5 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold uppercase tracking-wider">RERA</span>
          <?php endif; ?>
          <?php if(!empty($listing['is_featured'])): ?>
          <span class="px-3 md:px-4 py-1.5 rounded-full bg-[#f3e8ff] text-[#6b21a8] text-xs font-bold uppercase tracking-wider">Featured</span>
          <?php endif; ?>
        </div>
        <button class="flex items-center justify-center gap-2 bg-on-tertiary-container text-white px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold shadow-lg hover:shadow-on-tertiary-container/20 transition-all hover:-translate-y-0.5 active:translate-y-0 w-full sm:w-auto">
          <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">picture_as_pdf</span>
          Download Brochure
        </button>
      </div>

      <!-- Specifications Grid -->
      <div class="bg-surface-container-low rounded-2xl md:rounded-[2rem] p-6 md:p-8 lg:p-10 reveal">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-y-8 md:gap-y-10 gap-x-6 md:gap-x-8">
          <div class="flex items-start gap-3 md:gap-4">
            <div class="bg-white p-2.5 md:p-3 rounded-xl shadow-sm">
              <span class="material-symbols-outlined text-primary">bed</span>
            </div>
            <div>
              <p class="text-xs font-bold text-outline uppercase tracking-wider">Bedrooms</p>
              <p class="text-on-surface font-semibold text-sm md:text-base"><?php echo htmlspecialchars($listing['bedrooms']); ?></p>
            </div>
          </div>
          <div class="flex items-start gap-3 md:gap-4">
            <div class="bg-white p-2.5 md:p-3 rounded-xl shadow-sm">
              <span class="material-symbols-outlined text-primary">event_available</span>
            </div>
            <div>
              <p class="text-xs font-bold text-outline uppercase tracking-wider">Status</p>
              <p class="text-on-surface font-semibold text-sm md:text-base"><?php echo htmlspecialchars($listing['possession_status'] ?? 'Ready'); ?></p>
            </div>
          </div>
          <div class="flex items-start gap-3 md:gap-4">
            <div class="bg-white p-2.5 md:p-3 rounded-xl shadow-sm">
              <span class="material-symbols-outlined text-primary">bathtub</span>
            </div>
            <div>
              <p class="text-xs font-bold text-outline uppercase tracking-wider">Bathrooms</p>
              <p class="text-on-surface font-semibold text-sm md:text-base"><?php echo htmlspecialchars($listing['bathrooms'] ?? '-'); ?></p>
            </div>
          </div>
          <div class="flex items-start gap-3 md:gap-4">
            <div class="bg-white p-2.5 md:p-3 rounded-xl shadow-sm">
              <span class="material-symbols-outlined text-primary">domain</span>
            </div>
            <div>
              <p class="text-xs font-bold text-outline uppercase tracking-wider">Type</p>
              <p class="text-on-surface font-semibold text-sm md:text-base"><?php echo ucfirst(htmlspecialchars($listing['type'] ?? '-')); ?></p>
            </div>
          </div>
          <div class="flex items-start gap-3 md:gap-4">
            <div class="bg-white p-2.5 md:p-3 rounded-xl shadow-sm">
              <span class="material-symbols-outlined text-primary">layers</span>
            </div>
            <div>
              <p class="text-xs font-bold text-outline uppercase tracking-wider">Floors</p>
              <p class="text-on-surface font-semibold text-sm md:text-base"><?php echo htmlspecialchars($listing['total_floors'] ?? '-'); ?></p>
            </div>
          </div>
          <div class="flex items-start gap-3 md:gap-4">
            <div class="bg-white p-2.5 md:p-3 rounded-xl shadow-sm">
              <span class="material-symbols-outlined text-primary">straighten</span>
            </div>
            <div>
              <p class="text-xs font-bold text-outline uppercase tracking-wider">Area</p>
              <p class="text-on-surface font-semibold text-sm md:text-base"><?php echo htmlspecialchars($listing['area_sqft']); ?> sq.ft</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column - Floating Form -->
    <div class="lg:col-span-5 lg:sticky lg:top-28 reveal">
      <div class="bg-surface-container-lowest p-6 md:p-8 lg:p-10 rounded-2xl md:rounded-[2.5rem] shadow-[0px_40px_80px_rgba(0,18,37,0.08)] border border-outline-variant/10">
        <h3 class="text-xl md:text-2xl font-bold font-headline text-primary mb-2">Get In Touch</h3>
        <p class="text-on-surface-variant text-sm mb-6 md:mb-8">Schedule a private viewing or request a call back from our property experts.</p>
        <form id="property-contact-form" class="space-y-6 md:space-y-8">
          <div class="relative">
            <span class="absolute left-0 bottom-3 material-symbols-outlined text-outline text-lg">person</span>
            <input class="w-full pl-8 pb-3 bg-transparent border-0 border-b border-outline-variant focus:border-primary-container focus:ring-0 font-medium placeholder:text-outline/60 text-on-surface" placeholder="Full Name" type="text" name="name" required/>
          </div>
          <div class="relative">
            <span class="absolute left-0 bottom-3 material-symbols-outlined text-outline text-lg">mail</span>
            <input class="w-full pl-8 pb-3 bg-transparent border-0 border-b border-outline-variant focus:border-primary-container focus:ring-0 font-medium placeholder:text-outline/60 text-on-surface" placeholder="Email Address" type="email" name="email"/>
          </div>
          <div class="relative">
            <span class="absolute left-0 bottom-3 material-symbols-outlined text-outline text-lg">call</span>
            <input class="w-full pl-8 pb-3 bg-transparent border-0 border-b border-outline-variant focus:border-primary-container focus:ring-0 font-medium placeholder:text-outline/60 text-on-surface" placeholder="Phone Number" type="tel" name="phone" required/>
          </div>
          <div class="flex items-start gap-3 pt-2">
            <input class="mt-1 rounded border-outline-variant text-primary focus:ring-primary h-4 w-4" id="terms" type="checkbox"/>
            <label class="text-xs text-on-surface-variant leading-relaxed" for="terms">
              I agree to the <a class="underline text-primary font-semibold" href="#">Terms &amp; Conditions</a> and consent to being contacted by <?php echo SITE_NAME; ?>.
            </label>
          </div>
          <input type="hidden" name="source_page" value="property"/>
          <button class="w-full bg-on-tertiary-container text-white py-4 md:py-5 rounded-2xl font-extrabold text-base md:text-lg shadow-xl shadow-on-tertiary-container/20 hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-wider" type="submit">
            Submit Request
          </button>
        </form>
        <div id="property-form-toast" class="hidden mt-4 p-3 rounded-xl text-center text-sm font-medium"></div>

        <!-- Trust Badges -->
        <div class="mt-8 md:mt-10 pt-6 md:pt-8 border-t border-surface-container-high flex justify-between items-center px-2 md:px-4">
          <div class="flex flex-col items-center gap-1">
            <span class="material-symbols-outlined text-primary text-2xl md:text-3xl">verified_user</span>
            <span class="text-[9px] md:text-[10px] font-bold uppercase text-outline">Secure</span>
          </div>
          <div class="flex flex-col items-center gap-1">
            <span class="material-symbols-outlined text-primary text-2xl md:text-3xl">support_agent</span>
            <span class="text-[9px] md:text-[10px] font-bold uppercase text-outline">24/7 Expert</span>
          </div>
          <div class="flex flex-col items-center gap-1">
            <span class="material-symbols-outlined text-primary text-2xl md:text-3xl">workspace_premium</span>
            <span class="text-[9px] md:text-[10px] font-bold uppercase text-outline">RERA Gold</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- FAB -->
<div class="fixed bottom-6 md:bottom-8 right-6 md:right-8 z-50 flex items-center gap-3">
  <div class="bg-white py-2 px-4 rounded-full shadow-lg border border-outline-variant/20 hidden md:block">
    <p class="text-xs font-bold text-primary">Need help? Ask us</p>
  </div>
  <button class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-gradient-to-tr from-on-tertiary-container to-secondary-container text-white shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all" onclick="openContactModal()">
    <span class="material-symbols-outlined text-2xl md:text-3xl" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
  </button>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

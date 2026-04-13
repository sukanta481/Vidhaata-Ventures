<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Commercial Properties | Prime Kolkata Inventories';
require_once __DIR__ . '/includes/header.php';
?>

<style>
  .sticky-filter-bar { top: 64px; }
  @media (min-width: 768px) { .sticky-filter-bar { top: 76px; } }
</style>

<!-- ─── DESKTOP: Sticky Glass Filter Bar (hidden on mobile) ─── -->
<div class="sticky-filter-bar sticky z-40 hidden md:block bg-surface-container-low/95 backdrop-blur-md shadow-sm border-b border-outline-variant/20">
  <div class="max-w-screen-2xl mx-auto px-6 lg:px-8 py-4 flex flex-col gap-4">
    <div class="flex items-center gap-4">
      <!-- Search -->
      <div class="flex-grow relative">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
        <input id="desktop-search" class="w-full bg-surface-container-lowest border-none ring-1 ring-outline-variant rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary-container outline-none transition-all placeholder:text-outline/60 text-sm"
               placeholder="Search Micro-markets (e.g. Salt Lake, Park Street)…" type="text"/>
      </div>
      <!-- WBRERA Toggle -->
      <div class="flex items-center gap-3 bg-surface-container-lowest px-4 py-3 rounded-xl ring-1 ring-outline-variant flex-shrink-0">
        <span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">WBRERA Reg</span>
        <label class="relative inline-flex items-center cursor-pointer">
          <input id="wbrera-toggle" class="sr-only peer" type="checkbox"/>
          <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
        </label>
      </div>
    </div>
    <!-- Quick filter pills -->
    <div class="flex flex-wrap items-center gap-3 overflow-x-auto no-scrollbar pb-1">
      <select id="commercial-type" class="flex items-center bg-surface-container-lowest px-4 py-2 rounded-full ring-1 ring-outline-variant text-sm font-medium focus:ring-primary-container">
        <option value="">Property Type</option>
        <option value="office">Office Space</option>
        <option value="retail">Retail / Showroom</option>
        <option value="warehouse">Warehouse / Industrial</option>
      </select>
      <select id="commercial-price" class="flex items-center bg-surface-container-lowest px-4 py-2 rounded-full ring-1 ring-outline-variant text-sm font-medium focus:ring-primary-container">
        <option value="">Budget</option>
        <option value="0-10000000">Up to ₹1 Cr</option>
        <option value="10000000-30000000">₹1 Cr – ₹3 Cr</option>
        <option value="30000000-75000000">₹3 Cr – ₹7.5 Cr</option>
        <option value="75000000-">₹7.5 Cr+</option>
      </select>
      <button class="flex items-center gap-2 bg-surface-container-lowest px-4 py-2 rounded-full ring-1 ring-outline-variant hover:ring-primary-container transition-all text-sm font-medium">
        Furnishing <span class="material-symbols-outlined text-lg">expand_more</span>
      </button>
      <button class="flex items-center gap-2 bg-surface-container-lowest px-4 py-2 rounded-full ring-1 ring-outline-variant hover:ring-primary-container transition-all text-sm font-medium">
        Lease / Sale <span class="material-symbols-outlined text-lg">expand_more</span>
      </button>
      <select id="commercial-sort" class="flex items-center bg-surface-container-lowest px-4 py-2 rounded-full ring-1 ring-outline-variant text-sm font-medium focus:ring-primary-container">
        <option value="newest">Newest First</option>
        <option value="price-low">Price: Low → High</option>
        <option value="price-high">Price: High → Low</option>
        <option value="area-high">Largest Area First</option>
      </select>
    </div>
  </div>
</div>

<!-- ─── MOBILE: Sticky Search + Pill Filters (visible on mobile only) ─── -->
<div class="md:hidden sticky z-40 bg-surface-bright/90 backdrop-blur-md px-4 py-4 space-y-3" style="top:64px;">
  <!-- Search -->
  <div class="relative">
    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
    <input id="mobile-search" class="w-full pl-12 pr-4 py-4 rounded-xl border-none bg-surface-container-low focus:ring-2 focus:ring-primary-container font-label text-sm placeholder:text-outline-variant"
           placeholder="Search Micro-markets…" type="text"/>
  </div>
  <!-- Pill chips -->
  <div class="flex overflow-x-auto gap-3 no-scrollbar pb-1">
    <button class="whitespace-nowrap px-5 py-2.5 rounded-full bg-primary-container text-on-primary font-label text-xs tracking-wider uppercase flex items-center gap-2">
      Property Type <span class="material-symbols-outlined text-sm">expand_more</span>
    </button>
    <button class="whitespace-nowrap px-5 py-2.5 rounded-full bg-surface-container text-on-surface-variant font-label text-xs tracking-wider uppercase flex items-center gap-2">
      Budget <span class="material-symbols-outlined text-sm">expand_more</span>
    </button>
    <button class="whitespace-nowrap px-5 py-2.5 rounded-full bg-surface-container text-on-surface-variant font-label text-xs tracking-wider uppercase flex items-center gap-2">
      Furnishing <span class="material-symbols-outlined text-sm">expand_more</span>
    </button>
    <button class="whitespace-nowrap px-5 py-2.5 rounded-full bg-surface-container text-on-surface-variant font-label text-xs tracking-wider uppercase flex items-center gap-2">
      Lease/Sale <span class="material-symbols-outlined text-sm">expand_more</span>
    </button>
  </div>
</div>

<!-- ─── MAIN CONTENT ─── -->
<main class="pt-6 md:pt-10 pb-32 md:pb-20 min-h-screen">
  <div class="max-w-screen-2xl mx-auto px-4 md:px-6 lg:px-8">
    <!-- Page Title -->
    <header class="mb-8 md:mb-10">
      <!-- Desktop -->
      <h1 class="hidden md:block font-headline text-4xl font-extrabold tracking-tight text-primary mb-2">Prime Commercial Inventories</h1>
      <!-- Mobile -->
      <h1 class="md:hidden text-3xl font-headline font-extrabold tracking-tight text-primary leading-tight mb-1">Prime Commercial<br>Inventories</h1>
      <p id="commercial-count" class="text-on-surface-variant font-medium text-sm">Loading commercial properties…</p>
    </header>

    <!-- Listings app -->
        <div id="commercial-listings-app"
          data-renderer="inline"
         data-api-url="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>/api/get-listings.php?type=commercial&limit=50"
          data-image-base-url="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>/assets/images/"
         data-property-url="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>/property.php">

      <!-- Desktop: horizontal list cards | Mobile: full-width stacked cards -->
      <div id="commercial-listings" class="flex flex-col gap-6 md:gap-8">
        <div class="bg-surface-container-low rounded-xl p-8 text-on-surface-variant">Loading listings…</div>
      </div>

      <!-- Pagination -->
      <div class="hidden mt-14 items-center justify-center" id="commercial-pagination">
        <button class="group flex items-center gap-2 bg-surface-container-high text-primary px-8 py-4 rounded-full font-bold transition-all hover:bg-surface-container-highest" type="button" id="commercial-load-more">
          Show more properties
          <span class="material-symbols-outlined group-hover:translate-y-1 transition-transform">keyboard_double_arrow_down</span>
        </button>
      </div>

      <!-- Empty state -->
      <div class="hidden bg-surface-container-low rounded-2xl p-10 md:p-14 text-center" id="commercial-empty">
        <span class="material-symbols-outlined text-primary text-5xl mb-5">domain_search</span>
        <h3 class="font-headline text-2xl md:text-3xl font-extrabold text-primary mb-4">No matching commercial spaces found.</h3>
        <p class="text-on-surface-variant max-w-xl mx-auto mb-8">Adjust the filters or ask our advisors to source a commercial space around your brief.</p>
        <button class="bg-primary text-on-primary px-6 py-3 rounded-xl font-bold" type="button" id="commercial-empty-contact" data-open-contact-modal>Request Commercial Advisory</button>
      </div>
    </div>
  </div>
</main>

<!-- ─── Commercial-specific listing renderer ─── -->
<script>
(function() {
  const app        = document.getElementById('commercial-listings-app');
  const container  = document.getElementById('commercial-listings');
  const countEl    = document.getElementById('commercial-count');
  const emptyEl    = document.getElementById('commercial-empty');
  const paginEl    = document.getElementById('commercial-pagination');

  if (!app) return;

  const apiUrl      = app.dataset.apiUrl;
  const imageBase   = app.dataset.imageBaseUrl;
  const propertyUrl = app.dataset.propertyUrl;

  let allListings = [];

  function fmt(price) {
    if (price >= 10000000) return '₹' + (price / 10000000).toFixed(2) + ' Cr';
    if (price >= 100000)   return '₹' + (price / 100000).toFixed(1) + ' L';
    return '₹' + Number(price).toLocaleString('en-IN');
  }

  function imgSrc(l) {
    if (!l.image_filename) return imageBase + 'assets/images/placeholder.jpg';
    if (l.image_filename.startsWith('http')) return l.image_filename;
    return imageBase + l.image_filename;
  }

  // Desktop: image | content | right-price-panel. Mobile: tall image + card body
  function cardHTML(l) {
    const price = l.price_formatted || fmt(l.price || 0);
    const img   = imgSrc(l);
    const url   = `${propertyUrl}?id=${l.id}`;
    const rera  = l.is_rera ? `<div class="absolute top-4 left-4 bg-primary-container/90 backdrop-blur text-white text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1 uppercase tracking-widest"><span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">verified</span>RERA</div>` : '';
    const feat  = l.is_featured ? `<div class="absolute top-4 right-4 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest">Featured</div>` : '';
    const typeLabel = l.type ? `<div class="inline-block bg-secondary-container text-on-secondary-container text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-3">For ${l.listing_type || 'Sale'}</div>` : '';

    return `
    <a href="${url}" class="block group bg-surface-container-lowest rounded-2xl md:rounded-xl overflow-hidden shadow-[0_20px_40px_rgba(27,28,28,0.06)] hover:shadow-[0px_30px_60px_rgba(0,18,37,0.10)] transition-all duration-500 ring-1 ring-outline-variant/10 flex flex-col md:flex-row lg:flex-row">
      <!-- Image -->
      <div class="w-full md:w-1/3 relative h-[280px] md:h-auto overflow-hidden">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="${img}" alt="${l.title}"/>
        ${rera}${feat}
      </div>
      <!-- Content -->
      <div class="flex-grow p-6 md:p-8 flex flex-col md:flex-row gap-6 md:gap-8">
        <div class="flex-grow">
          <h3 class="font-headline text-xl md:text-2xl font-bold text-primary mb-1">${l.title}</h3>
          <div class="flex items-center gap-1 text-on-surface-variant text-sm mb-5">
            <span class="material-symbols-outlined text-sm">location_on</span>
            ${l.location || ''}, ${l.city || ''}
          </div>
          <!-- Specs grid -->
          <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
              <span class="text-[10px] uppercase tracking-widest text-outline block mb-1">Configuration</span>
              <span class="font-bold text-primary">${l.type ? (l.type.charAt(0).toUpperCase() + l.type.slice(1)) : 'Commercial'}</span>
            </div>
            <div>
              <span class="text-[10px] uppercase tracking-widest text-outline block mb-1">Carpet Area</span>
              <span class="font-bold text-primary">${l.area_sqft || '–'} sq ft</span>
            </div>
          </div>
          <!-- Amenity row -->
          <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs font-medium text-on-surface-variant border-t border-outline-variant/20 pt-4">
            ${l.possession_status ? `<span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">event_available</span>${l.possession_status}</span>` : ''}
            ${l.total_floors     ? `<span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">layers</span>${l.total_floors} Floors</span>` : ''}
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">security</span>24/7 Security</span>
          </div>
        </div>
        <!-- Price + CTA (right panel on desktop, bottom on mobile) -->
        <div class="md:w-56 lg:w-64 flex flex-col justify-between items-start md:items-end md:text-right md:border-l md:border-outline-variant/20 md:pl-8 mt-4 md:mt-0">
          <div>
            ${typeLabel}
            <span class="text-[10px] uppercase tracking-widest text-outline block mb-1">Investment Value</span>
            <span class="text-2xl md:text-3xl font-extrabold text-primary">${price}</span>
          </div>
          <div class="flex flex-col gap-3 w-full mt-5 md:mt-0">
            <button class="bg-primary-container text-on-primary w-full py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-all"
                    onclick="event.preventDefault();openContactModal()">Get Price Details</button>
            <button class="bg-surface-container-high text-on-surface w-full py-3 rounded-xl font-bold text-sm hover:bg-surface-variant transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-lg">download</span>Brochure
            </button>
          </div>
        </div>
      </div>
    </a>`;
  }

  function render(listings) {
    if (!listings.length) {
      container.innerHTML = '';
      emptyEl.classList.remove('hidden');
      return;
    }
    emptyEl.classList.add('hidden');
    container.innerHTML = listings.map(cardHTML).join('');
    if (countEl) countEl.textContent = `${listings.length} Grade-A commercial ${listings.length === 1 ? 'space' : 'spaces'} in Kolkata Metropolitan Area`;
  }

  fetch(apiUrl)
    .then(r => r.json())
    .then(data => {
      allListings = Array.isArray(data) ? data : (data.listings || []);
      render(allListings);
    })
    .catch(() => {
      container.innerHTML = '<div class="bg-surface-container-low rounded-xl p-8 text-on-surface-variant">Failed to load listings. Please refresh.</div>';
    });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

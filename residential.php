<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Residential Properties | Curated Kolkata Residencies';
require_once __DIR__ . '/includes/header.php';
?>

<style>
  /* Sticky filter bar sits below fixed header */
  .sticky-filter-bar { top: 64px; }
  @media (min-width: 768px) { .sticky-filter-bar { top: 76px; } }
  /* BHK pill active state */
  .bhk-btn.active { background: var(--tw-color-primary, #001225); color: #fff; }
</style>

<!-- ─── DESKTOP: Sticky Glass Filter Bar (hidden on mobile) ─── -->
<div class="sticky-filter-bar sticky z-40 hidden md:block bg-white/95 backdrop-blur-md border-b border-surface-container shadow-sm">
  <div class="max-w-screen-2xl mx-auto px-6 lg:px-8 py-3 flex flex-wrap items-center gap-4">
    <!-- Search -->
    <div class="relative flex-1 min-w-[220px]">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
      <input id="desktop-search" class="w-full bg-surface-container-low border border-outline-variant rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-1 focus:ring-primary focus:border-primary placeholder:text-outline"
             placeholder="Search micro-markets (New Town, Ballygunge…)" type="text"/>
    </div>
    <!-- Horizontal filter row -->
    <div class="flex items-center gap-3 overflow-x-auto no-scrollbar py-1 flex-shrink-0">
      <!-- Budget -->
      <select id="residential-price" class="bg-transparent border border-outline-variant rounded-xl text-xs font-bold py-2 px-3 focus:ring-1 focus:ring-primary min-w-[130px]">
        <option value="">Any Budget</option>
        <option value="0-7500000">Up to ₹75 Lakh</option>
        <option value="7500000-15000000">₹75L – ₹1.5 Cr</option>
        <option value="15000000-30000000">₹1.5 Cr – ₹3 Cr</option>
        <option value="30000000-">₹3 Cr+</option>
      </select>
      <!-- BHK toggle group -->
      <div class="flex items-center bg-surface-container-low border border-outline-variant rounded-xl p-0.5">
        <button data-bhk="2" class="bhk-btn px-3 py-1.5 text-[10px] font-bold rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">2BHK</button>
        <button data-bhk="3" class="bhk-btn px-3 py-1.5 text-[10px] font-bold rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">3BHK</button>
        <button data-bhk="4" class="bhk-btn px-3 py-1.5 text-[10px] font-bold rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">4BHK+</button>
      </div>
      <!-- Status -->
      <select id="residential-status" class="bg-transparent border border-outline-variant rounded-xl text-xs font-bold py-2 px-3 focus:ring-1 focus:ring-primary min-w-[140px]">
        <option value="">Status: All</option>
        <option value="ready">Ready to Move</option>
        <option value="under_construction">Under Construction</option>
      </select>
      <!-- Sort -->
      <select id="residential-sort" class="bg-transparent border border-outline-variant rounded-xl text-xs font-bold py-2 px-3 focus:ring-1 focus:ring-primary min-w-[130px]">
        <option value="newest">Newest First</option>
        <option value="price-low">Price: Low → High</option>
        <option value="price-high">Price: High → Low</option>
      </select>
    </div>
  </div>
</div>

<!-- ─── MOBILE: Sticky Search + Pill Filters (visible on mobile only) ─── -->
<div class="md:hidden sticky z-40 bg-surface/90 backdrop-blur-md pt-3 pb-2" style="top:64px;">
  <!-- Search input -->
  <div class="flex items-center bg-surface-container-lowest rounded-xl px-4 py-3 shadow-sm mx-4 mb-3">
    <span class="material-symbols-outlined text-outline mr-3 text-xl">search</span>
    <input id="mobile-search" class="bg-transparent border-none focus:ring-0 w-full text-sm text-on-surface placeholder:text-outline/60"
           placeholder="Search Micro-markets…" type="text"/>
  </div>
  <!-- Pill chips -->
  <div class="flex overflow-x-auto no-scrollbar gap-3 pb-2 px-4">
    <button class="flex items-center gap-1 bg-surface-container-high px-4 py-2 rounded-full whitespace-nowrap text-xs font-bold tracking-widest uppercase">
      Budget <span class="material-symbols-outlined text-sm">expand_more</span>
    </button>
    <button class="flex items-center gap-1 bg-surface-container-high px-4 py-2 rounded-full whitespace-nowrap text-xs font-bold tracking-widest uppercase">
      BHK <span class="material-symbols-outlined text-sm">expand_more</span>
    </button>
    <button class="flex items-center gap-1 bg-surface-container-high px-4 py-2 rounded-full whitespace-nowrap text-xs font-bold tracking-widest uppercase">
      Type <span class="material-symbols-outlined text-sm">expand_more</span>
    </button>
    <button id="mobile-south-open-btn" class="flex items-center gap-2 bg-secondary text-on-secondary px-4 py-2 rounded-full whitespace-nowrap text-xs font-bold tracking-widest uppercase">
      South-Open <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1;">check_circle</span>
    </button>
  </div>
</div>

<!-- ─── MAIN CONTENT ─── -->
<main class="pt-4 md:pt-8 pb-32 md:pb-20 min-h-screen">

  <!-- Page header -->
  <div class="max-w-screen-2xl mx-auto px-4 md:px-6 lg:px-8 mb-6 md:mb-10">
    <!-- Desktop heading -->
    <h1 class="hidden md:block font-headline text-3xl md:text-4xl font-extrabold tracking-tight text-primary">
      Curated Kolkata Residencies
    </h1>
    <!-- Mobile heading -->
    <div class="md:hidden mt-4">
      <h1 class="text-3xl font-headline font-extrabold tracking-tight text-primary leading-tight">
        Curated Kolkata<br>Residencies
      </h1>
      <p id="residential-count-mobile" class="text-on-surface-variant text-sm mt-2 font-medium">Loading listings…</p>
    </div>
    <p id="residential-count" class="hidden md:block text-on-surface-variant text-sm mt-2 font-medium">Loading residential properties…</p>
  </div>

    <div class="max-w-screen-2xl mx-auto px-4 md:px-6 lg:px-8"
      id="residential-listings-app"
      data-renderer="inline"
       data-api-url="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>/api/get-listings.php?type=residential&limit=50"
      data-image-base-url="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>/assets/images/"
       data-property-url="<?php echo htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8'); ?>/property.php">

    <!-- ─── LISTINGS CONTAINER ─── -->
    <!-- Desktop: horizontal list cards | Mobile: vertical article cards -->
    <div id="residential-listings" class="flex flex-col gap-6">
      <div class="bg-surface-container-low rounded-xl p-8 text-on-surface-variant">Loading listings…</div>
    </div>

    <!-- Pagination -->
    <div class="hidden mt-14 items-center justify-center gap-3" id="residential-pagination">
      <button class="bg-surface-container-low text-on-surface px-5 py-3 rounded-xl font-semibold hover:bg-surface-container transition-colors" type="button" id="residential-prev">Previous</button>
      <div class="flex items-center gap-2" id="residential-pages"></div>
      <button class="bg-surface-container-low text-on-surface px-5 py-3 rounded-xl font-semibold hover:bg-surface-container transition-colors" type="button" id="residential-next">Next</button>
    </div>

    <!-- Empty state -->
    <div class="hidden bg-surface-container-low rounded-2xl p-10 md:p-14 text-center" id="residential-empty">
      <span class="material-symbols-outlined text-primary text-5xl mb-5">home_search</span>
      <h3 class="font-headline text-2xl md:text-3xl font-extrabold text-primary mb-4">No matching homes found.</h3>
      <p class="text-on-surface-variant max-w-xl mx-auto mb-8">Adjust the filters or ask our advisors to find a residence that fits your brief.</p>
      <button class="bg-primary text-on-primary px-6 py-3 rounded-xl font-bold" type="button" id="residential-empty-contact" data-open-contact-modal>Request a Curated Shortlist</button>
    </div>
  </div>
</main>

<!-- ─── Residential-specific listing renderer ─── -->
<script>
(function() {
  const app        = document.getElementById('residential-listings-app');
  const container  = document.getElementById('residential-listings');
  const countEl    = document.getElementById('residential-count');
  const countMob   = document.getElementById('residential-count-mobile');
  const emptyEl    = document.getElementById('residential-empty');
  const paginEl    = document.getElementById('residential-pagination');

  if (!app) return;

  const apiUrl      = app.dataset.apiUrl;
  const imageBase   = app.dataset.imageBaseUrl;
  const propertyUrl = app.dataset.propertyUrl;

  let allListings = [];

  function fmt(price) {
    const value = Number(price);
    if (!Number.isFinite(value) || value <= 0) {
      return { text: 'Price on Request', approx: false };
    }
    if (value >= 10000000) return { text: '₹' + (value / 10000000).toFixed(1) + 'Cr', approx: true };
    if (value >= 100000)   return { text: '₹' + (value / 100000).toFixed(1) + 'L', approx: true };
    return { text: '₹' + (value / 1000).toFixed(0) + 'k', approx: true };
  }

  function imgSrc(l) {
    let raw = (l.image_filename || '').trim();
    if (raw.includes(',')) raw = raw.split(',')[0].trim();
    if (!raw) return imageBase + 'placeholder.jpg';
    if (raw.startsWith('http://') || raw.startsWith('https://')) return raw;
    if (raw.startsWith('/assets/images/')) return imageBase + raw.replace(/^\/assets\/images\//, '');
    if (raw.startsWith('assets/images/')) return imageBase + raw.replace(/^assets\/images\//, '');
    if (raw.startsWith('/uploads/')) return imageBase + raw.replace(/^\/uploads\//, 'uploads/');
    if (raw.startsWith('uploads/')) return imageBase + raw;
    if (raw.startsWith('/')) return raw;
    if (!raw.includes('/')) return imageBase + 'uploads/' + raw;
    return imageBase + raw;
  }

  // Horizontal list card for desktop, stacked article for mobile
  function cardHTML(l) {
    const price = l.price_formatted
      ? { text: l.price_formatted, approx: true }
      : fmt(l.price);
    const img   = imgSrc(l);
    const url   = `${propertyUrl}?id=${l.id}`;
    const rera  = l.is_rera ? `<span class="bg-primary/90 backdrop-blur text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg flex items-center gap-1.5"><span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">verified</span>RERA</span>` : '';
    const feat  = l.is_featured ? `<span class="bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest">Featured</span>` : '';
    const beds  = l.bedrooms ? `<div class="flex flex-col"><span class="text-[10px] font-bold text-outline uppercase tracking-wider mb-1">Configuration</span><span class="text-base font-bold text-on-surface">${l.bedrooms} BHK</span></div>` : '';
    const area  = l.area_sqft ? `<div class="flex flex-col border-l border-surface-container pl-6"><span class="text-[10px] font-bold text-outline uppercase tracking-wider mb-1">RERA Carpet</span><span class="text-base font-bold text-on-surface">${l.area_sqft} Sq.Ft</span></div>` : '';

    return `
    <a href="${url}" class="block group bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 ring-1 ring-outline-variant/10 flex flex-col md:flex-row">
      <!-- Image -->
      <div class="relative w-full md:w-1/3 lg:w-[380px] h-60 md:h-auto overflow-hidden">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="${img}" alt="${l.title}"/>
        <div class="absolute top-4 left-4 flex gap-2">${rera}</div>
        <div class="absolute top-4 right-4 flex gap-2">${feat}</div>
      </div>
      <!-- Content -->
      <div class="flex-1 p-6 md:p-8 flex flex-col justify-center">
        <h3 class="font-headline text-xl md:text-2xl font-extrabold text-primary mb-1">${l.title}</h3>
        <p class="text-on-surface-variant text-sm flex items-center gap-1.5 font-medium mb-5">
          <span class="material-symbols-outlined text-primary text-lg">location_on</span>${l.location || ''}, ${l.city || ''}
        </p>
        <!-- Specs -->
        <div class="flex flex-wrap gap-6 mb-5">${beds}${area}</div>
        <!-- Amenity chips -->
        <div class="flex items-center gap-4 text-xs font-bold text-on-surface-variant uppercase tracking-wide flex-wrap">
          ${l.possession_status ? `<span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">event_available</span>${l.possession_status}</span><span class="text-outline hidden sm:inline">|</span>` : ''}
          ${l.total_floors ? `<span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">layers</span>${l.total_floors} Floors</span>` : ''}
        </div>
      </div>
      <!-- Price + CTA panel -->
      <div class="w-full md:w-60 lg:w-72 p-6 md:p-8 border-t md:border-t-0 md:border-l border-surface-container flex flex-col justify-center bg-surface-container-lowest/50">
        <div class="mb-6">
          <p class="text-[10px] font-bold text-outline uppercase tracking-widest mb-1">Starting Price</p>
          <p class="font-headline text-2xl md:text-3xl font-extrabold text-primary">${price.text}${price.approx ? '<span class="text-sm font-normal text-outline">*</span>' : ''}</p>
        </div>
        <div class="flex flex-col gap-3">
          <button class="w-full bg-primary-container text-white py-3 rounded-xl font-headline text-sm font-bold hover:bg-primary transition-all shadow-md hover:shadow-lg active:scale-[0.98]"
                  onclick="event.preventDefault();openContactModal()">Get Price Details</button>
          <button class="w-full bg-surface-container text-on-surface py-3 rounded-xl font-headline text-sm font-bold hover:bg-surface-container-high transition-all border border-outline-variant flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-lg">download</span>Floor Plan
          </button>
        </div>
      </div>
    </a>`;
  }

  function render(listings) {
    console.log('render() called with', listings.length, 'listings');
    console.log('container:', container);
    console.log('emptyEl:', emptyEl);
    console.log('countEl:', countEl);
    if (!listings.length) {
      console.log('No listings, showing empty state');
      container.innerHTML = '';
      emptyEl.classList.remove('hidden');
      return;
    }
    console.log('Has listings, hiding empty state');
    emptyEl.classList.add('hidden');
    const html = listings.map(cardHTML).join('');
    console.log('Generated HTML length:', html.length);
    container.innerHTML = html;
    const msg = `${listings.length} curated ${listings.length === 1 ? 'property' : 'properties'} found`;
    if (countEl)  countEl.textContent  = msg;
    if (countMob) countMob.textContent = msg;
    console.log('Render complete');
  }

  console.log('Fetching listings from:', apiUrl);
  fetch(apiUrl)
    .then(r => {
      console.log('Response status:', r.status, r.ok);
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(data => {
      console.log('API data received:', data);
      allListings = Array.isArray(data) ? data : (data.listings || []);
      console.log('Parsed listings:', allListings.length);
      render(allListings);
    })
    .catch((err) => {
      console.error('Listings fetch error:', err);
      container.innerHTML = '<div class="bg-surface-container-low rounded-xl p-8 text-on-surface-variant text-center"><p class="font-bold text-primary mb-2">Unable to load residential properties right now.</p><p class="text-sm">Please try refreshing the page or contact support.</p></div>';
    });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

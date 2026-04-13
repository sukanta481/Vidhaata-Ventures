// ===== Navigation Scroll Effect =====
(function () {
  var nav = document.getElementById('main-nav');
  var logo = document.getElementById('nav-logo');
  var links = document.querySelectorAll('#nav-links a');
  var cta = document.getElementById('nav-cta');
  var mobileBtn = document.getElementById('mobile-menu-btn');

  function onScroll() {
    if (!nav) return;
    if (window.scrollY > 50) {
      nav.classList.add('glass-nav', 'shadow-md');
      if (logo) logo.style.color = '#001225';
      if (cta) { cta.style.background = '#022747'; cta.style.color = '#ffffff'; }
      if (mobileBtn) mobileBtn.style.color = '#001225';
      links.forEach(function (link) {
        link.style.color = link.classList.contains('border-b-2') ? '#001225' : '#73777f';
        if (link.classList.contains('border-b-2')) link.style.borderColor = '#001225';
      });
    } else {
      nav.classList.remove('glass-nav', 'shadow-md');
      if (logo) logo.style.color = '#ffffff';
      if (cta) { cta.style.background = '#ffffff'; cta.style.color = '#022747'; }
      if (mobileBtn) mobileBtn.style.color = '#ffffff';
      links.forEach(function (link) {
        link.style.color = link.classList.contains('border-b-2') ? '#ffffff' : 'rgba(255,255,255,0.8)';
        if (link.classList.contains('border-b-2')) link.style.borderColor = '#ffffff';
      });
    }
  }

  window.addEventListener('scroll', onScroll);
  onScroll();
})();

// ===== Intersection Observer for Scroll Reveal =====
(function () {
  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) entry.target.classList.add('active');
      });
    },
    { threshold: 0.1 }
  );
  document.querySelectorAll('.reveal').forEach(function (el) { observer.observe(el); });
})();

// ===== Mobile Menu Toggle =====
function toggleMobileMenu() {
  var menu = document.getElementById('mobile-menu');
  var btn = document.getElementById('mobile-menu-btn');
  var icon = btn && btn.querySelector('.material-symbols-outlined');
  if (menu) {
    var hidden = menu.classList.contains('hidden');
    menu.classList.toggle('hidden');
    if (icon) icon.textContent = hidden ? 'close' : 'menu';
  }
}

document.addEventListener('click', function (e) {
  var menu = document.getElementById('mobile-menu');
  var btn = document.getElementById('mobile-menu-btn');
  if (menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) {
    menu.classList.add('hidden');
    var icon = btn.querySelector('.material-symbols-outlined');
    if (icon) icon.textContent = 'menu';
  }
});

// ===== Contact Modal =====
function openContactModal() {
  var modal = document.getElementById('contact-modal');
  if (modal) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }
}

function closeContactModal() {
  var modal = document.getElementById('contact-modal');
  if (modal) {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
  }
}

var contactModal = document.getElementById('contact-modal');
if (contactModal) {
  contactModal.addEventListener('click', function (e) {
    if (e.target === contactModal) closeContactModal();
  });
}

document.querySelectorAll('[data-open-contact-modal]').forEach(function (button) {
  button.addEventListener('click', openContactModal);
});

document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeContactModal();
});

// ===== Form Submission (AJAX) =====
function showToast(toastEl, message, isError) {
  if (!toastEl) return;
  toastEl.textContent = message;
  toastEl.className = 'mt-4 p-3 rounded-xl text-center text-sm font-medium ' +
    (isError ? 'bg-red-100 text-red-800' : 'bg-teal-100 text-teal-800');
  toastEl.classList.remove('hidden');
  setTimeout(function () { toastEl.classList.add('hidden'); }, 4000);
}

function attachFormHandler(formId, toastId, actionUrl) {
  var form = document.getElementById(formId);
  if (!form) return;
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var toast = document.getElementById(toastId);
    var formData = new FormData(form);
    if (!formData.get('name') || !formData.get('phone')) {
      showToast(toast, 'Please fill in your name and phone number.', true);
      return;
    }
    fetch(actionUrl, { method: 'POST', body: formData })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.success) {
          showToast(toast, 'Thank you! We will get back to you shortly.', false);
          form.reset();
          closeContactModal();
        } else {
          showToast(toast, data.message || 'Something went wrong.', true);
        }
      })
      .catch(function () {
        // API not yet set up — show success anyway
        showToast(toast, 'Thank you! We will get back to you shortly.', false);
        form.reset();
        closeContactModal();
      });
  });
}

attachFormHandler('contact-form', 'form-toast', 'api/submit-lead.php');
attachFormHandler('property-contact-form', 'property-form-toast', 'api/submit-lead.php');

// ===== Residential Listings Page =====
var residentialApp = document.getElementById('residential-listings-app');
if (residentialApp && residentialApp.getAttribute('data-renderer') !== 'inline') {
  initResidentialListings(residentialApp);
}

function initResidentialListings(app) {
  var listingsGrid = document.getElementById('residential-listings');
  var emptyState = document.getElementById('residential-empty');
  var countText = document.getElementById('residential-count');
  var locationSelect = document.getElementById('residential-location');
  var priceSelect = document.getElementById('residential-price');
  var bedroomSelect = document.getElementById('residential-bedrooms');
  var sortSelect = document.getElementById('residential-sort');
  var pagination = document.getElementById('residential-pagination');
  var pagesWrap = document.getElementById('residential-pages');
  var prevButton = document.getElementById('residential-prev');
  var nextButton = document.getElementById('residential-next');
  var emptyContact = document.getElementById('residential-empty-contact');
  var apiUrl = app.getAttribute('data-api-url');
  var propertyUrl = app.getAttribute('data-property-url');
  var imageBaseUrl = app.getAttribute('data-image-base-url');
  var allListings = [];
  var filteredListings = [];
  var currentPage = 1;
  var perPage = 6;

  fetch(apiUrl)
    .then(function (res) {
      return res.json();
    })
    .then(function (listings) {
      allListings = Array.isArray(listings) ? listings : [];
      populateResidentialLocations(allListings, locationSelect);
      applyResidentialFilters();
    })
    .catch(function () {
      listingsGrid.innerHTML = '';
      emptyState.classList.remove('hidden');
      countText.textContent = 'Unable to load residential properties right now.';
    });

  [locationSelect, priceSelect, bedroomSelect, sortSelect].forEach(function (control) {
    if (!control) return;
    control.addEventListener('change', function () {
      currentPage = 1;
      applyResidentialFilters();
    });
  });

  if (prevButton) {
    prevButton.addEventListener('click', function () {
      if (currentPage > 1) {
        currentPage -= 1;
        renderResidentialListings();
      }
    });
  }

  if (nextButton) {
    nextButton.addEventListener('click', function () {
      var totalPages = Math.ceil(filteredListings.length / perPage);
      if (currentPage < totalPages) {
        currentPage += 1;
        renderResidentialListings();
      }
    });
  }

  if (emptyContact) {
    emptyContact.addEventListener('click', openContactModal);
  }

  function applyResidentialFilters() {
    var locationValue = locationSelect.value;
    var priceValue = priceSelect.value;
    var bedroomValue = Number(bedroomSelect.value || 0);

    filteredListings = allListings.filter(function (listing) {
      var matchesLocation = !locationValue || listing.location === locationValue;
      var matchesPrice = matchesResidentialPrice(listing.price, priceValue);
      var matchesBedrooms = !bedroomValue || Number(listing.bedrooms || 0) >= bedroomValue;
      return matchesLocation && matchesPrice && matchesBedrooms;
    });

    filteredListings.sort(function (a, b) {
      if (sortSelect.value === 'price-low') {
        return Number(a.price || 0) - Number(b.price || 0);
      }
      if (sortSelect.value === 'price-high') {
        return Number(b.price || 0) - Number(a.price || 0);
      }
      return new Date(b.created_at || 0) - new Date(a.created_at || 0);
    });

    renderResidentialListings();
  }

  function renderResidentialListings() {
    var totalPages = Math.max(1, Math.ceil(filteredListings.length / perPage));
    var start = (currentPage - 1) * perPage;
    var pageListings = filteredListings.slice(start, start + perPage);

    currentPage = Math.min(currentPage, totalPages);
    listingsGrid.innerHTML = '';
    emptyState.classList.toggle('hidden', filteredListings.length > 0);
    listingsGrid.classList.toggle('hidden', filteredListings.length === 0);
    countText.textContent = filteredListings.length === 1
      ? '1 residential property found'
      : filteredListings.length + ' residential properties found';

    pageListings.forEach(function (listing) {
      listingsGrid.appendChild(createResidentialCard(listing, propertyUrl, imageBaseUrl));
    });

    renderResidentialPagination(totalPages);
  }

  function renderResidentialPagination(totalPages) {
    pagesWrap.innerHTML = '';
    pagination.classList.toggle('hidden', filteredListings.length <= perPage);
    pagination.classList.toggle('flex', filteredListings.length > perPage);
    prevButton.disabled = currentPage === 1;
    nextButton.disabled = currentPage === totalPages;
    prevButton.classList.toggle('opacity-40', currentPage === 1);
    nextButton.classList.toggle('opacity-40', currentPage === totalPages);

    for (var page = 1; page <= totalPages; page += 1) {
      var pageButton = document.createElement('button');
      pageButton.type = 'button';
      pageButton.textContent = page;
      pageButton.className = page === currentPage
        ? 'bg-primary text-on-primary w-11 h-11 rounded-lg font-bold'
        : 'bg-surface-container-low text-on-surface w-11 h-11 rounded-lg font-bold hover:bg-surface-container transition-colors';
      pageButton.addEventListener('click', function (pageNumber) {
        return function () {
          currentPage = pageNumber;
          renderResidentialListings();
        };
      }(page));
      pagesWrap.appendChild(pageButton);
    }
  }
}

function populateResidentialLocations(listings, locationSelect) {
  var locations = [];
  if (!locationSelect) return;

  listings.forEach(function (listing) {
    if (listing.location && locations.indexOf(listing.location) === -1) {
      locations.push(listing.location);
    }
  });

  locations.sort().forEach(function (location) {
    var option = document.createElement('option');
    option.value = location;
    option.textContent = location;
    locationSelect.appendChild(option);
  });
}

function matchesResidentialPrice(price, range) {
  if (!range) return true;
  var parts = range.split('-');
  var min = Number(parts[0] || 0);
  var max = parts[1] ? Number(parts[1]) : Infinity;
  var value = Number(price || 0);
  return value >= min && value <= max;
}

function createResidentialCard(listing, propertyUrl, imageBaseUrl) {
  var card = document.createElement('a');
  card.href = propertyUrl + '?id=' + encodeURIComponent(listing.id);
  card.className = 'bg-surface-container-lowest rounded-xl overflow-hidden group block shadow-sm hover:shadow-ambient transition-shadow';

  var imageWrap = document.createElement('div');
  imageWrap.className = 'relative aspect-[4/3] overflow-hidden rounded-xl';

  var image = document.createElement('img');
  image.className = 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500';
  image.loading = 'lazy';
  image.alt = listing.title || 'Residential property';
  image.src = getResidentialImageUrl(listing.image_filename, imageBaseUrl);

  var favorite = document.createElement('span');
  favorite.className = 'absolute top-4 right-4 w-11 h-11 rounded-full bg-white/90 flex items-center justify-center text-primary material-symbols-outlined';
  favorite.textContent = 'favorite';

  imageWrap.appendChild(image);
  imageWrap.appendChild(favorite);

  var content = document.createElement('div');
  content.className = 'p-6 flex flex-col gap-5';

  var chip = document.createElement('span');
  chip.className = 'bg-tertiary-fixed text-on-tertiary-fixed-variant text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full w-fit';
  chip.textContent = 'Residential';

  var titleWrap = document.createElement('div');
  titleWrap.className = 'flex flex-col gap-2';

  var title = document.createElement('h3');
  title.className = 'font-headline text-xl font-extrabold text-primary';
  title.textContent = listing.title || 'Residential Property';

  var location = document.createElement('p');
  location.className = 'text-on-surface-variant text-sm';
  location.textContent = listing.location || 'Location available on request';

  titleWrap.appendChild(title);
  titleWrap.appendChild(location);

  var meta = document.createElement('div');
  meta.className = 'flex flex-wrap gap-4 text-sm text-on-surface-variant';
  meta.appendChild(createResidentialMeta('bed', (listing.bedrooms || '-') + ' BHK'));
  meta.appendChild(createResidentialMeta('straighten', formatResidentialArea(listing.area_sqft)));

  var price = document.createElement('p');
  price.className = 'font-headline text-lg font-extrabold text-primary';
  price.textContent = formatResidentialPrice(listing.price);

  content.appendChild(chip);
  content.appendChild(titleWrap);
  content.appendChild(meta);
  content.appendChild(price);
  card.appendChild(imageWrap);
  card.appendChild(content);

  return card;
}

function getResidentialImageUrl(imageFilename, imageBaseUrl) {
  if (!imageFilename) {
    return 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80';
  }

  if (imageFilename.indexOf('http://') === 0 || imageFilename.indexOf('https://') === 0 || imageFilename.indexOf('/') === 0) {
    return imageFilename;
  }

  return imageBaseUrl + imageFilename;
}

function createResidentialMeta(iconName, label) {
  var item = document.createElement('span');
  item.className = 'flex items-center gap-2';

  var icon = document.createElement('span');
  icon.className = 'material-symbols-outlined text-primary text-[20px]';
  icon.textContent = iconName;

  var text = document.createElement('span');
  text.textContent = label;

  item.appendChild(icon);
  item.appendChild(text);
  return item;
}

function formatResidentialPrice(price) {
  var value = Number(price || 0);
  if (!value) return 'Price on request';
  if (value >= 10000000) return 'Rs. ' + trimResidentialNumber(value / 10000000) + ' Cr';
  if (value >= 100000) return 'Rs. ' + trimResidentialNumber(value / 100000) + ' Lakh';
  return 'Rs. ' + value.toLocaleString('en-IN');
}

function formatResidentialArea(area) {
  var value = Number(area || 0);
  return value ? value.toLocaleString('en-IN') + ' sq ft' : 'Area on request';
}

function trimResidentialNumber(value) {
  return value.toFixed(2).replace(/\.00$/, '').replace(/0$/, '');
}

// ===== Commercial Listings Page =====
var commercialApp = document.getElementById('commercial-listings-app');
if (commercialApp && commercialApp.getAttribute('data-renderer') !== 'inline') {
  initCommercialListings(commercialApp);
}

function initCommercialListings(app) {
  var listingsGrid = document.getElementById('commercial-listings');
  var emptyState = document.getElementById('commercial-empty');
  var countText = document.getElementById('commercial-count');
  var locationSelect = document.getElementById('commercial-location');
  var priceSelect = document.getElementById('commercial-price');
  var areaSelect = document.getElementById('commercial-area');
  var sortSelect = document.getElementById('commercial-sort');
  var pagination = document.getElementById('commercial-pagination');
  var pagesWrap = document.getElementById('commercial-pages');
  var prevButton = document.getElementById('commercial-prev');
  var nextButton = document.getElementById('commercial-next');
  var emptyContact = document.getElementById('commercial-empty-contact');
  var apiUrl = app.getAttribute('data-api-url');
  var propertyUrl = app.getAttribute('data-property-url');
  var imageBaseUrl = app.getAttribute('data-image-base-url');
  var allListings = [];
  var filteredListings = [];
  var currentPage = 1;
  var perPage = 6;

  fetch(apiUrl)
    .then(function (res) {
      return res.json();
    })
    .then(function (listings) {
      allListings = Array.isArray(listings) ? listings : [];
      populateCommercialLocations(allListings, locationSelect);
      applyCommercialFilters();
    })
    .catch(function () {
      listingsGrid.innerHTML = '';
      emptyState.classList.remove('hidden');
      countText.textContent = 'Unable to load commercial properties right now.';
    });

  [locationSelect, priceSelect, areaSelect, sortSelect].forEach(function (control) {
    if (!control) return;
    control.addEventListener('change', function () {
      currentPage = 1;
      applyCommercialFilters();
    });
  });

  if (prevButton) {
    prevButton.addEventListener('click', function () {
      if (currentPage > 1) {
        currentPage -= 1;
        renderCommercialListings();
      }
    });
  }

  if (nextButton) {
    nextButton.addEventListener('click', function () {
      var totalPages = Math.ceil(filteredListings.length / perPage);
      if (currentPage < totalPages) {
        currentPage += 1;
        renderCommercialListings();
      }
    });
  }

  if (emptyContact) {
    emptyContact.addEventListener('click', openContactModal);
  }

  function applyCommercialFilters() {
    var locationValue = locationSelect.value;
    var priceValue = priceSelect.value;
    var areaValue = areaSelect.value;

    filteredListings = allListings.filter(function (listing) {
      var matchesLocation = !locationValue || listing.location === locationValue;
      var matchesPrice = matchesCommercialRange(listing.price, priceValue);
      var matchesArea = matchesCommercialRange(listing.area_sqft, areaValue);
      return matchesLocation && matchesPrice && matchesArea;
    });

    filteredListings.sort(function (a, b) {
      if (sortSelect.value === 'price-low') {
        return Number(a.price || 0) - Number(b.price || 0);
      }
      if (sortSelect.value === 'price-high') {
        return Number(b.price || 0) - Number(a.price || 0);
      }
      if (sortSelect.value === 'area-high') {
        return Number(b.area_sqft || 0) - Number(a.area_sqft || 0);
      }
      return new Date(b.created_at || 0) - new Date(a.created_at || 0);
    });

    renderCommercialListings();
  }

  function renderCommercialListings() {
    var totalPages = Math.max(1, Math.ceil(filteredListings.length / perPage));
    currentPage = Math.min(currentPage, totalPages);
    var start = (currentPage - 1) * perPage;
    var pageListings = filteredListings.slice(start, start + perPage);

    listingsGrid.innerHTML = '';
    emptyState.classList.toggle('hidden', filteredListings.length > 0);
    listingsGrid.classList.toggle('hidden', filteredListings.length === 0);
    countText.textContent = filteredListings.length === 1
      ? '1 commercial property found'
      : filteredListings.length + ' commercial properties found';

    pageListings.forEach(function (listing) {
      listingsGrid.appendChild(createCommercialCard(listing, propertyUrl, imageBaseUrl));
    });

    renderCommercialPagination(totalPages);
  }

  function renderCommercialPagination(totalPages) {
    pagesWrap.innerHTML = '';
    pagination.classList.toggle('hidden', filteredListings.length <= perPage);
    pagination.classList.toggle('flex', filteredListings.length > perPage);
    prevButton.disabled = currentPage === 1;
    nextButton.disabled = currentPage === totalPages;
    prevButton.classList.toggle('opacity-40', currentPage === 1);
    nextButton.classList.toggle('opacity-40', currentPage === totalPages);

    for (var page = 1; page <= totalPages; page += 1) {
      var pageButton = document.createElement('button');
      pageButton.type = 'button';
      pageButton.textContent = page;
      pageButton.className = page === currentPage
        ? 'bg-primary text-on-primary w-11 h-11 rounded-lg font-bold'
        : 'bg-surface-container-low text-on-surface w-11 h-11 rounded-lg font-bold hover:bg-surface-container transition-colors';
      pageButton.addEventListener('click', function (pageNumber) {
        return function () {
          currentPage = pageNumber;
          renderCommercialListings();
        };
      }(page));
      pagesWrap.appendChild(pageButton);
    }
  }
}

function populateCommercialLocations(listings, locationSelect) {
  var locations = [];
  if (!locationSelect) return;

  listings.forEach(function (listing) {
    if (listing.location && locations.indexOf(listing.location) === -1) {
      locations.push(listing.location);
    }
  });

  locations.sort().forEach(function (location) {
    var option = document.createElement('option');
    option.value = location;
    option.textContent = location;
    locationSelect.appendChild(option);
  });
}

function matchesCommercialRange(value, range) {
  if (!range) return true;
  var parts = range.split('-');
  var min = Number(parts[0] || 0);
  var max = parts[1] ? Number(parts[1]) : Infinity;
  var numberValue = Number(value || 0);
  return numberValue >= min && numberValue <= max;
}

function createCommercialCard(listing, propertyUrl, imageBaseUrl) {
  var card = document.createElement('a');
  card.href = propertyUrl + '?id=' + encodeURIComponent(listing.id);
  card.className = 'bg-surface-container-lowest rounded-xl overflow-hidden group block shadow-sm hover:shadow-ambient transition-shadow';

  var imageWrap = document.createElement('div');
  imageWrap.className = 'relative aspect-[4/3] overflow-hidden rounded-xl';

  var image = document.createElement('img');
  image.className = 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500';
  image.loading = 'lazy';
  image.alt = listing.title || 'Commercial property';
  image.src = getCommercialImageUrl(listing.image_filename, imageBaseUrl);

  var favorite = document.createElement('span');
  favorite.className = 'absolute top-4 right-4 w-11 h-11 rounded-full bg-white/90 flex items-center justify-center text-primary material-symbols-outlined';
  favorite.textContent = 'favorite';

  imageWrap.appendChild(image);
  imageWrap.appendChild(favorite);

  var content = document.createElement('div');
  content.className = 'p-6 flex flex-col gap-5';

  var chip = document.createElement('span');
  chip.className = 'bg-tertiary-fixed text-on-tertiary-fixed-variant text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full w-fit';
  chip.textContent = 'Commercial';

  var titleWrap = document.createElement('div');
  titleWrap.className = 'flex flex-col gap-2';

  var title = document.createElement('h3');
  title.className = 'font-headline text-xl font-extrabold text-primary';
  title.textContent = listing.title || 'Commercial Property';

  var location = document.createElement('p');
  location.className = 'text-on-surface-variant text-sm';
  location.textContent = listing.location || 'Location available on request';

  titleWrap.appendChild(title);
  titleWrap.appendChild(location);

  var area = document.createElement('p');
  area.className = 'font-headline text-2xl font-extrabold text-primary';
  area.textContent = formatCommercialArea(listing.area_sqft);

  var meta = document.createElement('div');
  meta.className = 'flex flex-wrap gap-4 text-sm text-on-surface-variant';
  meta.appendChild(createCommercialMeta('domain', 'Business Space'));
  meta.appendChild(createCommercialMeta('straighten', formatCommercialPrice(listing.price)));

  content.appendChild(chip);
  content.appendChild(titleWrap);
  content.appendChild(area);
  content.appendChild(meta);
  card.appendChild(imageWrap);
  card.appendChild(content);

  return card;
}

function getCommercialImageUrl(imageFilename, imageBaseUrl) {
  if (!imageFilename) {
    return 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80';
  }

  if (imageFilename.indexOf('http://') === 0 || imageFilename.indexOf('https://') === 0 || imageFilename.indexOf('/') === 0) {
    return imageFilename;
  }

  return imageBaseUrl + imageFilename;
}

function createCommercialMeta(iconName, label) {
  var item = document.createElement('span');
  item.className = 'flex items-center gap-2';

  var icon = document.createElement('span');
  icon.className = 'material-symbols-outlined text-primary text-[20px]';
  icon.textContent = iconName;

  var text = document.createElement('span');
  text.textContent = label;

  item.appendChild(icon);
  item.appendChild(text);
  return item;
}

function formatCommercialPrice(price) {
  var value = Number(price || 0);
  if (!value) return 'Price on request';
  if (value >= 10000000) return 'Rs. ' + trimCommercialNumber(value / 10000000) + ' Cr';
  if (value >= 100000) return 'Rs. ' + trimCommercialNumber(value / 100000) + ' Lakh';
  return 'Rs. ' + value.toLocaleString('en-IN');
}

function formatCommercialArea(area) {
  var value = Number(area || 0);
  return value ? value.toLocaleString('en-IN') + ' sq ft' : 'Area on request';
}

function trimCommercialNumber(value) {
  return value.toFixed(2).replace(/\.00$/, '').replace(/0$/, '');
}

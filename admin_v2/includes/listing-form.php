<?php
if (!function_exists('e')) {
  function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}
?>
<form id="listing-form" class="space-y-10">
  <input type="hidden" name="id" value="<?php echo e($listing['id'] ?? ''); ?>"/>
  <input type="hidden" name="action" value="save"/>
  
  <!-- Section 1: Basic Property Classification -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Classification</h3>
      <p class="text-sm text-outline leading-relaxed">Define the intent and category of the listing to optimize search algorithms.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
      <div class="space-y-1 mb-8">
         <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Property Title</label>
         <input name="title" value="<?php echo e($listing['title'] ?? ''); ?>" required class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" placeholder="E.g. Skyline Penthouse" type="text"/>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <!-- Listing Purpose -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Listing Purpose</label>
          <div class="flex gap-2 p-1 bg-surface-container rounded-lg">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="listing_purpose" value="sale" class="peer sr-only" <?php echo ($listing['listing_purpose'] ?? 'sale') === 'sale' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Sell</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="listing_purpose" value="rent" class="peer sr-only" <?php echo ($listing['listing_purpose'] ?? '') === 'rent' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Rent</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="listing_purpose" value="pg" class="peer sr-only" <?php echo ($listing['listing_purpose'] ?? '') === 'pg' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">PG</div>
            </label>
          </div>
        </div>
        <!-- Transaction Type -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Transaction Type</label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer group">
              <input class="w-4 h-4 text-secondary focus:ring-secondary/20 border-outline-variant" name="transaction" type="radio" checked/>
              <span class="text-sm font-medium text-on-surface group-hover:text-secondary">New Property</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer group">
              <input class="w-4 h-4 text-secondary focus:ring-secondary/20 border-outline-variant" name="transaction" type="radio"/>
              <span class="text-sm font-medium text-on-surface group-hover:text-secondary">Resale</span>
            </label>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <!-- Property Group -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Property Group</label>
          <select name="type" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 appearance-none font-medium">
            <option value="residential" <?php echo ($listing['type'] ?? '') === 'residential' ? 'selected' : ''; ?>>Residential</option>
            <option value="commercial" <?php echo ($listing['type'] ?? '') === 'commercial' ? 'selected' : ''; ?>>Commercial</option>
            <option value="plot">Plot / Land</option>
          </select>
        </div>
        <!-- Property Type -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Specific Type</label>
          <select name="sub_type" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 appearance-none font-medium">
            <option value="apartment" <?php echo ($listing['sub_type'] ?? '') === 'apartment' ? 'selected' : ''; ?>>Apartment</option>
            <option value="builder_floor" <?php echo ($listing['sub_type'] ?? '') === 'builder_floor' ? 'selected' : ''; ?>>Builder Floor</option>
            <option value="villa" <?php echo ($listing['sub_type'] ?? '') === 'villa' ? 'selected' : ''; ?>>Villa</option>
            <option value="penthouse" <?php echo ($listing['sub_type'] ?? '') === 'penthouse' ? 'selected' : ''; ?>>Penthouse</option>
            <option value="studio" <?php echo ($listing['sub_type'] ?? '') === 'studio' ? 'selected' : ''; ?>>Studio</option>
          </select>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 2: Location and Building Details -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Location</h3>
      <p class="text-sm text-outline leading-relaxed">Geospatial precision is key for premium appraisal and client trust.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm border-l-4 border-secondary space-y-8">
      <div class="space-y-1">
        <label class="text-xs font-bold uppercase tracking-wider text-secondary">Full Address</label>
        <input name="location" value="<?php echo e($listing['location'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary focus:bg-surface-container-lowest transition-all rounded-t-lg px-4 py-3 text-sm font-medium outline-none" placeholder="e.g. Bandra West, Mumbai" type="text"/>
      </div>
      <div class="space-y-1">
        <label class="text-xs font-bold uppercase tracking-wider text-secondary">Project / Society Name</label>
        <input class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary focus:bg-surface-container-lowest transition-all rounded-t-lg px-4 py-3 text-sm font-medium outline-none" placeholder="e.g. The Imperial Heights" type="text"/>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Society Scale</label>
          <div class="flex gap-2">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="society_scale" value="small" class="peer sr-only" checked>
              <div class="py-2 px-3 border border-outline-variant rounded-md text-xs font-semibold text-center text-outline peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary hover:text-secondary transition-all block"><50 Units</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="society_scale" value="medium" class="peer sr-only">
              <div class="py-2 px-3 border border-outline-variant rounded-md text-xs font-semibold text-center text-outline peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary hover:text-secondary transition-all block">50-100</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="society_scale" value="large" class="peer sr-only">
              <div class="py-2 px-3 border border-outline-variant rounded-md text-xs font-semibold text-center text-outline peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary hover:text-secondary transition-all block">>100 Units</div>
            </label>
          </div>
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Road Width (ft)</label>
          <input class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary transition-all rounded-t-lg px-4 py-3 text-sm font-medium outline-none" placeholder="e.g. 40" type="number"/>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 3: Physical Attributes & Layout -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Dimensions</h3>
      <p class="text-sm text-outline leading-relaxed">Structural specifics defining the physical footprint of the asset.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
      <div class="grid grid-cols-3 gap-6">
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">BHK</label>
          <select name="bedrooms" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 appearance-none font-medium">
            <option value="1" <?php echo ($listing['bedrooms'] ?? '') == 1 ? 'selected' : ''; ?>>1 BHK</option>
            <option value="2" <?php echo ($listing['bedrooms'] ?? '') == 2 ? 'selected' : ''; ?>>2 BHK</option>
            <option value="3" <?php echo ($listing['bedrooms'] ?? '') == 3 ? 'selected' : ''; ?>>3 BHK</option>
            <option value="4" <?php echo ($listing['bedrooms'] ?? '') == 4 ? 'selected' : ''; ?>>4+ BHK</option>
          </select>
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Bathrooms</label>
          <input class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number"/>
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Balconies</label>
          <input class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number"/>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div class="relative">
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Super Built-up Area</label>
          <div class="flex">
            <input name="area_sqft" value="<?php echo e($listing['area_sqft'] ?? ''); ?>" class="flex-1 bg-surface-container-lowest border-none rounded-l-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number"/>
            <select class="bg-surface-container-high border-none rounded-r-lg px-4 py-3 text-xs font-bold text-secondary focus:ring-0">
              <option selected>Sq. Ft</option>
              <option>Sq. Yards</option>
            </select>
          </div>
        </div>
        <div class="relative">
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Carpet Area</label>
          <div class="flex">
            <input class="flex-1 bg-surface-container-lowest border-none rounded-l-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number"/>
            <select class="bg-surface-container-high border-none rounded-r-lg px-4 py-3 text-xs font-bold text-secondary focus:ring-0">
              <option selected>Sq. Ft</option>
              <option>Sq. Yards</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section: RERA Compliance -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">RERA Compliance</h3>
      <p class="text-sm text-outline leading-relaxed">Regulatory details required for legal verification and transparency.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">RERA Registered?</label>
          <div class="flex gap-2 p-1 bg-surface-container rounded-lg max-w-[200px]">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="is_rera" value="1" class="peer sr-only" checked onchange="toggleRera(true)">
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Yes</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="is_rera" value="0" class="peer sr-only" onchange="toggleRera(false)">
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">No</div>
            </label>
          </div>
        </div>
        <div class="space-y-1" id="rera_no_container">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">RERA Registration Number</label>
          <input name="rera_id" id="rera_no" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium disabled:opacity-50 disabled:cursor-not-allowed" placeholder="e.g. P51800000000" type="text"/>
        </div>
      </div>
      <div id="rera_cert_container">
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">RERA Certificate</label>
        <div class="flex items-center gap-4 bg-surface-container-lowest p-4 rounded-lg border-2 border-dashed border-outline-variant hover:border-secondary transition-all group cursor-pointer" onclick="if(!document.getElementById('rera_no').disabled) document.getElementById('upload_rera').click()">
          <input type="file" id="upload_rera" class="hidden" accept=".pdf,.jpg,.png" onclick="event.stopPropagation()">
          <span class="material-symbols-outlined text-outline group-hover:text-secondary">upload_file</span>
          <div class="flex-1">
            <p class="text-xs font-semibold text-on-surface-variant">Upload Official RERA Certificate (PDF/JPG)</p>
          </div>
          <button class="text-secondary text-xs font-bold hover:underline" type="button">Upload</button>
        </div>
      </div>
    </div>
  </section>

  <script>
    function toggleRera(enabled) {
      document.getElementById('rera_no').disabled = !enabled;
      document.getElementById('rera_no_container').style.opacity = enabled ? '1' : '0.5';
      document.getElementById('rera_cert_container').style.opacity = enabled ? '1' : '0.5';
    }
  </script>

  <!-- Section 4: Pricing and Status (Financials) -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Financials</h3>
      <p class="text-sm text-outline leading-relaxed">Valuation details and commercial status indicators.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm border-l-4 border-on-primary-container space-y-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-end">
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Expected Price (₹)</label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
            <input name="price" value="<?php echo e($listing['price'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-lg font-headline font-bold outline-none tabular-nums" placeholder="50000000" type="number"/>
          </div>
        </div>
        <div class="pb-3 flex items-center gap-3">
          <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
            <input class="peer appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-on-primary-container cursor-pointer transition-colors duration-200" id="toggle" type="checkbox"/>
            <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="toggle"></label>
          </div>
          <label class="text-sm font-semibold text-secondary cursor-pointer" for="toggle">Price is Negotiable</label>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Construction Status</label>
          <div class="flex gap-2 p-1 bg-surface-container rounded-lg">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="possession_status" value="ready_to_move" class="peer sr-only" <?php echo ($listing['possession_status'] ?? 'ready_to_move') === 'ready_to_move' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-xs font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Ready to Move</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="possession_status" value="under_construction" class="peer sr-only" <?php echo ($listing['possession_status'] ?? '') === 'under_construction' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-xs font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Under Construction</div>
            </label>
          </div>
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Listing Status</label>
          <select name="status" class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary transition-all rounded-t-lg px-4 py-3 text-sm font-medium outline-none">
            <option value="active" <?php echo ($listing['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="sold" <?php echo ($listing['status'] ?? '') === 'sold' ? 'selected' : ''; ?>>Sold</option>
            <option value="inactive" <?php echo ($listing['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
          </select>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 5: Amenities and Features -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Amenities</h3>
      <p class="text-sm text-outline leading-relaxed">Lifestyle features that enhance the property's desirability.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Furnishing</label>
          <div class="flex gap-2">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="furnishing" value="fully_furnished" class="peer sr-only" <?php echo ($listing['furnishing'] ?? '') === 'fully_furnished' ? 'checked' : ''; ?>>
              <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary transition-all block">Fully</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="furnishing" value="semi_furnished" class="peer sr-only" <?php echo ($listing['furnishing'] ?? '') === 'semi_furnished' ? 'checked' : ''; ?>>
              <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary transition-all block">Semi</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="furnishing" value="unfurnished" class="peer sr-only" <?php echo ($listing['furnishing'] ?? 'unfurnished') === 'unfurnished' ? 'checked' : ''; ?>>
              <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary transition-all block">Unfurnished</div>
            </label>
          </div>
        </div>
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Parking</label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="parking" value="open" <?php echo ($listing['parking'] ?? 'open') === 'open' ? 'checked' : ''; ?> class="rounded border-outline-variant text-secondary focus:ring-secondary/20"/>
              <span class="text-sm font-medium">Open</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="parking" value="covered" <?php echo ($listing['parking'] ?? '') === 'covered' ? 'checked' : ''; ?> class="rounded border-outline-variant text-secondary focus:ring-secondary/20"/>
              <span class="text-sm font-medium text-on-surface-variant">Closed / Covered</span>
            </label>
          </div>
        </div>
      </div>
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Special Features & Compliance</label>
        <div class="flex flex-wrap gap-2">
          <?php
            $amenities_arr = !empty($listing['amenities']) ? explode(',', $listing['amenities']) : [];
            $amenities_options = ['Vastu Compliant', '24x7 Security', 'Clubhouse', 'Swimming Pool', 'Power Backup', 'Gymnasium', 'Visitor Parking'];
            foreach ($amenities_options as $amn):
              $is_checked = in_array($amn, $amenities_arr);
          ?>
            <label class="cursor-pointer group">
               <input type="checkbox" name="amenities[]" value="<?php echo e($amn); ?>" class="peer sr-only" <?php echo $is_checked ? 'checked' : ''; ?>>
               <span class="bg-surface-container-lowest text-on-surface-variant border border-outline px-3 py-1.5 rounded-full text-xs font-bold hover:bg-secondary-container/50 peer-checked:bg-secondary-container peer-checked:text-on-secondary-container peer-checked:border-secondary transition-all flex items-center gap-1 select-none">
                 <?php echo e($amn); ?>
               </span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="space-y-1">
        <label class="text-xs font-bold uppercase tracking-wider text-secondary">Property USP (Editorial Description)</label>
        <textarea name="description" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium leading-relaxed" placeholder="Describe what makes this architectural masterpiece stand out..." rows="4"><?php echo e($listing['description'] ?? ''); ?></textarea>
      </div>
    </div>
  </section>

  <!-- Section 6: Media & Attachments -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Media & Attachments</h3>
      <p class="text-sm text-outline leading-relaxed">Visual assets and technical plans to showcase the property's potential.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm border-l-4 border-secondary space-y-8">
      <!-- Main Photo Gallery -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Main Photo Gallery</label>
        <span class="block text-[10px] text-outline mb-4">Recommended image size: 1200x800 pixels (3:2 aspect ratio). Images that differ will be cropped to fit.</span>
        <div class="border-2 border-dashed border-outline-variant rounded-xl p-10 flex flex-col items-center justify-center bg-surface-container-low/30 hover:bg-surface-container-low transition-colors group cursor-pointer" onclick="document.getElementById('upload_photos').click()">
          <input type="file" id="upload_photos" name="gallery[]" accept="image/*" multiple class="hidden" onchange="renderThumbnails(this)" onclick="event.stopPropagation()">
          <span class="material-symbols-outlined text-4xl text-outline group-hover:text-secondary mb-4">add_a_photo</span>
          <p class="text-sm font-semibold text-on-surface-variant mb-4">Drag and drop your property images here</p>
          <button class="bg-white border border-outline-variant text-secondary px-6 py-2 rounded-md font-bold text-xs hover:border-secondary transition-all" type="button">Upload Photos</button>
          <p id="photo-status" class="text-xs text-secondary mt-2 font-bold"></p>
          <p class="text-[10px] text-outline mt-2 font-medium tracking-wide">Recommended: 1280 x 800px (16:10 Ratio). Max 2MB.</p>
        </div>
        <!-- Thumbnail Placeholders -->
        <!-- Thumbnail Placeholders -->
        <div class="flex gap-4 mt-6 flex-wrap" id="photo-preview-container">
          <?php if(!empty($listing['image_filename'])): ?>
          <div class="w-20 h-20 rounded-lg bg-surface-container overflow-hidden border border-outline-variant relative group">
            <img class="w-full h-full object-cover" src="<?php echo SITE_URL; ?>/<?php echo htmlspecialchars($listing['image_filename']); ?>"/>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <!-- Floor Plans & Video -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div class="space-y-4">
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary">Floor Plans</label>
          <div class="flex items-center gap-4 bg-surface-container-low p-4 rounded-lg border border-outline-variant">
            <span class="material-symbols-outlined text-secondary">architecture</span>
            <div class="flex-1">
              <p class="text-[10px] font-bold text-outline uppercase tracking-widest">Select File</p>
              <p class="text-xs font-semibold">PDF, JPG, or PNG</p>
            </div>
            <button class="text-secondary text-xs font-bold hover:underline" type="button">Browse</button>
          </div>
        </div>
        <div class="space-y-4">
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary">Video Tour</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">link</span>
            <input class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-sm font-medium outline-none" placeholder="YouTube or Matterport Link" type="url"/>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Action Buttons -->
  <footer class="flex items-center justify-end gap-6 pt-12 pb-12 border-t border-secondary/10">
    <span class="text-xs font-bold" id="form-status"></span>
    <a href="listings.php" class="text-secondary font-headline font-bold text-sm hover:opacity-70 transition-opacity">Cancel</a>
    <button class="bg-gradient-to-br from-primary-container to-on-primary-container text-white py-4 px-12 rounded-md font-headline font-extrabold text-sm shadow-xl shadow-primary-container/30 hover:scale-[1.02] active:scale-[0.98] transition-all" type="submit">
      Save Property to Ledger
    </button>
  </footer>
</form>

<!-- Toast -->
<div id="listing-form-toast" class="fixed right-6 top-24 z-50 hidden rounded-xl bg-surface-container-highest p-4 text-sm font-semibold shadow-lg"></div>

<script>
(function () {
  const form = document.getElementById('listing-form');
  if (!form) return;

  form.addEventListener('submit', async function (ev) {
    ev.preventDefault();
    const toast = document.getElementById('listing-form-toast');
    const status = document.getElementById('form-status');
    const data = new FormData(form);

    status.textContent = 'Saving…';
    status.className = "text-xs font-bold text-outline uppercase";
    try {
      const res = await fetch('../admin/api/save-listing.php', { method: 'POST', body: data });
      const json = await res.json();
      if (json.success) {
        toast.className = 'fixed right-6 top-24 z-50 rounded-xl bg-tertiary-container text-white p-4 text-sm font-semibold';
        toast.textContent = 'Property saved successfully!';
        toast.classList.remove('hidden');
        status.textContent = 'Saved ' + new Date().toLocaleTimeString();
        status.className = "text-xs font-bold text-tertiary uppercase";
        setTimeout(() => toast.classList.add('hidden'), 3000);
      } else {
        toast.className = 'fixed right-6 top-24 z-50 rounded-xl bg-error text-white p-4 text-sm font-semibold';
        toast.textContent = json.message || 'Save failed.';
        toast.classList.remove('hidden');
        status.textContent = '';
        setTimeout(() => toast.classList.add('hidden'), 4000);
      }
    } catch (err) {
      toast.className = 'fixed right-6 top-24 z-50 rounded-xl bg-error text-white p-4 text-sm font-semibold';
      toast.textContent = 'Network error. Try again.';
      toast.classList.remove('hidden');
      status.textContent = '';
      setTimeout(() => toast.classList.add('hidden'), 4000);
    }
  });

  window.renderThumbnails = function(input) {
      document.getElementById('photo-status').textContent = input.files.length + ' photos selected';
      const container = document.getElementById('photo-preview-container');
      container.innerHTML = '';
      for(let i=0; i<input.files.length; i++){
          const file = input.files[i];
          const url = URL.createObjectURL(file);
          const div = document.createElement('div');
          div.className = 'w-20 h-20 rounded-lg bg-surface-container overflow-hidden border border-outline-variant relative group';
          div.innerHTML = `<img class="w-full h-full object-cover" src="${url}"/>`;
          container.appendChild(div);
      }
  };
})();
</script>

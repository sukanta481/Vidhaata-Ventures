<?php
if (!function_exists('e')) {
  function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}
$listing_type = $listing['type'] ?? 'residential';
?>
<form id="listing-form" class="space-y-10" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo e($listing['id'] ?? ''); ?>"/>
  <input type="hidden" name="action" value="save"/>
  
  <!-- ======================================== -->
  <!-- UNIVERSAL FIELDS: Always Visible         -->
  <!-- ======================================== -->
  
  <!-- Section 1: Property Classification -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Classification</h3>
      <p class="text-sm text-outline leading-relaxed">Define the intent and category of the listing.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
      <div class="space-y-1 mb-2">
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Property Title</label>
        <input name="title" value="<?php echo e($listing['title'] ?? ''); ?>" required class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" placeholder="E.g. Luxury 3BHK Apartment" type="text"/>
      </div>

      <!-- PROPERTY GROUP & LISTING PURPOSE ROW -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <!-- Property Group (Universal) -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Property Group</label>
          <select name="type" id="property_group" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 appearance-none font-medium" onchange="handleDynamicForm()">
            <option value="residential" <?php echo in_array($listing_type, ['residential']) ? 'selected' : ''; ?>>Residential</option>
            <option value="commercial" <?php echo in_array($listing_type, ['commercial']) ? 'selected' : ''; ?>>Commercial</option>
            <option value="land_plot" <?php echo in_array($listing_type, ['plot', 'land_plot', 'residential_plot', 'commercial_plot']) ? 'selected' : ''; ?>>Land / Plot</option>
          </select>
        </div>

        <!-- Listing Purpose (Universal) -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Listing Purpose</label>
          <div class="flex gap-2 p-1 bg-surface-container rounded-lg">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="listing_purpose" value="sale" class="peer sr-only" <?php echo ($listing['listing_purpose'] ?? 'sale') === 'sale' ? 'checked' : ''; ?> onchange="handleDynamicForm()">
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Sell</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="listing_purpose" value="rent" class="peer sr-only" <?php echo ($listing['listing_purpose'] ?? '') === 'rent' ? 'checked' : ''; ?> onchange="handleDynamicForm()">
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Rent</div>
            </label>
            <label class="flex-1 cursor-pointer" id="pg_purpose_label">
              <input type="radio" name="listing_purpose" value="pg" class="peer sr-only" <?php echo ($listing['listing_purpose'] ?? '') === 'pg' ? 'checked' : ''; ?> onchange="handleDynamicForm()">
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">PG</div>
            </label>
          </div>
        </div>
      </div>

      <!-- SPECIFIC TYPE DROPDOWN (Dynamic options based on group) -->
      <div id="sub_type_section">
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Specific Type</label>
        <select name="sub_type" id="sub_type" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 appearance-none font-medium">
          <!-- Populated by JavaScript -->
        </select>
      </div>

      <!-- TRANSACTION TYPE (Sale only for Residential/Commercial) -->
      <div id="transaction_section" class="hidden">
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Transaction Type</label>
        <div class="flex gap-4">
          <label class="flex items-center gap-2 cursor-pointer group">
            <input class="w-4 h-4 text-secondary focus:ring-secondary/20 border-outline-variant" name="transaction" type="radio" value="new" <?php echo ($listing['transaction'] ?? 'new') === 'new' ? 'checked' : ''; ?>/>
            <span class="text-sm font-medium text-on-surface group-hover:text-secondary">New Property</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer group">
            <input class="w-4 h-4 text-secondary focus:ring-secondary/20 border-outline-variant" name="transaction" type="radio" value="resale" <?php echo ($listing['transaction'] ?? '') === 'resale' ? 'checked' : ''; ?>/>
            <span class="text-sm font-medium text-on-surface group-hover:text-secondary">Resale</span>
          </label>
        </div>
      </div>

      <!-- ======================================== -->
      <!-- RESIDENTIAL: Building Type Selector      -->
      <!-- ======================================== -->
      <div id="residential_building_type_section" class="hidden space-y-6 pt-6 border-t border-secondary/10">
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Building Type</label>
        <div class="flex gap-2 p-1 bg-surface-container rounded-lg max-w-[600px]">
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="building_type" id="building_society" value="society" class="peer sr-only" <?php echo ($listing['building_type'] ?? 'society') === 'society' ? 'checked' : ''; ?> onchange="handleDynamicForm()">
            <div class="py-2 px-3 rounded-md text-xs font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Gated Complex/Society</div>
          </label>
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="building_type" id="building_standalone" value="standalone" class="peer sr-only" <?php echo ($listing['building_type'] ?? '') === 'standalone' ? 'checked' : ''; ?> onchange="handleDynamicForm()">
            <div class="py-2 px-3 rounded-md text-xs font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Standalone Building</div>
          </label>
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="building_type" id="building_house" value="house" class="peer sr-only" <?php echo ($listing['building_type'] ?? '') === 'house' ? 'checked' : ''; ?> onchange="handleDynamicForm()">
            <div class="py-2 px-3 rounded-md text-xs font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Independent House/Villa</div>
          </label>
        </div>
      </div>

      <!-- RESIDENTIAL: Society/Gated Complex Fields -->
      <div id="residential_society_fields" class="hidden space-y-6 pt-6 border-t border-secondary/10">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Project/Society Name</label>
            <input name="society_name" value="<?php echo e($listing['society_name'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" placeholder="e.g. The Imperial Heights" type="text"/>
          </div>
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Society Scale</label>
            <div class="flex gap-2">
              <label class="flex-1 cursor-pointer">
                <input type="radio" name="society_scale" value="small" class="peer sr-only" <?php echo ($listing['society_scale'] ?? 'small') === 'small' ? 'checked' : ''; ?>>
                <div class="py-2 px-3 border border-outline-variant rounded-md text-xs font-semibold text-center text-outline peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary hover:text-secondary transition-all block"><50 Units</div>
              </label>
              <label class="flex-1 cursor-pointer">
                <input type="radio" name="society_scale" value="medium" class="peer sr-only" <?php echo ($listing['society_scale'] ?? '') === 'medium' ? 'checked' : ''; ?>>
                <div class="py-2 px-3 border border-outline-variant rounded-md text-xs font-semibold text-center text-outline peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary hover:text-secondary transition-all block">50-100</div>
              </label>
              <label class="flex-1 cursor-pointer">
                <input type="radio" name="society_scale" value="large" class="peer sr-only" <?php echo ($listing['society_scale'] ?? '') === 'large' ? 'checked' : ''; ?>>
                <div class="py-2 px-3 border border-outline-variant rounded-md text-xs font-semibold text-center text-outline peer-checked:border-secondary peer-checked:text-secondary hover:border-secondary hover:text-secondary transition-all block">>100 Units</div>
              </label>
            </div>
          </div>
        </div>
        <!-- Society Amenities Checklist -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Society Amenities</label>
          <div class="flex flex-wrap gap-2">
            <?php
              $society_amenities_arr = [];
              if (!empty($listing['society_amenities'])) {
                $decoded = json_decode($listing['society_amenities'], true);
                if (is_array($decoded)) $society_amenities_arr = $decoded;
              }
              $society_amenities_options = ['Clubhouse', 'Swimming Pool', '24/7 Security', 'Power Backup', 'Gymnasium', 'Tennis Court', 'Kids Play Area', 'Jogging Track', 'Garden/Park', 'Visitor Parking', 'Intercom', 'Lift', 'Water Storage', 'Fire Safety'];
              foreach ($society_amenities_options as $amn):
                $is_checked = in_array($amn, $society_amenities_arr);
            ?>
              <label class="cursor-pointer group">
                 <input type="checkbox" name="society_amenities[]" value="<?php echo e($amn); ?>" class="peer sr-only" <?php echo $is_checked ? 'checked' : ''; ?>>
                 <span class="bg-surface-container-lowest text-on-surface-variant border border-outline px-3 py-1.5 rounded-full text-xs font-bold hover:bg-secondary-container/50 peer-checked:bg-secondary-container peer-checked:text-on-secondary-container peer-checked:border-secondary transition-all flex items-center gap-1 select-none">
                   <?php echo e($amn); ?>
                 </span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- RESIDENTIAL: Standalone Building Fields -->
      <div id="residential_standalone_fields" class="hidden space-y-6 pt-6 border-t border-secondary/10">
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Building Name</label>
          <input name="society_name" value="<?php echo e($listing['society_name'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" placeholder="e.g. Shanti Apartments" type="text"/>
        </div>
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Basic Utilities</label>
          <div class="flex flex-wrap gap-2">
            <?php
              $basic_amenities_options = ['Lift', 'Water Source', 'Power Backup', 'Fire Safety'];
              foreach ($basic_amenities_options as $amn):
                $is_checked = in_array($amn, $society_amenities_arr);
            ?>
              <label class="cursor-pointer group">
                 <input type="checkbox" name="society_amenities[]" value="<?php echo e($amn); ?>" class="peer sr-only" <?php echo $is_checked ? 'checked' : ''; ?>>
                 <span class="bg-surface-container-lowest text-on-surface-variant border border-outline px-3 py-1.5 rounded-full text-xs font-bold hover:bg-secondary-container/50 peer-checked:bg-secondary-container peer-checked:text-on-secondary-container peer-checked:border-secondary transition-all flex items-center gap-1 select-none">
                   <?php echo e($amn); ?>
                 </span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Water Source</label>
          <select name="water_source" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium max-w-[300px]">
            <option value="">Select</option>
            <option value="municipal" <?php echo ($listing['water_source'] ?? '') === 'municipal' ? 'selected' : ''; ?>>Municipal</option>
            <option value="borewell" <?php echo ($listing['water_source'] ?? '') === 'borewell' ? 'selected' : ''; ?>>Borewell</option>
            <option value="both" <?php echo ($listing['water_source'] ?? '') === 'both' ? 'selected' : ''; ?>>Both</option>
          </select>
        </div>
      </div>

      <!-- RESIDENTIAL: House/Villa Fields -->
      <div id="residential_house_fields" class="hidden space-y-6 pt-6 border-t border-secondary/10">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="relative">
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Plot Area</label>
            <div class="flex">
              <input name="plot_area" value="<?php echo e($listing['plot_area_sqft'] ?? ''); ?>" class="flex-1 bg-surface-container-lowest border-none rounded-l-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="Plot area"/>
              <select name="plot_area_unit" class="bg-surface-container-high border-none rounded-r-lg px-4 py-3 text-xs font-bold text-secondary focus:ring-0">
                <option value="sqft" <?php echo ($listing['area_unit'] ?? 'sqft') === 'sqft' ? 'selected' : ''; ?>>Sq. Ft</option>
                <option value="sqyd" <?php echo ($listing['area_unit'] ?? '') === 'sqyd' ? 'selected' : ''; ?>>Sq. Yards</option>
                <option value="sqm" <?php echo ($listing['area_unit'] ?? '') === 'sqm' ? 'selected' : ''; ?>>Sq. Meters</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Features</label>
            <div class="space-y-4">
              <div class="flex items-center gap-3">
                <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
                  <input class="peer appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200" id="private_garden" name="private_garden" type="checkbox" value="1" <?php echo ($listing['private_garden'] ?? 0) ? 'checked' : ''; ?>/>
                  <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="private_garden"></label>
                </div>
                <label class="text-sm font-semibold text-on-surface" for="private_garden">Private Garden</label>
              </div>
              <div class="flex items-center gap-3">
                <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
                  <input class="peer appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200" id="terrace" name="terrace" type="checkbox" value="1" <?php echo ($listing['terrace'] ?? 0) ? 'checked' : ''; ?>/>
                  <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="terrace"></label>
                </div>
                <label class="text-sm font-semibold text-on-surface" for="terrace">Private Terrace</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======================================== -->
  <!-- UNIVERSAL: Location Fields               -->
  <!-- ======================================== -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Location</h3>
      <p class="text-sm text-outline leading-relaxed">Geospatial precision is key for client trust.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm border-l-4 border-secondary space-y-8">
      <div class="space-y-1">
        <label class="text-xs font-bold uppercase tracking-wider text-secondary">Full Address / Landmark</label>
        <input name="location" value="<?php echo e($listing['location'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary focus:bg-surface-container-lowest transition-all rounded-t-lg px-4 py-3 text-sm font-medium outline-none" placeholder="e.g. EM Bypass, Kolkata" type="text"/>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">City</label>
          <input name="city" value="<?php echo e($listing['city'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary focus:bg-surface-container-lowest transition-all rounded-t-lg px-4 py-3 text-sm font-medium outline-none" placeholder="e.g. Kolkata" type="text"/>
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Pincode</label>
          <input name="pincode" value="<?php echo e($listing['pincode'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary focus:bg-surface-container-lowest transition-all rounded-t-lg px-4 py-3 text-sm font-medium outline-none" placeholder="e.g. 700001" type="text"/>
        </div>
      </div>

      <!-- Floor Details (Hidden for Land/Plot) -->
      <div id="floor_details_section">
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Floor Details</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Floor Number</label>
            <input name="floor_number" value="<?php echo e($listing['floor_number'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 5"/>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Total Floors</label>
            <input name="total_floors" value="<?php echo e($listing['total_floors'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 20"/>
          </div>
        </div>
      </div>

      <!-- ======================================== -->
      <!-- LAND/PLOT: Specific Fields               -->
      <!-- ======================================== -->
      <div id="land_specific_fields" class="hidden space-y-8 pt-6 border-t border-secondary/10">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="relative">
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Total Area</label>
            <div class="flex">
              <input name="area_sqft" value="<?php echo e($listing['area_sqft'] ?? ''); ?>" class="flex-1 bg-surface-container-lowest border-none rounded-l-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="Total area"/>
              <select name="area_unit" class="bg-surface-container-high border-none rounded-r-lg px-4 py-3 text-xs font-bold text-secondary focus:ring-0">
                <option value="sqyd" <?php echo ($listing['area_unit'] ?? 'sqyd') === 'sqyd' ? 'selected' : ''; ?>>Sq. Yards</option>
                <option value="sqm" <?php echo ($listing['area_unit'] ?? '') === 'sqm' ? 'selected' : ''; ?>>Sq. Meters</option>
                <option value="acres" <?php echo ($listing['area_unit'] ?? '') === 'acres' ? 'selected' : ''; ?>>Acres</option>
                <option value="sqft" <?php echo ($listing['area_unit'] ?? '') === 'sqft' ? 'selected' : ''; ?>>Sq. Ft</option>
              </select>
            </div>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Road Width (feet)</label>
            <input name="road_width" value="<?php echo e($listing['road_width'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 30"/>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Plot Length</label>
            <input name="plot_length" value="<?php echo e($listing['plot_length'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 100"/>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Plot Width</label>
            <input name="plot_width" value="<?php echo e($listing['plot_width'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 50"/>
          </div>
        </div>
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Land Features</label>
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
                <input class="peer appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200" id="boundary_wall" name="boundary_wall" type="checkbox" value="1" <?php echo ($listing['boundary_wall'] ?? 0) ? 'checked' : ''; ?>/>
                <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="boundary_wall"></label>
              </div>
              <label class="text-sm font-semibold text-on-surface" for="boundary_wall">Boundary Wall Made</label>
            </div>
            <div class="flex items-center gap-3">
              <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
                <input class="peer appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200" id="corner_plot" name="corner_plot" type="checkbox" value="1" <?php echo ($listing['corner_plot'] ?? 0) ? 'checked' : ''; ?>/>
                <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="corner_plot"></label>
              </div>
              <label class="text-sm font-semibold text-on-surface" for="corner_plot">Corner Plot</label>
            </div>
          </div>
        </div>
        <!-- Land Regulatory Fields -->
        <div class="space-y-6 pt-4 border-t border-secondary/10">
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Zoning & Approvals</label>
          <div class="flex items-center gap-3 mb-2">
            <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
              <input class="peer appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200" id="na_approved" name="na_approved" type="checkbox" value="1" <?php echo ($listing['na_approved'] ?? 0) ? 'checked' : ''; ?> onchange="handleDynamicForm()"/>
              <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="na_approved"></label>
            </div>
            <label class="text-sm font-semibold text-on-surface" for="na_approved">NA (Non-Agricultural) Approved</label>
          </div>
          <div id="na_type_container" class="hidden">
            <select name="na_type" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium max-w-[300px]">
              <option value="">Select NA Type</option>
              <option value="na_residential" <?php echo ($listing['na_type'] ?? '') === 'na_residential' ? 'selected' : ''; ?>>NA - Residential</option>
              <option value="na_commercial" <?php echo ($listing['na_type'] ?? '') === 'na_commercial' ? 'selected' : ''; ?>>NA - Commercial</option>
              <option value="na_industrial" <?php echo ($listing['na_type'] ?? '') === 'na_industrial' ? 'selected' : ''; ?>>NA - Industrial</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======================================== -->
  <!-- RESIDENTIAL: Configuration (BHK, etc.)   -->
  <!-- ======================================== -->
  <section id="physical_attributes_section">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
      <div class="md:col-span-4">
        <h3 class="text-xl font-headline font-bold text-secondary mb-2">Dimensions</h3>
        <p class="text-sm text-outline leading-relaxed">Structural specifics of the property.</p>
      </div>
      <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
        <!-- BHK, Bathrooms, Balconies (Residential only) -->
        <div id="residential_dim_fields">
          <div class="grid grid-cols-3 gap-6">
            <div class="space-y-1">
              <label class="text-xs font-bold uppercase tracking-wider text-secondary">Configuration</label>
              <select name="bedrooms" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 appearance-none font-medium">
                <option value="">Select</option>
                <option value="1" <?php echo ($listing['bedrooms'] ?? '') == 1 ? 'selected' : ''; ?>>1 BHK</option>
                <option value="2" <?php echo ($listing['bedrooms'] ?? '') == 2 ? 'selected' : ''; ?>>2 BHK</option>
                <option value="3" <?php echo ($listing['bedrooms'] ?? '') == 3 ? 'selected' : ''; ?>>3 BHK</option>
                <option value="4" <?php echo ($listing['bedrooms'] ?? '') == 4 ? 'selected' : ''; ?>>4 BHK</option>
                <option value="5" <?php echo ($listing['bedrooms'] ?? '') == 5 ? 'selected' : ''; ?>>5+ BHK</option>
              </select>
            </div>
            <div class="space-y-1">
              <label class="text-xs font-bold uppercase tracking-wider text-secondary">Bathrooms</label>
              <input name="bathrooms" value="<?php echo e($listing['bathrooms'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number"/>
            </div>
            <div class="space-y-1">
              <label class="text-xs font-bold uppercase tracking-wider text-secondary">Balconies</label>
              <input name="balconies" value="<?php echo e($listing['balconies'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number"/>
            </div>
          </div>
        </div>

        <!-- Commercial: Washrooms & Furnishing -->
        <div id="commercial_dim_fields" class="hidden space-y-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div class="space-y-1">
              <label class="text-xs font-bold uppercase tracking-wider text-secondary">Washrooms</label>
              <select name="washrooms_type" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 appearance-none font-medium">
                <option value="">Select</option>
                <option value="private" <?php echo ($listing['washrooms_type'] ?? '') === 'private' ? 'selected' : ''; ?>>Private</option>
                <option value="shared" <?php echo ($listing['washrooms_type'] ?? '') === 'shared' ? 'selected' : ''; ?>>Shared</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Furnishing Status</label>
              <div class="flex gap-2">
                <label class="flex-1 cursor-pointer">
                  <input type="radio" name="furnishing" value="bare_shell" class="peer sr-only" <?php echo ($listing['furnishing'] ?? '') === 'bare_shell' ? 'checked' : ''; ?>>
                  <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary transition-all block">Bare Shell</div>
                </label>
                <label class="flex-1 cursor-pointer">
                  <input type="radio" name="furnishing" value="warm_shell" class="peer sr-only" <?php echo ($listing['furnishing'] ?? '') === 'warm_shell' ? 'checked' : ''; ?>>
                  <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary transition-all block">Warm Shell</div>
                </label>
                <label class="flex-1 cursor-pointer">
                  <input type="radio" name="furnishing" value="fully_furnished" class="peer sr-only" <?php echo ($listing['furnishing'] ?? '') === 'fully_furnished' ? 'checked' : ''; ?>>
                  <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary transition-all block">Fully Furn.</div>
                </label>
              </div>
            </div>
          </div>
          <!-- Commercial Building Amenities -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Building Amenities</label>
            <div class="grid grid-cols-2 gap-4">
              <?php
                $comm_amenities = ['Central AC' => 'central_ac', 'DG Power Backup' => 'dg_power_backup', 'Cafeteria/Pantry' => 'cafeteria_pantry', 'Visitor Parking' => 'visitor_parking'];
                foreach ($comm_amenities as $label => $field):
                  $checked = ($listing[$field] ?? 0) ? 'checked' : '';
              ?>
                <div class="flex items-center gap-3">
                  <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
                    <input class="peer appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200" id="<?php echo $field; ?>" name="<?php echo $field; ?>" type="checkbox" value="1" <?php echo $checked; ?>/>
                    <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="<?php echo $field; ?>"></label>
                  </div>
                  <label class="text-sm font-semibold text-on-surface" for="<?php echo $field; ?>"><?php echo $label; ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Area Fields (All types) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="relative">
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2" id="area_primary_label">Built-up Area (Sq. Ft.)</label>
            <div class="flex">
              <input name="area_sqft" value="<?php echo e($listing['area_sqft'] ?? ''); ?>" class="flex-1 bg-surface-container-lowest border-none rounded-l-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 1500"/>
              <select name="area_unit_residential" id="area_unit_residential" class="bg-surface-container-high border-none rounded-r-lg px-4 py-3 text-xs font-bold text-secondary focus:ring-0">
                <option value="sqft" <?php echo ($listing['area_unit_residential'] ?? 'sqft') === 'sqft' ? 'selected' : ''; ?>>Sq. Ft</option>
                <option value="sqyd" <?php echo ($listing['area_unit_residential'] ?? '') === 'sqyd' ? 'selected' : ''; ?>>Sq. Yards</option>
              </select>
            </div>
          </div>
          <div class="relative" id="carpet_area_row">
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Carpet Area (Sq. Ft.)</label>
            <div class="flex">
              <input name="carpet_area" value="<?php echo e($listing['carpet_area'] ?? ''); ?>" class="flex-1 bg-surface-container-lowest border-none rounded-l-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 1200"/>
              <span class="bg-surface-container-high border-none rounded-r-lg px-4 py-3 text-xs font-bold text-secondary">Sq. Ft</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======================================== -->
  <!-- UNIVERSAL: RERA Compliance               -->
  <!-- ======================================== -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">RERA Compliance</h3>
      <p class="text-sm text-outline leading-relaxed">Regulatory details for legal verification.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">RERA Registered?</label>
          <div class="flex gap-2 p-1 bg-surface-container rounded-lg max-w-[200px]">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="is_rera" value="1" class="peer sr-only" <?php echo ($listing['is_rera'] ?? 0) ? 'checked' : ''; ?> onchange="toggleRera(true)">
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Yes</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="is_rera" value="0" class="peer sr-only" <?php echo !($listing['is_rera'] ?? 0) ? 'checked' : ''; ?> onchange="toggleRera(false)">
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">No</div>
            </label>
          </div>
        </div>
        <div class="space-y-1" id="rera_no_container" <?php echo !($listing['is_rera'] ?? 0) ? 'style="opacity:0.5"' : ''; ?>>
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">RERA Registration Number</label>
          <input name="rera_id" id="rera_no" value="<?php echo e($listing['rera_id'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" placeholder="e.g. P51800000000" type="text" <?php echo !($listing['is_rera'] ?? 0) ? 'disabled' : ''; ?>/>
        </div>
      </div>
      <div id="rera_cert_container" <?php echo !($listing['is_rera'] ?? 0) ? 'style="opacity:0.5"' : ''; ?>>
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">RERA Certificate</label>
        <div class="flex items-center gap-4 bg-surface-container-lowest p-4 rounded-lg border-2 border-dashed border-outline-variant hover:border-secondary transition-all group cursor-pointer">
          <input type="file" id="upload_rera" class="hidden" accept=".pdf,.jpg,.png">
          <span class="material-symbols-outlined text-outline group-hover:text-secondary">upload_file</span>
          <div class="flex-1">
            <p class="text-xs font-semibold text-on-surface-variant">Upload Official RERA Certificate (PDF/JPG)</p>
          </div>
          <button class="text-secondary text-xs font-bold hover:underline" type="button" onclick="document.getElementById('upload_rera').click()">Upload</button>
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

  <!-- ======================================== -->
  <!-- Financials: Dynamic based on Group+Purpose -->
  <!-- ======================================== -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Financials</h3>
      <p class="text-sm text-outline leading-relaxed" id="financials_desc">Valuation details and commercial status.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm border-l-4 border-on-primary-container space-y-8">
      
      <!-- RESIDENTIAL SALE -->
      <div id="res_sale_fields">
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
              <input class="appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-on-primary-container cursor-pointer transition-colors duration-200 peer" id="is_negotiable_sale" name="is_negotiable" type="checkbox" value="1" <?php echo ($listing['is_negotiable'] ?? 0) ? 'checked' : ''; ?>/>
              <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="is_negotiable_sale"></label>
            </div>
            <label class="text-sm font-semibold text-secondary cursor-pointer" for="is_negotiable_sale">Price is Negotiable</label>
          </div>
        </div>
      </div>

      <!-- RESIDENTIAL RENT -->
      <div id="res_rent_fields" class="hidden space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Expected Monthly Rent (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="monthly_rent" value="<?php echo e($listing['monthly_rent'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-lg font-headline font-bold outline-none tabular-nums" placeholder="25000" type="number"/>
            </div>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Security Deposit (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="security_deposit" value="<?php echo e($listing['security_deposit'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-sm font-medium outline-none" placeholder="50000" type="number"/>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Tenant Preference</label>
          <div class="flex gap-2 p-1 bg-surface-container rounded-lg max-w-[400px]">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="tenant_preference" value="families" class="peer sr-only" <?php echo ($listing['tenant_preference'] ?? 'families') === 'families' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Families</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="tenant_preference" value="bachelors" class="peer sr-only" <?php echo ($listing['tenant_preference'] ?? '') === 'bachelors' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Bachelors</div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="tenant_preference" value="company" class="peer sr-only" <?php echo ($listing['tenant_preference'] ?? '') === 'company' ? 'checked' : ''; ?>>
              <div class="py-2 px-4 rounded-md text-sm font-semibold text-center text-outline peer-checked:bg-white peer-checked:text-secondary peer-checked:shadow-sm hover:bg-white/50 transition-all block">Company Lease</div>
            </label>
          </div>
        </div>
      </div>

      <!-- RESIDENTIAL PG -->
      <div id="res_pg_fields" class="hidden space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Monthly Rent per Bed (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="monthly_rent" value="<?php echo e($listing['monthly_rent'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-lg font-headline font-bold outline-none tabular-nums" placeholder="8000" type="number"/>
            </div>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Security Deposit (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="security_deposit" value="<?php echo e($listing['security_deposit'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-sm font-medium outline-none" placeholder="16000" type="number"/>
            </div>
          </div>
        </div>
      </div>

      <!-- COMMERCIAL SALE -->
      <div id="com_sale_fields" class="hidden space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-end">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Total Price (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="price" value="<?php echo e($listing['price'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-lg font-headline font-bold outline-none tabular-nums" placeholder="10000000" type="number"/>
            </div>
          </div>
          <div class="pb-3 flex items-center gap-3">
            <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
              <input class="appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-on-primary-container cursor-pointer transition-colors duration-200 peer" id="is_negotiable_com" name="is_negotiable" type="checkbox" value="1" <?php echo ($listing['is_negotiable'] ?? 0) ? 'checked' : ''; ?>/>
              <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="is_negotiable_com"></label>
            </div>
            <label class="text-sm font-semibold text-secondary cursor-pointer" for="is_negotiable_com">Price Negotiable</label>
          </div>
        </div>
        <!-- Pre-leased -->
        <div class="flex items-center gap-3">
          <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
            <input class="appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200 peer" id="pre_leased" name="pre_leased" type="checkbox" value="1" <?php echo ($listing['pre_leased'] ?? 0) ? 'checked' : ''; ?> onchange="handleDynamicForm()"/>
            <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="pre_leased"></label>
          </div>
          <label class="text-sm font-semibold text-on-surface" for="pre_leased">Pre-Leased Property</label>
        </div>
        <div id="pre_leased_fields" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4 border-t border-secondary/10">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Current Tenant Name</label>
            <input name="current_tenant" value="<?php echo e($listing['current_tenant'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="text" placeholder="e.g. ABC Corp"/>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Monthly Rent (₹)</label>
            <input name="monthly_rent_received" value="<?php echo e($listing['monthly_rent_received'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 50000"/>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Lease Expiry Date</label>
            <input name="lease_expiry_date" value="<?php echo e($listing['lease_expiry_date'] ?? ''); ?>" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="date"/>
          </div>
        </div>
      </div>

      <!-- COMMERCIAL RENT -->
      <div id="com_rent_fields" class="hidden space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Monthly Rent (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="monthly_rent" value="<?php echo e($listing['monthly_rent'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-lg font-headline font-bold outline-none tabular-nums" placeholder="50000" type="number"/>
            </div>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Security Deposit (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="security_deposit" value="<?php echo e($listing['security_deposit'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-sm font-medium outline-none" placeholder="100000" type="number"/>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Lock-in Period</label>
            <div class="flex">
              <input name="lockin_period_months" value="<?php echo e($listing['lockin_period_months'] ?? ''); ?>" class="flex-1 bg-surface-container-lowest border-none rounded-l-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium" type="number" placeholder="e.g. 6"/>
              <select name="lockin_period_unit" class="bg-surface-container-high border-none rounded-r-lg px-4 py-3 text-xs font-bold text-secondary focus:ring-0">
                <option value="months">Months</option>
                <option value="years">Years</option>
              </select>
            </div>
          </div>
          <div class="pb-3 flex items-center gap-3">
            <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
              <input class="appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-secondary cursor-pointer transition-colors duration-200 peer" id="revenue_share" name="revenue_share_model" type="checkbox" value="1" <?php echo ($listing['revenue_share_model'] ?? 0) ? 'checked' : ''; ?>/>
              <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="revenue_share"></label>
            </div>
            <label class="text-sm font-semibold text-on-surface" for="revenue_share">Revenue Share (Retail)</label>
          </div>
        </div>
      </div>

      <!-- LAND/PLOT SALE -->
      <div id="land_sale_fields" class="hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-end">
          <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Total Price (₹)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-medium">₹</span>
              <input name="price" value="<?php echo e($listing['price'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-on-primary-container focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-lg font-headline font-bold outline-none tabular-nums" placeholder="5000000" type="number"/>
            </div>
          </div>
          <div class="pb-3 flex items-center gap-3">
            <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
              <input class="appearance-none w-10 h-6 rounded-full bg-surface-container-high checked:bg-on-primary-container cursor-pointer transition-colors duration-200 peer" id="is_negotiable_land" name="is_negotiable" type="checkbox" value="1" <?php echo ($listing['is_negotiable'] ?? 0) ? 'checked' : ''; ?>/>
              <label class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-4 cursor-pointer" for="is_negotiable_land"></label>
            </div>
            <label class="text-sm font-semibold text-secondary cursor-pointer" for="is_negotiable_land">Price Negotiable</label>
          </div>
        </div>
      </div>

      <!-- Construction Status (Not for Rent/PG) -->
      <div id="construction_status_section">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Construction Status</label>
            <div class="flex gap-2 p-1 bg-surface-container rounded-lg max-w-[300px]">
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
    </div>
  </section>

  <!-- ======================================== -->
  <!-- Amenities & Features (Residential Only)  -->
  <!-- ======================================== -->
  <section id="amenities_features_section">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
      <div class="md:col-span-4">
        <h3 class="text-xl font-headline font-bold text-secondary mb-2">Amenities</h3>
        <p class="text-sm text-outline leading-relaxed">Lifestyle features that enhance property value.</p>
      </div>
      <div class="md:col-span-8 bg-surface-container-low p-8 rounded-xl space-y-8">
        <!-- Residential: Furnishing & Parking -->
        <div id="res_furn_parking">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Furnishing</label>
              <div class="flex gap-2">
                <label class="flex-1 cursor-pointer">
                  <input type="radio" name="furnishing" value="fully_furnished" class="peer sr-only" <?php echo ($listing['furnishing'] ?? '') === 'fully_furnished' ? 'checked' : ''; ?>>
                  <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary transition-all block">Fully</div>
                </label>
                <label class="flex-1 cursor-pointer">
                  <input type="radio" name="furnishing" value="semi_furnished" class="peer sr-only" <?php echo ($listing['furnishing'] ?? '') === 'semi_furnished' ? 'checked' : ''; ?>>
                  <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary transition-all block">Semi</div>
                </label>
                <label class="flex-1 cursor-pointer">
                  <input type="radio" name="furnishing" value="unfurnished" class="peer sr-only" <?php echo ($listing['furnishing'] ?? 'unfurnished') === 'unfurnished' ? 'checked' : ''; ?>>
                  <div class="px-4 py-2 bg-surface-container-lowest rounded-md text-xs font-bold border border-outline text-on-surface-variant text-center peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary transition-all block">Unfurnished</div>
                </label>
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Parking</label>
              <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="parking" value="open" <?php echo ($listing['parking'] ?? '') === 'open' ? 'checked' : ''; ?> class="rounded border-outline-variant text-secondary focus:ring-secondary/20"/>
                  <span class="text-sm font-medium">Open</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="parking" value="covered" <?php echo ($listing['parking'] ?? '') === 'covered' ? 'checked' : ''; ?> class="rounded border-outline-variant text-secondary focus:ring-secondary/20"/>
                  <span class="text-sm font-medium">Covered</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <!-- Special Features -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-4">Special Features</label>
          <div class="flex flex-wrap gap-2">
            <?php
              $amenities_arr = !empty($listing['amenities']) ? explode(',', $listing['amenities']) : [];
              $amenities_options = ['Vastu Compliant', '24x7 Security', 'Power Backup', 'Lift', 'Water Storage', 'Fire Safety', 'Visitor Parking'];
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
          <label class="text-xs font-bold uppercase tracking-wider text-secondary">Property USP (Description)</label>
          <textarea name="description" class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-secondary/20 font-medium leading-relaxed" placeholder="Describe what makes this property unique..." rows="4"><?php echo e($listing['description'] ?? ''); ?></textarea>
        </div>
      </div>
    </div>
  </section>

  <!-- ======================================== -->
  <!-- UNIVERSAL: Media & Attachments           -->
  <!-- ======================================== -->
  <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
    <div class="md:col-span-4">
      <h3 class="text-xl font-headline font-bold text-secondary mb-2">Media & Attachments</h3>
      <p class="text-sm text-outline leading-relaxed">Visual assets and technical plans.</p>
    </div>
    <div class="md:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm border-l-4 border-secondary space-y-8">
      <!-- Main Photo Gallery -->
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-secondary mb-2">Main Photo Gallery</label>
        <span class="block text-[10px] text-outline mb-4">Recommended: 1200x800 pixels (3:2 ratio)</span>
        <div class="border-2 border-dashed border-outline-variant rounded-xl p-10 flex flex-col items-center justify-center bg-surface-container-low/30 hover:bg-surface-container-low transition-colors group cursor-pointer" onclick="document.getElementById('upload_photos').click()">
          <input type="file" id="upload_photos" name="gallery[]" accept="image/*" multiple class="hidden" onchange="renderThumbnails(this)" onclick="event.stopPropagation()">
          <span class="material-symbols-outlined text-4xl text-outline group-hover:text-secondary mb-4">add_a_photo</span>
          <p class="text-sm font-semibold text-on-surface-variant mb-4">Drag and drop images here</p>
          <button class="bg-white border border-outline-variant text-secondary px-6 py-2 rounded-md font-bold text-xs hover:border-secondary transition-all" type="button">Upload Photos</button>
          <p id="photo-status" class="text-xs text-secondary mt-2 font-bold"></p>
        </div>
        <div class="flex gap-4 mt-6 flex-wrap" id="photo-preview-container">
          <?php if(!empty($listing['image_filename'])): ?>
          <?php $existing_images = array_values(array_filter(array_map('trim', explode(',', (string)$listing['image_filename'])))); ?>
          <?php foreach (array_slice($existing_images, 0, 4) as $img): ?>
          <div class="w-20 h-20 rounded-lg bg-surface-container overflow-hidden border border-outline-variant">
            <img class="w-full h-full object-cover" src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($img); ?>"/>
          </div>
          <?php endforeach; ?>
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
          <label class="block text-xs font-bold uppercase tracking-wider text-secondary">Video Tour URL</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">link</span>
            <input name="video_url" value="<?php echo e($listing['video_url'] ?? ''); ?>" class="w-full bg-surface-container border-b-2 border-transparent focus:border-secondary focus:bg-surface-container-lowest transition-all rounded-t-lg pl-10 pr-4 py-3 text-sm font-medium outline-none" placeholder="YouTube or Matterport Link" type="url"/>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======================================== -->
  <!-- Action Buttons                           -->
  <!-- ======================================== -->
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
// ========================================
// Dynamic Form Logic
// ========================================
function handleDynamicForm() {
  const propGroup = document.getElementById('property_group').value;
  const purpose = document.querySelector('input[name="listing_purpose"]:checked')?.value || 'sale';

  // Get building type
  const buildingType = document.querySelector('input[name="building_type"]:checked')?.value || 'society';

  // Hide ALL conditional sections first
  hideAllSections();

  // Based on property group, show relevant sections
  if (propGroup === 'residential') {
    showResidentialFields(buildingType, purpose);
  } else if (propGroup === 'commercial') {
    showCommercialFields(purpose);
  } else if (propGroup === 'land_plot') {
    showLandFields(purpose);
  }

  // Update sub-type dropdown options
  updateSubTypeOptions(propGroup);
}

function setSectionVisibility(id, isVisible) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.toggle('hidden', !isVisible);
  el.querySelectorAll('input, select, textarea').forEach((field) => {
    field.disabled = !isVisible;
  });
}

function hideAllSections() {
  // Building type sections
  const hide = (id) => setSectionVisibility(id, false);
  [
    'residential_building_type_section',
    'residential_society_fields',
    'residential_standalone_fields',
    'residential_house_fields',
    'transaction_section',
    'floor_details_section',
    'land_specific_fields',
    'residential_dim_fields',
    'commercial_dim_fields',
    'res_sale_fields',
    'res_rent_fields',
    'res_pg_fields',
    'com_sale_fields',
    'com_rent_fields',
    'land_sale_fields',
    'construction_status_section',
    'amenities_features_section',
    'res_furn_parking',
    'na_type_container',
    'pg_purpose_label',
    'pre_leased_fields',
    'carpet_area_row'
  ].forEach(hide);
}

function showResidentialFields(buildingType, purpose) {
  const show = (id) => setSectionVisibility(id, true);

  // Always show
  show('residential_building_type_section');
  show('floor_details_section');
  show('residential_dim_fields');
  show('amenities_features_section');
  show('res_furn_parking');
  show('carpet_area_row');

  // Building type specific
  if (buildingType === 'society') {
    show('residential_society_fields');
  } else if (buildingType === 'standalone') {
    show('residential_standalone_fields');
  } else if (buildingType === 'house') {
    show('residential_house_fields');
  }

  // Purpose specific
  if (purpose === 'sale') {
    show('transaction_section');
    show('res_sale_fields');
    show('construction_status_section');
  } else if (purpose === 'rent') {
    show('res_rent_fields');
  } else if (purpose === 'pg') {
    show('res_pg_fields');
  }

  // Hide construction status for rent/pg
}

function showCommercialFields(purpose) {
  const show = (id) => setSectionVisibility(id, true);

  show('floor_details_section');
  show('commercial_dim_fields');

  if (purpose === 'sale') {
    show('transaction_section');
    show('com_sale_fields');
    show('construction_status_section');
    // Check if pre-leased is checked to show pre-leased fields
    if (document.getElementById('pre_leased')?.checked) {
      show('pre_leased_fields');
    }
  } else if (purpose === 'rent') {
    show('com_rent_fields');
  }
}

function showLandFields(purpose) {
  const show = (id) => setSectionVisibility(id, true);

  show('land_specific_fields');

  if (document.getElementById('na_approved')?.checked) {
    show('na_type_container');
  }

  if (purpose === 'sale') {
    show('land_sale_fields');
    show('construction_status_section');
  }
}

function updateSubTypeOptions(propGroup) {
  const select = document.getElementById('sub_type');
  select.innerHTML = '';

  const options = {
    residential: [
      { value: 'apartment', label: 'Apartment / Flat' },
      { value: 'builder_floor', label: 'Builder Floor' },
      { value: 'independent_house', label: 'Independent House / Villa' },
      { value: 'penthouse', label: 'Penthouse' },
      { value: 'studio', label: 'Studio Apartment' }
    ],
    commercial: [
      { value: 'office_space', label: 'Office Space' },
      { value: 'retail_shop', label: 'Retail Shop / Showroom' },
      { value: 'warehouse', label: 'Warehouse / Godown' },
      { value: 'co_working', label: 'Co-working Space' },
      { value: 'institutional', label: 'Institutional' }
    ],
    land_plot: [
      { value: 'residential_plot', label: 'Residential Plot' },
      { value: 'commercial_plot', label: 'Commercial Plot' },
      { value: 'agricultural_land', label: 'Agricultural Land' },
      { value: 'industrial_plot', label: 'Industrial Plot' }
    ]
  };

  const opts = options[propGroup] || [];
  opts.forEach(opt => {
    const option = document.createElement('option');
    option.value = opt.value;
    option.textContent = opt.label;
    select.appendChild(option);
  });

  // Set current selection if editing
  const currentSubType = '<?php echo e($listing["sub_type"] ?? ""); ?>';
  if (currentSubType) {
    for (const opt of select.options) {
      if (opt.value === currentSubType) {
        select.value = currentSubType;
        break;
      }
    }
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', handleDynamicForm);

// Thumbnail rendering for photo uploads
window.renderThumbnails = function(input) {
  document.getElementById('photo-status').textContent = input.files.length + ' photos selected';
  const container = document.getElementById('photo-preview-container');
  container.innerHTML = '';
  for(let i = 0; i < input.files.length; i++) {
    const file = input.files[i];
    const url = URL.createObjectURL(file);
    const div = document.createElement('div');
    div.className = 'w-20 h-20 rounded-lg bg-surface-container overflow-hidden border border-outline-variant relative group';
    div.innerHTML = `<img class="w-full h-full object-cover" src="${url}"/>`;
    container.appendChild(div);
  }
};

// Form submission handler
(function () {
  const form = document.getElementById('listing-form');
  if (!form) return;

  form.addEventListener('submit', async function (ev) {
    ev.preventDefault();
    const toast = document.getElementById('listing-form-toast');
    const status = document.getElementById('form-status');
    const data = new FormData(form);

    // Debug: Log the ID being sent
    const listingId = data.get('id');
    console.log('Form submission - ID:', listingId, 'Type:', typeof listingId);

    status.textContent = 'Saving…';
    status.className = "text-xs font-bold text-outline uppercase";
    try {
      const res = await fetch('../admin/api/save-listing.php', { method: 'POST', body: data });
      const json = await res.json();
      console.log('Server response:', json);
      
      if (json.success) {
        let message = 'Property saved successfully!';
        if (json.uploaded_count > 0) {
          message += ` (${json.uploaded_count} images uploaded)`;
        }
        
        // Show upload warnings if any
        if (json.upload_warnings && json.upload_warnings.length > 0) {
          message += '\n\nUpload warnings:\n' + json.upload_warnings.join('\n');
          console.warn('Upload warnings:', json.upload_warnings);
        }
        
        toast.className = 'fixed right-6 top-24 z-50 rounded-xl bg-tertiary-container text-white p-4 text-sm font-semibold shadow-lg';
        toast.textContent = message;
        toast.classList.remove('hidden');
        status.textContent = 'Saved ' + new Date().toLocaleTimeString();
        status.className = "text-xs font-bold text-tertiary uppercase";
        setTimeout(() => toast.classList.add('hidden'), 3000);
      } else {
        // ERROR: Red notification bar, visible for 15 seconds
        toast.className = 'fixed right-6 top-24 z-50 rounded-xl bg-red-600 text-white p-5 text-sm font-semibold shadow-lg border-2 border-red-400';
        toast.innerHTML = '<div class="flex items-start gap-3"><span class="material-symbols-outlined text-2xl">error</span><div><p class="font-bold mb-1">Save Failed</p><p class="text-xs font-normal opacity-90">' + (json.message || 'An error occurred while saving.') + '</p></div></div>';
        toast.classList.remove('hidden');
        status.textContent = '';
        status.className = "text-xs font-bold text-red-500 uppercase";
        // Hold error notification for 15 seconds
        setTimeout(() => toast.classList.add('hidden'), 15000);
      }
    } catch (err) {
      // NETWORK ERROR: Red notification bar, visible for 15 seconds
      toast.className = 'fixed right-6 top-24 z-50 rounded-xl bg-red-600 text-white p-5 text-sm font-semibold shadow-lg border-2 border-red-400';
      toast.innerHTML = '<div class="flex items-start gap-3"><span class="material-symbols-outlined text-2xl">wifi_off</span><div><p class="font-bold mb-1">Network Error</p><p class="text-xs font-normal opacity-90">Could not connect to server. Please check your connection and try again.</p></div></div>';
      toast.classList.remove('hidden');
      status.textContent = '';
      status.className = "text-xs font-bold text-red-500 uppercase";
      // Hold error notification for 15 seconds
      setTimeout(() => toast.classList.add('hidden'), 15000);
    }
  });
})();
</script>
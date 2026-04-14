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
    $listing = [
        'title' => 'The Marble Villa',
        'city' => 'Kolkata',
        'location' => 'Alipore',
        'society_name' => '',
        'price' => 84500000,
        'image_filename' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB7jVCO9aynJFTpwxz2CwOEp-xK5JWsW-gMcDo_oLLntgHe7Wxza3xSP6AwUxtirGMggSKe-2OqT15y94oC67V8ZbO3y5dS9cTfxe6BuuNHioo9m3oDyw6bRNYrUvgRLbHcQ01471ttm2jHCPfj9EbeZ3lJ0gwxJ7dRnF8mEDNROuSeVL3TG3zVILlouspy4zLvn3SqczzZUJqoMtcrWtMEsP28k3pFzwr-ZZnl_0WtYYoVY-dd5nhzay9Ga-LcScAuPHkb1xyzBJ8',
        'is_rera' => 1,
        'rera_id' => 'HIRA/P/KOL/2024/000452',
        'bedrooms' => '4.5',
        'possession_status' => 'Dec 2026',
        'total_floors' => 'G+24',
        'area_sqft' => '3240',
        'bathrooms' => '4',
        'type' => 'residential',
        'description' => 'Emerging from the historic Alipore skyline, The Marble Villa represents a paradigm shift in urban living. Designed by award-winning curators, the structure utilizes a seamless skeleton of white Carrara marble and reinforced glass to invite the city\'s light while maintaining absolute sanctuary. Every corridor is a gallery; every window, a canvas of Kolkata\'s evolving heritage.',
        'orientation' => 'South-Open & Vastu Compliant',
        'floor_height' => '11.5 Feet',
        'amenities' => json_encode([
            ['icon' => 'fitness_center', 'name' => 'Technogym Studio'],
            ['icon' => 'spa', 'name' => 'Ayurvedic Wellness Spa'],
            ['icon' => 'theater_comedy', 'name' => 'Private Screening Room'],
            ['icon' => 'local_library', 'name' => "The Curator's Library"],
            ['icon' => 'ev_station', 'name' => 'Dual EV Chargers'],
            ['icon' => 'security', 'name' => '5-Tier Biometric Security'],
        ]),
        'floor_plan_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBRhBmNDuCbn3fsi2DI6dBMOq0O-lfjqGIaaUwrv1rx6J4r3sk2TET7g-JYbtumDZKnEFTS0Wio_jBkTihbfLKYLuly7Y3nhzgrdlk1bP8EtKYvnO79-6NIf7opC8C-ntUTjiBpAmip3Bt-LmtL2XJavBz-wg6b_mhcyLJPd6nySuzIKcHZui3eU9qURAm-nJdbPNP5X6yCMw4WD2Sj8BZEPonj-MZoaEmNlbF22szWM5cnYIZ9ue3ofOaOrRBPY6zboHRi6GodK3c',
        'map_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDcSj01r8GrA1FPU7YiBVpDyLN42pTh_QSN0czp13nostcZKjoXP-2F38edSFCecfsJYfBQEqhIKtOeIlyvFtLQIqoh-jtykD2T_-IoNIXSQGyIKCF-0FOIwuK8iH6WIUCX2wTUJCmneNmVOHOGMbePsBfMsXSa53MVvyidxcQQ8boNRpcwIdVpsz-38umIRAy7ENR7Puj4vIcFCCooYkEfjDZLlPbqxn-_rWwBYTXmEwm7Glnf9r2O1TT1vGraYa_NMPvbYUMS4g',
        'nearby_places' => json_encode([
            ['category' => 'Transit & Access', 'icon' => 'train', 'places' => [['name' => 'Jatin Das Park Metro', 'distance' => '1.2 KM'], ['name' => 'Majerhat Station', 'distance' => '2.5 KM']]],
            ['category' => 'Healthcare', 'icon' => 'local_hospital', 'places' => [['name' => 'Woodlands Hospital', 'distance' => '0.8 KM'], ['name' => 'BM Birla Heart Research', 'distance' => '1.1 KM']]],
            ['category' => 'Education', 'icon' => 'school', 'places' => [['name' => "La Martiniere (Boys/Girls)", 'distance' => '3.4 KM'], ['name' => "St. Xavier's Collegiate", 'distance' => '4.2 KM']]],
        ]),
        'gallery_images' => [
            'https://lh3.googleusercontent.com/aida-public/AB6AXuB7jVCO9aynJFTpwxz2CwOEp-xK5JWsW-gMcDo_oLLntgHe7Wxza3xSP6AwUxtirGMggSKe-2OqT15y94oC67V8ZbO3y5dS9cTfxe6BuuNHioo9m3oDyw6bRNYrUvgRLbHcQ01471ttm2jHCPfj9EbeZ3lJ0gwxJ7dRnF8mEDNROuSeVL3TG3zVILlouspy4zLvn3SqczzZUJqoMtcrWtMEsP28k3pFzwr-ZZnl_0WtYYoVY-dd5nhzay9Ga-LcScAuPHkb1xyzBJ8',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuBnWaHZL_jy638j30MU8QWTnc0v_P_ZoQR4EWmcxukWMMw4DVRBKEQoVNucZvpWVDL-IXG-dWfacYvLKPhQW0jkFk09TvUYFsJTLxmyeqAzQekZU4L5LEXcrGtsKa6ImFii8qG-VJiQWcKU_YSGNqNCviPJxcWL-L8frLPptrI0HyjvUmINLgrJqgkWsD7P0fygfu_Vz4BdcvAdK_N5XyhYrbmos5qyrXMgYEaq5YYUXndBtEyY1iCm10_yNM10Yh1uO5E3SgJcFHQ',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuBkqMNrbXthy9v1mp90BMyRCnYteHi0UI-kCO2fGHePArKnWTeHN7WYpakn2RqKcdEN0FcyGkuBkgX_lbeRSGucoXPlOUVmm0WvIsRm4Z-mlrkOU3wvvKeatNlb4hrG6M3fJUy8HyWEQjLAK8JVAKpN0OxVPakyWvRxW7WH7QKifTuzTyx7j6ia1N7LWcPSR9JB-Y2pDXzShigoS5P6RyEMhG2Ij7xVbfl692wEXZS2P28yCz8BeY_CONPq9Ut3V9HuxBaLGgxx4qg',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuA19sGDINs4a-xrAIv04CkbrEO-jq2Qf1vDDSwnGPZih-NKtgJIiGBa8BC2mzGQJCcXI8yBqIeKk8nj4Pd4vChwBw-HQ2vJGOAJ4fTTFepkMuj9NLClX4M_ECxXGMMTsPOn5eoEgi1OFgyNnF3ZdeB26pAFhJkldKH8g0Ver0lpkxJfdvLriDaYN25GnMN6Q6_0cLBTNWdb5Hf-Tbao2zM4JFiqGTE3okMaPG7ijdFZaH0-PLSGrQuhK9k4oM_eFYceq_DyP17aYsA',
        ],
    ];
} else {
    // Parse amenities and nearby from JSON if stored
    if (!empty($listing['amenities'])) {
        $listing['amenities'] = json_decode($listing['amenities'], true);
    }
    if (!empty($listing['nearby_places'])) {
        $listing['nearby_places'] = json_decode($listing['nearby_places'], true);
    }
    if (!empty($listing['gallery_images'])) {
        $listing['gallery_images'] = json_decode($listing['gallery_images'], true);
    }
    if (empty($listing['gallery_images']) && !empty($listing['image_filename'])) {
        $listing['gallery_images'] = array_values(array_filter(array_map('trim', explode(',', (string)$listing['image_filename']))));
    }
}

// Format price
if ($listing['price'] >= 10000000) {
    $price_display = '₹' . number_format($listing['price'] / 10000000, 2) . ' Cr*';
} else {
    $price_display = '₹' . number_format($listing['price'] / 100000, 2) . ' Lakh*';
}

// EMI calculation
$emi_monthly = number_format(($listing['price'] * 0.8 * pow(1 + 0.084/12, 240) * 0.084/12) / (pow(1 + 0.084/12, 240) - 1));

$page_title = $listing['title'] ?? 'Property Details';
require_once __DIR__ . '/includes/header.php';
?>

<style>
    .hero-gradient {
        background: linear-gradient(to bottom, rgba(0, 18, 37, 0) 40%, rgba(0, 18, 37, 0.9) 100%);
    }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .mask-image-radial {
        mask-image: radial-gradient(circle at center, black 0%, transparent 80%);
    }
</style>

<!-- ============================================================
     DESKTOP Property Details Page
     ============================================================ -->
<main class="hidden md:block">
    <!-- Hero Section -->
    <header class="relative h-[921px] w-full overflow-hidden">
        <div class="absolute inset-0 grid grid-cols-4 gap-2 p-2">
            <div class="col-span-2 row-span-2 relative overflow-hidden rounded-xl">
                <?php
                $heroImage = $listing['gallery_images'][0] ?? $listing['image_filename'] ?? '';
                if (strpos($heroImage, 'http') !== 0 && !empty($heroImage)) {
                    $heroImage = SITE_URL . '/assets/images/' . ltrim($heroImage, '/');
                }
                ?>
                <img alt="<?php echo htmlspecialchars($listing['title']); ?>" class="w-full h-full object-cover" src="<?php echo htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8'); ?>"/>
            </div>
            <?php for ($i = 1; $i < 4; $i++): ?>
            <div class="relative overflow-hidden rounded-xl">
                <?php
                $img = $listing['gallery_images'][$i] ?? '';
                if (strpos($img, 'http') !== 0 && !empty($img)) {
                    $img = SITE_URL . '/assets/images/' . ltrim($img, '/');
                }
                ?>
                <img alt="<?php echo htmlspecialchars($listing['title']); ?>" class="w-full h-full object-cover" src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>"/>
            </div>
            <?php endfor; ?>
        </div>
        <div class="absolute inset-0 hero-gradient pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full p-12 text-on-primary">
            <div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
                <div>
                    <span class="font-label text-xs uppercase tracking-widest bg-tertiary-fixed text-on-tertiary-fixed px-3 py-1 rounded-full mb-4 inline-block">Exclusive Listing</span>
                    <h1 class="font-headline text-5xl md:text-7xl font-extrabold tracking-tighter mb-2"><?php echo htmlspecialchars($listing['title']); ?></h1>
                    <p class="font-headline text-xl opacity-90 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary-fixed">location_on</span>
                        <?php echo htmlspecialchars($listing['location'] . ', ' . $listing['city']); ?>
                    </p>
                </div>
                <div class="absolute top-24 right-12 text-right text-on-primary bg-black/20 backdrop-blur-md p-6 rounded-xl border border-white/20 z-10">
                    <p class="font-label text-xs uppercase tracking-widest opacity-70 mb-1">Starting from</p>
                    <p class="font-headline text-4xl font-bold mb-4"><?php echo $price_display; ?></p>
                    <div class="pt-4 border-t border-white/20">
                        <p class="font-headline text-2xl font-bold leading-tight">
                            WBRERA REG ID: <br/><?php echo htmlspecialchars($listing['rera_id'] ?? 'HIRA/P/KOL/2024/000452'); ?>
                        </p>
                        <p class="font-body text-sm mt-2 opacity-80 font-bold uppercase tracking-wider">www.rera.wb.gov.in</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Information Split Section -->
    <section class="max-w-screen-2xl mx-auto px-8 py-24 grid grid-cols-1 lg:grid-cols-12 gap-16">
        <!-- Left Column: Narrative -->
        <div class="lg:col-span-8 space-y-16">
            <!-- Architectural Narrative -->
            <section>
                <h2 class="font-headline text-3xl font-bold mb-8 text-primary">Architectural Narrative</h2>
                <p class="font-body text-lg text-on-surface-variant leading-relaxed max-w-3xl">
                    <?php echo htmlspecialchars($listing['description'] ?? 'A luxury residential property in Kolkata.'); ?>
                </p>
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="bg-surface-container-low p-6 rounded-xl">
                        <p class="font-label text-[0.6875rem] uppercase tracking-widest text-outline mb-2">Configuration</p>
                        <p class="font-headline text-xl font-bold text-primary"><?php echo htmlspecialchars($listing['bedrooms']); ?> BHK</p>
                    </div>
                    <div class="bg-surface-container-low p-6 rounded-xl">
                        <p class="font-label text-[0.6875rem] uppercase tracking-widest text-outline mb-2">RERA Carpet</p>
                        <p class="font-headline text-xl font-bold text-primary"><?php echo htmlspecialchars($listing['area_sqft']); ?> Sq.Ft.</p>
                    </div>
                    <div class="bg-surface-container-low p-6 rounded-xl">
                        <p class="font-label text-[0.6875rem] uppercase tracking-widest text-outline mb-2">Orientation</p>
                        <p class="font-headline text-xl font-bold text-primary"><?php echo htmlspecialchars($listing['orientation'] ?? 'South-Open'); ?></p>
                    </div>
                    <div class="bg-surface-container-low p-6 rounded-xl">
                        <p class="font-label text-[0.6875rem] uppercase tracking-widest text-outline mb-2">Floor Height</p>
                        <p class="font-headline text-xl font-bold text-primary"><?php echo htmlspecialchars($listing['floor_height'] ?? '11.5 Feet'); ?></p>
                    </div>
                </div>
            </section>

            <!-- Curated Amenities -->
            <section>
                <h2 class="font-headline text-3xl font-bold mb-8 text-primary">Curated Amenities</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-10 gap-x-6">
                    <?php
                    $amenities = $listing['amenities'] ?? [
                        ['icon' => 'fitness_center', 'name' => 'Technogym Studio'],
                        ['icon' => 'spa', 'name' => 'Ayurvedic Wellness Spa'],
                        ['icon' => 'theater_comedy', 'name' => 'Private Screening Room'],
                        ['icon' => 'local_library', 'name' => "The Curator's Library"],
                        ['icon' => 'ev_station', 'name' => 'Dual EV Chargers'],
                        ['icon' => 'security', 'name' => '5-Tier Biometric Security'],
                    ];
                    foreach ($amenities as $amenity):
                    ?>
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary flex items-center justify-center">
                            <span class="material-symbols-outlined"><?php echo $amenity['icon']; ?></span>
                        </div>
                        <span class="font-headline font-semibold text-on-surface-variant"><?php echo htmlspecialchars($amenity['name']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Right Column: Sticky Form -->
        <div class="lg:col-span-4">
            <div class="sticky top-28 space-y-8">
                <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-xl shadow-on-surface/5">
                    <h3 class="font-headline text-2xl font-bold text-primary mb-6">Schedule a Site Visit</h3>
                    <form id="property-contact-form" class="space-y-6">
                        <div>
                            <input class="w-full border-0 border-b border-outline-variant bg-transparent py-3 px-0 focus:ring-0 focus:border-primary placeholder:text-outline transition-all" placeholder="Full Name" type="text" name="name" required/>
                        </div>
                        <div>
                            <input class="w-full border-0 border-b border-outline-variant bg-transparent py-3 px-0 focus:ring-0 focus:border-primary placeholder:text-outline transition-all" placeholder="Phone Number" type="tel" name="phone" required/>
                        </div>
                        <input type="hidden" name="source_page" value="property"/>
                        <button class="w-full bg-primary-container text-on-primary py-4 rounded-lg font-headline font-bold text-lg hover:opacity-90 transition-opacity" type="submit">Request Private Tour</button>
                    </form>
                    <div id="property-form-toast" class="hidden mt-4 p-3 rounded-xl text-center text-sm font-medium"></div>
                </div>
                <div class="bg-primary text-on-primary p-8 rounded-2xl">
                    <h3 class="font-headline text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">calculate</span>
                        EMI Projection
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center opacity-80">
                            <span class="font-label text-xs uppercase tracking-widest">Est. Monthly</span>
                            <span class="font-headline font-bold">₹<?php echo $emi_monthly; ?></span>
                        </div>
                        <div class="w-full bg-on-primary/10 h-1 rounded-full overflow-hidden">
                            <div class="bg-secondary-fixed w-2/3 h-full"></div>
                        </div>
                        <p class="text-[0.625rem] opacity-50 italic">Calculated at 8.4% p.a. for 20 years. Subject to bank approval.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Details: Floor Plans -->
    <section class="bg-surface-container-low py-24">
        <div class="max-w-screen-2xl mx-auto px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-4">
                <div>
                    <span class="font-label text-xs uppercase tracking-widest text-secondary font-bold">Technical Blueprint</span>
                    <h2 class="font-headline text-4xl font-bold text-primary mt-2">The Unit Hierarchy</h2>
                </div>
                <div class="flex gap-4">
                    <button class="px-6 py-2 bg-surface-container-lowest text-primary font-bold rounded-full shadow-sm">Level 04 - 18</button>
                    <button class="px-6 py-2 text-outline-variant font-bold hover:text-primary transition-colors">Penthouse Skydeck</button>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="bg-surface-container-lowest p-8 rounded-3xl relative">
                    <?php
                    $floorPlanImg = $listing['floor_plan_image'] ?? '';
                    if (strpos($floorPlanImg, 'http') !== 0 && !empty($floorPlanImg)) {
                        $floorPlanImg = SITE_URL . '/assets/images/' . ltrim($floorPlanImg, '/');
                    }
                    ?>
                    <img alt="Floor plan" class="w-full h-auto" src="<?php echo htmlspecialchars($floorPlanImg, ENT_QUOTES, 'UTF-8'); ?>"/>
                    <div class="absolute bottom-12 right-12 flex flex-col items-center">
                        <span class="material-symbols-outlined text-primary text-4xl mb-1">north_west</span>
                        <span class="font-label text-[0.6rem] uppercase font-bold text-primary">True North</span>
                    </div>
                </div>
                <div class="space-y-8">
                    <div class="flex items-start gap-4">
                        <span class="font-headline text-3xl font-light text-outline">01</span>
                        <div>
                            <h4 class="font-headline text-xl font-bold text-primary">Master Wing</h4>
                            <p class="text-on-surface-variant mt-1">Spanning 850 Sq.Ft. with walk-in wardrobe and a private sunrise deck.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="font-headline text-3xl font-light text-outline">02</span>
                        <div>
                            <h4 class="font-headline text-xl font-bold text-primary">Culinary Lab</h4>
                            <p class="text-on-surface-variant mt-1">Poggenpohl cabinetry with a separate 'dirty kitchen' for hospitality management.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="font-headline text-3xl font-light text-outline">03</span>
                        <div>
                            <h4 class="font-headline text-xl font-bold text-primary">Living Canvas</h4>
                            <p class="text-on-surface-variant mt-1">45-foot continuous span of marble flooring with no visible columns.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Locality -->
    <section class="py-24 max-w-screen-2xl mx-auto px-8">
        <h2 class="font-headline text-3xl font-bold mb-12 text-primary">The Alipore Latitude</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 h-[500px] bg-surface-container-high rounded-3xl overflow-hidden relative">
                <?php
                $mapImg = $listing['map_image'] ?? '';
                if (strpos($mapImg, 'http') !== 0 && !empty($mapImg)) {
                    $mapImg = SITE_URL . '/assets/images/' . ltrim($mapImg, '/');
                }
                ?>
                <img alt="Location Map" class="w-full h-full object-cover" src="<?php echo htmlspecialchars($mapImg, ENT_QUOTES, 'UTF-8'); ?>"/>
                <div class="absolute top-1/2 left-1/3 w-8 h-8 bg-primary rounded-full border-4 border-on-primary shadow-2xl flex items-center justify-center animate-pulse">
                    <div class="w-2 h-2 bg-on-primary rounded-full"></div>
                </div>
            </div>
            <div class="space-y-10">
                <?php
                $nearbyPlaces = $listing['nearby_places'] ?? [
                    ['category' => 'Transit & Access', 'icon' => 'train', 'places' => [['name' => 'Jatin Das Park Metro', 'distance' => '1.2 KM'], ['name' => 'Majerhat Station', 'distance' => '2.5 KM']]],
                    ['category' => 'Healthcare', 'icon' => 'local_hospital', 'places' => [['name' => 'Woodlands Hospital', 'distance' => '0.8 KM'], ['name' => 'BM Birla Heart Research', 'distance' => '1.1 KM']]],
                    ['category' => 'Education', 'icon' => 'school', 'places' => [['name' => "La Martiniere (Boys/Girls)", 'distance' => '3.4 KM'], ['name' => "St. Xavier's Collegiate", 'distance' => '4.2 KM']]],
                ];
                foreach ($nearbyPlaces as $category):
                ?>
                <div>
                    <h4 class="font-headline font-bold text-lg mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary"><?php echo $category['icon']; ?></span>
                        <?php echo htmlspecialchars($category['category']); ?>
                    </h4>
                    <ul class="space-y-3 font-body text-sm text-on-surface-variant">
                        <?php foreach ($category['places'] as $place): ?>
                        <li class="flex justify-between"><span><?php echo htmlspecialchars($place['name']); ?></span> <span class="font-bold text-primary"><?php echo htmlspecialchars($place['distance']); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- WBRERA Legal Disclaimer -->
    <div class="bg-surface-container py-4 border-t border-outline-variant/20">
        <div class="max-w-screen-2xl mx-auto px-8 text-[0.7rem] text-on-surface-variant font-medium text-center uppercase tracking-widest leading-relaxed">
            Legal Compliance: Project RERA Registration Number <?php echo htmlspecialchars($listing['rera_id'] ?? 'HIRA/P/KOL/2024/000452'); ?>. All images, layouts and specifications are indicative and subject to change by the competent authority. Visit wbrera.wb.gov.in for official project documentation.
        </div>
    </div>
</main>

<!-- ============================================================
     MOBILE Property Details Page
     ============================================================ -->
<main class="md:hidden pt-16 pb-32">
    <!-- Hero Carousel Section -->
    <section class="relative w-full h-[618px] overflow-hidden">
        <?php
        $heroMobileImg = $listing['gallery_images'][0] ?? $listing['image_filename'] ?? '';
        if (strpos($heroMobileImg, 'http') !== 0 && !empty($heroMobileImg)) {
            $heroMobileImg = SITE_URL . '/assets/images/' . ltrim($heroMobileImg, '/');
        }
        ?>
        <img alt="<?php echo htmlspecialchars($listing['title']); ?>" class="w-full h-full object-cover" src="<?php echo htmlspecialchars($heroMobileImg, ENT_QUOTES, 'UTF-8'); ?>"/>
        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-primary/90 to-transparent flex flex-col justify-end p-6 pb-12">
            <div class="flex flex-col gap-1 mb-4">
                <span class="text-on-primary/60 text-[10px] tracking-[0.15em] uppercase font-label">The Collection</span>
                <h2 class="text-4xl font-extrabold text-on-primary font-headline tracking-tighter"><?php echo htmlspecialchars($listing['title']); ?></h2>
            </div>
            <div class="flex justify-between items-end">
                <div class="flex flex-col">
                    <span class="text-on-primary font-light text-sm"><?php echo htmlspecialchars($listing['location'] . ', ' . $listing['city']); ?></span>
                </div>
                <div class="text-right">
                    <span class="text-on-primary font-bold text-lg">Starting from <?php echo $price_display; ?></span>
                </div>
            </div>
            <!-- WBRERA Text -->
            <div class="mt-4 pt-4 border-t border-on-primary/10">
                <p class="text-on-primary/40 leading-tight uppercase tracking-widest text-sm text-on-primary/80">WBRERA REG. NO: <?php echo htmlspecialchars($listing['rera_id'] ?? 'HIRA/P/KOL/2024/000452'); ?> | <a class="underline" href="https://www.rera.wb.gov.in">www.rera.wb.gov.in</a><br/>AN ARCHITECTURAL EDITORIAL CURATED PROPERTY</p>
            </div>
        </div>
    </section>

    <!-- Quick Specs Grid -->
    <section class="px-6 py-10 grid grid-cols-2 gap-4">
        <div class="bg-surface-container-low p-6 rounded-xl flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary text-xl">bed</span>
            <span class="font-headline font-bold text-lg text-primary"><?php echo htmlspecialchars($listing['bedrooms']); ?> BHK</span>
            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-label">Configuration</span>
        </div>
        <div class="bg-surface-container-low p-6 rounded-xl flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary text-xl">straighten</span>
            <span class="font-headline font-bold text-lg text-primary"><?php echo htmlspecialchars($listing['area_sqft']); ?> Sq.Ft</span>
            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-label">RERA Carpet</span>
        </div>
        <div class="bg-surface-container-low p-6 rounded-xl flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary text-xl">explore</span>
            <span class="font-headline font-bold text-lg text-primary"><?php echo htmlspecialchars($listing['orientation'] ?? 'South-Open'); ?></span>
            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-label">Vastu Compliant</span>
        </div>
        <div class="bg-surface-container-low p-6 rounded-xl flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary text-xl">vertical_align_top</span>
            <span class="font-headline font-bold text-lg text-primary"><?php echo htmlspecialchars($listing['floor_height'] ?? '11.5 Feet'); ?></span>
            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-label">Ceilings</span>
        </div>
    </section>

    <!-- Narrative Section -->
    <section class="px-6 py-8">
        <h3 class="font-headline text-2xl font-light mb-4 text-primary">A Sanctuary of Light</h3>
        <p class="text-on-surface-variant leading-relaxed font-body text-sm mb-4">
            <?php echo htmlspecialchars($listing['description'] ?? 'A luxury residential property in Kolkata.'); ?>
        </p>
        <a class="text-primary font-bold text-xs uppercase tracking-[0.2em] underline underline-offset-8 decoration-primary/20 hover:decoration-primary transition-all" href="#">Read More</a>
    </section>

    <!-- Amenities Section -->
    <section class="py-10">
        <div class="px-6 mb-6">
            <h3 class="font-headline text-xs font-bold uppercase tracking-[0.3em] text-on-surface-variant">Curated Rituals</h3>
        </div>
        <div class="flex overflow-x-auto hide-scrollbar gap-6 px-6 pb-4">
            <?php foreach ($amenities as $amenity): ?>
            <div class="flex-shrink-0 flex flex-col items-center gap-4 w-28">
                <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-3xl"><?php echo $amenity['icon']; ?></span>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-center"><?php echo htmlspecialchars($amenity['name']); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Floor Plan Section -->
    <section class="px-6 py-12 bg-surface-container-low">
        <div class="mb-10">
            <h3 class="font-headline text-3xl font-light text-primary">The Unit Hierarchy</h3>
        </div>
        <div class="relative bg-surface-container-lowest rounded-3xl p-8 mb-8 overflow-hidden aspect-square flex items-center justify-center">
            <?php
            $floorPlanImgMobile = $listing['floor_plan_image'] ?? '';
            if (strpos($floorPlanImgMobile, 'http') !== 0 && !empty($floorPlanImgMobile)) {
                $floorPlanImgMobile = SITE_URL . '/assets/images/' . ltrim($floorPlanImgMobile, '/');
            }
            ?>
            <img alt="Architectural floor plan" class="max-w-full h-auto opacity-80 mix-blend-multiply" src="<?php echo htmlspecialchars($floorPlanImgMobile, ENT_QUOTES, 'UTF-8'); ?>"/>
            <div class="absolute top-6 right-6">
                <span class="material-symbols-outlined text-primary opacity-30 text-4xl">explore</span>
            </div>
        </div>
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between group cursor-pointer">
                <div class="flex flex-col">
                    <span class="text-primary font-headline font-bold text-lg">Master Wing</span>
                    <span class="text-[10px] text-on-surface-variant uppercase tracking-widest">1,200 Sq.Ft Private Zone</span>
                </div>
                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">east</span>
            </div>
            <div class="flex items-center justify-between group cursor-pointer">
                <div class="flex flex-col">
                    <span class="text-primary font-headline font-bold text-lg">Culinary Lab</span>
                    <span class="text-[10px] text-on-surface-variant uppercase tracking-widest">Gourmet Italian Fitted</span>
                </div>
                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">east</span>
            </div>
            <div class="flex items-center justify-between group cursor-pointer">
                <div class="flex flex-col">
                    <span class="text-primary font-headline font-bold text-lg">Living Canvas</span>
                    <span class="text-[10px] text-on-surface-variant uppercase tracking-widest">Triple Height Atrium</span>
                </div>
                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">east</span>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-12">
        <div class="px-6 mb-8">
            <h3 class="font-headline text-3xl font-light text-primary">The Alipore Latitude</h3>
            <p class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant mt-2 font-label">The Pinnacle of Kolkata 700027</p>
        </div>
        <div class="w-full h-[350px] mb-8 relative">
            <?php
            $mapImgMobile = $listing['map_image'] ?? '';
            if (strpos($mapImgMobile, 'http') !== 0 && !empty($mapImgMobile)) {
                $mapImgMobile = SITE_URL . '/assets/images/' . ltrim($mapImgMobile, '/');
            }
            ?>
            <img alt="Location Map" class="w-full h-full object-cover" src="<?php echo htmlspecialchars($mapImgMobile, ENT_QUOTES, 'UTF-8'); ?>"/>
            <div class="absolute inset-0 bg-primary/5 pointer-events-none"></div>
        </div>
        <div class="px-6 grid grid-cols-1 gap-6">
            <?php foreach ($nearbyPlaces as $category): ?>
                <?php foreach ($category['places'] as $place): ?>
            <div class="flex gap-4 items-start">
                <span class="material-symbols-outlined text-primary/40"><?php echo $category['icon']; ?></span>
                <div>
                    <p class="font-bold text-sm"><?php echo htmlspecialchars($place['name']); ?></p>
                    <p class="text-xs text-on-surface-variant"><?php echo htmlspecialchars($place['distance']); ?></p>
                </div>
            </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- EMI Card Section -->
    <section class="px-6 pb-12">
        <div class="bg-primary-container text-on-primary p-8 rounded-[2rem] relative overflow-hidden shadow-2xl">
            <!-- Abstract Grain Pattern Overlay -->
            <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
            <h4 class="font-headline text-xs font-bold uppercase tracking-[0.3em] opacity-60 mb-8">EMI Projection</h4>
            <div class="mb-10">
                <span class="text-5xl font-extrabold font-headline tracking-tighter">₹<?php echo number_format((int)($emi_monthly / 100000)); ?><span class="text-xl ml-1 opacity-60">Lakhs/mo*</span></span>
            </div>
            <div class="space-y-8 relative z-10">
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between text-[10px] uppercase tracking-widest font-bold">
                        <span>Downpayment</span>
                        <span>₹<?php echo number_format($listing['price'] * 0.2 / 10000000, 2); ?> Cr (20%)</span>
                    </div>
                    <input class="w-full h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-white" type="range" value="20"/>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between text-[10px] uppercase tracking-widest font-bold">
                        <span>Tenure</span>
                        <span>20 Years</span>
                    </div>
                    <input class="w-full h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-white" type="range" value="20"/>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Mobile Bottom Action Bar (Fixed Shell) -->
<footer class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-[#ffffff] shadow-[0px_-10px_30px_rgba(27,28,28,0.04)] px-4 pt-3 pb-8">
    <button onclick="openContactModal()" class="w-full bg-[#022747] text-[#ffffff] rounded-xl py-4 flex items-center justify-center gap-3 scale-[0.98] active:scale-95 transition-all duration-300 ease-out font-headline font-bold uppercase tracking-[0.2em] text-xs">
        <span class="material-symbols-outlined text-lg">calendar_month</span>
        Schedule Private Tour
    </button>
</footer>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Kolkata Luxury Real Estate | Find Your Dream Home';
require_once __DIR__ . '/includes/header.php';
?>

<main class="pt-16 md:pt-0">

  <!-- =========================================================
       HERO SECTION
       ========================================================= -->
  <section class="relative min-h-[90vh] md:min-h-[921px] flex items-center justify-center md:pt-20 overflow-hidden">
    <!-- Background Image + Overlay -->
    <div class="absolute inset-0 z-0">
      <img class="w-full h-full object-cover" alt="Kolkata skyline at dusk with the iconic Howrah Bridge and modern high-rises reflecting in the Ganges"
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuADnvZqHuIRh2i6widHAsRavAD1OCkH4qntBEdu6QHiLf_uGHJ2wruPwgtaov4ILlbCxSA1rWO1BbnHER95UqBTuYCQIGNWRAZ5rRqTLCKzKws9Urm1I_jBsFllHzdNqw7epr8gAiJe50dIeYi-NBcDirpffR6udPlAto5GmZyQNfx33crohwDRXYZpfKFGHU9xEPOpux61FsqLAcFvl1v0Yen2xxFJ1-Vq5vDBSVSCinM5LfHt0qbV6QQTJ51m2IPL2_l6oz4QXnU"/>
      <div class="absolute inset-0 bg-primary/40"></div>
    </div>

    <!-- Desktop Hero Content -->
    <div class="relative z-10 w-full max-w-5xl px-6 md:px-8 text-center hidden md:block">
      <h1 class="font-headline text-5xl md:text-7xl font-extrabold text-white mb-8 tracking-tight">
        Space, Curated.
      </h1>
      <!-- Glassmorphic Search Bar -->
      <div class="glass-nav p-2 md:p-3 rounded-2xl shadow-2xl flex flex-col md:flex-row items-center gap-2 max-w-4xl mx-auto border border-white/20">
        <div class="flex-1 w-full flex items-center gap-4 px-6 py-3 bg-white/50 rounded-xl">
          <span class="material-symbols-outlined text-outline">search</span>
          <input class="bg-transparent border-none focus:ring-0 w-full text-on-surface placeholder:text-outline font-medium" placeholder="Search New Town, South Kolkata, or EM Bypass..." type="text"/>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
          <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-8 py-4 bg-surface-container-highest text-on-surface font-semibold rounded-xl hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-[20px]">tune</span>
            Filters
          </button>
          <button class="flex-1 md:flex-none px-10 py-4 bg-primary-container text-on-primary font-bold rounded-l-none rounded-r-xl hover:opacity-90 transition-all active:scale-[0.98]">
            Search
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Hero Content -->
    <div class="md:hidden relative z-10 w-full flex flex-col items-center px-6 pb-10 justify-end min-h-[80vh]">
      <div class="w-full max-w-md space-y-8 flex flex-col items-center">
        <div class="text-center">
          <h1 class="text-white font-headline text-5xl font-extrabold tracking-tight leading-none drop-shadow-lg">Space, Curated.</h1>
          <p class="text-white/90 font-medium text-lg text-center mt-4 drop-shadow-md max-w-[280px] mx-auto">Find your perfect home or commercial space in Kolkata.</p>
        </div>
        <!-- Mobile Search Card -->
        <div class="w-full bg-white/95 backdrop-blur-md rounded-2xl p-5 shadow-2xl flex flex-col gap-4">
          <div class="flex items-center gap-3 border-b border-outline-variant/30 pb-3">
            <span class="material-symbols-outlined text-primary">location_on</span>
            <input class="bg-transparent border-none focus:ring-0 w-full text-primary font-medium placeholder:text-outline p-0" placeholder="Search New Town, Ballygunge..." type="text"/>
          </div>
          <div class="flex gap-2">
            <button class="flex-1 flex items-center justify-between px-3 py-3 border border-outline-variant/30 rounded-lg text-sm font-medium text-primary">
              <span>Budget</span>
              <span class="material-symbols-outlined text-sm">expand_more</span>
            </button>
            <button class="flex-1 flex items-center justify-between px-3 py-3 border border-outline-variant/30 rounded-lg text-sm font-medium text-primary">
              <span>BHK / Type</span>
              <span class="material-symbols-outlined text-sm">expand_more</span>
            </button>
          </div>
          <button class="w-full bg-primary-container text-on-primary py-4 rounded-xl font-bold tracking-wide active:scale-95 transition-transform">
            Search Properties
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================
       EXPERTISE / INTRO SECTION
       ========================================================= -->
  <section class="py-16 md:py-24 bg-surface-container-low overflow-hidden reveal">
    <!-- Desktop: two-column layout -->
    <div class="hidden md:grid max-w-screen-2xl mx-auto px-8 grid-cols-1 lg:grid-cols-2 gap-20 items-center">
      <div class="relative">
        <div class="relative z-10 rounded-xl overflow-hidden shadow-2xl">
          <img class="w-full h-[600px] object-cover" alt="Professional real estate advisors in a modern Kolkata office"
               src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcg6EAk9hQ_MBJbggVhJ2QFxeIQtSNWwwmIu4LD_EB3s1fdLAui_eQzv-BoKWLkK62HIy9V2we727xK5T31osbP9i0WW0ZNC0vKlftx9uj2_FEvzGFzcvVsmztgqsk-vapJKLXqIxANokFkvDxYgryPQl6JR-xkXuruCEk-RtRCW0dSiPdVNvg8lX6QsXwsm9kfIPzGsLNwb3te2OQLHfgmSGeU3gkjdZq4nTy257MBk7rEwWBIznZJsc4wiocLf-LupjHfE1hNu0"/>
        </div>
        <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-fixed rounded-full -z-0 opacity-50"></div>
        <div class="absolute -bottom-12 -right-12 w-80 h-80 bg-secondary-fixed opacity-30 rounded-full -z-0"></div>
      </div>
      <div class="max-w-xl">
        <span class="text-on-tertiary-container font-bold tracking-widest uppercase text-xs mb-6 block">Our Expertise</span>
        <h2 class="font-headline text-4xl md:text-5xl font-extrabold text-primary mb-8 leading-tight">
          We don't just sell property. We curate legacies.
        </h2>
        <p class="text-on-surface-variant text-lg mb-10 leading-relaxed">
          With decades of mastery in luxury real estate, <?php echo SITE_NAME; ?> provides an unparalleled concierge experience for Kolkata's discerning investors. We are curators of space, light, and architectural significance.
        </p>
        <div class="grid grid-cols-2 gap-8 mb-12">
          <div>
            <div class="text-3xl font-headline font-extrabold text-primary mb-2">₹1,200 Cr+</div>
            <p class="text-sm font-medium text-outline">Closed Volume</p>
          </div>
          <div>
            <div class="text-3xl font-headline font-extrabold text-primary mb-2">15+</div>
            <p class="text-sm font-medium text-outline">Premium Localities</p>
          </div>
        </div>
        <button onclick="openContactModal()" class="px-10 py-5 bg-primary text-on-primary font-bold rounded-lg hover:opacity-90 transition-all flex items-center gap-3">
          Book Free Site Visit <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </div>
    </div>

    <!-- Mobile: stacked layout with overlapping card -->
    <div class="md:hidden px-6">
      <div class="relative mb-12">
        <div class="aspect-[4/5] rounded-[2rem] overflow-hidden shadow-xl">
          <img class="w-full h-full object-cover" alt="Professional real estate consultants reviewing architectural blueprints"
               src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcg6EAk9hQ_MBJbggVhJ2QFxeIQtSNWwwmIu4LD_EB3s1fdLAui_eQzv-BoKWLkK62HIy9V2we727xK5T31osbP9i0WW0ZNC0vKlftx9uj2_FEvzGFzcvVsmztgqsk-vapJKLXqIxANokFkvDxYgryPQl6JR-xkXuruCEk-RtRCW0dSiPdVNvg8lX6QsXwsm9kfIPzGsLNwb3te2OQLHfgmSGeU3gkjdZq4nTy257MBk7rEwWBIznZJsc4wiocLf-LupjHfE1hNu0"/>
        </div>
        <div class="absolute -bottom-6 -right-2 bg-primary p-8 rounded-2xl text-white shadow-2xl max-w-[75%]">
          <p class="font-headline font-semibold text-lg leading-snug">We don't just sell property. We curate legacies.</p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-8 mb-10 pt-8">
        <div>
          <p class="text-3xl font-headline font-bold text-primary">₹1,200 Cr+</p>
          <p class="text-[10px] uppercase tracking-widest text-outline font-bold mt-1">Closed Volume</p>
        </div>
        <div>
          <p class="text-3xl font-headline font-bold text-primary">15+</p>
          <p class="text-[10px] uppercase tracking-widest text-outline font-bold mt-1">Localities</p>
        </div>
      </div>
      <button onclick="openContactModal()" class="w-full bg-primary-container text-on-primary py-5 rounded-2xl font-bold text-lg tracking-tight shadow-xl shadow-primary-container/20">
        Book Free Site Visit
      </button>
    </div>
  </section>

  <!-- =========================================================
       KOLKATA MICRO-MARKETS
       ========================================================= -->
  <section class="py-16 md:py-24 reveal">
    <!-- Desktop: 4-column staggered grid -->
    <div class="hidden md:block max-w-screen-2xl mx-auto px-8">
      <h2 class="font-headline text-4xl font-extrabold text-primary mb-16 text-center">Top Kolkata Micro-Markets</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <a class="group relative aspect-[4/5] rounded-xl overflow-hidden" href="<?php echo SITE_URL; ?>/residential.php">
          <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Modern high-rise residential complexes in New Town, Kolkata" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAPnTByjsyxVIHJsjsPpMd7k1vlpf9O97ffrAePXl08iuqDp7YkaWsuLFk1H1Iu4kmcps49XuVmto_Sb_NlBxOuWnenFFgFdwY7bUqsrCB6PsDxYl3FMR1aSHpe817N5sjcoMdDxChS5W-SkpLF_akfPsMUEQsg5BcmB4hkMMBwar_nHlZWQmPuUHGTSjjpRpqZK_xsxQ_fNt8CpjNAs4BdZc-SlQL17WdoV8DuHn0XMPzPkWKrThaX3gePh0mhgUampp-HiJeAWf8"/>
          <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent"></div>
          <div class="absolute bottom-6 left-6 text-white">
            <p class="text-xs font-bold tracking-widest uppercase mb-1">Modern Living</p>
            <h3 class="text-2xl font-headline font-bold">New Town</h3>
          </div>
        </a>
        <a class="group relative aspect-[4/5] rounded-xl overflow-hidden mt-8 md:mt-12" href="<?php echo SITE_URL; ?>/residential.php">
          <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Planned residential area in Rajarhat with greenery" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDWmPJxv_xvuvzOd_k6MYan7BC1eUANVizd0V41XazDwfxUGST8b6QisP5jzkJ6PLafHvC-WrqQF3DX26F_SedPqiyypsyMJ0TayXZ8Yu1y-vCydw58Lo7_oexCQ8U48_-Q94qJvj-7TvI8tfTkNTr_xOQrOIT4dcoYyWeGjaAwV7Ie3A-F1aezUNtpql0eR92bEaDqyUMQ8KXLs5n94sKAYdBveJwZV4xaqqGGR8w6K50weJvuU9bMD-TE0DFlw83MVmZoxjcuaGE"/>
          <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent"></div>
          <div class="absolute bottom-6 left-6 text-white">
            <p class="text-xs font-bold tracking-widest uppercase mb-1">Smart City</p>
            <h3 class="text-2xl font-headline font-bold">Rajarhat</h3>
          </div>
        </a>
        <a class="group relative aspect-[4/5] rounded-xl overflow-hidden" href="<?php echo SITE_URL; ?>/residential.php">
          <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Premium heritage and luxury residential area in South Kolkata" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDP6wl_d5qEKQfY13oo8JaGJXOeuS4g34SYOHm1aWhd_DTI8-O2HQulRpwDgEvCqePgiC4YbS1S7pfVYdkpofOCpNJtCl579-6gJO0BMG-Wbw4F32oCxQLox1F2tsYApRf5Nel1Pup7uZ3Ss_w6V8iA9YuCcnFvizXvRuVo9Qg0sxQTsfA5FH2YSAzzuTtfjYUM1O38sMekG2br4cf5W0v04eyq8zsea_oNRrVVOGuZdlE2Gx6_XYNJSoLi2nX3dr9LHqnCgge7S5A"/>
          <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent"></div>
          <div class="absolute bottom-6 left-6 text-white">
            <p class="text-xs font-bold tracking-widest uppercase mb-1">Legacy Posh</p>
            <h3 class="text-2xl font-headline font-bold">South Kolkata</h3>
          </div>
        </a>
        <a class="group relative aspect-[4/5] rounded-xl overflow-hidden mt-8 md:mt-12" href="<?php echo SITE_URL; ?>/residential.php">
          <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="High-speed expressway and luxury high-rises along EM Bypass" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBqBuKi8tvLxKrLUTcumYcgvXdY79qUOFhG_g7HB7ZeIlE5BAVfAQyISXcgKVUoBcqhQg1IdDKaj-4sfteu_yYmw7MxgqwKmxAKYvYAxU6cQoi_tFrdlPy4lsopnAmG6J7GlPPBTg8M5t1KJfnNQx-h557AHwnmx977hl_CrRwk9dFB0sdnzskEnuttWZh695ePWB9Te1kzOYEPz7ZUpq7DnJXh55IznBanTM25JOLlSB4C0JA3h4F4uv1IMVExUZSrYvPe50tDO9g"/>
          <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent"></div>
          <div class="absolute bottom-6 left-6 text-white">
            <p class="text-xs font-bold tracking-widest uppercase mb-1">Growth Corridor</p>
            <h3 class="text-2xl font-headline font-bold">EM Bypass</h3>
          </div>
        </a>
      </div>
    </div>

    <!-- Mobile: horizontal scroll carousel -->
    <div class="md:hidden bg-surface-container-low py-10 overflow-hidden">
      <h3 class="px-6 text-2xl font-headline font-extrabold text-primary mb-6">Top Kolkata Micro-Markets</h3>
      <div class="flex overflow-x-auto no-scrollbar gap-4 px-6 snap-x snap-mandatory">
        <a href="<?php echo SITE_URL; ?>/residential.php" class="min-w-[80%] snap-start bg-surface-container-lowest rounded-[2.5rem] overflow-hidden group block">
          <div class="h-56 relative">
            <img class="w-full h-full object-cover" alt="New Town Kolkata" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAPnTByjsyxVIHJsjsPpMd7k1vlpf9O97ffrAePXl08iuqDp7YkaWsuLFk1H1Iu4kmcps49XuVmto_Sb_NlBxOuWnenFFgFdwY7bUqsrCB6PsDxYl3FMR1aSHpe817N5sjcoMdDxChS5W-SkpLF_akfPsMUEQsg5BcmB4hkMMBwar_nHlZWQmPuUHGTSjjpRpqZK_xsxQ_fNt8CpjNAs4BdZc-SlQL17WdoV8DuHn0XMPzPkWKrThaX3gePh0mhgUampp-HiJeAWf8"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-5 left-6 text-white">
              <h4 class="text-xl font-bold font-headline">New Town</h4>
              <p class="text-xs font-medium opacity-80">Planned Smart City</p>
            </div>
          </div>
        </a>
        <a href="<?php echo SITE_URL; ?>/residential.php" class="min-w-[80%] snap-start bg-surface-container-lowest rounded-[2.5rem] overflow-hidden group block">
          <div class="h-56 relative">
            <img class="w-full h-full object-cover" alt="South Kolkata" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDP6wl_d5qEKQfY13oo8JaGJXOeuS4g34SYOHm1aWhd_DTI8-O2HQulRpwDgEvCqePgiC4YbS1S7pfVYdkpofOCpNJtCl579-6gJO0BMG-Wbw4F32oCxQLox1F2tsYApRf5Nel1Pup7uZ3Ss_w6V8iA9YuCcnFvizXvRuVo9Qg0sxQTsfA5FH2YSAzzuTtfjYUM1O38sMekG2br4cf5W0v04eyq8zsea_oNRrVVOGuZdlE2Gx6_XYNJSoLi2nX3dr9LHqnCgge7S5A"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-5 left-6 text-white">
              <h4 class="text-xl font-bold font-headline">South Kolkata</h4>
              <p class="text-xs font-medium opacity-80">Elite Heritage Zone</p>
            </div>
          </div>
        </a>
        <a href="<?php echo SITE_URL; ?>/residential.php" class="min-w-[80%] snap-start bg-surface-container-lowest rounded-[2.5rem] overflow-hidden group block">
          <div class="h-56 relative">
            <img class="w-full h-full object-cover" alt="EM Bypass" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBqBuKi8tvLxKrLUTcumYcgvXdY79qUOFhG_g7HB7ZeIlE5BAVfAQyISXcgKVUoBcqhQg1IdDKaj-4sfteu_yYmw7MxgqwKmxAKYvYAxU6cQoi_tFrdlPy4lsopnAmG6J7GlPPBTg8M5t1KJfnNQx-h557AHwnmx977hl_CrRwk9dFB0sdnzskEnuttWZh695ePWB9Te1kzOYEPz7ZUpq7DnJXh55IznBanTM25JOLlSB4C0JA3h4F4uv1IMVExUZSrYvPe50tDO9g"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-5 left-6 text-white">
              <h4 class="text-xl font-bold font-headline">EM Bypass</h4>
              <p class="text-xs font-medium opacity-80">Growth Corridor</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- =========================================================
       WHY US SECTION
       ========================================================= -->
  <section class="py-16 md:py-24 px-6 md:px-8 reveal">
    <!-- Desktop dark card -->
    <div class="hidden md:block max-w-screen-2xl mx-auto bg-primary-container rounded-2xl p-12 md:p-16 text-white shadow-2xl">
      <div class="mb-12">
        <div class="flex items-center gap-4 mb-6">
          <div class="w-1.5 h-8 bg-on-tertiary-container rounded-full"></div>
          <h2 class="font-headline text-3xl font-extrabold tracking-tight">Why Us</h2>
        </div>
        <p class="text-lg text-white/90 leading-relaxed max-w-5xl">
          At <?php echo SITE_NAME; ?>, we're more than just Property Advisors. We're a one-stop shop for all your home-buying needs in Kolkata. From choosing the perfect property to financing to transforming it into your dream home…
          <a class="text-on-tertiary-container font-bold hover:underline ml-1" href="<?php echo SITE_URL; ?>/about.php">Explore More</a>
        </p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 border-t border-white/10 pt-16">
        <?php
        $why_us = [
          ['icon' => 'currency_rupee', 'label' => 'Zero Brokerage'],
          ['icon' => 'description', 'label' => 'KMC/BLRO Mutation'],
          ['icon' => 'verified_user', 'label' => 'Legal Title Verification'],
          ['icon' => 'account_balance', 'label' => 'Home Loan Support'],
        ];
        foreach ($why_us as $item): ?>
          <div class="flex flex-col items-center text-center group">
            <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-6 transition-all group-hover:bg-on-tertiary-container/20">
              <span class="material-symbols-outlined text-on-tertiary-container text-4xl"><?php echo $item['icon']; ?></span>
            </div>
            <h3 class="font-headline font-bold text-lg"><?php echo $item['label']; ?></h3>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Mobile trust grid -->
    <div class="md:hidden bg-primary py-16 px-6 rounded-3xl text-white">
      <h3 class="text-3xl font-headline font-bold mb-12 text-center">Institutional Grade Trust</h3>
      <div class="grid grid-cols-2 gap-y-10 gap-x-6">
        <?php foreach ($why_us as $item): ?>
          <div class="flex flex-col items-center text-center space-y-3">
            <div class="w-18 h-18 rounded-full bg-white/10 flex items-center justify-center w-20 h-20">
              <span class="material-symbols-outlined text-4xl"><?php echo $item['icon']; ?></span>
            </div>
            <p class="text-xs font-bold tracking-widest uppercase leading-tight"><?php echo $item['label']; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =========================================================
       NEWLY LISTED PROPERTIES
       ========================================================= -->
  <section class="py-16 md:py-24 bg-surface-container-highest reveal">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-8">
      <div class="flex items-center justify-between mb-10 md:mb-16">
        <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-primary">Newly Listed</h2>
        <a href="<?php echo SITE_URL; ?>/residential.php" class="text-primary font-bold text-sm underline underline-offset-4 md:hidden">View All</a>
        <div class="hidden md:flex gap-4">
          <button class="w-12 h-12 flex items-center justify-center rounded-full bg-white text-primary shadow-sm hover:shadow-md transition-shadow">
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <button class="w-12 h-12 flex items-center justify-center rounded-full bg-primary text-white shadow-sm hover:shadow-md transition-shadow">
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
        </div>
      </div>

      <!-- Desktop: grid -->
      <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-10">
        <?php
        require_once __DIR__ . '/includes/db.php';
        if (isset($pdo)) {
            $stmt = $pdo->prepare('SELECT * FROM listings WHERE status != "deleted" ORDER BY id DESC LIMIT 3');
            $stmt->execute();
            $recent_listings = $stmt->fetchAll();
            foreach ($recent_listings as $lst):
                $hero_img_name = trim((string)($lst['image_filename'] ?? ''));
                if (strpos($hero_img_name, ',') !== false) {
                  $hero_img_name = trim(explode(',', $hero_img_name)[0]);
                }
                $hero_img = (!empty($hero_img_name) && strpos($hero_img_name, 'http') === false) ? SITE_URL . '/assets/images/' . ltrim($hero_img_name, '/') : ($hero_img_name ?: SITE_URL . '/assets/images/placeholder.jpg');
                $price_fmt = $lst['price'] >= 10000000 ? round($lst['price'] / 10000000, 2) . ' Cr' : round($lst['price'] / 100000, 2) . ' Lakh';
        ?>
        <a href="<?php echo SITE_URL; ?>/property.php?id=<?php echo $lst['id']; ?>" class="bg-surface-container-lowest rounded-xl overflow-hidden group block shadow-sm border border-outline-variant/10 hover:shadow-xl transition-all">
          <div class="relative overflow-hidden aspect-[16/10]">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="<?php echo htmlspecialchars($lst['title']); ?>" src="<?php echo htmlspecialchars($hero_img); ?>"/>
            <button class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white hover:text-primary transition-colors" onclick="event.preventDefault()">
              <span class="material-symbols-outlined">favorite</span>
            </button>
            <?php if (!empty($lst['is_featured'])): ?>
            <div class="absolute top-4 left-4 bg-secondary text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-md shadow-sm">Featured</div>
            <?php endif; ?>
          </div>
          <div class="p-8">
            <div class="flex justify-between items-start mb-6">
              <div>
                <h3 class="text-xl font-headline font-bold text-primary mb-1"><?php echo htmlspecialchars($lst['title']); ?></h3>
                <p class="text-outline text-sm"><?php echo htmlspecialchars($lst['location'] . ', ' . $lst['city']); ?></p>
              </div>
              <div class="text-xl font-headline font-extrabold text-primary whitespace-nowrap ml-4">&#8377;<?php echo $price_fmt; ?></div>
            </div>
            <div class="flex items-center gap-6 text-on-surface-variant font-medium text-sm">
              <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">bed</span><?php echo htmlspecialchars($lst['bedrooms'] ?: '-'); ?></div>
              <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">bathtub</span><?php echo htmlspecialchars($lst['bathrooms'] ?: '-'); ?></div>
              <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">square_foot</span><?php echo htmlspecialchars($lst['area_sqft']); ?></div>
            </div>
            <button onclick="event.preventDefault(); openContactModal();" class="w-full mt-8 py-3 bg-primary text-on-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm uppercase tracking-wider">
              Book Site Visit
            </button>
          </div>
        </a>
        <?php endforeach;
        } ?>
      </div>

      <!-- Mobile: horizontal scroll -->
      <div class="md:hidden flex overflow-x-auto no-scrollbar gap-5 snap-x snap-mandatory -mx-6 px-6">
        <?php
        if (isset($pdo) && isset($recent_listings) && !empty($recent_listings)):
          foreach ($recent_listings as $lst):
            $hero_img_name = trim((string)($lst['image_filename'] ?? ''));
            if (strpos($hero_img_name, ',') !== false) {
              $hero_img_name = trim(explode(',', $hero_img_name)[0]);
            }
            $hero_img = (!empty($hero_img_name) && strpos($hero_img_name, 'http') === false) ? SITE_URL . '/assets/images/' . ltrim($hero_img_name, '/') : ($hero_img_name ?: SITE_URL . '/assets/images/placeholder.jpg');
            $price_fmt = $lst['price'] >= 10000000 ? round($lst['price'] / 10000000, 2) . ' Cr' : round($lst['price'] / 100000, 2) . ' Lakh';
        ?>
        <a href="<?php echo SITE_URL; ?>/property.php?id=<?php echo $lst['id']; ?>" class="min-w-[88%] snap-start bg-surface-container-low rounded-[2.5rem] p-5 flex flex-col shadow-sm block">
          <div class="h-64 rounded-[2rem] overflow-hidden mb-5">
            <img class="w-full h-full object-cover" alt="<?php echo htmlspecialchars($lst['title']); ?>" src="<?php echo htmlspecialchars($hero_img); ?>"/>
          </div>
          <div class="flex-1 px-1">
            <div class="flex justify-between items-start mb-2">
              <h4 class="text-xl font-bold font-headline text-primary"><?php echo htmlspecialchars($lst['title']); ?></h4>
              <span class="bg-tertiary-fixed text-on-tertiary-fixed px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight">Luxury</span>
            </div>
            <p class="text-outline flex items-center gap-1 text-sm mb-4">
              <span class="material-symbols-outlined text-sm">location_on</span><?php echo htmlspecialchars($lst['location'] . ', ' . $lst['city']); ?>
            </p>
            <p class="text-2xl font-extrabold font-headline text-primary mb-5">&#8377;<?php echo $price_fmt; ?></p>
            <button onclick="event.preventDefault(); openContactModal();" class="w-full bg-primary-container text-on-primary py-4 rounded-2xl font-bold tracking-tight shadow-lg active:scale-[0.98]">
              Get Price Details
            </button>
          </div>
        </a>
        <?php endforeach;
        endif; ?>
      </div>
    </div>
  </section>

  <!-- =========================================================
       CLIENT FEEDBACK
       ========================================================= -->
  <section class="py-16 md:py-24 bg-surface reveal">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-8">
      <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-primary mb-12 md:mb-16 text-center">Client Feedback</h2>

      <!-- Desktop: 3-col grid -->
      <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php
        $testimonials = [
          ['quote' => '"The level of architectural insight provided by ' . SITE_NAME . ' is unmatched in Kolkata. They didn\'t just show us houses; they curated a selection of art we could live in."', 'name' => 'Ananya Sen', 'role' => 'Industrialist'],
          ['quote' => '"Discreet and deeply knowledgeable about the Kolkata market. They secured our South Kolkata penthouse in record time through their exclusive network."', 'name' => 'Rajesh Gupta', 'role' => 'Real Estate Investor'],
          ['quote' => '"A concierge experience from start to finish. ' . SITE_NAME . ' understands that for us, a home is more than an asset — it\'s a sanctuary for our legacy."', 'name' => 'S. Mukhopadhyay', 'role' => 'Creative Director'],
        ];
        foreach ($testimonials as $t): ?>
          <div class="bg-white p-10 rounded-xl shadow-sm border border-outline-variant/30 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
              <div class="flex gap-0.5 text-on-tertiary-container mb-6">
                <?php for ($i = 0; $i < 5; $i++): ?>
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1">star</span>
                <?php endfor; ?>
              </div>
              <p class="text-on-surface-variant italic text-lg leading-relaxed mb-8"><?php echo $t['quote']; ?></p>
            </div>
            <div class="flex items-center gap-4 border-t border-outline-variant/30 pt-8">
              <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-outline">person</span>
              </div>
              <div>
                <p class="font-headline font-bold text-primary"><?php echo $t['name']; ?></p>
                <p class="text-xs text-outline font-medium tracking-wide uppercase"><?php echo $t['role']; ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Mobile: horizontal scroll -->
      <div class="md:hidden flex overflow-x-auto no-scrollbar gap-5 snap-x snap-mandatory -mx-6 px-6 mb-10">
        <?php foreach ($testimonials as $t): ?>
          <div class="min-w-[85%] snap-start bg-white p-8 rounded-[2.5rem] shadow-sm border border-surface-container">
            <div class="flex gap-1 mb-5">
              <?php for ($i = 0; $i < 5; $i++): ?>
              <span class="material-symbols-outlined text-tertiary-fixed-dim" style="font-variation-settings: 'FILL' 1">star</span>
              <?php endfor; ?>
            </div>
            <p class="italic text-primary/80 mb-6 font-medium leading-relaxed"><?php echo $t['quote']; ?></p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-outline">person</span>
              </div>
              <div>
                <p class="text-sm font-bold text-primary"><?php echo $t['name']; ?></p>
                <p class="text-[10px] text-outline uppercase font-bold tracking-widest mt-0.5"><?php echo $t['role']; ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =========================================================
       OUR PARTNERS
       ========================================================= -->
  <section class="py-16 md:py-24 bg-surface-container-low reveal">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-8">
      <div class="flex items-center gap-4 mb-12 md:mb-16">
        <div class="w-1.5 h-8 bg-on-tertiary-container rounded-full"></div>
        <h2 class="font-headline text-2xl md:text-3xl font-extrabold text-primary tracking-tight">Our Developer Partners</h2>
      </div>
      <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-outline text-center mb-8 md:hidden">Preferred Developer Partners</p>
      <div class="w-full">
        <img alt="Kolkata Developer Partners Logos" class="w-full h-auto object-contain grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all duration-500"
             src="https://lh3.googleusercontent.com/aida/ADBb0uh_RMJHfDJS1QJqU3A1hww1P_M3svsdMjCa5qPnG2nK9Oi2nDHGy3qIlOcKUvRvyThAaBwNFkMIGBP4QUUUCojC_3oJsoKj_zAQR3N_vht7uydXRFBgISyLzvPyThle0xHDdc-X6VVs7FEKTmpUYovT79ORGkIrRmJzEwZ-4gkvQI1FdWPMg5zwBfY9eFBg-sapOFbDDI9SvHrWp76IpNinJhCyQv8OgdzNRhxgF6s1OjQbyknTt0u5hJvwah2sfAetaXoqYtAyoQ"/>
      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

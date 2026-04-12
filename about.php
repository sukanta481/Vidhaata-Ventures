<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'About Us – Your Trusted Partner in Kolkata Real Estate';
require_once __DIR__ . '/includes/header.php';
?>

<main class="pt-16 md:pt-0">

  <!-- =========================================================
       HERO SECTION
       ========================================================= -->
  <section class="relative h-[60vh] md:h-[819px] w-full flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img alt="Kolkata Skyline" class="w-full h-full object-cover brightness-[55%]"
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuCouMkH5vaAfMttqQsPpWE7dG7Z-jrFE4gskrH6fKhIKnYrisE3ipOvDRfRLKvVsUHJDcpGVFkqgQAcwuvkb8ZfBA02DPtsD7pFFZ-7-Fvnvx9KRTV5XT-9KY_hgGOheFLo6-iSi28VDFC3T78lSsJb_uESb1omkcfNO9jnTinqoNmJcmMUHNmyx6eEgWkwQ2KfK4nMLNSwBkKFIoGE-nDpRsOa6_eljugtv8PYeRjSH7iNehY7nOFLvIlpdoWxRpv5gw5QnG1v3M4"/>
    </div>
    <!-- Desktop hero text (left aligned) -->
    <div class="relative z-10 max-w-screen-2xl mx-auto px-8 md:px-10 w-full hidden md:block">
      <div class="max-w-3xl">
        <span class="inline-block text-tertiary-fixed bg-tertiary-container/30 px-4 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-6">Established 2012</span>
        <h1 class="text-6xl md:text-8xl font-extrabold text-white tracking-tighter mb-8 leading-[1.1] font-headline">
          Your Trusted<br/>Partner in<br/>Kolkata Real Estate.
        </h1>
        <p class="text-xl text-slate-200 font-light max-w-xl leading-relaxed">
          Navigating the heritage and modernization of the City of Joy with precision, integrity, and unparalleled local expertise.
        </p>
      </div>
    </div>
    <!-- Mobile hero text (centered) -->
    <div class="md:hidden relative z-10 w-full flex flex-col items-center justify-center px-6 text-center">
      <h1 class="text-4xl font-extrabold text-white tracking-tight mb-4 font-headline leading-tight">
        Your Trusted Partner in Kolkata Real Estate
      </h1>
      <div class="w-12 h-1 bg-tertiary-fixed rounded-full mt-2"></div>
    </div>
  </section>

  <!-- =========================================================
       OUR STORY
       ========================================================= -->
  <section class="py-16 md:py-24 px-6 md:px-10 max-w-screen-2xl mx-auto reveal">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
      <div class="relative">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary-container rounded-full opacity-10 blur-3xl"></div>
        <div class="rounded-xl md:rounded-2xl shadow-2xl relative z-10 overflow-hidden aspect-[4/5]">
          <img alt="Premium Kolkata Architecture"
               class="w-full h-full object-cover"
               src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcg6EAk9hQ_MBJbggVhJ2QFxeIQtSNWwwmIu4LD_EB3s1fdLAui_eQzv-BoKWLkK62HIy9V2we727xK5T31osbP9i0WW0ZNC0vKlftx9uj2_FEvzGFzcvVsmztgqsk-vapJKLXqIxANokFkvDxYgryPQl6JR-xkXuruCEk-RtRCW0dSiPdVNvg8lX6QsXwsm9kfIPzGsLNwb3te2OQLHfgmSGeU3gkjdZq4nTy257MBk7rEwWBIznZJsc4wiocLf-LupjHfE1hNu0"/>
        </div>
      </div>
      <div class="space-y-6 md:space-y-8">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-primary font-headline leading-tight">A Legacy Built on Concrete Trust</h2>
        <div class="space-y-5 text-on-surface-variant leading-relaxed text-base md:text-lg">
          <p>As Kolkata natives, we founded <?php echo SITE_NAME; ?> to simplify the complex property landscape of West Bengal for every aspiring homeowner.</p>
          <p>We hold deep roots in the bustling tech corridors of <strong class="text-primary">New Town</strong> and the prestigious residential lanes of <strong class="text-primary">South Kolkata</strong>. From Salt Lake to Ballygunge, our team possesses an intimate understanding of neighbourhood dynamics, legal intricacies, and future development prospects.</p>
          <p>We don't just sell properties; we facilitate the building of homes and the securing of commercial futures in the cultural capital of India.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================
       TRUST BANNER
       ========================================================= -->
  <section class="bg-primary-container py-12 md:py-14 px-6 md:px-10 reveal">
    <div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row justify-around items-start md:items-center gap-8 md:gap-4 text-white">
      <?php
      $trust_items = [
        ['icon' => 'verified_user', 'title' => 'WBRERA Registered Agent', 'sub' => 'Full regulatory compliance'],
        ['icon' => 'gavel',         'title' => '100% Legal Transparency', 'sub' => 'Clear titles & documentation'],
        ['icon' => 'payments',      'title' => 'Zero Hidden Charges',     'sub' => 'Ethical pricing, no surprises'],
      ];
      foreach ($trust_items as $item): ?>
        <div class="flex items-center gap-4 group">
          <div class="p-3 bg-white/10 rounded-xl group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-3xl"><?php echo $item['icon']; ?></span>
          </div>
          <div>
            <p class="font-bold text-lg tracking-tight"><?php echo $item['title']; ?></p>
            <p class="text-on-primary-container text-sm"><?php echo $item['sub']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- =========================================================
       OUR LEADERSHIP / FOUNDERS
       ========================================================= -->
  <section class="py-16 md:py-24 px-6 md:px-10 bg-surface-container-low reveal">
    <div class="max-w-screen-2xl mx-auto">
      <div class="mb-12 md:mb-16 text-center md:text-left">
        <h2 class="text-3xl md:text-4xl font-bold text-primary mb-3 font-headline">Our Leadership</h2>
        <p class="text-on-surface-variant max-w-2xl md:mx-0 mx-auto">Our founders bring decades of collective expertise in the local market, maintaining a personal touch with every client we serve.</p>
      </div>

      <!-- Desktop: side-by-side card with image -->
      <div class="hidden md:grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20">
        <?php
        $founders = [
          ['name' => 'Anil Kumar Chowdhury', 'role' => 'Founder & Principal Advisor', 'bio' => 'With over 20 years of expertise in South Kolkata\'s luxury landscape, Anil personally oversees every premium residential acquisition. His commitment to direct client interaction ensures your property journey is guided by the most seasoned eyes in the city.', 'img' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80'],
          ['name' => 'Anish Chowdhury', 'role' => 'CEO & Property Advisor', 'bio' => 'Anish leads our New Town operations with a focus on strategic commercial leasing and modern high-rise developments. His hands-on approach ensures every client benefits from deep network access and an unparalleled understanding of West Bengal\'s evolving real estate laws.', 'img' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=800&q=80'],
        ];
        foreach ($founders as $f): ?>
          <div class="bg-surface rounded-2xl overflow-hidden hover:shadow-2xl transition-all group flex flex-col md:flex-row">
            <div class="md:w-1/2 h-80 md:h-auto overflow-hidden">
              <img alt="<?php echo htmlspecialchars($f['name']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?php echo $f['img']; ?>"/>
            </div>
            <div class="p-8 md:w-1/2 flex flex-col justify-center">
              <h3 class="text-2xl font-bold text-primary font-headline"><?php echo htmlspecialchars($f['name']); ?></h3>
              <p class="text-secondary font-semibold text-sm mb-6 uppercase tracking-wider"><?php echo htmlspecialchars($f['role']); ?></p>
              <p class="text-on-surface-variant text-base leading-relaxed"><?php echo htmlspecialchars($f['bio']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Mobile: stacked circular photo cards -->
      <div class="md:hidden flex flex-col gap-16">
        <?php foreach ($founders as $f): ?>
          <div class="flex flex-col items-center text-center">
            <div class="w-44 h-44 rounded-full overflow-hidden border-4 border-surface-container-lowest shadow-2xl mb-6">
              <img alt="<?php echo htmlspecialchars($f['name']); ?>" class="w-full h-full object-cover" src="<?php echo $f['img']; ?>"/>
            </div>
            <h4 class="font-headline text-2xl font-bold text-primary"><?php echo htmlspecialchars($f['name']); ?></h4>
            <p class="text-secondary font-semibold text-sm tracking-widest uppercase mt-1 mb-4"><?php echo htmlspecialchars($f['role']); ?></p>
            <p class="text-on-surface-variant leading-relaxed px-4 text-sm"><?php echo htmlspecialchars($f['bio']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =========================================================
       HOW WE WORK / THE ELITE JOURNEY
       ========================================================= -->
  <section class="py-16 md:py-24 bg-surface reveal">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-10">
      <div class="text-center mb-12 md:mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4 font-headline">How We Work</h2>
        <div class="w-24 h-1 bg-secondary mx-auto mb-6"></div>
        <p class="text-on-surface-variant max-w-2xl mx-auto text-sm md:text-base">A transparent, structured approach to securing your ideal property in the City of Joy.</p>
      </div>

      <!-- Desktop: 4 phase cards grid -->
      <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php
        $phases = [
          ['num' => '01', 'icon' => 'psychology',      'title' => 'Phase 1: Consultation & Mapping',  'desc' => 'We begin by understanding your lifestyle, budget, and specific location preferences through in-depth requirement mapping.'],
          ['num' => '02', 'icon' => 'travel_explore',  'title' => 'Phase 2: Curated Discovery',        'desc' => 'Based on your map, we curate a shortlist of handpicked properties, arranging exclusive private tours and neighbourhood analysis.'],
          ['num' => '03', 'icon' => 'gavel',            'title' => 'Phase 3: Due Diligence',            'desc' => 'Our legal team performs rigorous document verification and price negotiation to ensure you get the best value with zero risk.'],
          ['num' => '04', 'icon' => 'key',              'title' => 'Phase 4: Seamless Handover',        'desc' => 'From final payment and registration to mutation and possession, we manage the entire paperwork journey for you.'],
        ];
        foreach ($phases as $phase): ?>
          <div class="relative p-8 bg-surface-container-low rounded-2xl border border-outline-variant/30 hover:border-secondary transition-colors group">
            <div class="text-4xl font-black text-outline-variant/20 absolute top-4 right-6 group-hover:text-secondary/10 transition-colors"><?php echo $phase['num']; ?></div>
            <div class="w-14 h-14 bg-primary-container rounded-xl flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-white text-3xl"><?php echo $phase['icon']; ?></span>
            </div>
            <h3 class="text-xl font-bold text-primary mb-3 font-headline"><?php echo $phase['title']; ?></h3>
            <p class="text-on-surface-variant text-sm leading-relaxed"><?php echo $phase['desc']; ?></p>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Mobile: horizontal scroll carousel -->
      <div class="md:hidden flex overflow-x-auto gap-5 no-scrollbar snap-x snap-mandatory -mx-6 px-6">
        <?php foreach ($phases as $phase): ?>
          <div class="flex-none w-[85%] snap-center bg-surface-container-lowest p-8 rounded-2xl shadow-sm border-l-4 border-primary">
            <span class="text-tertiary-fixed-dim font-headline font-black text-5xl opacity-30 block mb-2"><?php echo $phase['num']; ?></span>
            <h5 class="text-xl font-bold text-primary mb-2 font-headline"><?php echo $phase['title']; ?></h5>
            <p class="text-on-surface-variant text-sm leading-relaxed"><?php echo $phase['desc']; ?></p>
          </div>
        <?php endforeach; ?>
        <div class="flex-none w-4"></div>
      </div>
    </div>
  </section>

  <!-- =========================================================
       STATS BANNER
       ========================================================= -->
  <section class="px-6 md:px-10 py-12 md:py-16 bg-surface reveal">
    <div class="max-w-screen-2xl mx-auto bg-primary rounded-2xl p-8 md:p-14">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-white">
        <?php
        $stats = [
          ['val' => '500+', 'label' => 'Properties Sold'],
          ['val' => '1,200+', 'label' => 'Happy Clients'],
          ['val' => '12+', 'label' => 'Years of Experience'],
          ['val' => '8', 'label' => 'Cities Covered'],
        ];
        foreach ($stats as $s): ?>
          <div>
            <p class="font-headline text-4xl md:text-5xl font-extrabold mb-2"><?php echo $s['val']; ?></p>
            <p class="text-white/70 text-sm uppercase tracking-widest font-bold"><?php echo $s['label']; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =========================================================
       FINAL CTA
       ========================================================= -->
  <section class="py-16 md:py-24 px-6 md:px-10 reveal">
    <div class="max-w-4xl mx-auto text-center bg-primary-container rounded-[2rem] p-10 md:p-20 relative overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <img alt="Victoria Memorial background" class="w-full h-full object-cover"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXV8NIy2FdVK9vHKEnMJ_Q6Ttdf9bXOtaHGj7V4anKsvQaENC4OU8FrQdCVaX6fdDASj8vPPs-o4hWWhIX1r0BnCUIOaGLCpptGZXuvxCsdzdvKtYWymum0Thpkk6BOAm39Wu_71Du1VSnvWKDARxnravBZhDuUYIZdNUUMcMCjhan6Pc_ithEV3BVd2xs-Kon-cnNI8Z90JyhApEDrMRPff5oLXtgF0TK67UOB6vrR5kR0u-xpSxxrzcKk"/>
      </div>
      <div class="relative z-10">
        <h2 class="text-3xl md:text-5xl font-bold text-white mb-5 md:mb-6 font-headline leading-tight">Looking to buy or sell in Kolkata? Let's talk.</h2>
        <p class="text-on-primary-container text-base md:text-lg mb-8 md:mb-10 max-w-xl mx-auto leading-relaxed">
          Our team is ready to guide you through every step of your property journey. Schedule your free consultation today.
        </p>
        <button onclick="openContactModal()" class="bg-white text-primary-container px-8 md:px-10 py-4 rounded-xl font-bold text-lg hover:bg-slate-100 transition-all active:scale-95 shadow-xl w-full md:w-auto">
          Schedule a Consultation
        </button>
      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

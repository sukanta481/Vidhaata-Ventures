<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Our Services – End-to-End Real Estate Solutions';
require_once __DIR__ . '/includes/header.php';

$services = [
  ['icon' => 'balance',        'title' => 'Legal & Title Verification',         'copy' => 'Rigorous multi-point legal checks ensuring clear property titles and complete risk mitigation for your investment.', 'cta' => 'Get Legal Help'],
  ['icon' => 'account_balance','title' => 'Home Loan Assistance',                'copy' => 'Personalised financial advisory and streamlined loan processing with leading banking partners in Kolkata.', 'cta' => 'Apply Now'],
  ['icon' => 'description',    'title' => 'Property Mutation (KMC & BLRO)',       'copy' => 'Efficient handling of municipal and block land records updates to ensure legal ownership status post-purchase.', 'cta' => 'Consult Mutation'],
  ['icon' => 'edit_note',      'title' => 'Drafting & Documentation',             'copy' => 'Meticulous preparation of Sale Deeds, Gift Deeds, and Power of Attorney by expert legal drafters.', 'cta' => 'Draft Documents'],
  ['icon' => 'architecture',   'title' => 'Property Consulting & Vastu',          'copy' => 'Harmonising modern architectural layouts with traditional Vastu principles for balanced, auspicious living spaces.', 'cta' => 'Book Vastu Audit'],
  ['icon' => 'handshake',      'title' => 'Resale & Rental Agreements',           'copy' => 'Comprehensive management of lease contracts and secondary market transactions with complete transparency.', 'cta' => 'Get Agreement'],
  ['icon' => 'real_estate_agent','title' => 'Property Advisory', 'copy' => 'A measured first step for buyers, sellers, and investors who want clarity before committing. We study your brief and shortlist the right opportunities.', 'cta' => 'Start Advisory'],
  ['icon' => 'manage_accounts','title' => 'Property Management',                  'copy' => 'Ongoing support for owners who need coordination around occupancy, upkeep, tenant touchpoints, and asset readiness.', 'cta' => 'Plan Ongoing Care'],
];
?>

<main class="pt-16 md:pt-0">

  <!-- =========================================================
       HERO SECTION
       ========================================================= -->
  <section class="relative h-[52vh] md:h-[700px] w-full flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img alt="Professionals in meeting in a modern Kolkata office"
           class="w-full h-full object-cover"
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuA55fvIYqMeKHodlR446KLZQAqcLJDJdbDdYnC0p3B5pffkeTZTjrlnaohtPr_C9oEEP5YyUFsqf_ja-Dzs5Ek4QPNht5T-G42UKXSQQL3eCJBvb2qe2NFK5eCQN8nTwE57BlO7HurYOXNURFRB8PiYETYZTiRNSQeec60PK4s5wGyv8EV0Ts6RPSPRY3dEXKep7hd3unRmlcz2iwsFhLuY4D_Xt2YlTTPU_14NNdMEsQdXHt3AKWm3xT3mePUbHwAYBQFaJPvtq70"/>
      <div class="absolute inset-0 bg-primary/60"></div>
    </div>

    <!-- Desktop hero text -->
    <div class="hidden md:block relative z-10 max-w-screen-2xl mx-auto px-8 w-full">
      <p class="text-sm font-bold uppercase tracking-widest mb-5 text-primary-fixed">Our Services</p>
      <h1 class="font-headline text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6">
        End-to-End Real<br/>Estate Solutions.
      </h1>
      <p class="max-w-2xl text-white/85 text-lg leading-relaxed">
        Navigating Kolkata's complex property landscape with heritage-level expertise and digital-first precision.
      </p>
      <button onclick="openContactModal()" class="mt-8 px-8 py-4 bg-secondary-container text-on-secondary-container font-bold rounded-xl hover:opacity-90 active:scale-95 transition-all flex items-center gap-3">
        <span class="material-symbols-outlined">chat</span>
        Message us on WhatsApp
      </button>
    </div>

    <!-- Mobile hero text -->
    <div class="md:hidden relative z-10 px-6 text-center flex flex-col items-center max-w-sm">
      <h1 class="font-headline font-extrabold text-4xl tracking-tight text-white mb-4 leading-tight">
        End-to-End Real Estate Solutions in Kolkata.
      </h1>
      <p class="text-white/80 text-base mb-8 leading-relaxed">
        Navigating Kolkata's complex property landscape with heritage-level expertise.
      </p>
      <button onclick="openContactModal()" class="w-full py-4 px-6 rounded-xl flex items-center justify-center gap-3 font-headline font-bold text-sm tracking-widest uppercase hover:opacity-90 active:scale-95 transition-all bg-secondary-container text-on-secondary-container">
        <span class="material-symbols-outlined">chat</span>
        Message us on WhatsApp
      </button>
    </div>
  </section>

  <!-- =========================================================
       TRUST STRIP
       ========================================================= -->
  <section class="bg-primary py-10 md:py-12 px-6 md:px-10 reveal">
    <div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row gap-8 md:gap-0 md:justify-around items-start md:items-center text-white">
      <?php
      $trust = [
        ['icon' => 'verified_user', 'label' => 'Zero Hidden Charges'],
        ['icon' => 'gavel',        'label' => '100% RERA Compliant'],
        ['icon' => 'door_front',   'label' => 'Hassle-Free Doorstep Assistance'],
      ];
      foreach ($trust as $t): ?>
        <div class="flex items-center gap-5">
          <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-secondary-fixed flex-shrink-0">
            <span class="material-symbols-outlined text-3xl"><?php echo $t['icon']; ?></span>
          </div>
          <span class="font-headline font-semibold text-lg tracking-tight"><?php echo $t['label']; ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- =========================================================
       SERVICES GRID (Desktop: 2-col alternating, Mobile: stacked cards)
       ========================================================= -->
  <section class="py-16 md:py-20 reveal">
    <!-- Desktop: alternating image+text layout -->
    <div class="hidden md:block max-w-screen-2xl mx-auto px-8">
      <div class="mb-16 text-center">
        <h2 class="font-headline font-bold text-4xl text-primary tracking-tighter mb-4">Bespoke Advisory Services</h2>
        <div class="h-1 w-16 bg-on-tertiary-container mx-auto"></div>
      </div>
      <?php foreach ($services as $index => $service):
        $is_reverse = $index % 2 === 1;
        $section_bg = $index % 2 === 1 ? 'bg-surface-container-low' : 'bg-surface';
        $img_order  = $is_reverse ? 'lg:order-2' : '';
        $txt_order  = $is_reverse ? 'lg:order-1' : '';
      ?>
        <div class="<?php echo $section_bg; ?> py-16 md:py-20 rounded-2xl mb-4">
          <div class="max-w-screen-2xl mx-auto px-8 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-center">
            <div class="<?php echo $img_order; ?> flex items-center justify-center">
              <div class="w-32 h-32 bg-primary-container rounded-3xl flex items-center justify-center shadow-2xl">
                <span class="material-symbols-outlined text-white" style="font-size:4rem"><?php echo htmlspecialchars($service['icon']); ?></span>
              </div>
            </div>
            <div class="<?php echo $txt_order; ?> max-w-xl <?php echo $is_reverse ? 'lg:ml-auto' : ''; ?>">
              <p class="text-on-tertiary-container font-bold uppercase tracking-widest text-xs mb-5">
                Service <?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?>
              </p>
              <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-primary mb-5 leading-tight">
                <?php echo htmlspecialchars($service['title']); ?>
              </h2>
              <p class="text-on-surface-variant text-base md:text-lg leading-relaxed mb-8">
                <?php echo htmlspecialchars($service['copy']); ?>
              </p>
              <button onclick="openContactModal()" class="bg-primary-container text-on-primary px-8 py-4 rounded-xl font-bold hover:opacity-90 transition-all active:scale-95 flex items-center gap-2">
                <?php echo htmlspecialchars($service['cta']); ?>
                <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Mobile: stacked service cards -->
    <div class="md:hidden px-5">
      <div class="mb-10">
        <h2 class="font-headline font-bold text-3xl text-primary tracking-tighter">Bespoke Advisory</h2>
        <div class="h-1 w-12 bg-on-tertiary-container mt-2"></div>
      </div>
      <div class="grid grid-cols-1 gap-6">
        <?php foreach ($services as $service): ?>
          <div class="bg-surface-container-lowest rounded-2xl p-7 shadow-sm group">
            <span class="material-symbols-outlined text-primary-container text-4xl mb-5 block"><?php echo htmlspecialchars($service['icon']); ?></span>
            <h3 class="font-headline font-extrabold text-xl mb-3 text-primary"><?php echo htmlspecialchars($service['title']); ?></h3>
            <p class="text-on-surface-variant text-sm mb-6 leading-relaxed"><?php echo htmlspecialchars($service['copy']); ?></p>
            <button onclick="openContactModal()" class="w-full py-4 font-headline font-bold text-xs tracking-widest uppercase rounded-lg bg-primary-container text-on-primary hover:opacity-90 active:scale-95 transition-all">
              <?php echo htmlspecialchars($service['cta']); ?>
            </button>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =========================================================
       TESTIMONIAL
       ========================================================= -->
  <section class="py-16 md:py-24 bg-white px-6 md:px-10 flex flex-col items-center text-center reveal">
    <span class="material-symbols-outlined text-6xl text-surface-container-high mb-6">format_quote</span>
    <blockquote class="font-headline font-light italic text-xl md:text-2xl text-primary leading-relaxed mb-8 max-w-3xl mx-auto">
      "The team at <?php echo SITE_NAME; ?> transformed a nightmare property dispute into a seamless resolution. Their knowledge of KMC regulations is unparalleled in Kolkata."
    </blockquote>
    <div class="h-px w-16 bg-outline-variant mb-6"></div>
    <cite class="not-italic">
      <span class="block font-headline font-bold text-primary tracking-widest uppercase text-sm">Aniruddha Bose</span>
      <span class="block font-body text-on-surface-variant text-xs mt-1">Industrialist, Salt Lake City</span>
    </cite>
  </section>

  <!-- =========================================================
       BOTTOM CTA
       ========================================================= -->
  <section class="px-6 md:px-10 py-12 md:py-16 reveal">
    <div class="max-w-screen-2xl mx-auto bg-primary-container rounded-3xl p-8 md:p-14 text-center flex flex-col items-center">
      <h2 class="font-headline font-bold text-2xl md:text-4xl text-white mb-5 leading-tight">
        Have a complex property issue? Let us solve it for you today.
      </h2>
      <p class="text-on-primary-container text-sm md:text-base mb-8 max-w-xl leading-relaxed">
        Our expert team is ready to guide you through every step of the process with clarity and care.
      </p>
      <button onclick="openContactModal()" class="w-full md:w-auto bg-secondary-container text-on-secondary-container py-4 px-10 rounded-xl flex items-center justify-center gap-3 font-headline font-black text-sm tracking-widest uppercase shadow-lg hover:opacity-90 active:scale-95 transition-all">
        <span class="material-symbols-outlined">chat</span>
        Book a Free Consultation
      </button>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

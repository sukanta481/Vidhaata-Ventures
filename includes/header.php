<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
// Pages with NO dark hero behind the navbar need glass+dark text from the start
$nav_needs_glass = in_array($current_page, ['residential','commercial','property','services','about']);
$nav_logo_class  = $nav_needs_glass ? 'text-[#001225]' : 'text-white';
$nav_link_active = $nav_needs_glass ? 'text-[#001225] font-bold border-b-2 border-[#001225] pb-1' : 'text-white font-bold border-b-2 border-white pb-1';
$nav_link_idle   = $nav_needs_glass ? 'text-[#43474e] font-medium hover:text-[#001225]' : 'text-white/80 font-medium hover:text-white';
$nav_cta_class   = $nav_needs_glass ? 'bg-primary-container text-white' : 'bg-white text-primary-container';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME; ?></title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        'primary':           '#001225',
        'primary-container': '#022747',
        'on-primary':        '#ffffff',
        'on-primary-container': '#728fb4',
        'on-primary-fixed':  '#001c37',
        'on-primary-fixed-variant': '#2b486a',
        'primary-fixed':     '#d2e4ff',
        'primary-fixed-dim': '#abc9f1',
        'secondary':         '#00696b',
        'secondary-container':'#5ff4f7',
        'secondary-fixed':   '#63f7fa',
        'secondary-fixed-dim':'#3cdbde',
        'on-secondary':      '#ffffff',
        'on-secondary-container':'#006e70',
        'on-secondary-fixed':'#002020',
        'on-secondary-fixed-variant':'#004f51',
        'tertiary':          '#230a00',
        'tertiary-container':'#431a00',
        'tertiary-fixed':    '#ffdbc9',
        'tertiary-fixed-dim':'#ffb68e',
        'on-tertiary':       '#ffffff',
        'on-tertiary-container':'#df6d1d',
        'on-tertiary-fixed': '#331200',
        'on-tertiary-fixed-variant':'#763300',
        'surface':           '#fbf9f8',
        'surface-dim':       '#dcd9d9',
        'surface-bright':    '#fbf9f8',
        'surface-container-lowest':'#ffffff',
        'surface-container-low':'#f6f3f2',
        'surface-container': '#f0eded',
        'surface-container-high':'#eae8e7',
        'surface-container-highest':'#e4e2e1',
        'surface-variant':   '#e4e2e1',
        'surface-tint':      '#436083',
        'on-surface':        '#1b1c1c',
        'on-surface-variant':'#43474e',
        'on-background':     '#1b1c1c',
        'background':        '#fbf9f8',
        'outline':           '#73777f',
        'outline-variant':   '#c3c6cf',
        'inverse-surface':   '#303030',
        'inverse-on-surface':'#f3f0f0',
        'inverse-primary':   '#abc9f1',
        'error':             '#ba1a1a',
        'error-container':   '#ffdad6',
        'on-error':          '#ffffff',
        'on-error-container':'#93000a',
      },
      borderRadius: {
        DEFAULT: '0.25rem',
        lg: '0.5rem',
        xl: '0.75rem',
        '2xl': '1rem',
        '3xl': '1.5rem',
        full: '9999px',
      },
      fontFamily: {
        headline: ['Manrope', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
        label: ['Inter', 'sans-serif'],
      },
      boxShadow: {
        ambient: '0px 20px 40px rgba(27, 28, 28, 0.06)',
      },
    },
  },
}
</script>
<link href="assets/css/custom.css" rel="stylesheet"/>
<style>
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }
  .glass-nav {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  }
  .reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out;
  }
  .reveal.active {
    opacity: 1;
    transform: translateY(0);
  }
  /* Mobile bottom nav safe area */
  .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
  /* Hide scrollbar utility */
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-fixed selection:text-primary">

<!-- ============================================================
     DESKTOP Navigation (hidden on mobile)
     ============================================================ -->
<nav class="hidden md:block fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-screen-2xl transition-all duration-300 rounded-b-xl px-4<?php echo $nav_needs_glass ? ' glass-nav shadow-md' : ''; ?>" id="main-nav">
  <div class="flex justify-between items-center px-8 py-4 w-full max-w-screen-2xl mx-auto font-headline tracking-tight">
    <a href="index.php" class="inline-flex items-center" id="nav-logo" aria-label="<?php echo SITE_NAME; ?> home">
      <img src="assets/images/vidhaataventureslogo.png" alt="<?php echo SITE_NAME; ?>" class="h-10 w-auto <?php echo $nav_needs_glass ? '' : 'brightness-0 invert'; ?> transition duration-300"/>
    </a>

    <div class="flex items-center gap-8" id="nav-links">
      <a class="<?php echo $current_page === 'index'       ? $nav_link_active : $nav_link_idle; ?> transition-colors duration-300" href="index.php">Home</a>
      <a class="<?php echo $current_page === 'residential'  ? $nav_link_active : $nav_link_idle; ?> transition-colors duration-300" href="residential.php">Residential</a>
      <a class="<?php echo $current_page === 'commercial'   ? $nav_link_active : $nav_link_idle; ?> transition-colors duration-300" href="commercial.php">Commercial</a>
      <a class="<?php echo $current_page === 'services'     ? $nav_link_active : $nav_link_idle; ?> transition-colors duration-300" href="services.php">Services</a>
      <a class="<?php echo $current_page === 'about'        ? $nav_link_active : $nav_link_idle; ?> transition-colors duration-300" href="about.php">About Us</a>
    </div>

    <div class="flex items-center gap-4">
      <span class="hidden lg:block <?php echo $nav_needs_glass ? 'text-[#43474e] border-[#c3c6cf]' : 'text-white/70 border-white/20'; ?> text-[10px] font-medium tracking-wider uppercase border px-2 py-1 rounded">WBRERA Reg. No: HIRA/A/KOL/2024/000XXX</span>
      <button class="<?php echo $nav_cta_class; ?> px-6 py-2.5 rounded-lg font-bold hover:opacity-90 active:scale-95 transition-all text-sm" id="nav-cta" onclick="openContactModal()">Book Free Site Visit</button>
    </div>
  </div>
</nav>

<!-- ============================================================
     MOBILE Top App Bar (hidden on desktop)
     ============================================================ -->
<header class="md:hidden bg-white/80 backdrop-blur-xl fixed top-0 w-full z-50 flex items-center justify-between px-5 h-16 shadow-ambient">
  <button onclick="toggleMobileMenu()" class="text-slate-900 p-1 -ml-1 hover:opacity-80 active:scale-95 transition-all" aria-label="Open menu">
    <span class="material-symbols-outlined text-2xl" id="mobile-hamburger-icon">menu</span>
  </button>
  <a href="index.php" class="absolute left-1/2 -translate-x-1/2 inline-flex items-center" aria-label="<?php echo SITE_NAME; ?> home">
    <img src="assets/images/vidhaataventureslogo.png" alt="<?php echo SITE_NAME; ?>" class="h-8 w-auto"/>
  </a>
  <button onclick="openContactModal()" class="text-primary-container text-sm font-bold border border-primary-container/30 rounded-lg px-3 py-1.5 active:scale-95 transition-all">
    Contact
  </button>
</header>

<!-- Mobile Slide-down Menu -->
<div id="mobile-menu" class="md:hidden hidden fixed top-16 left-0 right-0 z-40 bg-white/95 backdrop-blur-xl shadow-ambient mx-0 px-6 py-6 transition-all">
  <div class="flex flex-col gap-1">
    <a class="<?php echo $current_page === 'index' ? 'text-primary font-bold bg-surface-container-low' : 'text-on-surface-variant font-medium'; ?> text-lg py-3 px-4 rounded-xl" href="index.php">Home</a>
    <a class="<?php echo $current_page === 'residential' ? 'text-primary font-bold bg-surface-container-low' : 'text-on-surface-variant font-medium'; ?> text-lg py-3 px-4 rounded-xl" href="residential.php">Residential</a>
    <a class="<?php echo $current_page === 'commercial' ? 'text-primary font-bold bg-surface-container-low' : 'text-on-surface-variant font-medium'; ?> text-lg py-3 px-4 rounded-xl" href="commercial.php">Commercial</a>
    <a class="<?php echo $current_page === 'services' ? 'text-primary font-bold bg-surface-container-low' : 'text-on-surface-variant font-medium'; ?> text-lg py-3 px-4 rounded-xl" href="services.php">Services</a>
    <a class="<?php echo $current_page === 'about' ? 'text-primary font-bold bg-surface-container-low' : 'text-on-surface-variant font-medium'; ?> text-lg py-3 px-4 rounded-xl" href="about.php">About Us</a>
    <button class="bg-primary text-on-primary px-6 py-3 rounded-xl font-bold mt-3 w-full" onclick="openContactModal(); closeMobileMenu();">Book Free Site Visit</button>
  </div>
</div>

<!-- Contact Modal -->
<div class="fixed inset-0 z-[100] hidden items-center justify-center bg-primary/40 backdrop-blur-sm" id="contact-modal">
  <div class="bg-surface-container-lowest p-8 md:p-10 rounded-3xl shadow-ambient w-[90%] max-w-lg mx-auto relative">
    <button class="absolute top-4 right-4 text-outline hover:text-on-surface transition-colors" onclick="closeContactModal()">
      <span class="material-symbols-outlined text-2xl">close</span>
    </button>
    <h3 class="text-2xl font-bold font-headline text-primary mb-2">Book a Free Site Visit</h3>
    <p class="text-on-surface-variant text-sm mb-8">Schedule a viewing or request a callback from our property experts.</p>
    <form id="contact-form" class="space-y-6">
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
      <div class="relative">
        <span class="absolute left-0 top-1 material-symbols-outlined text-outline text-lg">chat</span>
        <textarea class="w-full pl-8 pb-3 bg-transparent border-0 border-b border-outline-variant focus:border-primary-container focus:ring-0 font-medium placeholder:text-outline/60 text-on-surface resize-none" placeholder="Your Message (optional)" name="message" rows="2"></textarea>
      </div>
      <input type="hidden" name="source_page" value="<?php echo $current_page; ?>"/>
      <button class="w-full bg-gradient-to-b from-on-tertiary-container to-[#c55a10] text-white py-4 rounded-2xl font-extrabold text-lg shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-wider" type="submit">
        Submit Request
      </button>
    </form>
    <div id="form-toast" class="hidden mt-4 p-3 rounded-xl text-center text-sm font-medium"></div>
  </div>
</div>


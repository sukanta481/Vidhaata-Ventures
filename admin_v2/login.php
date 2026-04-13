<?php
session_start();

if (!empty($_SESSION['admin_logged_in'])) {
  header('Location: index.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/../includes/config.php';

  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS_HASH)) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: index.php');
    exit;
  } else {
    $error = 'Invalid credentials. Please try again.';
  }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Architectural Ledger - Secure Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            "colors": {
                 "on-tertiary-fixed": "#002204", "on-surface-variant": "#43474e", "tertiary-fixed": "#a3f69c",
                 "surface": "#effcfd", "surface-container-lowest": "#ffffff", "on-surface": "#121d1e",
                 "primary-container": "#641e00", "surface-container": "#e4f0f1", "on-primary-container": "#ff753d",
                 "surface-container-highest": "#d8e5e6", "on-background": "#121d1e", "secondary-container": "#b6d0ff",
                 "primary": "#401100", "error": "#ba1a1a", "surface-container-low": "#eaf6f7",
                 "background": "#effcfd", "secondary": "#455f88", "outline": "#74777f",
                 "surface-variant": "#d8e5e6", "outline-variant": "#c4c6cf", "error-container": "#ffdad6", "on-error": "#ffffff"
            },
            "fontFamily": {
                "headline": ["Manrope"],
                "body": ["Inter"],
                "label": ["Inter"]
            }
        },
    },
}
</script>
<style>
.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
body { font-family: 'Inter', sans-serif; }
h1, h2, h3, .font-headline { font-family: 'Manrope', sans-serif; }
</style>
</head>
<body class="bg-surface text-on-surface overflow-hidden">
<main class="flex min-h-screen w-full">
<!-- Left Side: Architectural Visual -->
<section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-on-background">
<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD5A5X558xgRLZjFQScfH7qGYD349qL0Mz0kWHtSJKwLHRWELr_tUuDROIwOczj6hNVeWrsBmLkv0XwBQiUrIjcLVzq16F2G0BzpaR89SA9FvZVxDcEMPV8mAJxChahr8mjxTHXcSwuVZiZh33eI3TW2IPcUPNHJ18SHxX4Ii3jbPX_SI0_zmKx_OP7Q1CzUmzAwpW3e7-uvhxrgENPmkaDHoc32GrrbbgZW9UQcuXzx4b3S_PzOcWbd1itc5G2sOiz0xafoQQrQC2d');"></div>
<div class="absolute inset-0 bg-gradient-to-t from-on-background/80 via-on-background/20 to-transparent"></div>
<div class="relative z-10 flex flex-col justify-end p-20 w-full">
<div class="max-w-xl">
<p class="font-headline text-on-primary-container text-xs uppercase tracking-[0.3em] mb-6">Editorial Archive 04</p>
<h2 class="font-headline text-5xl font-bold text-white leading-tight tracking-tighter mb-8">
    "Luxury is not a price point, it's a standard of legacy."
</h2>
<div class="w-16 h-1 bg-secondary mb-4"></div>
<p class="text-surface-container-highest/80 font-body text-lg italic">The Architectural Ledger: Precision in every transaction.</p>
</div>
</div>
</section>

<!-- Right Side: Login Form -->
<section class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 md:p-24 bg-surface-container-lowest">
<div class="w-full max-w-md">
<div class="mb-12">
<img src="../assets/images/vidhaataventureslogo.png" alt="Vidhaata Ventures" class="h-12 w-auto mb-4"/>
<h1 class="font-headline text-2xl font-semibold text-on-surface tracking-tight">Welcome Back, Broker</h1>
<p class="text-outline font-body text-sm mt-1">Log in to manage your high-end portfolio</p>
</div>

<form method="POST" action="" class="space-y-6">
<div class="space-y-2">
<label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider font-label">Username</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-secondary transition-colors">person</span>
<input name="username" class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-secondary/20 focus:bg-surface-container-lowest transition-all text-on-surface font-body outline-none" placeholder="broker_username" type="text" required autofocus autocomplete="username"/>
</div>
</div>

<div class="space-y-2">
<label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider font-label">Password</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-secondary transition-colors">lock</span>
<input name="password" id="password_input" class="w-full pl-12 pr-12 py-4 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-secondary/20 focus:bg-surface-container-lowest transition-all text-on-surface font-body outline-none" placeholder="••••••••" type="password" required autocomplete="current-password"/>
<button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-secondary transition-colors" onclick="const p=document.getElementById('password_input');p.type=p.type==='password'?'text':'password';">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>

<?php if ($error): ?>
<div class="rounded-lg bg-error-container p-3 text-sm font-semibold text-error text-center">
  <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
</div>
<?php endif; ?>

<button class="w-full bg-secondary text-white font-headline font-bold py-4 rounded-lg shadow-lg shadow-secondary/10 hover:bg-secondary/90 active:scale-[0.98] transition-all tracking-wide mt-2" type="submit">
    Secure Log In
</button>
</form>

<footer class="mt-20 pt-10 border-t border-surface-container flex flex-wrap justify-between gap-4">
<span class="text-[10px] uppercase tracking-widest font-label text-outline/50">© <?php echo date('Y') ?> Architectural Ledger</span>
</footer>
</div>
</section>
</main>
</body>
</html>

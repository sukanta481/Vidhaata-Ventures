<?php
/**
 * Shared admin layout shell.
 */

if (!function_exists('e')) {
  function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}

function admin_head(string $title = ''): void
{
  $site = defined('SITE_NAME') ? SITE_NAME : 'Vidhaata Ventures';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo e($title ? $title . ' | ' . $site : $site . ' Admin'); ?></title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          "colors": {
            "on-tertiary-fixed": "#002204",
            "surface": "#effcfd",
            "primary-fixed-dim": "#ffb59a",
            "on-tertiary-fixed-variant": "#005312",
            "on-tertiary": "#ffffff",
            "surface-container-high": "#deebeb",
            "surface-tint": "#a83900",
            "secondary": "#455f88",
            "on-primary-container": "#ff753d",
            "surface-container-low": "#eaf6f7",
            "secondary-container": "#b6d0ff",
            "on-surface": "#121d1e",
            "on-primary-fixed-variant": "#802a00",
            "surface-container": "#e4f0f1",
            "primary-fixed": "#ffdbcf",
            "tertiary-fixed": "#a3f69c",
            "on-tertiary-container": "#61b15f",
            "inverse-surface": "#273233",
            "on-secondary-container": "#3f5882",
            "background": "#effcfd",
            "surface-container-highest": "#d8e5e6",
            "on-secondary-fixed-variant": "#2d476f",
            "on-error": "#ffffff",
            "outline-variant": "#c4c6cf",
            "surface-container-lowest": "#ffffff",
            "surface-dim": "#d0dcdd",
            "tertiary-container": "#00400c",
            "inverse-primary": "#ffb59a",
            "outline": "#74777f",
            "error-container": "#ffdad6",
            "on-primary": "#ffffff",
            "on-secondary": "#ffffff",
            "on-error-container": "#93000a",
            "primary": "#401100",
            "secondary-fixed": "#d6e3ff",
            "primary-container": "#641e00",
            "surface-bright": "#effcfd",
            "on-secondary-fixed": "#001b3c",
            "on-background": "#121d1e",
            "tertiary": "#002805",
            "on-primary-fixed": "#380d00",
            "surface-variant": "#d8e5e6",
            "tertiary-fixed-dim": "#88d982",
            "secondary-fixed-dim": "#adc7f7",
            "error": "#ba1a1a",
            "on-surface-variant": "#43474e",
            "inverse-on-surface": "#e7f3f4"
          },
          "borderRadius": {
            "DEFAULT": "0.125rem",
            "lg": "0.25rem",
            "xl": "0.5rem",
            "full": "0.75rem"
          },
          "fontFamily": {
            "headline": ["Manrope"],
            "body": ["Inter"],
            "label": ["Inter"],
            "manrope": ["Manrope"]
          }
        },
      },
    };
  </script>

  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #d8e5e6; border-radius: 10px; }
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<?php
}

function admin_body_open(): void
{
?>
<body class="bg-surface font-body text-on-surface min-h-screen">
  
  <!-- TopNavBar -->
  <?php require __DIR__ . '/topbar.php'; ?>

  <div class="flex pt-[72px] min-h-screen">
    <!-- SideNavBar -->
    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 lg:ml-64 p-8 max-w-[1400px] mx-auto w-full">
<?php
}

function admin_footer(): void
{
?>
    </main>
  </div>
  
  <!-- Mobile Bottom NavBar (Visible on small screens) -->
  <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-surface-container-high px-6 py-3 flex justify-between items-center z-50 shadow-2xl">
    <a href="index.php" class="flex flex-col items-center gap-1 <?php echo strpos($_SERVER['SCRIPT_NAME'], 'index.php') !== false ? 'text-secondary' : 'text-outline'; ?>">
      <span class="material-symbols-outlined text-2xl" data-icon="dashboard">dashboard</span>
      <span class="text-[10px] font-bold">Home</span>
    </a>
    <a href="listings.php" class="flex flex-col items-center gap-1 <?php echo strpos($_SERVER['SCRIPT_NAME'], 'listings.php') !== false ? 'text-secondary' : 'text-outline'; ?>">
      <span class="material-symbols-outlined text-2xl" data-icon="real_estate_agent">real_estate_agent</span>
      <span class="text-[10px] font-bold">Props</span>
    </a>
    <a href="leads.php" class="flex flex-col items-center gap-1 <?php echo strpos($_SERVER['SCRIPT_NAME'], 'leads.php') !== false ? 'text-secondary' : 'text-outline'; ?>">
      <span class="material-symbols-outlined text-2xl" data-icon="group">group</span>
      <span class="text-[10px] font-bold">Leads</span>
    </a>
    <a href="account.php" class="flex flex-col items-center gap-1 <?php echo strpos($_SERVER['SCRIPT_NAME'], 'account.php') !== false ? 'text-secondary' : 'text-outline'; ?>">
      <span class="material-symbols-outlined text-2xl" data-icon="settings" <?php if(strpos($_SERVER['SCRIPT_NAME'], 'account.php') !== false) echo "style=\"font-variation-settings: 'FILL' 1;\""; ?>>settings</span>
      <span class="text-[10px] font-bold">Settings</span>
    </a>
  </nav>

  <!-- Optional Toast Container -->
  <div id="toast-container" class="fixed top-[80px] right-6 z-50 flex flex-col gap-2"></div>
</body>
</html>
<?php
}

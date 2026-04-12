<?php
require_once __DIR__ . '/../admin/includes/auth-check.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/admin-layout.php';

$active_page = 'account';
$page_title = 'Account Settings';
admin_head($page_title);
?>
<style>
.active-tab-indicator { position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background-color: #455f88; }
</style>
<?php admin_body_open(); ?>

<!-- Main Content Area -->
<div class="flex-1 p-8 overflow-x-hidden">

  <!-- Header Section -->
  <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
      <nav class="flex items-center gap-2 text-xs font-bold text-outline uppercase tracking-widest mb-4">
        <a class="hover:text-secondary" href="index.php">Admin</a>
        <span class="material-symbols-outlined text-[10px]">chevron_right</span>
        <span class="text-secondary">Settings</span>
      </nav>
      <h2 class="text-4xl font-headline font-extrabold text-on-surface tracking-tight mb-2">Account Settings</h2>
      <p class="text-secondary font-body max-w-2xl">Architectural Ledger Configuration</p>
    </div>
    <div class="flex gap-3">
      <button class="px-6 py-2.5 bg-surface-container-lowest text-secondary font-bold rounded-lg border border-surface-container-high hover:bg-surface-container-low transition-all text-sm">
        Export Config
      </button>
    </div>
  </div>

  <!-- Settings Shell -->
  <div class="bg-surface-container-low rounded-xl p-1 overflow-hidden shadow-sm border border-surface-container-high">
    <!-- Navigation Tabs -->
    <div class="bg-surface-container-lowest rounded-t-xl flex items-center px-6 border-b border-surface-container-high overflow-x-auto custom-scrollbar">
      <button class="relative px-6 py-5 text-sm font-semibold text-secondary whitespace-nowrap">
        Profile Settings
        <div class="active-tab-indicator"></div>
      </button>
      <button class="px-6 py-5 text-sm font-medium text-outline whitespace-nowrap hover:text-secondary transition-colors">Security</button>
    </div>

    <!-- Settings Content Stage -->
    <div class="bg-surface-container-lowest p-8 md:p-12 space-y-16">
      
      <!-- Section 1: Profile Settings -->
      <section class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="md:col-span-1">
          <h3 class="text-xl font-headline font-bold text-on-surface mb-2">Profile Identity</h3>
          <p class="text-sm text-outline leading-relaxed">Manage your public broker profile and credentials.</p>
        </div>
        <div class="md:col-span-2 space-y-8">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-xs font-bold text-secondary uppercase tracking-wider">Username</label>
              <input class="w-full bg-surface-container-low border-none focus:ring-2 focus:ring-secondary/20 rounded-lg py-3 px-4 text-on-surface font-medium transition-all" type="text" value="admin" readonly disabled/>
            </div>
          </div>
        </div>
      </section>

      <!-- Section 2: Security -->
      <section class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="md:col-span-1">
          <h3 class="text-xl font-headline font-bold text-on-surface mb-2">Security & Access</h3>
          <p class="text-sm text-outline leading-relaxed">Protect your high-value client data and deal confidentialities.</p>
        </div>
        <div class="md:col-span-2 space-y-8">
          <div class="space-y-4">
            <div class="flex items-center justify-between p-5 bg-surface-container-low rounded-xl">
              <div class="flex gap-4 items-center">
                <span class="material-symbols-outlined text-secondary text-3xl">lock_reset</span>
                <div>
                  <p class="font-bold text-on-surface font-headline">Change Password</p>
                  <p class="text-xs text-outline">Update your secure login credentials.</p>
                </div>
              </div>
              <button onclick="alert('Config is read-only right now.');" class="px-4 py-2 border-2 border-secondary text-secondary rounded-lg text-xs font-bold hover:bg-secondary hover:text-white transition-all">UPDATE</button>
            </div>
          </div>
        </div>
      </section>

    </div>
    
  </div>

</div>

<?php admin_footer(); ?>

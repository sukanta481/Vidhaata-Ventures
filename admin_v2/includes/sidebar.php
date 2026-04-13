<?php
/**
 * Shared admin sidebar component.
 *
 * Expects $active_page variable to be set before inclusion.
 */
global $active_page;
if (!isset($active_page)) {
  $active_page = 'dashboard';
}

function nav_classes($page, $active_page) {
    if ($page === $active_page) {
        return "flex items-center gap-3 text-[#455f88] font-bold border-l-4 border-[#455f88] pl-4 py-3 bg-white/60 transition-all duration-200";
    }
    return "flex items-center gap-3 text-slate-500 pl-5 py-3 hover:bg-white/40 transition-all duration-200";
}
?>
<aside id="admin-sidebar" class="w-64 fixed left-0 top-[72px] bottom-0 bg-[#eaf6f7] border-r border-surface-container-high lg:flex flex-col py-6 overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40">
  <div class="px-6 mb-8">
    <div class="mb-6">
      <p class="font-manrope font-black text-[#121d1e] text-sm">Admin Portal</p>
      <p class="text-xs text-slate-500 font-manrope mt-1">Operations Dashboard</p>
    </div>
    <button onclick="location.href='add-listing.php'" class="w-full py-3 px-4 bg-gradient-to-br from-primary-container to-on-primary-container text-white rounded-md font-semibold text-sm shadow-sm active:scale-95 duration-150 ease-in-out flex items-center justify-center gap-2">
      <span class="material-symbols-outlined text-lg" data-icon="add">add</span>
      New Listing
    </button>
  </div>
  
  <nav class="flex-1 font-manrope text-sm tracking-wide">
    <a class="<?php echo nav_classes('dashboard', $active_page); ?>" href="index.php">
      <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
      <span>Dashboard</span>
    </a>
    <a class="<?php echo nav_classes('listings', $active_page); ?>" href="listings.php">
      <span class="material-symbols-outlined" data-icon="inventory">inventory</span>
      <span>Inventory</span>
    </a>
    <a class="<?php echo nav_classes('leads', $active_page); ?>" href="leads.php">
      <span class="material-symbols-outlined" data-icon="group">group</span>
      <span>Leads</span>
    </a>
    
    <div class="h-[1px] bg-surface-container-high mx-5 my-2"></div>
    
    <a class="<?php echo nav_classes('account', $active_page); ?>" href="account.php">
      <span class="material-symbols-outlined" data-icon="settings" <?php if($active_page === 'account') echo "style=\"font-variation-settings: 'FILL' 1;\""; ?>>settings</span>
      <span>Settings</span>
    </a>
  </nav>

  <div class="px-6 py-4 border-t border-surface-container-high space-y-1">
    <a href="#" class="flex items-center gap-3 text-slate-500 pl-2 py-2 hover:text-[#455f88] transition-colors">
      <span class="material-symbols-outlined text-lg" data-icon="contact_support">contact_support</span>
      <span class="text-xs font-semibold">Support</span>
    </a>
    <a href="logout.php" class="flex items-center gap-3 text-slate-500 pl-2 py-2 hover:text-error transition-colors">
      <span class="material-symbols-outlined text-lg" data-icon="logout">logout</span>
      <span class="text-xs font-semibold">Log Out</span>
    </a>
  </div>
</aside>

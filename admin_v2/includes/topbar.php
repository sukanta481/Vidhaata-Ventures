<?php
/**
 * Shared admin top bar component.
 *
 * Usage: include before or after sidebar in the layout.
 */
?>
<header class="fixed top-0 left-0 right-0 z-50 bg-[#effcfd]/80 backdrop-blur-md border-b border-surface-container-high h-[72px] flex items-center justify-between px-8">
  <div class="flex items-center gap-4 lg:gap-12">
    <div class="flex items-center gap-2">
      <!-- Mobile sidebar toggle (optional if you want to implement JS) -->
      <button onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')" class="lg:hidden p-2 text-secondary hover:bg-surface-container-high rounded-full transition-colors">
        <span class="material-symbols-outlined">menu</span>
      </button>
      <h1 class="text-xl font-bold tracking-tighter text-[#121d1e] font-manrope hidden sm:block">Architectural Ledger</h1>
    </div>
    
    <div class="hidden md:flex items-center gap-2 bg-surface-container rounded-full px-4 py-1.5 w-[300px] lg:w-[400px]">
      <span class="material-symbols-outlined text-outline text-xl" data-icon="search">search</span>
      <input class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-outline/60" placeholder="Search inventory, leads, or tasks..." type="text"/>
      <span class="text-[10px] font-bold text-outline border border-outline/30 px-1.5 py-0.5 rounded">⌘K</span>
    </div>
  </div>
  
  <div class="flex items-center gap-2 lg:gap-4">
    <button class="p-2 hover:bg-surface-container-high rounded-full transition-colors relative">
      <span class="material-symbols-outlined text-secondary" data-icon="notifications">notifications</span>
      <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full ring-2 ring-surface"></span>
    </button>
    <button class="p-2 hover:bg-surface-container-high rounded-full transition-colors" onclick="location.href='account.php'">
      <span class="material-symbols-outlined text-secondary" data-icon="settings">settings</span>
    </button>
    <div class="h-8 w-[1px] bg-surface-container-high mx-1 lg:mx-2"></div>
    <div class="flex items-center gap-3 pl-2 cursor-pointer group" onclick="location.href='account.php'">
      <div class="text-right hidden sm:block">
        <p class="text-xs font-bold text-on-surface leading-none mb-0.5">Admin User</p>
        <p class="text-[10px] text-outline leading-none">Senior Broker</p>
      </div>
      <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-surface-container-high group-hover:border-secondary transition-colors flex items-center justify-center bg-primary-container text-white font-bold text-lg">
        A
      </div>
    </div>
  </div>
</header>

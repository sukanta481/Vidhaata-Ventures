<?php
require_once __DIR__ . '/../admin/includes/auth-check.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/admin-layout.php';

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

// ─── Fetch leads ──────────────────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT id, name, phone, email, message, source_page, status, followup_at, created_at FROM leads ORDER BY followup_at DESC, created_at DESC LIMIT 200");
$stmt->execute();
$all_leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pipeline_statuses = ['new', 'contacted', 'qualified', 'site_visit', 'negotiation'];
$leads_by_status   = array_fill_keys($pipeline_statuses, []);
foreach ($all_leads as $lead) {
    $s = $lead['status'];
    if (isset($leads_by_status[$s])) {
        $leads_by_status[$s][] = $lead;
    } else {
        $leads_by_status['new'][] = $lead;
    }
}

// ─── Metrics ──────────────────────────────────────────────────────────────────
$m = $pdo->query("SELECT
    SUM(status='new') as new_count,
    SUM(status NOT IN ('new','closed')) as active_count,
    SUM(DATE(created_at)=CURDATE() AND status!='new') as followups
    FROM leads")->fetch(PDO::FETCH_ASSOC);
$new_count    = (int)($m['new_count']    ?? 0);
$active_count = (int)($m['active_count'] ?? 0);
$today_fup    = (int)($m['followups']    ?? 0);

$active_page = 'leads';
$page_title  = 'Sales Pipeline';
admin_head($page_title);
?>

<style>
/* ── Utilities ── */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(196,198,207,.1); border-radius:10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(69,95,136,.2); border-radius:10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(69,95,136,.4); }

/* ── Kanban edge fade ── */
.edge-fade-right { position: relative; }
.edge-fade-right::after {
  content:''; position:absolute; top:0; right:0; bottom:6px; width:80px;
  background: linear-gradient(to right, transparent, #effcfd);
  pointer-events:none; z-index:20;
}

/* ── Slide-out panel ── */
#leadPanel {
  position: fixed;
  top: 72px;
  right: -460px;
  width: 430px;
  height: calc(100vh - 72px);
  z-index: 45;
  transition: right .35s cubic-bezier(.4,0,.2,1);
  overflow-y: auto;
  border-left: 1px solid rgba(196,198,207,.2);
  background: rgba(255,255,255,.97);
  backdrop-filter: blur(24px);
}
#leadPanel.open { right: 0; }

/* ── Backdrop ── */
#panelBackdrop {
  display:none; position:fixed; inset:0; z-index:44;
  background: rgba(18,29,30,.15); backdrop-filter: blur(2px);
}
#panelBackdrop.show { display:block; }

/* ── Status change modal ── */
#statusModal {
  display:none; position:fixed; inset:0; z-index:55;
  background: rgba(18,29,30,.3); backdrop-filter: blur(4px);
  align-items: center; justify-content: center;
}
#statusModal.show { display:flex; }

/* ── Status buttons in panel ── */
.stage-btn {
  font-size:11px; font-weight:700; padding:6px 12px;
  border-radius:8px; border:1px solid rgba(196,198,207,.3);
  transition: all .18s; cursor:pointer; background:transparent;
}
.stage-btn:hover { background:#455f88; color:#fff; border-color:#455f88; }
.stage-btn.active { background:#455f88; color:#fff; border-color:#455f88; }

/* ── Activity timeline ── */
.activity-track {
  position:relative; padding-left:24px;
}
.activity-track::before {
  content:''; position:absolute; left:6px; top:6px; bottom:0; width:1px;
  background:rgba(196,198,207,.4);
}
.activity-dot {
  position:absolute; left:-18px; top:5px;
  width:9px; height:9px; border-radius:50%;
}

/* ── Filter dropdown ── */
#filterDropdown {
  display:none; position:absolute; top:calc(100% + 8px); right:0;
  width:300px; z-index:100;
  background:#fff; border-radius:12px;
  box-shadow:0 8px 32px rgba(18,29,30,.14);
  border:1px solid rgba(196,198,207,.25);
  animation: fadeSlideDown .18s ease;
}
#filterDropdown.open { display:block; }
@keyframes fadeSlideDown {
  from { opacity:0; transform:translateY(-6px); }
  to   { opacity:1; transform:translateY(0); }
}
.filter-chip {
  font-size:11px; font-weight:700; padding:5px 12px;
  border-radius:8px; border:1px solid rgba(196,198,207,.35);
  cursor:pointer; transition:all .15s; background:transparent;
  white-space:nowrap;
}
.filter-chip:hover, .filter-chip.active {
  background:#455f88; color:#fff; border-color:#455f88;
}
.filter-chip.active-source {
  background:#ff753d; color:#fff; border-color:#ff753d;
}
/* hidden card by filter */
.lead-card.filtered-out { display:none; }

/* ── Task checkbox accent ── */
input[type=checkbox] { accent-color: #ff753d; }

/* ── Lead card ── */
.lead-card { transition: box-shadow .2s, transform .15s; }
.lead-card:hover { box-shadow: 0 4px 16px rgba(69,95,136,.12); }
.lead-card:active { transform: scale(.98); }
.lead-card.selected { box-shadow: 0 0 0 2px #ff753d; }
</style>

<?php admin_body_open(); ?>

<!-- ── Backdrop ── -->
<div id="panelBackdrop" onclick="closePanel()"></div>

<!-- ════════════════════════════════════════════════
     LEAD DETAIL SLIDE-OUT PANEL
════════════════════════════════════════════════ -->
<aside id="leadPanel" class="flex flex-col">
  <div class="p-6 flex-1 overflow-y-auto custom-scrollbar">

    <!-- Header row -->
    <div class="flex items-center justify-between mb-6">
      <button onclick="closePanel()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
        <span class="material-symbols-outlined text-secondary text-xl">close</span>
      </button>
      <div class="flex gap-1">
        <button id="panelNoteBtn" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors" title="Add note">
          <span class="material-symbols-outlined text-secondary text-xl">note_add</span>
        </button>
        <button id="panelDeleteBtn" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-error/10 transition-colors" title="Delete lead">
          <span class="material-symbols-outlined text-error text-xl">delete</span>
        </button>
      </div>
    </div>

    <!-- Avatar & name -->
    <div class="flex flex-col items-center text-center mb-6">
      <div id="panelAvatar" class="w-20 h-20 rounded-full border-4 border-surface-container-low mb-3 flex items-center justify-center bg-secondary text-white text-3xl font-headline font-extrabold">?</div>
      <h3 id="panelName" class="text-2xl font-headline font-extrabold text-on-surface">—</h3>
      <p class="text-secondary font-medium text-sm flex items-center gap-1 justify-center mt-1">
        <span class="material-symbols-outlined text-sm">social_leaderboard</span>
        <span id="panelSource">—</span>
      </p>
      <span id="panelStatusBadge" class="mt-2 text-[10px] uppercase font-bold px-3 py-1 rounded-full bg-secondary-container/30 text-on-secondary-container">New Lead</span>
    </div>

    <!-- Quick actions -->
    <div class="grid grid-cols-2 gap-3 mb-8">
      <a id="panelCallBtn" href="#" class="flex items-center justify-center gap-2 py-3 bg-secondary text-white rounded-xl font-headline font-bold text-sm shadow-sm hover:-translate-y-0.5 transition-all">
        <span class="material-symbols-outlined text-lg">call</span> Call Now
      </a>
      <a id="panelWaBtn" href="#" target="_blank" class="flex items-center justify-center gap-2 py-3 bg-on-tertiary-container text-white rounded-xl font-headline font-bold text-sm shadow-sm hover:-translate-y-0.5 transition-all">
        <span class="material-symbols-outlined text-lg">chat</span> WhatsApp
      </a>
    </div>

    <!-- Lead details -->
    <div class="space-y-3 mb-8">
      <div class="flex justify-between items-center pb-3 border-b border-outline-variant/10">
        <span class="text-xs font-bold text-secondary uppercase tracking-widest">Phone</span>
        <span id="panelPhone" class="text-sm font-headline font-bold">—</span>
      </div>
      <div class="flex justify-between items-center pb-3 border-b border-outline-variant/10">
        <span class="text-xs font-bold text-secondary uppercase tracking-widest">Email</span>
        <span id="panelEmail" class="text-sm font-headline font-bold truncate max-w-[190px]">—</span>
      </div>
      <div class="flex justify-between items-center pb-3 border-b border-outline-variant/10">
        <span class="text-xs font-bold text-secondary uppercase tracking-widest">Source</span>
        <span id="panelSourceDetail" class="text-sm font-headline font-bold">—</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-xs font-bold text-secondary uppercase tracking-widest">Received</span>
        <span id="panelDate" class="text-sm font-headline font-bold">—</span>
      </div>
      <div class="flex justify-between items-center pb-3 border-b border-outline-variant/10">
        <span class="text-xs font-bold text-secondary uppercase tracking-widest">Follow-up</span>
        <span id="panelFollowup" class="text-sm font-headline font-bold text-on-primary-container">—</span>
      </div>
    </div>

    <!-- Pipeline stage buttons -->
    <div class="mb-6">
      <h4 class="text-xs font-bold text-secondary uppercase tracking-widest mb-3">Move Pipeline Stage</h4>
      <div class="flex flex-wrap gap-2">
        <?php
        $stage_labels = ['new'=>'New Lead','contacted'=>'Contacted','qualified'=>'Qualified','site_visit'=>'Site Visit','negotiation'=>'Negotiation','closed'=>'Closed'];
        foreach ($stage_labels as $sv => $sl): ?>
        <button class="stage-btn" data-status="<?= $sv ?>" onclick="promptStatusChange('<?= $sv ?>')">
          <?= $sl ?>
        </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Proposed Properties -->
    <div class="mb-6 border border-outline-variant/15 rounded-xl overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low">
        <h4 class="text-xs font-bold text-secondary uppercase tracking-widest flex items-center gap-1.5">
          <span class="material-symbols-outlined text-sm">home_work</span> Proposed Properties
        </h4>
        <button id="proposePropertyBtn"
          class="text-[11px] font-bold text-on-primary-container bg-on-primary-container/10 hover:bg-on-primary-container/20 px-3 py-1 rounded-full transition-colors flex items-center gap-1">
          <span class="material-symbols-outlined text-sm">add</span> Propose
        </button>
      </div>
      <div id="proposedPropertiesList" class="p-3 space-y-2 min-h-[48px]">
        <p class="text-xs text-secondary text-center py-2 italic">Loading…</p>
      </div>
    </div>

    <!-- Activity timeline -->
    <div>
      <h4 class="text-xs font-bold text-secondary uppercase tracking-widest mb-4">Activity Timeline</h4>
      <div id="panelTimeline" class="activity-track space-y-6">
        <p class="text-xs text-secondary">Loading…</p>
      </div>
    </div>

  </div>
</aside>

<!-- ════════════════════════════════════════════════
     STATUS CHANGE MODAL (with notes)
════════════════════════════════════════════════ -->
<div id="statusModal" role="dialog" aria-modal="true">
  <div class="bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-2xl ring-1 ring-outline-variant/15 overflow-hidden">

    <!-- Header -->
    <div class="px-6 py-5 border-b border-outline-variant/10 flex justify-between items-start">
      <div>
        <h3 class="text-lg font-headline font-extrabold text-on-surface" id="statusModalTitle">Update Status</h3>
        <p class="text-xs text-secondary mt-0.5" id="statusModalSubtitle">Moving lead to new stage</p>
      </div>
      <button onclick="closeStatusModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors mt-0.5">
        <span class="material-symbols-outlined text-secondary text-lg">close</span>
      </button>
    </div>

    <!-- Body -->
    <div class="px-6 py-5 space-y-4">
      <!-- Stage indicator -->
      <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
        <div class="flex-1">
          <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-0.5">From</p>
          <p class="text-sm font-headline font-bold text-on-surface" id="smFromStatus">—</p>
        </div>
        <span class="material-symbols-outlined text-secondary">arrow_forward</span>
        <div class="flex-1 text-right">
          <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-0.5">To</p>
          <p class="text-sm font-headline font-bold text-on-primary-container" id="smToStatus">—</p>
        </div>
      </div>

      <!-- Site Visit: which property was visited? -->
      <div id="siteVisitPropertyRow" class="hidden">
        <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">
          <span class="material-symbols-outlined text-sm align-middle">home_work</span>
          Property Visited <span class="text-outline normal-case font-normal">(optional)</span>
        </label>
        <select id="siteVisitListingId"
          class="w-full bg-surface-container border-none focus:ring-2 focus:ring-on-primary-container/40 rounded-xl p-3 text-sm text-on-surface">
          <option value="">— Select property visited —</option>
        </select>
      </div>

      <!-- Follow-up date & time -->
      <div>
        <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">
          <span class="material-symbols-outlined text-sm align-middle">calendar_today</span>
          Follow-up Date & Time <span class="text-outline normal-case font-normal">(optional)</span>
        </label>
        <input id="followupDatetime" type="datetime-local"
          class="w-full bg-surface-container border-none focus:ring-2 focus:ring-on-primary-container/40 rounded-xl p-3 text-sm text-on-surface"/>
      </div>

      <!-- Notes textarea -->
      <div>
        <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">
          Notes <span class="text-outline normal-case font-normal">(optional)</span>
        </label>
        <textarea id="statusNotes" rows="3"
          class="w-full bg-surface-container border-none focus:ring-2 focus:ring-on-primary-container/40 rounded-xl p-3 text-sm text-on-surface placeholder-on-surface-variant/40 resize-none"
          placeholder="e.g. Called client. Interested in 3BHK in South Mumbai. Will visit next Saturday."></textarea>
      </div>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/10 flex items-center justify-between">
      <button onclick="closeStatusModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Cancel</button>
      <button id="confirmStatusBtn" class="px-7 py-2.5 bg-gradient-to-tr from-primary-container to-on-primary-container text-white text-sm font-extrabold rounded-lg shadow hover:scale-[1.02] active:scale-95 transition-all">
        Confirm &amp; Save
      </button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════
     ADD NOTE MODAL
════════════════════════════════════════════════ -->
<!-- Add Note Modal -->
<div id="noteModal" role="dialog" aria-modal="true"
  style="display:none;position:fixed;inset:0;z-index:55;background:rgba(18,29,30,.3);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div class="bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-2xl ring-1 ring-outline-variant/15 overflow-hidden">
    <div class="px-6 py-5 border-b border-outline-variant/10 flex justify-between items-center">
      <h3 class="text-lg font-headline font-extrabold text-on-surface">Add Note</h3>
      <button onclick="closeNoteModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
        <span class="material-symbols-outlined text-secondary text-lg">close</span>
      </button>
    </div>
    <div class="px-6 py-5">
      <textarea id="noteText" rows="5"
        class="w-full bg-surface-container border-none focus:ring-2 focus:ring-on-primary-container/40 rounded-xl p-3 text-sm text-on-surface placeholder-on-surface-variant/40 resize-none"
        placeholder="Type your note here… e.g. client asked for brochure, follow up Thursday."></textarea>
    </div>
    <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/10 flex items-center justify-between">
      <button onclick="closeNoteModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Cancel</button>
      <button id="saveNoteBtn" class="px-7 py-2.5 bg-gradient-to-tr from-secondary to-secondary/80 text-white text-sm font-extrabold rounded-lg shadow hover:scale-[1.02] active:scale-95 transition-all">
        Save Note
      </button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════
     PROPOSE PROPERTY MODAL
════════════════════════════════════════════════ -->
<div id="proposeModal" role="dialog" aria-modal="true"
  style="display:none;position:fixed;inset:0;z-index:60;background:rgba(18,29,30,.35);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div class="bg-surface-container-lowest w-full max-w-lg rounded-2xl shadow-2xl ring-1 ring-outline-variant/15 overflow-hidden flex flex-col max-h-[90vh]">

    <div class="px-6 py-5 border-b border-outline-variant/10 flex justify-between items-center">
      <div>
        <h3 class="text-lg font-headline font-extrabold text-on-surface">Propose a Property</h3>
        <p class="text-xs text-secondary mt-0.5" id="proposeModalSubtitle">Select from your active inventory</p>
      </div>
      <button onclick="closeProposeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
        <span class="material-symbols-outlined text-secondary text-lg">close</span>
      </button>
    </div>

    <div class="p-5 flex-1 overflow-y-auto custom-scrollbar space-y-4">
      <!-- Search -->
      <div class="relative">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-base pointer-events-none">search</span>
        <input id="proposeSearchInput" type="text" placeholder="Search properties…"
          class="w-full pl-9 pr-3 py-2.5 bg-surface-container border-none rounded-xl text-sm focus:ring-2 focus:ring-secondary/20 transition-all"
          oninput="filterProposeList()">
      </div>

      <!-- Property list -->
      <div id="proposeListings" class="space-y-2 max-h-64 overflow-y-auto custom-scrollbar">
        <p class="text-xs text-secondary text-center py-4">Loading inventory…</p>
      </div>

      <!-- Notes -->
      <div>
        <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Notes <span class="text-outline normal-case font-normal">(optional)</span></label>
        <textarea id="proposeNotes" rows="3"
          class="w-full bg-surface-container border-none focus:ring-0 rounded-xl p-3 text-sm resize-none placeholder-on-surface-variant/40"
          placeholder="Any specific note about this proposal…"></textarea>
      </div>
    </div>

    <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/10 flex items-center justify-between">
      <button onclick="closeProposeModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Cancel</button>
      <button id="confirmProposeBtn" class="px-7 py-2.5 bg-gradient-to-tr from-on-primary-container to-primary-container text-white text-sm font-extrabold rounded-lg shadow hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50" disabled>
        Propose Property
      </button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════ -->
<div class="flex-1 overflow-x-hidden">

  <!-- ── Stats Cards ── -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
      <div class="relative z-10">
        <p class="text-secondary text-sm font-headline font-semibold mb-2">New Leads</p>
        <h3 class="text-4xl font-headline font-extrabold text-on-surface"><?= e($new_count) ?></h3>
        <p class="text-xs text-on-tertiary-container mt-2 flex items-center gap-1">
          <span class="material-symbols-outlined text-xs">trending_up</span> Awaiting first contact
        </p>
      </div>
      <span class="material-symbols-outlined absolute -bottom-4 -right-4 text-secondary/10 text-8xl group-hover:scale-110 transition-transform">group_add</span>
    </div>

    <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
      <div class="relative z-10">
        <p class="text-secondary text-sm font-headline font-semibold mb-2">Today's Follow-ups</p>
        <h3 class="text-4xl font-headline font-extrabold text-on-surface"><?= e($today_fup) ?></h3>
        <p class="text-xs text-[#9A3412] font-bold mt-2 flex items-center gap-1">
          <span class="material-symbols-outlined text-xs">schedule</span> Check overdue contacts
        </p>
      </div>
      <span class="material-symbols-outlined absolute -bottom-4 -right-4 text-secondary/10 text-8xl group-hover:scale-110 transition-transform">history</span>
    </div>

    <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
      <div class="relative z-10">
        <p class="text-secondary text-sm font-headline font-semibold mb-2">Active Pipeline</p>
        <h3 class="text-4xl font-headline font-extrabold text-on-surface"><?= e($active_count) ?></h3>
        <p class="text-xs text-secondary mt-2 flex items-center gap-1">
          <span class="material-symbols-outlined text-xs">event</span> In progress
        </p>
      </div>
      <span class="material-symbols-outlined absolute -bottom-4 -right-4 text-secondary/10 text-8xl group-hover:scale-110 transition-transform">location_on</span>
    </div>

  </div>

  <!-- ── Kanban + Task Sidebar ── -->
  <div class="flex flex-col xl:flex-row gap-8">

    <!-- Kanban Board -->
    <div class="flex-1 min-w-0">
      <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
        <h2 class="text-xl font-headline font-extrabold tracking-tight">Sales Pipeline</h2>

        <!-- Search bar -->
        <div class="flex-1 min-w-0 max-w-xs relative">
          <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-secondary text-base pointer-events-none">search</span>
          <input id="leadSearchInput" type="text" placeholder="Search leads…"
            class="w-full pl-8 pr-3 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-secondary/20 focus:bg-surface-container-lowest transition-all"
            oninput="applyFilters()">
        </div>

        <div class="flex gap-2 items-center shrink-0">
          <button type="button" id="addLeadBtn" class="p-2 px-4 flex items-center gap-2 bg-primary text-on-primary rounded-md hover:opacity-90 transition-opacity font-bold text-sm">
            <span class="material-symbols-outlined text-sm">add</span> Add Lead
          </button>

          <!-- Filter button + dropdown wrapper -->
          <div class="relative" id="filterWrapper">
            <button type="button" id="filterBtn"
              class="p-2 px-3 flex items-center gap-1.5 bg-surface-container-low rounded-md hover:bg-surface-container transition-colors font-bold text-sm"
              onclick="toggleFilter()">
              <span class="material-symbols-outlined text-sm">filter_list</span>
              <span id="filterBtnLabel">Filter</span>
              <span id="filterCount" class="hidden text-[10px] bg-on-primary-container text-white font-bold px-1.5 py-0.5 rounded-full">0</span>
            </button>

            <!-- Filter Dropdown -->
            <div id="filterDropdown">
              <div class="p-4 border-b border-outline-variant/10">
                <div class="flex items-center justify-between mb-1">
                  <h4 class="text-xs font-bold text-on-surface uppercase tracking-widest">Filters</h4>
                  <button onclick="clearFilters()" class="text-[11px] font-bold text-secondary hover:text-on-primary-container transition-colors">Clear all</button>
                </div>
              </div>

              <!-- Source filter -->
              <div class="p-4 border-b border-outline-variant/10">
                <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-3">Source</p>
                <div class="flex flex-wrap gap-2" id="sourceChips">
                  <?php
                  $sources = array_unique(array_column($all_leads, 'source_page'));
                  $sources = array_values(array_filter($sources));
                  sort($sources);
                  foreach ($sources as $src): ?>
                  <button type="button" class="filter-chip source-chip" data-source="<?= e($src) ?>" onclick="toggleSourceFilter(this)">
                    <?= e($src) ?>
                  </button>
                  <?php endforeach; ?>
                  <?php if (empty($sources)): ?>
                  <span class="text-xs text-secondary italic">No sources yet</span>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Date range filter -->
              <div class="p-4 border-b border-outline-variant/10">
                <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-3">Date Added</p>
                <div class="flex flex-wrap gap-2" id="dateChips">
                  <button type="button" class="filter-chip date-chip active" data-days="0" onclick="toggleDateFilter(this)">All Time</button>
                  <button type="button" class="filter-chip date-chip" data-days="1"  onclick="toggleDateFilter(this)">Today</button>
                  <button type="button" class="filter-chip date-chip" data-days="7"  onclick="toggleDateFilter(this)">Last 7 days</button>
                  <button type="button" class="filter-chip date-chip" data-days="30" onclick="toggleDateFilter(this)">Last 30 days</button>
                </div>
              </div>

              <!-- Apply button -->
              <div class="p-4">
                <button onclick="toggleFilter()" class="w-full py-2 bg-secondary text-white text-xs font-extrabold rounded-lg hover:bg-secondary/90 transition-colors">Done</button>
              </div>
            </div>
          </div><!-- /filterWrapper -->

          <a href="?export=1" class="p-2 px-4 flex items-center gap-2 bg-surface-container-low rounded-md hover:bg-surface-container transition-colors font-bold text-sm">
            <span class="material-symbols-outlined text-sm">file_download</span> Export
          </a>
        </div>
      </div>

      <div class="edge-fade-right">
        <div class="overflow-x-auto pb-6 custom-scrollbar">
          <div class="flex gap-6 min-w-max pr-20">

            <?php
            $columns = [
              ['id' => 'new',         'title' => 'New Lead',    'border' => 'border-on-primary-container'],
              ['id' => 'contacted',   'title' => 'Contacted',   'border' => 'border-secondary/50'],
              ['id' => 'qualified',   'title' => 'Qualified',   'border' => 'border-on-tertiary-container/70'],
              ['id' => 'site_visit',  'title' => 'Site Visit',  'border' => 'border-secondary-container'],
              ['id' => 'negotiation', 'title' => 'Negotiation', 'border' => 'border-primary-container/70'],
            ];
            foreach ($columns as $col):
              $col_leads = $leads_by_status[$col['id']] ?? [];
            ?>
            <div class="w-72 flex flex-col gap-4" data-col-id="<?= e($col['id']) ?>">
              <div class="flex items-center justify-between px-2">
                <h4 class="text-xs font-headline font-bold uppercase tracking-widest text-secondary/60">
                  <?= e($col['title']) ?> (<span class="col-count"><?= count($col_leads) ?></span>)
                </h4>
                <span class="material-symbols-outlined text-secondary/40 text-sm">more_horiz</span>
              </div>

              <?php foreach ($col_leads as $lead): ?>
              <?php
                $diff = time() - strtotime($lead['created_at']);
                if ($diff < 3600)      $ta = round($diff/60)    . 'm ago';
                elseif ($diff < 86400) $ta = round($diff/3600)  . 'h ago';
                else                   $ta = round($diff/86400) . 'd ago';
                $msg_short = $lead['message'] ? e(mb_substr($lead['message'], 0, 42)) . '…' : 'No message provided';
                $payload = json_encode([
                  'id'         => $lead['id'],
                  'name'       => $lead['name'],
                  'phone'      => $lead['phone'],
                  'email'      => $lead['email'],
                  'message'    => $lead['message'],
                  'source_page'=> $lead['source_page'],
                  'status'     => $lead['status'],
                  'followup_at'=> $lead['followup_at'],
                  'created_at' => date('M d, Y · g:i A', strtotime($lead['created_at'])),
                  'time_ago'   => $ta,
                ]);
              ?>
              <div class="lead-card bg-surface-container-lowest p-4 rounded-xl shadow-sm border-l-4 <?= $col['border'] ?> group cursor-pointer"
                   data-lead-id="<?= $lead['id'] ?>"
                   data-name="<?= e(strtolower($lead['name'])) ?>"
                   data-phone="<?= e($lead['phone']) ?>"
                   data-source="<?= e($lead['source_page'] ?: '') ?>"
                   data-ts="<?= strtotime($lead['created_at']) ?>"
                   onclick="openPanel(<?= htmlspecialchars($payload, ENT_QUOTES) ?>)">

                <div class="flex justify-between items-start mb-2">
                  <h5 class="font-headline font-bold text-sm pr-2 truncate"><?= e($lead['name']) ?></h5>
                  <span class="bg-secondary-container/30 text-on-secondary-container text-[10px] px-2 py-0.5 rounded-full uppercase font-bold shrink-0">
                    <?= e($lead['source_page'] ?: 'Direct') ?>
                  </span>
                </div>

                <p class="text-xs text-secondary/70 mb-3 truncate" title="<?= e($lead['message']) ?>"><?= $msg_short ?></p>

                <div class="flex items-center justify-between text-xs text-secondary font-medium">
                  <span><?= $ta ?></span>
                  <div class="flex gap-2">
                    <a href="tel:<?= e($lead['phone']) ?>" onclick="event.stopPropagation()"
                       class="w-6 h-6 flex items-center justify-center rounded-full bg-secondary/5 text-secondary hover:bg-secondary hover:text-white transition-all">
                      <span class="material-symbols-outlined text-sm">call</span>
                    </a>
                    <?php if ($lead['email']): ?>
                    <a href="mailto:<?= e($lead['email']) ?>" onclick="event.stopPropagation()"
                       class="w-6 h-6 flex items-center justify-center rounded-full bg-on-tertiary-container/5 text-on-tertiary-container hover:bg-on-tertiary-container hover:text-white transition-all">
                      <span class="material-symbols-outlined text-sm">mail</span>
                    </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>

              <div class="drop-placeholder border-2 border-dashed border-outline-variant/20 h-32 rounded-xl flex items-center justify-center <?= empty($col_leads) ? '' : 'hidden' ?>">
                <span class="text-[10px] font-bold text-outline-variant uppercase tracking-widest">No results</span>
              </div>

            </div>
            <?php endforeach; ?>

          </div>
        </div>
      </div>
    </div><!-- /kanban -->

    <!-- Today's Tasks Sidebar -->
    <div class="w-full xl:w-80 flex flex-col gap-6">
      <h2 class="text-xl font-headline font-extrabold tracking-tight">Today's Focus</h2>
      <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm">
        <div class="flex flex-col gap-4">

          <?php
          $focus_leads = array_slice($leads_by_status['new'], 0, 4);
          if (empty($focus_leads)): ?>
          <p class="text-sm text-secondary text-center py-3">No urgent tasks right now. Great job!</p>
          <?php else: foreach ($focus_leads as $idx => $fl): ?>
          <div class="flex items-start gap-3 <?= $idx > 0 ? 'border-t border-outline-variant/10 pt-3' : '' ?>">
            <input type="checkbox" class="mt-1 h-4 w-4 rounded shrink-0" id="task_<?= $fl['id'] ?>">
            <label for="task_<?= $fl['id'] ?>" class="cursor-pointer">
              <p class="text-sm font-semibold text-on-surface leading-snug" style="transition:.2s;">Call <?= e($fl['name']) ?></p>
              <p class="text-[10px] text-secondary uppercase font-bold mt-0.5"><?= e($fl['phone']) ?> · New Lead</p>
            </label>
          </div>
          <?php endforeach; endif; ?>

          <div class="border-t border-outline-variant/10 pt-3 flex items-start gap-3">
            <input type="checkbox" class="mt-1 h-4 w-4 rounded shrink-0" id="task_review">
            <label for="task_review" class="cursor-pointer">
              <p class="text-sm font-semibold text-on-surface">Weekly Pipeline Review</p>
              <p class="text-[10px] text-secondary uppercase font-bold mt-0.5">4:00 PM</p>
            </label>
          </div>

        </div>
        <button onclick="document.getElementById('addLeadBtn').click()"
          class="w-full mt-6 py-2 text-xs font-bold text-secondary border border-secondary/20 rounded-lg hover:bg-surface-container transition-colors">
          + Add New Lead
        </button>
      </div>
    </div>

  </div>
</div><!-- /main content -->

<!-- ════════════════════════════════════════════════
     ADD LEAD MODAL
════════════════════════════════════════════════ -->
<div id="addLeadModal" class="hidden fixed inset-0 z-50 bg-on-background/20 backdrop-blur-sm flex items-center justify-center p-4 lg:pl-64">
  <div class="bg-surface-container-lowest w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-xl shadow-2xl flex flex-col ring-1 ring-outline-variant/15">
    <form id="addLeadForm" class="flex flex-col h-full overflow-hidden">

      <div class="px-8 py-6 border-b border-outline-variant/10 flex justify-between items-center">
        <h2 class="text-2xl font-headline font-extrabold tracking-tight text-on-surface">Add New Lead</h2>
        <button type="button" class="closeLeadModal p-2 hover:bg-surface-container-high rounded-full transition-colors">
          <span class="material-symbols-outlined text-on-surface-variant">close</span>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">

        <section>
          <div class="flex items-center gap-2 mb-4">
            <span class="text-[10px] font-bold uppercase tracking-[.2em] text-secondary">01</span>
            <h3 class="text-sm font-headline font-bold uppercase tracking-wider">Basic Information</h3>
          </div>
          <div class="grid grid-cols-2 gap-6">
            <div class="col-span-2 sm:col-span-1">
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Full Name *</label>
              <input name="name" required placeholder="e.g. Rahul Sharma" type="text"
                class="w-full bg-surface-container border-none focus:ring-0 focus:bg-surface-container-lowest transition-all p-3 text-sm rounded-lg"/>
            </div>
            <div class="col-span-2 sm:col-span-1">
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Phone Number *</label>
              <div class="flex bg-surface-container rounded-lg overflow-hidden focus-within:bg-surface-container-lowest transition-all">
                <div class="px-3 flex items-center border-r border-outline-variant/20 bg-surface-container-high">
                  <span class="text-xs font-bold">+91</span>
                </div>
                <input name="phone" required placeholder="98765 43210" type="tel"
                  class="w-full bg-transparent border-none focus:ring-0 p-3 text-sm"/>
              </div>
            </div>
            <div class="col-span-2 sm:col-span-1">
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Email Address</label>
              <input name="email" placeholder="rahul.s@example.com" type="email"
                class="w-full bg-surface-container border-none focus:ring-0 focus:bg-surface-container-lowest transition-all p-3 text-sm rounded-lg"/>
            </div>
            <div class="col-span-2 sm:col-span-1 flex items-end">
              <div class="w-full flex items-center justify-between p-3 bg-surface-container-low rounded-lg border border-outline-variant/10">
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[#25D366] text-xl" style="font-variation-settings:'FILL' 1;">chat</span>
                  <span class="text-xs font-semibold">WhatsApp Available?</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input name="whatsapp_available" class="sr-only peer" type="checkbox" value="1"/>
                  <div class="w-9 h-5 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-on-tertiary-container"></div>
                </label>
              </div>
            </div>
          </div>
        </section>

        <section>
          <div class="flex items-center gap-2 mb-4">
            <span class="text-[10px] font-bold uppercase tracking-[.2em] text-secondary">02</span>
            <h3 class="text-sm font-headline font-bold uppercase tracking-wider">Lead Context</h3>
          </div>
          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Inquiry Source</label>
              <select name="source_page" class="w-full bg-surface-container border-none focus:ring-0 p-3 text-sm rounded-lg appearance-none">
                <option value="Housing">Housing</option>
                <option value="Website">Website</option>  
                <option value="99acres">99acres</option>
                <option value="MagicBricks">MagicBricks</option>
                <option value="Personal Referral">Personal Referral</option>
                <option value="Facebook">Facebook</option>
                <option value="Instagram">Instagram</option>
                <option value="Walk-in">Walk-in</option>
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Lead Type</label>
              <div class="flex gap-2">
                <?php foreach(['buyer'=>'Buyer','seller'=>'Seller','tenant'=>'Tenant'] as $v=>$l): ?>
                <label class="flex-1 cursor-pointer">
                  <input <?= $v==='buyer'?'checked':'' ?> class="hidden peer" name="lead_type" type="radio" value="<?= $v ?>"/>
                  <div class="text-center p-2 rounded-lg border border-outline-variant/20 text-xs font-bold peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary transition-all"><?= $l ?></div>
                </label>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </section>

        <section>
          <div class="flex items-center gap-2 mb-4">
            <span class="text-[10px] font-bold uppercase tracking-[.2em] text-secondary">03</span>
            <h3 class="text-sm font-headline font-bold uppercase tracking-wider">Requirements</h3>
          </div>
          <div class="space-y-5">

            <!-- Budget Range -->
            <div>
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Budget Range</label>
              <div class="flex gap-4">
                <div class="flex-1 relative">
                  <input name="budget_min" type="number" min="0" placeholder="Min"
                    class="w-full bg-surface-container border-none focus:ring-0 focus:bg-surface-container-lowest transition-all p-3 pr-14 text-sm rounded-lg"/>
                  <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-on-surface-variant/60 pointer-events-none">LAKH</span>
                </div>
                <div class="flex-1 relative">
                  <input name="budget_max" type="number" min="0" placeholder="Max"
                    class="w-full bg-surface-container border-none focus:ring-0 focus:bg-surface-container-lowest transition-all p-3 pr-14 text-sm rounded-lg"/>
                  <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-on-surface-variant/60 pointer-events-none">CRORE</span>
                </div>
              </div>
            </div>

            <!-- Preferred Locations -->
            <div>
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Preferred Locations</label>
              <input name="preferred_locations" type="text" placeholder="e.g. South Mumbai, Worli Seaface, Bandra West"
                class="w-full bg-surface-container border-none focus:ring-0 focus:bg-surface-container-lowest transition-all p-3 text-sm rounded-lg"/>
            </div>

            <!-- Configuration -->
            <div>
              <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-tighter mb-2">Configuration Preference</label>
              <div class="flex flex-wrap gap-2">
                <?php foreach(['1 BHK','2 BHK','3 BHK','4+ BHK','Penthouse','Plot','Commercial'] as $cfg): ?>
                <label class="cursor-pointer">
                  <input class="peer hidden" type="checkbox" name="configuration[]" value="<?= e($cfg) ?>">
                  <div class="px-3 py-1.5 bg-surface-container-low border border-outline-variant/10 rounded-lg text-xs font-bold hover:border-secondary peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary transition-all">
                    <?= e($cfg) ?>
                  </div>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

          </div>
        </section>

        <section>
          <div class="flex items-center gap-2 mb-4">
            <span class="text-[10px] font-bold uppercase tracking-[.2em] text-secondary">04</span>
            <h3 class="text-sm font-headline font-bold uppercase tracking-wider">Notes</h3>
          </div>
          <textarea name="message" rows="4" placeholder="Client looking for sea-facing apartment. Highly motivated buyer…"
            class="w-full bg-surface-container border-none focus:ring-0 focus:bg-surface-container-lowest transition-all p-4 text-sm rounded-lg resize-none"></textarea>
        </section>


      </div>

      <div class="px-8 py-5 bg-surface-container-low border-t border-outline-variant/10 flex items-center justify-between">
        <button type="button" class="closeLeadModal px-6 py-2.5 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">Cancel</button>
        <button type="submit" class="px-8 py-3 bg-gradient-to-tr from-primary-container to-on-primary-container text-white text-sm font-extrabold rounded-lg shadow hover:scale-[1.02] active:scale-95 transition-all">
          Add Lead to Pipeline
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════ -->
<script>
/* ─── State ─────────────────────────────────────────────────────────────────── */
let currentLead      = null;
let pendingStatus    = null;

/* ─── Status helpers ─────────────────────────────────────────────────────────── */
const STAGE_LABELS = {
  new:'New Lead', contacted:'Contacted', qualified:'Qualified',
  site_visit:'Site Visit', negotiation:'Negotiation', closed:'Closed'
};
const STAGE_COLORS = {
  new:       'bg-secondary-container/30 text-on-secondary-container',
  contacted: 'bg-secondary/20 text-secondary',
  qualified: 'bg-on-tertiary-container/20 text-on-tertiary-container',
  site_visit:'bg-secondary-container text-on-secondary-container',
  negotiation:'bg-primary-container/20 text-on-primary-container',
  closed:    'bg-outline-variant/30 text-secondary',
};
const ACT_ICONS = {
  created:       { icon:'add_circle',       color:'#ff753d' },
  status_change: { icon:'swap_horiz',       color:'#455f88' },
  note:          { icon:'sticky_note_2',    color:'#61b15f' },
  proposal:      { icon:'home_work',        color:'#7c5cbf' },
};

/* ─── Inventory cache for dropdowns ───────────────────────────────────────────────────── */
let inventoryCache = null;
async function fetchInventory() {
  if (inventoryCache) return inventoryCache;
  const r = await fetch('../admin/api/get-listings-simple.php');
  const d = await r.json();
  inventoryCache = d.listings || [];
  return inventoryCache;
}

/* ─── Open lead panel ─────────────────────────────────────────────────────────── */
function openPanel(lead) {
  currentLead = lead;

  // Un-select previously selected card
  document.querySelectorAll('.lead-card.selected').forEach(c => c.classList.remove('selected'));
  const card = document.querySelector(`.lead-card[data-lead-id="${lead.id}"]`);
  if (card) card.classList.add('selected');

  // Populate header
  document.getElementById('panelAvatar').textContent        = (lead.name || '?').charAt(0).toUpperCase();
  document.getElementById('panelName').textContent          = lead.name     || '—';
  document.getElementById('panelSource').textContent        = lead.source_page || 'Direct';
  document.getElementById('panelSourceDetail').textContent  = lead.source_page || 'Direct';
  document.getElementById('panelPhone').textContent         = lead.phone    || '—';
  document.getElementById('panelEmail').textContent         = lead.email || '—';
  document.getElementById('panelDate').textContent          = lead.created_at;
  // Follow-up display
  document.getElementById('panelFollowup').textContent = lead.followup_at ? formatDate(lead.followup_at) : '—';

  document.getElementById('panelCallBtn').href  = 'tel:' + (lead.phone || '');
  document.getElementById('panelWaBtn').href    = 'https://wa.me/91' + (lead.phone || '').replace(/\D/g, '');

  // Status badge
  const badge = document.getElementById('panelStatusBadge');
  badge.textContent = STAGE_LABELS[lead.status] || lead.status;
  badge.className   = 'mt-2 text-[10px] uppercase font-bold px-3 py-1 rounded-full ' + (STAGE_COLORS[lead.status] || 'bg-outline-variant/30 text-secondary');

  // Highlight active stage button
  document.querySelectorAll('.stage-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.status === lead.status);
  });

  // Load activities + proposals from DB
  loadTimeline(lead.id);
  loadProposals(lead.id);

  document.getElementById('leadPanel').classList.add('open');
  document.getElementById('panelBackdrop').classList.add('show');
}

function closePanel() {
  document.getElementById('leadPanel').classList.remove('open');
  document.getElementById('panelBackdrop').classList.remove('show');
  document.querySelectorAll('.lead-card.selected').forEach(c => c.classList.remove('selected'));
  currentLead = null;
}

/* ─── Activity timeline ──────────────────────────────────────────────────────── */
function loadTimeline(leadId) {
  const tl = document.getElementById('panelTimeline');
  tl.innerHTML = '<p class="text-xs text-secondary italic">Loading…</p>';

  fetch(`../admin/api/get-lead-activities.php?lead_id=${leadId}`)
    .then(r => r.json())
    .then(data => {
      if (!data.success || !data.activities.length) {
        tl.innerHTML = '<p class="text-xs text-secondary italic">No activity recorded yet.</p>';
        return;
      }
      tl.innerHTML = data.activities.map((a, i) => {
        const cfg = ACT_ICONS[a.action_type] || ACT_ICONS.note;
        const isLast = i === data.activities.length - 1;
        let title = '', sub = '', bodyHtml = '';

        if (a.action_type === 'created') {
          title = 'Lead added to pipeline';
          sub   = a.to_status ? `Stage: ${STAGE_LABELS[a.to_status] || a.to_status}` : '';
        } else if (a.action_type === 'status_change') {
          const from = STAGE_LABELS[a.from_status] || a.from_status || '—';
          const to   = STAGE_LABELS[a.to_status]   || a.to_status   || '—';
          title = `Moved to <strong>${to}</strong>`;
          sub   = `from ${from}`;
        } else if (a.action_type === 'proposal') {
          title = 'Property Proposed';
          sub   = '';
        } else {
          title = 'Note added';
        }

        if (a.notes) {
          bodyHtml = `<div class="mt-2 p-2 bg-surface-container-low rounded-lg italic text-[11px] text-secondary/80 leading-relaxed">"${escHtml(a.notes)}"</div>`;
        }

        const dt = formatDate(a.created_at);

        return `
        <div class="relative ${!isLast ? '' : ''}">
          <div class="activity-dot" style="background:${cfg.color};position:absolute;left:-18px;top:5px;width:9px;height:9px;border-radius:50%;box-shadow:0 0 0 3px ${cfg.color}22;"></div>
          <p class="text-xs font-bold text-on-surface">${title}</p>
          ${sub ? `<p class="text-[10px] text-secondary mt-0.5">${escHtml(sub)}</p>` : ''}
          <p class="text-[10px] text-secondary/60 mt-0.5">${dt}</p>
          ${bodyHtml}
        </div>`;
      }).join('');
    })
    .catch(() => {
      tl.innerHTML = '<p class="text-xs text-error">Failed to load activities.</p>';
    });
}

/* ─── Status change flow ─────────────────────────────────────────────────────── */

/** Format Date to YYYY-MM-DDThh:mm for datetime-local input */
function toLocalDatetimeStr(dt) {
  const pad = n => String(n).padStart(2, '0');
  return `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
}

function promptStatusChange(newStatus) {
  if (!currentLead) return;
  if (newStatus === currentLead.status) return; // no change

  pendingStatus = newStatus;
  document.getElementById('smFromStatus').textContent = STAGE_LABELS[currentLead.status] || currentLead.status;
  document.getElementById('smToStatus').textContent   = STAGE_LABELS[newStatus]           || newStatus;
  document.getElementById('statusModalTitle').textContent    = 'Update Stage';
  document.getElementById('statusModalSubtitle').textContent = `Moving "${currentLead.name}" to ${STAGE_LABELS[newStatus]}`;
  document.getElementById('statusNotes').value = '';

  // Set default follow-up to 24 hours from now
  const tomorrow = new Date(Date.now() + 86400000);
  document.getElementById('followupDatetime').value = toLocalDatetimeStr(tomorrow);

  document.getElementById('statusModal').classList.add('show');
  setTimeout(() => document.getElementById('statusNotes').focus(), 100);
}

function closeStatusModal() {
  document.getElementById('statusModal').classList.remove('show');
  pendingStatus = null;
}

document.getElementById('confirmStatusBtn').addEventListener('click', function() {
  if (!currentLead || !pendingStatus) return;

  this.textContent = 'Saving…';
  this.disabled    = true;

  const notes     = document.getElementById('statusNotes').value.trim();
  const listingId = document.getElementById('siteVisitListingId').value;
  const followup  = document.getElementById('followupDatetime').value;
  const fd = new FormData();
  fd.append('action', 'update_status');
  fd.append('status', pendingStatus);
  fd.append('ids',    JSON.stringify([currentLead.id]));
  fd.append('notes',  notes);
  fd.append('followup_at', followup || '');
  if (pendingStatus === 'site_visit' && listingId) fd.append('listing_id', listingId);

  fetch('../admin/api/lead-bulk-action.php', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        closeStatusModal();
        // Update local state
        currentLead.status = pendingStatus;
        // Update badge & stage buttons
        const badge = document.getElementById('panelStatusBadge');
        badge.textContent = STAGE_LABELS[pendingStatus];
        badge.className   = 'mt-2 text-[10px] uppercase font-bold px-3 py-1 rounded-full ' + (STAGE_COLORS[pendingStatus] || '');
        document.querySelectorAll('.stage-btn').forEach(b => b.classList.toggle('active', b.dataset.status === pendingStatus));
        // Reload timeline
        loadTimeline(currentLead.id);
        // Reload page after short delay so kanban updates
        setTimeout(() => location.reload(), 1200);
      } else {
        alert(data.message || 'Failed to update status.');
      }
    })
    .catch(() => alert('Network error.'))
    .finally(() => {
      this.textContent = 'Confirm & Save';
      this.disabled    = false;
    });
});

/* ─── Note flow ──────────────────────────────────────────────────────────────── */
document.getElementById('panelNoteBtn').addEventListener('click', function() {
  if (!currentLead) return;
  document.getElementById('noteText').value = '';
  document.getElementById('noteModal').style.display = 'flex';
  setTimeout(() => document.getElementById('noteText').focus(), 100);
});

function closeNoteModal() {
  document.getElementById('noteModal').style.display = 'none';
}

document.getElementById('saveNoteBtn').addEventListener('click', function() {
  if (!currentLead) return;
  const note = document.getElementById('noteText').value.trim();
  if (!note) { alert('Please enter a note.'); return; }

  this.textContent = 'Saving…';
  this.disabled    = true;

  const fd = new FormData();
  fd.append('action', 'add_note');
  fd.append('ids',    JSON.stringify([currentLead.id]));
  fd.append('notes',  note);

  fetch('../admin/api/lead-bulk-action.php', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        closeNoteModal();
        loadTimeline(currentLead.id);
      } else {
        alert(data.message || 'Failed to save note.');
      }
    })
    .catch(() => alert('Network error.'))
    .finally(() => {
      this.textContent = 'Save Note';
      this.disabled    = false;
    });
});

/* ─── Delete lead ────────────────────────────────────────────────────────────── */
document.getElementById('panelDeleteBtn').addEventListener('click', function() {
  if (!currentLead) return;
  if (!confirm(`Delete "${currentLead.name}"? This cannot be undone.`)) return;

  const fd = new FormData();
  fd.append('action', 'delete');
  fd.append('ids',    JSON.stringify([currentLead.id]));

  fetch('../admin/api/lead-bulk-action.php', { method:'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) { closePanel(); location.reload(); }
      else alert(data.message || 'Failed to delete.');
    });
});

/* ─── Add Lead modal ─────────────────────────────────────────────────────────── */
const addLeadModal = document.getElementById('addLeadModal');
document.getElementById('addLeadBtn').addEventListener('click', () => addLeadModal.classList.remove('hidden'));
document.querySelectorAll('.closeLeadModal').forEach(b => b.addEventListener('click', () => addLeadModal.classList.add('hidden')));
addLeadModal.addEventListener('click', e => { if (e.target === addLeadModal) addLeadModal.classList.add('hidden'); });

document.getElementById('addLeadForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const btn = this.querySelector('[type=submit]');
  btn.textContent = 'Adding…'; btn.disabled = true;
  fetch('../admin/api/add-lead.php', { method:'POST', body: new FormData(this) })
    .then(r => r.json())
    .then(d => { if (d.success) location.reload(); else alert(d.message || 'Failed.'); })
    .catch(() => alert('Error adding lead.'))
    .finally(() => { btn.textContent = 'Add Lead to Pipeline'; btn.disabled = false; });
});

/* ─── Task checkbox strikethrough ───────────────────────────────────────────── */
document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
  cb.addEventListener('change', function() {
    const label = this.nextElementSibling;
    if (label) label.style.opacity   = this.checked ? '0.4' : '1';
    const p     = label && label.querySelector('p');
    if (p)    p.style.textDecoration = this.checked ? 'line-through' : '';
  });
});

/* ─── Keyboard ───────────────────────────────────────────────────────────────── */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    if (document.getElementById('statusModal').classList.contains('show')) { closeStatusModal(); return; }
    if (document.getElementById('noteModal').style.display === 'flex') { closeNoteModal(); return; }
    closePanel();
  }
});

/* ─── Close status modal on backdrop click ───────────────────────────────────── */
document.getElementById('statusModal').addEventListener('click', function(e) {
  if (e.target === this) closeStatusModal();
});
document.getElementById('noteModal').addEventListener('click', function(e) {
  if (e.target === this) closeNoteModal();
});

/* ─── Helpers ────────────────────────────────────────────────────────────────── */
function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function formatDate(dt) {
  if (!dt) return '';
  const d = new Date(dt.replace(' ', 'T'));
  return d.toLocaleString('en-IN', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' });
}

function formatFollowupDate(dt) {
  if (!dt) return '—';
  const d = new Date(dt);
  return d.toLocaleString('en-IN', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' });
}

/* ─── Proposed properties (panel section) ───────────────────────────────────── */
function loadProposals(leadId) {
  const list = document.getElementById('proposedPropertiesList');
  list.innerHTML = '<p class="text-xs text-secondary italic text-center py-2">Loading…</p>';

  fetch(`../admin/api/get-lead-proposals.php?lead_id=${leadId}`)
    .then(r => r.json())
    .then(data => {
      if (!data.success || !data.proposals.length) {
        list.innerHTML = '<p class="text-xs text-secondary italic text-center py-2">No properties proposed yet.</p>';
        return;
      }
      list.innerHTML = data.proposals.map(p => {
        const price = p.price ? '₹' + Number(p.price).toLocaleString('en-IN') : 'Price on Request';
        const beds  = p.bedrooms ? p.bedrooms + ' BHK · ' : '';
        return `
        <div class="flex items-start gap-2 p-2 rounded-lg bg-surface-container-low">
          <span class="material-symbols-outlined text-on-tertiary-container text-base mt-0.5">home_work</span>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-on-surface truncate">${escHtml(p.title || '—')}</p>
            <p class="text-[10px] text-secondary">${escHtml(beds + (p.location || ''))} · ${escHtml(price)}</p>
            ${p.notes ? `<p class="text-[10px] text-secondary/70 italic mt-0.5">"${escHtml(p.notes)}"</p>` : ''}
          </div>
        </div>`;
      }).join('');
    })
    .catch(() => {
      list.innerHTML = '<p class="text-xs text-error text-center py-2">Failed to load.</p>';
    });
}

/* ─── Propose property modal ─────────────────────────────────────────────────── */
let selectedListingId = null;
let proposeListingsData = [];

document.getElementById('proposePropertyBtn').addEventListener('click', async function() {
  if (!currentLead) return;
  selectedListingId = null;
  document.getElementById('confirmProposeBtn').disabled = true;
  document.getElementById('proposeNotes').value = '';
  document.getElementById('proposeSearchInput').value = '';
  document.getElementById('proposeModalSubtitle').textContent = `For ${currentLead.name}`;
  document.getElementById('proposeModal').style.display = 'flex';

  const listEl = document.getElementById('proposeListings');
  listEl.innerHTML = '<p class="text-xs text-secondary text-center py-4">Loading inventory…</p>';

  try {
    proposeListingsData = await fetchInventory();
    renderProposeList(proposeListingsData);
  } catch {
    listEl.innerHTML = '<p class="text-xs text-error text-center py-4">Failed to load inventory.</p>';
  }
});

function closeProposeModal() {
  document.getElementById('proposeModal').style.display = 'none';
  selectedListingId = null;
}

function filterProposeList() {
  const q = (document.getElementById('proposeSearchInput').value || '').toLowerCase();
  const filtered = q ? proposeListingsData.filter(l =>
    (l.label || '').toLowerCase().includes(q) || (l.location || '').toLowerCase().includes(q)
  ) : proposeListingsData;
  renderProposeList(filtered);
}

function renderProposeList(listings) {
  const listEl = document.getElementById('proposeListings');
  if (!listings.length) {
    listEl.innerHTML = '<p class="text-xs text-secondary text-center py-4">No properties found.</p>';
    return;
  }
  listEl.innerHTML = listings.map(l => `
    <div class="propose-listing-item flex items-start gap-3 p-3 rounded-xl cursor-pointer border border-transparent hover:border-secondary/20 hover:bg-surface-container transition-all"
         data-id="${l.id}" onclick="selectProposeListing(${l.id}, this)">
      <span class="material-symbols-outlined text-secondary text-base mt-0.5">apartment</span>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-on-surface truncate">${escHtml(l.title)}</p>
        <p class="text-[10px] text-secondary">${escHtml(l.location || '')}${l.bedrooms ? ' · ' + l.bedrooms + ' BHK' : ''} · ${escHtml(l.price_fmt || '')}</p>
      </div>
      <span class="check-icon material-symbols-outlined text-secondary hidden text-base">check_circle</span>
    </div>`).join('');
}

function selectProposeListing(id, el) {
  document.querySelectorAll('.propose-listing-item').forEach(item => {
    item.classList.remove('border-secondary', 'bg-secondary/5');
    item.querySelector('.check-icon').classList.add('hidden');
  });
  el.classList.add('border-secondary', 'bg-secondary/5');
  el.querySelector('.check-icon').classList.remove('hidden');
  selectedListingId = id;
  document.getElementById('confirmProposeBtn').disabled = false;
}

document.getElementById('confirmProposeBtn').addEventListener('click', function() {
  if (!currentLead || !selectedListingId) return;
  this.textContent = 'Saving…';
  this.disabled = true;

  const fd = new FormData();
  fd.append('action',     'propose_property');
  fd.append('ids',        JSON.stringify([currentLead.id]));
  fd.append('listing_id', selectedListingId);
  fd.append('notes',      document.getElementById('proposeNotes').value.trim());

  fetch('../admin/api/lead-bulk-action.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        closeProposeModal();
        loadProposals(currentLead.id);
        loadTimeline(currentLead.id);
      } else {
        alert(data.message || 'Failed to propose property.');
      }
    })
    .catch(() => alert('Network error.'))
    .finally(() => {
      this.textContent = 'Propose Property';
      this.disabled = false;
    });
});

document.getElementById('proposeModal').addEventListener('click', function(e) {
  if (e.target === this) closeProposeModal();
});

/* ═══════════════════════════════════════════════════════
   FILTER SYSTEM
═══════════════════════════════════════════════════════ */

let activeSources = new Set();  // selected source values
let activeDays    = 0;          // 0 = all time

/* ─── Toggle dropdown open/close ─────────────────────────────────────────── */
function toggleFilter() {
  const dd = document.getElementById('filterDropdown');
  dd.classList.toggle('open');
}

/* ─── Close dropdown when clicking outside ───────────────────────────────── */
document.addEventListener('click', function(e) {
  const wrapper = document.getElementById('filterWrapper');
  if (wrapper && !wrapper.contains(e.target)) {
    document.getElementById('filterDropdown').classList.remove('open');
  }
});

/* ─── Source chip toggle ─────────────────────────────────────────────────── */
function toggleSourceFilter(btn) {
  const src = btn.dataset.source;
  if (activeSources.has(src)) {
    activeSources.delete(src);
    btn.classList.remove('active-source');
  } else {
    activeSources.add(src);
    btn.classList.add('active-source');
  }
  applyFilters();
  updateFilterBadge();
}

/* ─── Date chip single-select ────────────────────────────────────────────── */
function toggleDateFilter(btn) {
  document.querySelectorAll('.date-chip').forEach(c => c.classList.remove('active'));
  btn.classList.add('active');
  activeDays = parseInt(btn.dataset.days, 10);
  applyFilters();
  updateFilterBadge();
}

/* ─── Clear all filters ──────────────────────────────────────────────────── */
function clearFilters() {
  activeSources.clear();
  activeDays = 0;
  document.querySelectorAll('.source-chip').forEach(c => c.classList.remove('active-source'));
  document.querySelectorAll('.date-chip').forEach(c => c.classList.remove('active'));
  const allTimeBtn = document.querySelector('.date-chip[data-days="0"]');
  if (allTimeBtn) allTimeBtn.classList.add('active');
  document.getElementById('leadSearchInput').value = '';
  applyFilters();
  updateFilterBadge();
}

/* ─── Core filter logic ──────────────────────────────────────────────────── */
function applyFilters() {
  const searchQuery = (document.getElementById('leadSearchInput').value || '').toLowerCase().trim();
  const now         = Date.now();
  const msPerDay    = 86400000;
  const cutoff      = activeDays > 0 ? now - (activeDays * msPerDay) : 0;

  // Each kanban column wrapper
  document.querySelectorAll('[data-col-id]').forEach(col => {
    let visCount = 0;
    col.querySelectorAll('.lead-card').forEach(card => {
      const name   = (card.dataset.name   || '').toLowerCase();
      const phone  = (card.dataset.phone  || '').toLowerCase();
      const source = (card.dataset.source || '');
      const ts     = parseInt(card.dataset.ts || '0', 10) * 1000; // JS ms

      // 1. Search match
      const matchSearch = !searchQuery || name.includes(searchQuery) || phone.includes(searchQuery) || source.toLowerCase().includes(searchQuery);

      // 2. Source match
      const matchSource = activeSources.size === 0 || activeSources.has(source);

      // 3. Date match
      const matchDate = cutoff === 0 || ts >= cutoff;

      if (matchSearch && matchSource && matchDate) {
        card.classList.remove('filtered-out');
        visCount++;
      } else {
        card.classList.add('filtered-out');
      }
    });

    // Update column count badge
    const badge = col.querySelector('.col-count');
    if (badge) badge.textContent = visCount;

    // Show/hide "drop here" placeholder depending on visible cards
    const placeholder = col.querySelector('.drop-placeholder');
    if (placeholder) placeholder.style.display = visCount === 0 ? 'flex' : 'none';
  });
}

/* ─── Update filter badge on button ─────────────────────────────────────── */
function updateFilterBadge() {
  const total   = activeSources.size + (activeDays > 0 ? 1 : 0);
  const countEl = document.getElementById('filterCount');
  const labelEl = document.getElementById('filterBtnLabel');
  if (total > 0) {
    countEl.textContent = total;
    countEl.classList.remove('hidden');
    labelEl.textContent = 'Filtered';
    document.getElementById('filterBtn').classList.add('bg-secondary', 'text-white');
    document.getElementById('filterBtn').classList.remove('bg-surface-container-low');
  } else {
    countEl.classList.add('hidden');
    labelEl.textContent = 'Filter';
    document.getElementById('filterBtn').classList.remove('bg-secondary', 'text-white');
    document.getElementById('filterBtn').classList.add('bg-surface-container-low');
  }
}
</script>

<?php admin_footer(); ?>

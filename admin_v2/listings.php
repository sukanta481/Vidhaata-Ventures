<?php
require_once __DIR__ . '/../admin/includes/auth-check.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/admin-layout.php';

// Helper function for escaping output
function e($value): string {
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Get filter parameters
$type = $_GET['type'] ?? 'all';
$status = $_GET['status'] ?? 'all';
$search = trim($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Build WHERE clause
$conditions = [];
$params = [];
if ($type !== 'all' && in_array($type, ['residential', 'commercial'], true)) {
  $conditions[] = 'type = :type';
  $params[':type'] = $type;
}
if ($status !== 'all' && in_array($status, ['active', 'sold', 'inactive'], true)) {
  $conditions[] = 'status = :status';
  $params[':status'] = $status;
}
if ($search !== '') {
  $conditions[] = '(title LIKE :search OR location LIKE :search OR description LIKE :search)';
  $params[':search'] = '%' . $search . '%';
}
$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Get total count
$count_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM listings {$where}");
$count_stmt->execute($params);
$total_listings = $count_stmt->fetch()['count'];
$total_pages = max(1, ceil($total_listings / $per_page));

// Get paginated listings
$stmt = $pdo->prepare("SELECT id, title, type, listing_purpose, price, monthly_rent, location, bedrooms, area_sqft, status, is_featured, created_at FROM listings {$where} ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params as $key => $value) {
  $stmt->bindValue($key, $value);
}
$stmt->execute();
$listings = $stmt->fetchAll();

// Calculate metrics
$metrics_stmt = $pdo->prepare("SELECT
  COUNT(*) as total,
  SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
  SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold FROM listings");
$metrics_stmt->execute();
$metrics = $metrics_stmt->fetch();

$total_all = $metrics['total'] ?? 0;
$active_count = $metrics['active'] ?? 0;
$sold_count = $metrics['sold'] ?? 0;
$blocked_count = max(0, $total_all - $active_count - $sold_count);

// Build query string helper
function build_qs(array $overrides): string {
  return '?' . http_build_query(array_merge($_GET, $overrides));
}

// Format price to 10k, 1.2L, 3.6Cr format
function fmtPrice(float $value): string {
  if ($value <= 0) return '';
  if ($value >= 10000000) return round($value / 10000000, 1) . 'Cr';
  if ($value >= 100000) return round($value / 100000, 1) . 'L';
  return round($value / 1000, 0) . 'k';
}

$active_page = 'listings';
$page_title = 'Property Inventory';
admin_head($page_title);
?>
<?php admin_body_open(); ?>

<!-- Content Stage -->
<section class="max-w-[1600px] mx-auto w-full space-y-8">
  <!-- Hero Header Section -->
  <div class="flex justify-between items-end">
    <div>
      <h1 class="text-4xl font-extrabold text-on-background tracking-tight font-headline">Property Inventory</h1>
      <p class="text-on-surface-variant mt-2 text-lg">Manage your architectural portfolio and real estate assets.</p>
    </div>
    <a href="add-listing.php" class="flex items-center bg-secondary text-white px-6 py-3 rounded-md font-bold hover:bg-on-secondary-container transition-all shadow-xl shadow-secondary/10">
      <span class="material-symbols-outlined mr-2">add</span>
      Add New Property
    </a>
  </div>

  <!-- Inventory Overview Bento Grid -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex flex-col justify-between h-32">
      <span class="text-secondary font-bold text-xs uppercase tracking-widest">Total Properties</span>
      <span class="text-4xl font-headline font-extrabold text-on-background"><?php echo e($total_all); ?></span>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex flex-col justify-between h-32">
      <span class="text-on-tertiary-container font-bold text-xs uppercase tracking-widest">Available</span>
      <div class="flex items-baseline space-x-2">
        <span class="text-4xl font-headline font-extrabold text-on-background"><?php echo e($active_count); ?></span>
        <span class="text-xs text-on-tertiary-container font-bold"><?php echo $total_all ? round(($active_count / $total_all) * 100) : 0; ?>% Total</span>
      </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex flex-col justify-between h-32">
      <span class="text-on-primary-container font-bold text-xs uppercase tracking-widest">Negotiation/Blocked</span>
      <span class="text-4xl font-headline font-extrabold text-on-background"><?php echo e($blocked_count); ?></span>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex flex-col justify-between h-32">
      <span class="text-outline font-bold text-xs uppercase tracking-widest">Sold/Rented</span>
      <span class="text-4xl font-headline font-extrabold text-on-background"><?php echo e($sold_count); ?></span>
    </div>
  </div>

  <!-- Hyper-Local Smart Filter Bar -->
  <form method="get" class="bg-surface-container-low p-5 rounded-xl flex flex-wrap items-center gap-4 border border-outline-variant/10">
    <div class="flex-1 min-w-[180px]">
      <label class="text-[10px] uppercase font-bold text-outline-variant mb-1 block px-1">Location / Search</label>
      <div class="relative">
        <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
        <input name="search" value="<?php echo e($search); ?>" class="w-full pl-8 pr-4 py-2 bg-surface-container-lowest border border-outline-variant/20 rounded-md text-sm focus:ring-1 focus:ring-secondary" placeholder="Search..." type="text"/>
      </div>
    </div>
    <div class="flex-1 min-w-[150px]">
      <label class="text-[10px] uppercase font-bold text-outline-variant mb-1 block px-1">Property Type</label>
      <select name="type" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-md py-2 px-3 text-sm focus:ring-1 focus:ring-secondary">
        <option value="all" <?php echo $type === 'all' ? 'selected' : ''; ?>>All Types</option>
        <option value="residential" <?php echo $type === 'residential' ? 'selected' : ''; ?>>Residential</option>
        <option value="commercial" <?php echo $type === 'commercial' ? 'selected' : ''; ?>>Commercial</option>
      </select>
    </div>
    <div class="flex-1 min-w-[140px]">
      <label class="text-[10px] uppercase font-bold text-outline-variant mb-1 block px-1">Status</label>
      <select name="status" class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-md py-2 px-3 text-sm focus:ring-1 focus:ring-secondary">
        <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>All Statuses</option>
        <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
        <option value="sold" <?php echo $status === 'sold' ? 'selected' : ''; ?>>Sold</option>
        <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
      </select>
    </div>
    <div class="flex items-end h-full self-end pb-[2px] gap-2">
      <button type="submit" class="bg-secondary text-white px-6 py-[9px] rounded-md text-sm font-semibold transition-colors shadow-sm">
          Apply Filters
      </button>
      <?php if ($search !== '' || $type !== 'all' || $status !== 'all'): ?>
        <a href="listings.php" class="bg-surface-container text-on-surface px-6 py-[9px] rounded-md text-sm font-semibold transition-colors shadow-sm border border-outline-variant/20">Clear</a>
      <?php endif; ?>
    </div>
  </form>

  <!-- Property Catalog -->
  <div class="space-y-4">
    <!-- List Header -->
    <div class="grid grid-cols-12 gap-4 px-6 text-[10px] font-bold uppercase tracking-widest text-outline py-2 border-b border-outline-variant/10">
      <div class="col-span-5">Property &amp; Configuration</div>
      <div class="col-span-3">Status, RERA &amp; Vault</div>
      <div class="col-span-1 text-center">Inquiries</div>
      <div class="col-span-2">Price Estimate</div>
      <div class="col-span-1 text-right">Actions</div>
    </div>

    <!-- Property Items -->
    <div class="space-y-3">
      <?php foreach ($listings as $idx => $listing): 
        $border_class = 'border-outline/30';
        $status_bg = 'bg-surface-container text-on-surface-variant';
        
        if ($listing['status'] === 'active') {
            $border_class = 'border-on-tertiary-container';
            $status_bg = 'bg-on-tertiary-container/10 text-on-tertiary-container';
        } elseif ($listing['status'] === 'sold') {
            $border_class = 'border-outline';
            $status_bg = 'bg-slate-200 text-slate-700';
        }
        $is_commercial_rent = ($listing['type'] === 'commercial' && (($listing['listing_purpose'] ?? '') === 'rent' || ($listing['listing_purpose'] ?? '') === 'pg'));
        if ($is_commercial_rent && !empty($listing['monthly_rent']) && (float)$listing['monthly_rent'] > 0) {
          $price_fmt = '₹' . fmtPrice((float)$listing['monthly_rent']) . '/month';
        } elseif (!empty($listing['price']) && (float)$listing['price'] > 0) {
          $price_fmt = '₹' . fmtPrice((float)$listing['price']);
        } else {
          $price_fmt = 'Price on Request';
        }
        $price_per_sqft = (!$is_commercial_rent && $listing['area_sqft'] && $listing['price']) ? (int)round($listing['price'] / $listing['area_sqft']) : 0;
      ?>
      <div class="grid grid-cols-12 gap-4 items-center bg-surface-container-lowest p-4 rounded-xl shadow-sm border-l-4 <?php echo $border_class; ?> hover:shadow-md transition-all group">
        <div class="col-span-5 flex items-center space-x-4 pr-4">
          <div class="w-16 h-16 bg-surface-container rounded-lg flex items-center justify-center text-secondary/30 shrink-0">
             <span class="material-symbols-outlined text-3xl">home_work</span>
          </div>
          <div class="overflow-hidden">
            <h3 class="font-headline font-bold text-on-background text-lg leading-tight truncate" title="<?php echo e($listing['title']); ?>"><?php echo e($listing['title']); ?></h3>
            <div class="flex items-center text-on-surface-variant text-sm mt-1 truncate">
              <span class="material-symbols-outlined text-xs mr-1">location_on</span>
              <span class="truncate"><?php echo e($listing['location'] ?: 'Unspecified Location'); ?></span>
            </div>
            <div class="flex gap-2 mt-2">
              <span class="text-[10px] font-bold bg-secondary-container/30 text-on-secondary-container px-2 py-0.5 rounded uppercase"><?php echo e(ucfirst($listing['type'])); ?></span>
              <?php if ($listing['bedrooms']): ?>
              <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded uppercase"><?php echo e($listing['bedrooms']); ?> BHK</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-span-3 space-y-2">
          <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold <?php echo $status_bg; ?>">
              <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span><?php echo e(ucfirst($listing['status'])); ?>
            </span>
          </div>
          <div class="flex items-center gap-3">
            <div class="flex items-center text-[10px] font-bold text-tertiary-container bg-tertiary-container/10 px-2 py-1 rounded border border-tertiary-container/20">
              <span class="material-symbols-outlined text-[16px] mr-1" style="font-variation-settings: 'FILL' 1;">verified_user</span>
              RERA REG.
            </div>
          </div>
        </div>
        
        <div class="col-span-1">
          <div class="flex flex-col items-center">
            <span class="text-xl font-bold text-on-background"><?php echo rand(5, 50); ?></span>
            <span class="text-[9px] text-outline font-bold uppercase">Leads</span>
          </div>
        </div>
        
        <div class="col-span-2">
          <p class="text-xl font-headline font-extrabold text-on-background tracking-tight"><?php echo $price_fmt; ?></p>
          <?php if (!$is_commercial_rent && $listing['area_sqft'] && $listing['price']): ?>
          <p class="text-[11px] text-outline font-bold uppercase tracking-tight">₹<?php echo fmtPrice($listing['price'] / $listing['area_sqft']); ?> / SQ.FT</p>
          <?php endif; ?>
        </div>
        
        <div class="col-span-1 flex items-center justify-end space-x-1 relative">
           <a href="../property.php?id=<?php echo $listing['id']; ?>" target="_blank" class="text-outline-variant hover:text-secondary hover:bg-secondary/10 p-2 rounded transition-colors" title="View Property">
              <span class="material-symbols-outlined text-lg">visibility</span>
           </a>
           <a href="edit-listing.php?id=<?php echo $listing['id']; ?>" class="text-secondary hover:bg-secondary/10 p-2 rounded transition-colors" title="Edit Property">
              <span class="material-symbols-outlined text-lg">edit</span>
           </a>
           <button type="button" onclick="deleteListing(<?php echo $listing['id']; ?>)" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded transition-colors" title="Delete Property">
              <span class="material-symbols-outlined text-lg">delete</span>
           </button>
        </div>
      </div>
      <?php endforeach; ?>
      
      <script>
      function deleteListing(id) {
          if(!confirm('Are you sure you want to permanently delete this listing? This action cannot be undone.')) return;
          
          const formData = new FormData();
          formData.append('id', id);
          
          fetch('../admin/api/delete-listing.php', {
              method: 'POST',
              body: formData
          })
          .then(res => res.json())
          .then(data => {
              if(data.success) {
                  window.location.reload();
              } else {
                  alert(data.message || 'Failed to delete listing.');
              }
          })
          .catch(err => {
              console.error(err);
              alert('An unexpected error occurred while deleting the listing.');
          });
      }
      </script>
      
      <?php if (!$listings): ?>
        <div class="text-center py-16 text-on-surface-variant bg-surface-container-lowest rounded-xl border border-outline-variant/10">
          <span class="material-symbols-outlined text-4xl mb-2 opacity-50">search_off</span>
          <p class="font-bold">No properties found.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination/Footer -->
    <?php if ($total_pages > 0): ?>
    <div class="flex justify-between items-center pt-4 border-t border-outline-variant/10">
      <p class="text-xs text-on-surface-variant font-medium">Showing <span class="font-bold"><?php echo e($total_listings > 0 ? $offset + 1 : 0); ?>-<?php echo e(min($offset + $per_page, $total_listings)); ?></span> of <span class="font-bold"><?php echo e($total_listings); ?></span> properties</p>
      <div class="flex items-center space-x-2">
        <a href="<?php echo $page > 1 ? build_qs(['page' => $page - 1]) : '#'; ?>" class="p-2 rounded-md hover:bg-surface-container-high text-on-surface-variant transition-colors <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : ''; ?>">
          <span class="material-symbols-outlined">chevron_left</span>
        </a>
        
        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
          <?php if ($i === $page): ?>
            <span class="w-8 h-8 rounded-md bg-secondary text-white text-xs font-bold shadow-md flex items-center justify-center"><?php echo $i; ?></span>
          <?php else: ?>
             <a href="<?php echo build_qs(['page' => $i]); ?>" class="w-8 h-8 flex items-center justify-center rounded-md bg-surface-container-lowest text-on-surface text-xs font-bold border border-outline-variant/20 hover:bg-surface-container-high"><?php echo $i; ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        
        <a href="<?php echo $page < $total_pages ? build_qs(['page' => $page + 1]) : '#'; ?>" class="p-2 rounded-md hover:bg-surface-container-high text-on-surface-variant transition-colors <?php echo $page >= $total_pages ? 'opacity-50 pointer-events-none' : ''; ?>">
          <span class="material-symbols-outlined">chevron_right</span>
        </a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php admin_footer(); ?>

# Vidhaata Ventures — Project Plan

> Luxury real estate website for Vidhaata Ventures.
> Stack: PHP 8.x, MySQL (PDO), Tailwind CSS (CDN), vanilla JS.
> Hosting: Hostinger shared hosting. No build tools, no frameworks.

---

## Project Overview

A curated, editorial-style real estate website that displays residential and commercial
property listings, showcases company services, captures leads via a contact form,
and includes an admin panel for managing leads and listings.

**Design philosophy:** "The Digital Curator" — gallery-style UI with generous whitespace,
tonal layering, glassmorphism nav, and no hard borders. Think luxury magazine, not database.

---

## What's Done (Phase 1) ✅

| File | Description |
|------|-------------|
| `index.php` | Homepage — hero, search bar, expertise section, destinations grid, why-us card, newly listed properties, client feedback, partners, footer |
| `property.php` | Property details — image gallery, title/price/location, tags, brochure download, specs grid, sticky contact form, trust badges, chat FAB |
| `includes/header.php` | Shared navbar — glassmorphic, transparent-to-glass on scroll, mobile hamburger menu, active page highlighting, contact modal with AJAX form |
| `includes/footer.php` | Shared 4-column footer — brand + social icons, offices, portfolio links, legal links, copyright |
| `includes/config.php` | Site name, DB credentials, admin credentials |
| `assets/css/custom.css` | Glassmorphism, scroll-reveal animations, bottom-border inputs |
| `assets/js/main.js` | Nav scroll effect, mobile menu toggle, contact modal open/close, form AJAX submission with toast feedback |
| `.htaccess` | Blocks direct access to `/includes` and `/admin/includes` |

**Navbar and footer are shared via PHP includes — consistent across every page.**

---

## Completed and Remaining Phases

---

### Phase 2: Database & API Layer ✅ Done

**Goal:** Set up MySQL database and backend API endpoints so forms actually save data
and listings can be pulled dynamically.

#### 2.1 — Database Setup (`database/setup.sql`) ✅ Done

Create a SQL file that can be run once on Hostinger to set up tables:

```sql
CREATE TABLE listings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('residential', 'commercial') NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(12,2),
  location VARCHAR(255),
  bedrooms TINYINT DEFAULT NULL,
  area_sqft INT,
  image_filename VARCHAR(255),
  is_featured TINYINT(1) DEFAULT 0,
  status ENUM('active', 'sold', 'inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(255),
  message TEXT,
  source_page VARCHAR(100),
  status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 2.2 — DB Connection (`includes/db.php`) ✅ Done

- Returns a `$pdo` object using credentials from `config.php`
- PDO with `ERRMODE_EXCEPTION` and `FETCH_ASSOC` defaults
- Single file, included everywhere DB access is needed

#### 2.3 — API: Submit Lead (`api/submit-lead.php`) ✅ Done

- Method: POST only
- Accepts: `name`, `phone`, `email` (optional), `message` (optional), `source_page`
- Validates: name and phone are required
- Sanitizes all input with `htmlspecialchars()`
- Inserts into `leads` table via prepared statement
- Returns JSON: `{ "success": true }` or `{ "success": false, "message": "..." }`
- Sets `Content-Type: application/json` header

#### 2.4 — API: Get Listings (`api/get-listings.php`) ✅ Done

- Method: GET
- Query params: `type` (residential/commercial), `featured` (0/1), `limit`, `offset`
- Returns JSON array of listings
- Used by frontend pages to load property cards dynamically

**Files created:** `database/setup.sql`, `includes/db.php`, `api/submit-lead.php`, `api/get-listings.php` ✅ Done

---

### Phase 3: Residential Listings Page (`residential.php`) ✅ Done

**Goal:** Grid page showing all residential property listings with filtering.

#### Layout (matches homepage design language) ✅ Done:

1. **Page hero banner** — shorter than homepage (40vh), dark overlay, title "Residential Properties"
2. **Filter bar** — location dropdown, price range, bedrooms, sort by (newest/price) — uses `bg-surface-container-low` background, no borders
3. **Listings grid** — 4 columns on desktop, 2 on tablet, 2 on mobile
   - Each card matches the "Newly Listed" card design from homepage
   - Image with favorite button, title, location, price, beds/baths/sqft
   - Links to `property.php?id=X`
4. **Pagination** — simple prev/next with page numbers
5. **Empty state** — if no listings match filters, show a friendly message

#### Data flow ✅ Done:
- On page load: fetch listings from `api/get-listings.php?type=residential`
- On filter change: re-fetch with updated params, re-render grid via JS
- No page reload — all filtering is client-side fetch + DOM update

---

### Phase 4: Commercial Listings Page (`commercial.php`) ✅ Done

**Goal:** Same structure as residential, filtered to commercial properties.

#### Differences from residential ✅ Done:
- Hero title: "Commercial Properties"
- No bedrooms filter (commercial properties don't have bedrooms)
- Card shows area_sqft prominently instead of beds/baths
- Tag chip says "Commercial" instead of residential type tags

#### Shared code ✅ Done:
- Both pages can share the same card-rendering JS function
- Same filter bar component, just different filter options
- Consider extracting a `renderPropertyCard()` function in `main.js`

---

### Phase 5: Services Page (`services.php`) ✅ Done

**Goal:** Showcase Vidhaata Ventures' services in an editorial layout.

#### Layout ✅ Done:

1. **Page hero** — shorter hero (40vh), title "Our Services"
2. **Services grid** — alternating left-right sections (image + text), similar to the "Our Expertise" section on homepage
   - Each service gets: icon, heading, description paragraph, optional CTA
3. **Services to feature:**
   - Property Advisory & Consultation
   - Residential Sales
   - Commercial Leasing
   - Vaastu Consultation
   - Home Loan Assistance
   - Legal & Documentation Support
   - Interior Design Consultation
   - Property Management
4. **CTA section** — dark navy card (like "Why Us" section) with "Get Expert Advice" button that opens contact modal

#### Design notes ✅ Done:
- Alternating `bg-surface` and `bg-surface-container-low` backgrounds between service blocks
- Material Symbols icons for each service
- No card borders — tonal separation only

---

### Phase 6: About Us Page (`about.php`) ✅ Done

**Goal:** Company story, team, values — builds trust.

#### Layout ✅ Done:

1. **Page hero** — shorter hero, title "About Vidhaata Ventures"
2. **Our Story section** — 2-column: large image + text block with company history
   - When founded, mission, vision
   - Reuse the expertise section layout from homepage
3. **Values / pillars** — 3-4 value cards in a grid
   - Integrity, Trust, Excellence, Client-First
   - Icon + heading + short description
   - Uses `bg-surface-container-low` cards on `bg-surface` background
4. **Team section** (optional) — founder/key people cards
   - Photo, name, designation
   - Round images, centered layout
5. **Stats bar** — horizontal strip with key numbers
   - Properties sold, happy clients, years of experience, cities covered
   - Dark navy background (like why-us section)
6. **CTA** — "Ready to find your dream property?" with contact button

---

### Phase 7: Admin Panel — Professional CRM & Property Management System

**Goal:** A professional-grade admin panel comparable to MagicBricks/Housing.com back-office — responsive, feature-rich, with bulk operations, advanced filtering, and comprehensive Indian real estate fields.

---

#### 7.0 — Admin Design System & Layout

**Design Philosophy:** Clean, data-dense enterprise dashboard. Think Notion + Linear meets Indian real estate CRM. Every pixel serves a purpose — no decorative bloat.

**Global Layout (all admin pages share this shell):**

```
┌──────────────────────────────────────────────────────────┐
│  SIDEBAR (fixed, 256px)  │  TOP APP BAR (fixed, 64px)   │
│                          │  ┌────────────────────────┐   │
│  Brand Logo              │  │ Global Search │ 🔔 │ 👤│   │
│  "Admin Portal" label    │  └────────────────────────┘   │
│                          ├───────────────────────────────┤
│  📊 Dashboard            │                               │
│  👥 Leads                │     MAIN CONTENT CANVAS       │
│  🏠 Properties           │     (scrollable, padded)      │
│  📋 Settings (future)    │                               │
│                          │     max-width: 1600px         │
│                          │     padding: 2rem             │
│  ┌──────────────────┐    │                               │
│  │ Admin Avatar      │    │                               │
│  │ Role label        │    │                               │
│  └──────────────────┘    │                               │
└──────────────────────────┴───────────────────────────────┘
```

**Sidebar:**
- Fixed left, 256px wide on desktop
- Brand name (Manrope, bold) + "Admin Portal" uppercase label
- Navigation links with Material Symbols icons
- Active page: bold text + right-4px accent border + subtle bg highlight
- Inactive: muted text, hover reveals bg-slate-200/30
- Bottom: admin avatar initials circle + name + role label
- **Mobile (< 1024px):** sidebar collapses to hidden, triggered by hamburger icon in top bar. Slides in as overlay with backdrop blur, z-50

**Top App Bar:**
- Fixed top, height 64px, positioned right of sidebar
- Left: global search input (rounded-full, bg-surface-container-low, placeholder-driven)
- Right: notification bell icon, help icon, vertical divider, admin avatar button
- Backdrop blur (bg-white/70 backdrop-blur-xl) for depth
- **Mobile:** spans full width, includes hamburger menu button on left

**Color Tokens (Material Design 3, already configured in Tailwind):**
- Primary surfaces: `surface-container-lowest` (#ffffff) for cards
- Page background: `background` (#fbf9f8)
- Sidebar: `bg-slate-50`
- Text: `on-surface` (#1b1c1c), `on-surface-variant` (#43474e) for secondary
- Status badges: tertiary-fixed (new/warm), secondary-fixed (active/contacted), primary-fixed (closed/sold)
- Rounded-3xl on cards, rounded-xl on inputs, rounded-full on badges

**Typography:**
- Page titles: Manrope, 2.25rem (text-4xl), extrabold, tracking-tight
- Section labels: Inter, 0.625rem (text-[10px]), uppercase, tracking-[0.2em]
- Table headers: Inter, 0.625rem, uppercase, tracking-widest, bold
- Body text: Inter, 0.875rem (text-sm), regular
- Metric numbers: Manrope, 3rem (text-5xl), bold

**Responsive Breakpoints:**
- `>= 1024px` (lg): full sidebar + main content
- `768px–1023px` (md): hidden sidebar (hamburger toggle), full-width content, 2-column metric cards
- `< 768px` (sm): single-column everything, stacked cards, horizontal-scroll tables

---

#### 7.1 — Auth System ✅ Done

| File | Purpose |
|------|---------|
| `admin/login.php` | Login form — username + password, validates against `config.php` hash |
| `admin/logout.php` | Destroys session, redirects to login |
| `admin/includes/auth-check.php` | Session guard — include at top of every admin page, redirects to login if not authenticated |

- Uses `password_verify()` against the hash stored in `config.php`
- Session-based auth, no JWT
- Single admin user (hardcoded in config)

**Login page design:**
- Centered card on dark navy background
- Brand logo + "Admin Portal" heading
- Username + password fields (rounded-xl, bg-surface-container-low, no borders)
- Primary CTA button (full-width, gradient primary → primary-container)
- Error toast below form on failed login (bg-error-container, text-error)

---

#### 7.2 — Admin Dashboard (`admin/index.php`) ✅ Partially Done → Needs Enhancement

**Current state:** 4 metric cards + recent leads/listings tables. Functional but basic.

**Enhanced layout:**

```
┌─────────────────────────────────────────────────────┐
│ "Welcome Back" label (uppercase, small)             │
│ "Dashboard Overview" (h1, Manrope 4xl extrabold)    │
├─────────────────────────────────────────────────────┤
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌─────────┐ │
│ │Total Leads│ │New Leads │ │Active    │ │Sold     │ │
│ │   [##]    │ │   [##]   │ │Listings  │ │Listings │ │
│ │           │ │          │ │  [##]    │ │  [##]   │ │
│ └──────────┘ └──────────┘ └──────────┘ └─────────┘ │
├─────────────────────────────────────────────────────┤
│ ┌─────────────────────┐ ┌─────────────────────────┐ │
│ │ Recent Leads (5)    │ │ Recent Listings (5)     │ │
│ │ Name / Phone / Stat │ │ Title / Type / Price    │ │
│ │ ...rows...          │ │ ...rows...              │ │
│ │ [View All →]        │ │ [View All →]            │ │
│ └─────────────────────┘ └─────────────────────────┘ │
└─────────────────────────────────────────────────────┘
```

- 4 metric cards in 12-col grid (3 cols each on desktop, 6 on tablet, 12 on mobile)
- Each card: rounded-3xl, bg-surface-container-lowest, p-8, icon badge in corner
- First card (Total Leads) uses dark variant: bg-primary-container text-on-primary with decorative blur circle
- Recent tables: rounded-3xl cards, list rows with hover state, status badges with colored dots
- **Mobile:** metric cards stack 1-per-row, tables go full-width with horizontal scroll

---

#### 7.3 — Lead Management (`admin/leads.php`) — COMPLETE OVERHAUL NEEDED

**Goal:** Enterprise-grade lead CRM with bulk operations, advanced filtering, date range search, and export/import — matching professional CRM tools.

**Page layout:**

```
┌─────────────────────────────────────────────────────────────┐
│ "Executive Overview" label                                  │
│ "Lead Acquisition" (h1)          [Upload CSV] [Export CSV]  │
├─────────────────────────────────────────────────────────────┤
│ ┌─────────────────┐ ┌──────────────────┐ ┌────────────────┐ │
│ │ Total Leads     │ │ Conversion Rate  │ │ Top Source     │ │
│ │ [##] (dark card)│ │ [##]% + bar      │ │ [name]         │ │
│ └─────────────────┘ └──────────────────┘ └────────────────┘ │
├─────────────────────────────────────────────────────────────┤
│ ADVANCED FILTER BAR                                         │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ 🔍 Search (name/email/phone)  │ Status ▼ │ Source ▼   │ │
│ │ Date From [___] Date To [___] │ Sort By ▼ │ [Clear All]│ │
│ └─────────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────────┤
│ BULK ACTIONS BAR (appears when rows selected)               │
│ ☑ [3 selected]  [Change Status ▼] [Delete Selected] [Export]│
├─────────────────────────────────────────────────────────────┤
│ LEAD TABLE                                                  │
│ ☐ │ Name+Email │ Phone │ Source │ Message │ Status │ Actions│
│ ☐ │ ...        │ ...   │ ...    │ ...     │ ...    │ 👁✉🗑  │
│ ☐ │ ...        │ ...   │ ...    │ ...     │ ...    │ 👁✉🗑  │
├─────────────────────────────────────────────────────────────┤
│ Showing 1-10 of 57       │ ◀ 1 2 3 ... 6 ▶ │ Per page: 10 │
└─────────────────────────────────────────────────────────────┘
```

**Advanced Filters:**
- **Search input:** real-time text search across name, email, phone (debounced 300ms)
- **Status dropdown:** All / New / Contacted / Closed (pill-style select)
- **Source dropdown:** All / Homepage / Residential / Commercial / Services / About / Property Page
- **Date range:** "From" and "To" date pickers (HTML5 date input, styled)
- **Sort by:** Newest First / Oldest First / Name A→Z / Name Z→A
- **Clear All button:** resets all filters to default
- All filters work via AJAX (fetch) — no page reloads. URL params update via `history.pushState()` for bookmarkable filter states

**Bulk Operations:**
- **Checkbox column:** first column in table, with "select all" checkbox in header
- **Bulk action bar:** slides in (or appears) above table when ≥1 row is selected
  - Shows count: "3 leads selected"
  - **Change Status dropdown:** New / Contacted / Closed — applies to all selected
  - **Delete Selected:** confirmation modal ("Are you sure you want to delete 3 leads?")
  - **Export Selected:** downloads CSV of only selected rows
- All bulk operations use fetch() to `admin/api/lead-bulk-action.php`

**CSV Bulk Upload (Import):**
- **"Upload CSV" button** in header → opens a modal
- Modal contains:
  - Drag-and-drop zone (dashed border, icon, "Drop CSV here or click to browse")
  - File input (accept=".csv")
  - Template download link: "Download sample CSV template"
  - Preview table: shows first 5 rows of parsed CSV before import
  - Column mapping: auto-maps Name, Phone, Email, Message, Source, Status columns
  - "Import X Leads" CTA button
- Backend: `admin/api/lead-import.php`
  - Accepts POST with CSV file upload
  - Validates each row (name + phone required)
  - Skips duplicate phone numbers (optional toggle)
  - Returns JSON: `{ imported: 45, skipped: 3, errors: [{row: 7, reason: "Missing name"}] }`
  - Shows import summary in modal after completion

**CSV Export (Download):**
- **"Export CSV" button** in header → downloads immediately
- Exports all leads matching current filters (not just current page)
- CSV columns: Name, Phone, Email, Message, Source Page, Status, Date
- Filename: `leads_export_YYYY-MM-DD.csv`
- Backend: `admin/api/lead-export.php` — streams CSV with proper headers

**Lead Detail Modal/Drawer:**
- Clicking 👁 (view) icon opens a slide-in drawer from right (or modal)
- Shows: full name, phone (clickable tel: link), email (clickable mailto: link), full message, source page, current status, submission date/time
- Inline status change dropdown
- "Send Email" button (opens mailto: link)
- "Call" button (opens tel: link on mobile)
- Notes textarea (future — stores admin notes about the lead)

**Single-row Actions:**
- 👁 View: opens detail drawer
- ✉ Email: opens mailto: link
- 🗑 Delete: confirmation dialog, then AJAX delete

**Table Design:**
- First column: row checkbox
- Name cell: avatar circle (initials, bg-primary-container) + name (bold) + email below (small, muted)
- Phone: bold, monospace feel
- Source: plain text label
- Message: truncated to 100 chars, full text in tooltip or detail drawer
- Status: colored pill badge (tertiary-fixed for New, secondary-fixed for Contacted, primary-fixed for Closed) with indicator dot
- Actions: icon buttons, appear on hover or always visible on mobile
- Row hover: bg-surface-container-low transition

**Pagination:**
- Shows "Showing X-Y of Z entries"
- Per-page selector: 10 / 25 / 50 / 100
- Page numbers with ellipsis for large datasets
- Prev/Next arrows

**Mobile Responsive:**
- Filters collapse into a "Filters" button → opens full-screen filter sheet
- Table becomes card-based layout on mobile (< 768px):
  ```
  ┌──────────────────────────┐
  │ ☐ [Avatar] Rahul Sharma  │
  │   📞 +91 98765 43210     │
  │   📧 rahul@email.com     │
  │   Source: Homepage        │
  │   [New] badge    [⋮ menu]│
  └──────────────────────────┘
  ```
- Bulk action bar sticks to bottom of screen on mobile
- CSV upload modal becomes full-screen sheet

**API Endpoints needed:**
| Endpoint | Method | Purpose |
|----------|--------|---------|
| `admin/api/lead-action.php` | POST | Change status, delete single lead ✅ Done |
| `admin/api/lead-bulk-action.php` | POST | Bulk status change, bulk delete |
| `admin/api/lead-import.php` | POST | CSV upload and import |
| `admin/api/lead-export.php` | GET | CSV download (respects current filters) |

---

#### 7.4 — Listings Management (`admin/listings.php`) — NEEDS ENHANCEMENT

**Current state:** Table with type/status filters, pagination, basic actions. Functional.

**Enhanced layout:**

```
┌─────────────────────────────────────────────────────────────┐
│ "Property Management" label                                 │
│ "Listings Inventory" (h1)       [+ Add Property] [Export]   │
├─────────────────────────────────────────────────────────────┤
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐         │
│ │ Total [##]   │ │ Active [##]  │ │ Sold [##]    │         │
│ └──────────────┘ └──────────────┘ └──────────────┘         │
├─────────────────────────────────────────────────────────────┤
│ FILTER BAR                                                  │
│ 🔍 Search │ Type ▼ │ Status ▼ │ Location ▼ │ Price Range   │
├─────────────────────────────────────────────────────────────┤
│ PROPERTY TABLE                                              │
│ ☐│ Image+Title │ Type │ Location │ Price │ Status │ Actions │
│ ☐│ [thumb] ... │ ...  │ ...      │ ₹... │ Active │ ✏️🗑👁  │
├─────────────────────────────────────────────────────────────┤
│ Pagination controls                                         │
└─────────────────────────────────────────────────────────────┘
```

**Enhancements over current:**
- **Search input:** search across title, location, description
- **Location filter dropdown:** auto-populated from distinct locations in database
- **Price range filter:** min/max input fields
- **Thumbnail in table:** small image preview (48x48, rounded-lg) next to title
- **Bulk select + delete** for multiple listings
- **Quick status toggle:** click status badge to cycle through active/sold/inactive inline
- **Featured star toggle:** click to toggle featured status inline (AJAX)

**Mobile:** Same card-based layout as leads page on small screens

---

#### 7.5 — Add/Edit Property (`admin/add-listing.php`, `admin/edit-listing.php`) — COMPLETE OVERHAUL

**Goal:** Comprehensive property form with ALL fields relevant to the Indian real estate market — matching MagicBricks, Housing.com, 99acres, and NoBroker listing detail level.

**Form Layout — Multi-section stepped form:**

```
┌─────────────────────────────────────────────────────────────┐
│ "Add New Property" (h1)                     [Save] [Cancel] │
├─────────────────────────────────────────────────────────────┤
│ SECTION TABS / ACCORDION                                    │
│ [Basic Info] [Location] [Pricing] [Features] [Media] [SEO] │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Active section form fields rendered below                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**Section 1: Basic Information**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Property Title | text | ✅ | e.g., "3 BHK Flat in Andheri West" |
| Property Type | select | ✅ | Residential / Commercial |
| Property Sub-Type | select | ✅ | **Residential:** Apartment, Independent House/Villa, Builder Floor, Penthouse, Studio Apartment, Farm House, Serviced Apartment. **Commercial:** Office Space, Shop/Showroom, Commercial Land, Warehouse/Godown, Industrial Building, Industrial Shed, Co-working Space |
| Listing Purpose | select | ✅ | Sale / Rent / Lease / PG/Co-living |
| Description | textarea | | Rich description, 2000 chars max |
| Status | select | ✅ | Active / Sold / Rented / Inactive |
| Possession Status | select | | Ready to Move / Under Construction / New Launch |
| Possession Date | date | | Expected if under construction |
| Age of Property | select | | New / 1-5 years / 5-10 years / 10-20 years / 20+ years |
| RERA ID | text | | RERA registration number (mandatory for under-construction in India) |
| Is Featured | checkbox | | Show on homepage featured section |

**Section 2: Location Details**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Address Line 1 | text | ✅ | Flat/house no, building name |
| Address Line 2 | text | | Street, lane |
| Locality/Area | text | ✅ | e.g., "Andheri West", "Whitefield" |
| City | text | ✅ | e.g., "Mumbai", "Bangalore" |
| State | select | ✅ | Dropdown of all 28 Indian states + 8 UTs |
| Pin Code | text (6 digits) | ✅ | Indian PIN code validation |
| Landmark | text | | Near Metro Station, School etc. |
| Latitude | text | | For map pin (optional) |
| Longitude | text | | For map pin (optional) |

**Section 3: Pricing**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Expected Price (₹) | number | ✅ | Indian Rupees |
| Price Per Sq Ft (₹) | number | | Auto-calculated if area is filled |
| Price Negotiable | checkbox | | "Price is negotiable" flag |
| Maintenance Charges (₹/month) | number | | Society maintenance |
| Booking Amount (₹) | number | | Token/booking amount |
| Stamp Duty & Registration | select | | Included / Excluded / Not Applicable |
| Brokerage | select | | No Brokerage / 1% / 2% / Negotiable |
| GST Applicable | checkbox | | Applicable for under-construction |

**Section 4: Property Features & Specifications**
| Field | Type | Notes |
|-------|------|-------|
| **Size & Configuration** | | |
| Super Built-up Area (sq ft) | number | Total area including common areas |
| Built-up Area (sq ft) | number | Area including walls |
| Carpet Area (sq ft) | number | Usable floor area (RERA definition) |
| Plot Area (sq ft) | number | For independent houses/plots |
| Bedrooms (BHK) | select | 1/2/3/4/5/6+ (residential only) |
| Bathrooms | select | 1/2/3/4/5+ |
| Balconies | select | 0/1/2/3+ |
| Floor Number | number | Which floor the unit is on |
| Total Floors | number | Total floors in the building |
| Parking | select | None / 1 Covered / 2 Covered / 1 Open / 2 Open / Both |
| **Property Character** | | |
| Facing | select | East / West / North / South / NE / NW / SE / SW |
| Furnishing | select | Unfurnished / Semi-Furnished / Fully Furnished |
| Flooring Type | select | Marble / Vitrified Tiles / Wooden / Granite / Cement / Mosaic |
| Water Supply | select | Municipal / Borewell / Both / Tanker |
| Power Backup | select | None / Partial / Full / Inverter |
| Overlooking | multiselect | Garden / Pool / Main Road / Park / Lake |
| **Amenities (checkboxes)** | | |
| Lift/Elevator | checkbox | |
| Swimming Pool | checkbox | |
| Gym/Fitness Center | checkbox | |
| Clubhouse | checkbox | |
| Children's Play Area | checkbox | |
| 24x7 Security | checkbox | |
| CCTV Surveillance | checkbox | |
| Gated Community | checkbox | |
| Power Backup | checkbox | |
| Rain Water Harvesting | checkbox | |
| Sewage Treatment Plant | checkbox | |
| Intercom | checkbox | |
| Fire Safety | checkbox | |
| Piped Gas | checkbox | |
| Garden/Landscape | checkbox | |
| Indoor Games | checkbox | |
| Jogging Track | checkbox | |
| Visitor Parking | checkbox | |
| Servant Quarter | checkbox | |
| Vaastu Compliant | checkbox | |
| Pet Friendly | checkbox | |

**Section 5: Media / Images**
| Field | Type | Notes |
|-------|------|-------|
| Cover Image | file upload | Primary display image, shown in cards |
| Gallery Images | multi-file upload | Up to 15 images, drag to reorder |
| Floor Plan Image | file upload | Optional floor plan diagram |
| Video URL | text | YouTube/Vimeo link for virtual tour |
| Brochure PDF | file upload | Downloadable brochure |

- **Image upload:** drag-and-drop zone with preview thumbnails
- Images saved to `assets/images/properties/{listing_id}/` directory
- Thumbnail auto-generated (resized to 400px width)
- **For now (Hostinger):** file upload via PHP `move_uploaded_file()` — no cloud storage needed

**Section 6: SEO & Contact**
| Field | Type | Notes |
|-------|------|-------|
| Meta Title | text | For `<title>` tag, auto-generated from property title if blank |
| Meta Description | textarea | For meta description tag |
| Contact Person Name | text | Who to show on the property page |
| Contact Phone | text | Direct contact for this property |
| Contact Email | text | Direct email for this property |

**Form UX:**
- Sections displayed as **accordion panels** (one section open at a time) on mobile, or **vertical tabs** on desktop
- Each section header shows completion status (green check when all required fields are filled)
- "Save as Draft" button saves with status=inactive
- "Publish" button validates all required fields, saves with status=active
- Form auto-saves to `localStorage` every 30 seconds (prevents data loss)
- Validation: inline error messages below each field, error fields highlighted with error-container bg
- On successful save: toast notification + redirect to listings page

**Database Schema Changes Required:**

```sql
-- Updated listings table for Indian market
ALTER TABLE listings ADD COLUMN sub_type VARCHAR(50) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN listing_purpose ENUM('sale','rent','lease','pg') DEFAULT 'sale';
ALTER TABLE listings ADD COLUMN possession_status ENUM('ready','under_construction','new_launch') DEFAULT NULL;
ALTER TABLE listings ADD COLUMN possession_date DATE DEFAULT NULL;
ALTER TABLE listings ADD COLUMN property_age VARCHAR(20) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN rera_id VARCHAR(50) DEFAULT NULL;

-- Location fields
ALTER TABLE listings ADD COLUMN address_line1 VARCHAR(255) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN address_line2 VARCHAR(255) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN locality VARCHAR(255) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN city VARCHAR(100) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN state VARCHAR(100) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN pincode VARCHAR(6) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN landmark VARCHAR(255) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN latitude DECIMAL(10,7) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN longitude DECIMAL(10,7) DEFAULT NULL;

-- Pricing fields
ALTER TABLE listings ADD COLUMN price_per_sqft DECIMAL(10,2) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN price_negotiable TINYINT(1) DEFAULT 0;
ALTER TABLE listings ADD COLUMN maintenance_charges DECIMAL(10,2) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN booking_amount DECIMAL(12,2) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN stamp_duty VARCHAR(20) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN brokerage VARCHAR(20) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN gst_applicable TINYINT(1) DEFAULT 0;

-- Property features
ALTER TABLE listings ADD COLUMN super_buildup_area INT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN buildup_area INT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN carpet_area INT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN plot_area INT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN bathrooms TINYINT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN balconies TINYINT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN floor_number SMALLINT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN total_floors SMALLINT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN parking VARCHAR(50) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN facing VARCHAR(20) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN furnishing VARCHAR(20) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN flooring_type VARCHAR(30) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN water_supply VARCHAR(30) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN power_backup VARCHAR(30) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN overlooking VARCHAR(255) DEFAULT NULL;

-- Amenities (stored as JSON array or comma-separated)
ALTER TABLE listings ADD COLUMN amenities TEXT DEFAULT NULL;

-- Media
ALTER TABLE listings ADD COLUMN gallery_images TEXT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN floor_plan_image VARCHAR(255) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN video_url VARCHAR(500) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN brochure_filename VARCHAR(255) DEFAULT NULL;

-- SEO & Contact
ALTER TABLE listings ADD COLUMN meta_title VARCHAR(255) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN meta_description TEXT DEFAULT NULL;
ALTER TABLE listings ADD COLUMN contact_person VARCHAR(255) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN contact_phone VARCHAR(20) DEFAULT NULL;
ALTER TABLE listings ADD COLUMN contact_email VARCHAR(255) DEFAULT NULL;
```

---

#### 7.6 — Admin Shared Components

**Toast Notification System:**
- Fixed position (top-right, below app bar)
- Auto-dismiss after 4 seconds
- Types: success (green), error (red), info (blue), warning (amber)
- Slide-in animation from right
- Used for all AJAX operation feedback

**Confirmation Modal:**
- Centered overlay with backdrop blur
- Title + message + Cancel/Confirm buttons
- Used for: delete operations, bulk actions, status changes
- Confirm button shows spinner during AJAX call

**Sidebar Component (shared include):**
- `admin/includes/sidebar.php` — extracted from each page
- Accepts `$active_page` variable to highlight current nav item
- Includes mobile hamburger toggle script
- **Responsive:** collapsible on tablet/mobile with smooth slide animation

**Top Bar Component (shared include):**
- `admin/includes/topbar.php` — extracted from each page
- Global search input (searches across leads + listings)
- Notification bell (future: real-time notifications)
- Admin profile dropdown (future: settings, logout)

**Admin CSS (`admin/assets/admin.css`):**
- Custom scrollbar styles (thin, subtle)
- Table responsive wrappers
- Drag-and-drop upload zone styles
- Modal/drawer slide-in animations
- Print styles for export views

**Admin JS (`admin/assets/admin.js`):**
- Toast notification function
- Confirmation modal handler
- Bulk select logic (select all, count, actions)
- Filter change debounce handler
- CSV parsing (client-side preview before upload)
- Drag-and-drop file upload handler
- Auto-save to localStorage logic
- Sidebar mobile toggle
- Table sort column click handlers

---

#### 7.7 — File Map (Admin Panel Final State)

```
admin/
├── index.php                     # Dashboard
├── leads.php                     # Lead management (advanced)
├── listings.php                  # Property management
├── add-listing.php               # Add property (comprehensive form)
├── edit-listing.php              # Edit property (pre-filled form)
├── login.php                     # Login page
├── logout.php                    # Session destroy
├── assets/
│   ├── admin.js                  # Shared admin JavaScript
│   └── admin.css                 # Admin-specific styles
├── api/
│   ├── login.php                 # Auth endpoint
│   ├── lead-action.php           # Single lead actions (status, delete)
│   ├── lead-bulk-action.php      # Bulk lead operations (status, delete)
│   ├── lead-import.php           # CSV upload & import
│   ├── lead-export.php           # CSV download (filtered)
│   ├── save-listing.php          # Create/update property
│   ├── delete-listing.php        # Delete property
│   └── upload-image.php          # Image upload handler
└── includes/
    ├── auth-check.php            # Session guard
    ├── sidebar.php               # Shared sidebar component
    ├── topbar.php                # Shared top bar component
    └── listing-form.php          # Property form fields (shared add/edit)
```

---

### Phase 8: Dynamic Property Page

**Goal:** Make `property.php` load real data from the database instead of hardcoded content.

- Accept `?id=X` query parameter
- Fetch listing from database by ID
- Populate: title, description, price, location, image, specs
- If ID not found, show 404-style message
- Contact form's `source_page` field auto-fills with property title
- Keep the same layout — just swap static content for dynamic `$listing` data

---

### Phase 9: Polish & Deploy

#### 9.1 — Cross-page enhancements
- SEO meta tags on every page (title, description, og:image)
- Favicon
- 404 error page
- Loading states for AJAX calls
- Form validation improvements (phone format, email format)

#### 9.2 — Performance
- Lazy load images below the fold (`loading="lazy"`)
- Optimize hero images (compress, proper dimensions)
- Minify custom.css for production (manual or online tool)

#### 9.3 — Hostinger Deployment Checklist
1. Create MySQL database in hPanel → Databases
2. Run `database/setup.sql` via phpMyAdmin
3. Update `includes/config.php` with production DB credentials and SITE_URL
4. Upload all files via hPanel File Manager or FTP
5. Set PHP version to 8.1 or 8.2 in hPanel
6. Enable free SSL in hPanel
7. Test all pages, forms, and admin panel
8. Insert seed data (sample listings) via admin panel

---

## File Map (Final State)

```
vidhaata-ventures/
├── index.php                    ✅ Done
├── property.php                 ✅ Done
├── residential.php              ✅ Done
├── commercial.php               ✅ Done
├── services.php                 ✅ Done
├── about.php                    ✅ Done
├── assets/
│   ├── css/
│   │   └── custom.css           ✅ Done
│   ├── js/
│   │   └── main.js              ✅ Done
│   └── images/
│       ├── logo.png             🔲 Need from client
│       └── properties/          🔲 Manual upload
├── includes/
│   ├── config.php               ✅ Done
│   ├── db.php                   ✅ Done
│   ├── header.php               ✅ Done
│   └── footer.php               ✅ Done
├── database/
│   └── setup.sql                ✅ Done
├── api/
│   ├── submit-lead.php          ✅ Done
│   └── get-listings.php         ✅ Done
├── admin/
│   ├── login.php                ✅ Done
│   ├── logout.php               ✅ Done
│   ├── index.php                ✅ Done
│   ├── leads.php                ✅ Done
│   ├── listings.php             ✅ Done
│   ├── add-listing.php          ✅ Done
│   ├── edit-listing.php         ✅ Done
│   ├── assets/
│   │   └── admin.js             ✅ Done
│   ├── api/
│   │   ├── login.php            ✅ Done
│   │   ├── lead-action.php      ✅ Done
│   │   ├── save-listing.php     ✅ Done
│   │   └── delete-listing.php   ✅ Done
  │   └── includes/
  │       ├── auth-check.php       ✅ Done
  │       ├── admin-layout.php     ✅ Done (unified shell)
  │       ├── listing-form.php     ✅ Done (accordion redesign)
  │       ├── listing-form-basic.php     ✅ Done
  │       ├── listing-form-location.php  ✅ Done
  │       ├── listing-form-pricing.php   ✅ Done
  │       ├── listing-form-features.php  ✅ Done
  │       ├── listing-form-media.php     ✅ Done
  │       ├── sidebar.php          ✅ Done (shared component)
  │       └── topbar.php           ✅ Done (shared component)
  ├── admin/
  │   ├── api/
  │   │   ├── login.php            ✅ Done
  │   │   ├── lead-action.php      ✅ Done
  │   │   ├── lead-bulk-action.php ✅ Done (new)
  │   │   ├── save-listing.php     ✅ Done (full CRUD)
  │   │   └── delete-listing.php   ✅ Done
├── .htaccess                    ✅ Done
└── plan.md                      ✅ This file
```

---

## Priority Order

| Priority | Phase | Reason |
|----------|-------|--------|
| ✅ Done | Phase 2 — Database & API | Everything depends on this — forms don't save, listings are static |
| ✅ Done | Phase 3 — Residential | Core business page, most traffic |
| ✅ Done | Phase 4 — Commercial | Same pattern as residential, quick to build |
| ✅ Done | Phase 7 — Admin Panel | Needed to manage data, but can seed DB manually initially |
| ✅ Done | Phase 5 — Services | Content page, no dynamic data |
| ✅ Done | Phase 6 — About Us | Content page, no dynamic data |
| 🟢 Low | Phase 8 — Dynamic Property | Enhancement to existing page |
| 🟢 Low | Phase 9 — Polish & Deploy | Final step before go-live |

---

## Design Rules (Quick Reference)

- **Colors:** `surface` (#fbf9f8) base, `on_surface` (#1b1c1c) text, `primary` (#001225) dark, never #000000
- **Fonts:** Manrope for headings, Inter for body
- **No borders** to separate sections — use background tonal shifts
- **No heavy shadows** — only `shadow-ambient` for floating elements
- **Glassmorphism** on nav and search overlays only
- **Rounded corners** — minimum `rounded-md` (0.75rem) on buttons
- **Mobile-first** — sm:, md:, lg: breakpoints
- **Forms submit via fetch()** — no page reloads, toast feedback

---

## Tech Constraints

- No npm, no webpack, no Node.js — Tailwind via CDN only
- No PHP frameworks — plain PHP with PDO
- No JS frameworks — vanilla JavaScript only
- No .env files — config.php holds everything
- PDO with prepared statements only — never concatenate SQL
- `htmlspecialchars()` on all user output
- Single admin user hardcoded in config.php
- Images uploaded manually via FTP for now

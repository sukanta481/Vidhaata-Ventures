# Project: Vidhaata Ventures — Real Estate Website

## What this is
A small business real estate website for Vidhaata Ventures.
It displays residential and commercial property listings, company services,
and captures leads via a contact form. An admin panel manages leads and listings.

## Current status
Greenfield — building from scratch.

---

## Pages
- `index.html` — Homepage / hero
- `residential.html` — Residential property listings
- `commercial.html` — Commercial property listings
- `services.html` — Services offered
- `about.html` — About Us
- Contact is a button/modal (no separate page) that opens a lead form

## Admin pages (protected)
- `admin/index.php` — Dashboard (leads count, listing count)
- `admin/leads.php` — View, filter, delete leads
- `admin/listings.php` — Add, edit, delete property listings
- `admin/login.php` — Admin login (session-based auth)

---

## Stack
- Frontend: HTML5, Tailwind CSS (CDN), vanilla JavaScript
- Backend: PHP 8.x
- Database: MySQL (via MySQLi or PDO — use PDO only)
- Hosting: Hostinger shared hosting
- No build tools — no npm, no webpack, no Node.js
- Tailwind via CDN: <script src="https://cdn.tailwindcss.com"></script>

---

## Folder structure
```
vidhaata-ventures/
├── index.html
├── residential.html
├── commercial.html
├── services.html
├── about.html
├── assets/
│   ├── css/
│   │   └── custom.css        # Only for things Tailwind can't handle
│   ├── js/
│   │   └── main.js           # Shared JS (nav, modal, form submit)
│   └── images/
│       ├── logo.png
│       └── properties/       # Property images
├── includes/
│   ├── db.php                # PDO connection (credentials via config)
│   ├── header.php            # Shared nav HTML
│   ├── footer.php            # Shared footer HTML
│   └── config.php            # DB credentials, site settings (gitignored)
├── api/
│   ├── submit-lead.php       # POST: saves contact form lead
│   └── get-listings.php      # GET: returns listings as JSON
├── admin/
│   ├── login.php
│   ├── logout.php
│   ├── index.php             # Dashboard
│   ├── leads.php
│   ├── listings.php
│   ├── add-listing.php
│   ├── edit-listing.php
│   └── includes/
│       └── auth-check.php    # Session guard — include at top of every admin page
└── .htaccess                 # Block direct access to includes/, api/ from browser
```

---

## Database (MySQL)
Two tables only. Keep it simple.

### `listings`
```sql
CREATE TABLE listings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('residential', 'commercial') NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(12,2),
  location VARCHAR(255),
  bedrooms TINYINT DEFAULT NULL,       -- NULL for commercial
  area_sqft INT,
  image_filename VARCHAR(255),
  is_featured TINYINT(1) DEFAULT 0,
  status ENUM('active', 'sold', 'inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### `leads`
```sql
CREATE TABLE leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(255),
  message TEXT,
  source_page VARCHAR(100),            -- which page they submitted from
  status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Design system (from DESIGN.md — follow strictly)

### Philosophy: "The Digital Curator"
This is an editorial, gallery-style UI — not a utility grid. Think luxury real estate magazine,
not property database. Generous whitespace, tonal layering, and subtle depth over hard borders.

### Color usage
- Page background: always `surface` (#fbf9f8)
- Section backgrounds: use `surface_low` or `surface_mid` — never draw a border to separate sections
- Cards and modals: `surface_card` (#ffffff) — they "float" above the page by tonal contrast alone
- Body text: `on_surface` (#1b1c1c) — never pure #000000
- Dark hero/CTA backgrounds: `primary` (#001225)
- CTA buttons: gradient from `primary` (#001225) to `primary_container` (#022747)

### The No-Line Rule
**Never use `border` or `divide` Tailwind classes to section content areas.**
Define boundaries through:
- Background color shifts between `surface`, `surface_low`, `surface_mid`
- Whitespace — if spacing looks enough, double it
- Ghost border fallback ONLY for accessibility: `border border-outline_variant` (15% opacity)

### Typography rules
- All display headings and hero text: `font-display` (Manrope)
- All body copy, labels, UI elements: `font-body` (Inter)
- Hero statements: `text-display-lg font-display` — 3.5rem, bold
- Section headings: `text-headline-lg font-display`
- Card headings: `text-headline-md font-display`
- Body copy: `text-body-md font-body` — 0.875rem
- Category/metadata labels: `text-label-sm font-body` — uppercase, tracked, 0.6875rem

### Elevation and depth
- Do NOT use standard Tailwind shadow-md, shadow-lg etc. — they are too heavy
- Floating elements only (drawers, modals): `shadow-ambient`
- Card lift achieved purely by placing bg-surface_card on top of bg-surface_low — no shadow needed
- Allow property images to slightly overlap section backgrounds for architectural depth effect

### Glassmorphism — nav and search overlays only
Add this to custom.css:
```css
.glass {
  background: rgba(246, 243, 242, 0.70);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
}
```
Use `.glass` class on the sticky nav and hero search bar only.

### Component patterns

**Primary CTA button:**
```html
<button class="bg-gradient-to-b from-primary to-primary_container text-on_primary
               px-6 py-3 rounded-md font-body font-medium tracking-wide
               hover:opacity-90 transition-opacity">
  Book a Visit
</button>
```

**Secondary button:**
```html
<button class="bg-surface_low text-on_surface px-6 py-3 rounded-md font-body
               hover:bg-surface_mid transition-colors">
  Save Search
</button>
```

**Tertiary text link:**
```html
<a class="text-primary font-body underline-offset-4 hover:underline">View All →</a>
```

**Property card:**
- Background: bg-surface_card, rounded-xl on the image (1.5rem), shadow-ambient on the card
- No divider lines between price / address / specs — use gap-6 only
- Property type chip: `bg-accent text-on_surface text-label-sm px-3 py-1 rounded-full`

**Input / search field:**
- No border by default — bg-surface_low fill with rounded-md
- Focus state: ring-1 ring-primary (not a border)
- Error state: bg-error_container text-error — no red border

### Design Do's
- Double margin if it looks right — luxury means space
- Let images overlap section edges for depth
- Align components to headline baselines

### Design Don'ts
- Never #000000 — use on_surface (#1b1c1c) always
- Never wrap content in a solid-bordered box — use background tonal shift
- Never shadow-md or heavier — only shadow-ambient for floating elements
- Never sharp corners on buttons — minimum rounded-md (0.75rem)

---

## Coding conventions

### General
- All PHP files use PDO with prepared statements — never string-concatenate SQL
- No inline JavaScript in HTML files — all JS goes in assets/js/main.js
- No inline styles — use Tailwind classes only; custom.css only as last resort
- All form submissions are via fetch() (AJAX) — no full page reloads for forms
- Use PHP sessions for admin auth — no JWT, no cookies beyond session

### PHP
- Every admin page starts with: `require_once '../includes/auth-check.php';`
- db.php returns a $pdo object — always include it as: `require_once '../includes/db.php';`
- Sanitize all output with htmlspecialchars() before echoing user data
- Return JSON from api/ files: always set header('Content-Type: application/json')

### HTML / Tailwind
- Mobile-first — use sm:, md:, lg: breakpoints
- Tailwind config block goes in every HTML <head> (copy exactly):
```js
tailwind.config = { theme: { extend: {
  colors: {
    primary:          '#001225',   // deep navy — dark backgrounds, headings
    primary_container:'#022747',   // slightly lighter navy — CTA buttons, gradients
    surface:          '#fbf9f8',   // page base background
    surface_low:      '#f6f3f2',   // secondary sections
    surface_mid:      '#f0eded',   // grouped content areas
    surface_card:     '#ffffff',   // elevated cards / modals
    on_surface:       '#1b1c1c',   // body text (never pure black)
    on_primary:       '#ffffff',   // text on dark navy backgrounds
    accent:           '#ffdbc9',   // warm chip/tag highlights (luxury feel)
    outline_variant:  'rgba(27,28,28,0.15)', // ghost border fallback only
    error:            '#ba1a1a',   // error text
    error_container:  '#ffdad6',   // error field background
  },
  fontFamily: {
    display: ['Manrope', 'sans-serif'],   // headings, hero
    body:    ['Inter', 'sans-serif'],      // UI, body text
  },
  fontSize: {
    'display-lg': ['3.5rem',  { lineHeight: '1.1', fontWeight: '700' }],
    'headline-lg':['2rem',    { lineHeight: '1.2', fontWeight: '600' }],
    'headline-md':['1.5rem',  { lineHeight: '1.3', fontWeight: '600' }],
    'body-md':    ['0.875rem',{ lineHeight: '1.6', fontWeight: '400' }],
    'label-sm':   ['0.6875rem',{ lineHeight: '1.4', fontWeight: '700',
                                 letterSpacing: '0.05em', textTransform:'uppercase' }],
  },
  borderRadius: {
    md: '0.75rem',
    xl: '1.5rem',    // property card images
  },
  boxShadow: {
    ambient: '0px 20px 40px rgba(27, 28, 28, 0.06)',  // floating drawers only
  },
}}}
```
- Google Fonts link (add to every <head> before Tailwind):
  `<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">`
- Navigation is the same on all pages — copy from header.php

### JavaScript
- Use fetch() for all API calls — no jQuery, no axios
- Form validation: check required fields before fetch, show inline error messages
- No alert() — use a toast/banner div for user feedback

---

## Do NOT
- Do not use any PHP framework (no Laravel, no CodeIgniter) — plain PHP only
- Do not use npm or any build step — this deploys as static files + PHP
- Do not store passwords in plain text — use password_hash() / password_verify()
- Do not put DB credentials in any file that is not config.php
- Do not use GET requests to submit form data or leads
- Do not create new database tables without asking first
- Do not use JavaScript frameworks (no React, no Vue) — vanilla JS only
- Do not add .env files — Hostinger shared hosting does not support them reliably

---

## Hostinger deployment notes
- PHP version: set to 8.1 or 8.2 in Hostinger hPanel
- MySQL: created via hPanel → Databases; credentials go into includes/config.php
- File upload via hPanel File Manager or FTP (FileZilla)
- .htaccess is supported — use it to block /includes and /api from direct browser access
- No SSH access assumed — keep everything deployable via FTP drag-and-drop

---

## Known constraints
- No SSL-related PHP config needed — Hostinger provides free SSL, just enable in hPanel
- Image uploads handled manually (FTP) for now — no file upload feature in admin yet
- Admin has a single hardcoded user in config.php (no user management table needed)

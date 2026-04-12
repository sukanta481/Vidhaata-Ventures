# Design System Document: Architectural Editorial

## 1. Overview & Creative North Star: "The Digital Curator"
The objective of this design system is to transcend the "utility-first" look of standard real estate platforms and enter the realm of high-end editorial curation. We are not building a database; we are designing a gallery of aspirations.

**Creative North Star: The Digital Curator**
This system treats the screen as a physical space. It utilizes intentional asymmetry, generous negative space (white space is a luxury commodity), and a sophisticated layering of tonal surfaces to establish trust through design authority. We break the rigid, boxed-in grid of traditional web design by allowing elements to overlap and bleed across sections, creating a fluid, bespoke experience that mirrors the flow of a premium architectural tour.

---

## 2. Colors
Our palette is anchored in deep, authoritative tones of charcoal and navy, balanced by soft, architectural neutrals and high-energy accents for action.

### The "No-Line" Rule
To maintain a high-end feel, **the use of 1px solid borders for sectioning or containment is strictly prohibited.** Boundaries must be defined through:
- **Background Tonal Shifts:** Use `surface_container_low` against a `surface` background to define a new functional area.
- **Intentional Spacing:** Use the spacing scale to create "invisible" barriers.

### Surface Hierarchy & Nesting
Treat the UI as a series of stacked, physical layers. 
- **Base Layer:** `surface` (#fbf9f8)
- **Secondary Areas:** `surface_container_low` (#f6f3f2) or `surface_container` (#f0eded)
- **Elevated Cards/Modals:** `surface_container_lowest` (#ffffff) to make them "pop" forward without relying on heavy shadows.

### The "Glass & Gradient" Rule
For floating elements (navigation bars, search overlays), use **Glassmorphism**. Combine `surface_container_low` at 70% opacity with a `backdrop-blur` of 20px. 
To add "visual soul," use subtle linear gradients for primary CTAs or hero sections, transitioning from `primary` (#001225) to `primary_container` (#022747). This creates depth that flat hex codes cannot replicate.

---

## 3. Typography
We utilize a high-contrast typographic scale to create an editorial hierarchy.

- **Display & Headlines (Manrope):** Geometric, modern, and commanding. Use `display-lg` (3.5rem) for hero statements to create an immediate sense of scale.
- **UI & Body (Inter):** Highly legible and professional. Use `body-md` (0.875rem) for general information to maintain a clean, non-cluttered appearance.
- **Labels (Inter Bold/Uppercase):** Use `label-sm` (0.6875rem) with increased letter-spacing (0.05em) for category tags or metadata. This mimics luxury print magazine layouts.

---

## 4. Elevation & Depth
Depth in this system is organic, not artificial. We prioritize tonal layering over traditional drop shadows.

- **The Layering Principle:** Place a `surface_container_lowest` card atop a `surface_container_high` background. The subtle shift in hex value provides a natural lift.
- **Ambient Shadows:** If a floating effect is required (e.g., a "Contact Agent" drawer), use an extra-diffused shadow.
    - *Shadow:* `0px 20px 40px rgba(27, 28, 28, 0.06)` (using `on_surface` color at low opacity).
- **The "Ghost Border" Fallback:** If accessibility requires a stroke, use the `outline_variant` token at 15% opacity. This provides a "suggestion" of a container without breaking the editorial flow.

---

## 5. Components

### Buttons
- **Primary:** Background: `primary_container` (#022747); Text: `on_primary`. Shape: `md` (0.75rem). Avoid sharp corners.
- **Secondary:** Background: `secondary_fixed`; Text: `on_secondary_fixed`. Used for "Save Search" or "Filter."
- **Tertiary:** Background: Transparent; Text: `primary`. Use for "View All" links with a subtle `primary` underline that expands on hover.

### Property Cards
Forbid divider lines. Instead:
1. Use `surface_container_lowest` for the card body.
2. Use `xl` (1.5rem) rounded corners for images to soften the aesthetic.
3. Separate property price, address, and specs using vertical white space (1.5rem gap) rather than horizontal lines.

### Inputs & Search
- **The "Hero Search":** A floating bar using the Glassmorphism rule. Use `label-md` for floating labels that shrink into the `outline` area when active. 
- **States:** Error states use `error` (#ba1a1a) text but keep the container in `error_container` with a `none` border to avoid a "shouting" UI.

### Chips (Property Tags)
Use `tertiary_fixed` (#ffdbc9) for "New Construction" or "Luxury" tags. The warm tone contrasts beautifully against the `primary` blue, drawing the eye to key selling points without using "Alert" colors.

---

## 6. Do's and Don'ts

### Do:
- **Use "Breathing Room":** If you think there is enough margin, double it. High-end real estate is about the luxury of space.
- **Layer Elements:** Allow a property image to slightly overlap a `surface_container` background to create a sense of depth and architectural intent.
- **Align to Typography:** Let the baseline of your `headline-lg` dictate the alignment of adjacent components.

### Don't:
- **Never use 100% Black:** Use `on_surface` (#1b1c1c) for text and `primary` (#001225) for dark backgrounds. Pure black feels "un-designed."
- **Avoid "The Box":** Do not wrap every piece of content in a bordered container. Use background shifts (`surface_container_low`) to group items.
- **No Heavy Shadows:** If a shadow looks like a shadow, it’s too dark. It should look like a soft glow or a natural occlusion of light.
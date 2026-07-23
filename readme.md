# Filter Gallery - Responsive WordPress Plugin

[![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-blue.svg)](https://wordpress.org/plugins/filter-gallery/)
[![License](https://img.shields.io/badge/License-GPLv2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.1.3-orange.svg)](https://github.com/FARAZFRANK/filter-gallery/releases)

**Filter Gallery** is a lightweight, high-performance WordPress plugin designed to create stunning, filterable image galleries and portfolios. Built with a modern React-based admin interface and optimized Isotope filtering, it offers a premium experience for both developers and site owners.

---

## 🚀 Key Features

- **Unlimited Galleries & Shortcodes**: Create as many galleries as needed, using `[filter-gallery]` or `[ufg]` shortcodes.
- **Responsive Columns & Masonry Layouts**: Adapts grid and display columns automatically to device viewports with Masonry support.
- **Easy Drag-and-Drop Management**: Simple interface for ordering images (ID and manual sorting).
- **Standard Lightbox Overlay**: Includes a built-in lightbox with title overlays.
- **Category Filtering & Controls**: Set default loaded category, configure "All" button visibility, customize label, or assign custom icons.
- **Media Styling & Customizer**: Adjust grid padding, border spacing, customize image titles and descriptions (with length limits) inside settings.
- **Speed Optimized**: No frontend bloat, enqueued scripts without Bootstrap or FontAwesome.
- **Import/Export Dashboard**: Migrate gallery layout configurations to other setups effortlessly.

## 🛠 Installation

1. Download the plugin and extract the files.
2. Upload the `filter-gallery` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to **Filter Gallery** in your admin sidebar to create your first gallery.
5. Copy the generated shortcode and paste it into any page or post.

## 📖 Usage

### Shortcodes
- **New Shortcode**: `[filter-gallery id="1"]`
- **Legacy Shortcode**: `[ufg id="1"]`

### Admin Management
The new dashboard allows you to:
- Add and organize image filters (categories).
- Upload and drag-to-reorder gallery images.
- Configure responsive column layouts.
- Preview your changes instantly.

## 💎 Pro Version

Upgrade to **Filter Gallery Pro** for even more powerful features:
- **Deep Hierarchical Nested Filters**: Create multi-level filter nesting categories (up to 5 levels).
- **Custom Redirect Links**: Link gallery images directly to external or internal URLs with "Read More" button customizer.
- **Searchable Icons Picker**: Assign FontAwesome icons easily to filter tags using a searchable selection picker.
- **Dynamic AJAX Loading**: Speed up large portfolios with AJAX Asynchronous Pagination, AJAX Load More, and CSS preloaders.
- **Advanced Columns & Layouts**: Fully customize grid columns (1 to 6) and enable Justified Grid layout options.
- **Category Styling & Colors**: Dedicated level-by-level color pickers for category text, background, hovers, and active states.
- **Advanced Image Sorting**: Sort images by Random shuffle, Title, ID, or manual drag-and-drop.
- **Customizable Lightbox**: Show full media descriptions and slide index numbering in the modal.
- **15+ Hover Animations**: Select from a premium list of hover transitions and zoom overlays.
- **Premium Utility Tools**: Duplicate filters, bulk delete media, and priority customer support desk assistance.

👉 [Get Filter Gallery Pro](https://wpfrank.com/plugins/filter-gallery-pro)

## 🏗 Developer Documentation

### Hooks & Filters
*Documentation coming soon...*

## 📜 License
This project is licensed under the GPL v2 or later.

## 📝 Changelog

### 1.1.3 (2026-07-23)
- **Feature**: Defaulted button padding size to 'small' (8px 16px) globally and for legacy migrations.
- **Feature**: Clamped migrated columns settings to maximum Free limits (Tablet & Mobile Landscape max 3, Mobile Portrait max 2) to prevent locked PRO status on Free version.
- **Feature**: Ensured Image Title is enabled and Image Description is disabled by default for migrated galleries.
- **Feature**: Set default hover text color of Level 1 (Parent) buttons to '#000000' (black) globally.
- **Fix**: Improved frontend fallback to default WordPress media titles when custom image titles are empty, and prevented empty title/description boxes.
- **Fix**: Fixed incorrect redirect to 'filter-gallery-pro' page on multiple gallery deletions.

### 1.1.2 (2026-07-22)
- **Feature**: Implemented automatic data migration and settings normalization for legacy v0.2.3 galleries.
- **Feature**: Added responsive, large tab navigation controls and shortcode copy badge scaling.
- **Fix**: Resolved tab buttons and main header layout container overlapping on mobile screens by implementing a custom sticky floating-pill design.
- **Fix**: Prevented layout cuts on mobile screen widths (< 640px) by hiding tab navigation text and separators.
- **Fix**: Improved filters manager layout on mobile screens by allowing horizontal scrolling and wrapping.
- **Fix**: Optimized the "Confirm & Deploy Changes" save button to fit as a sleek single-line pill on mobile.
- **Fix**: Solved jQuery selector crash on frontend digit-starting DOM IDs and added defensive array checks.
- **Security Fix**: Fixed output escaping for `UFG_VERSION`, `admin_url()`, `$indent`, `$prefix`, and `$get_count_html` to satisfy WordPress guidelines security rules.
- **Security Fix**: Added input sanitization for `$_POST['ufg_gallery_id']` on direct assignment.
- **Security Fix**: Resolved various PHPCS and security guidelines warnings across all files.

### 1.1.1 (2026-07-21)
- **Feature Lock**: Restricted column layout options in Free version to standard limits (Desktop: 3, 4, 6 columns; Tablet: 1, 2, 3 columns; Mobile Landscape: 1, 2, 3 columns; Mobile Portrait: 1, 2 columns), marking other values as PRO.
- **Feature Lock**: Limited sorting options to ID-based ascending/descending and None/Manual, disabling title-based and random sorting options as PRO.
- **Feature Lock**: Marked Lightbox Description and Image Numbering as PRO features.
- **Feature Lock**: Moved Custom CSS setting, Load More (Lazy Loading) pagination, and Read More link settings to PRO.
- **Feature Lock**: Restricted filter nesting / hierarchy parent relationships to the PRO version.
- **Bug Fix**: Fixed React rendering crash in the admin panel.

---
Developed by [FARAZFRANK](https://wpfrank.com/)

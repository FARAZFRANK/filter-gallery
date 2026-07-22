=== Filter Gallery ===
Contributors: awordpresslife, razipathhan, hanif0991, muhammadshahid, fkfaisalkhan007, sharikkhan007, zishlife, FARAZFRANK
Donate link: https://wpfrank.com/
Tags: responsive, filter gallery, portfolio, image gallery, masonry
Requires at least: 6.0
Tested up to: 7.0.2
Stable tag: 1.1.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Build a responsive filter gallery for your portfolio. Organize images with filters in a stunning grid or masonry layout easily.

== Description ==

The **Filter Gallery** plugin makes it simple to create beautiful, organized image displays on your WordPress site. Whether you need a professional portfolio showcase, a product catalog, or a creative photography album, this tool lets you sort content dynamically without page reloads.

**Check Free Version Demo:** [Filter Gallery](https://wpfrank.com/demo/filter-gallery-free-wordpress-pluign/)
**Check Pro Version Demo:** [Filter Gallery Pro](https://wpfrank.com/demo/filter-gallery-pro/)
**More About Pro:** [Filter Gallery Pro Features](https://wpfrank.com/wordpress-plugins/filter-gallery-pro/)
**Where To Buy:** [Buy Filter Gallery Pro](https://wpfrank.com/account/signup/filter-gallery-pro)

= Video Tutorials =

https://www.youtube.com/watch?v=tV4AvFGgC2U

We designed this plugin to be intuitive and lightweight. You can upload images, assign them to categories (filters), and let your visitors browse your work seamlessly. The gallery automatically adapts to desktops, tablets, and mobile screens, ensuring a perfect viewing experience everywhere.

= Key Features =

*   **Unlimited Galleries & Shortcodes:** Create as many galleries as needed, using `[filter-gallery]` or `[ufg]` shortcodes.
*   **Responsive Columns & Masonry Layouts:** Adapts grid and display columns automatically to device viewports with Masonry support.
*   **Easy Drag-and-Drop Management:** Simple interface for ordering images (ID and manual sorting).
*   **Standard Lightbox Overlay:** Includes a built-in lightbox with title overlays.
*   **Category Filtering & Controls:** Set default loaded category, configure "All" button visibility, customize label, or assign custom icons.
*   **Media Styling & Customizer:** Adjust grid padding, border spacing, customize image titles and descriptions (with length limits) inside settings.
*   **Speed Optimized:** No frontend bloat, enqueued scripts without Bootstrap or FontAwesome.
*   **Import/Export Dashboard:** Migrate gallery layout configurations to other setups effortlessly.

= Pro Version Features =

Unlock the full potential of your portfolios with the Pro Version:

*   **Deep Hierarchical Nested Filters:** Create multi-level filter nesting categories (up to 5 levels).
*   **Custom Redirect Links:** Link gallery images directly to external or internal URLs with "Read More" button customizer.
*   **Searchable Icons Picker:** Assign FontAwesome icons easily to filter tags using a searchable selection picker.
*   **Dynamic AJAX Loading:** Speed up large portfolios with AJAX Asynchronous Pagination, AJAX Load More, and CSS preloaders.
*   **Advanced Columns & Layouts:** Fully customize grid columns (1 to 6) and enable Justified Grid layout options.
*   **Category Styling & Colors:** Dedicated level-by-level color pickers for category text, background, hovers, and active states.
*   **Advanced Image Sorting:** Sort images by Random shuffle, Title, ID, or manual drag-and-drop.
*   **Customizable Lightbox:** Show full media descriptions and slide index numbering in the modal.
*   **15+ Hover Animations:** Select from a premium list of hover transitions and zoom overlays.
*   **Premium Utility Tools:** Duplicate filters, bulk delete media, and priority customer support desk assistance.

== Installation ==

1.  Upload the `filter-gallery` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Navigate to the "Filter Gallery" menu to create your first gallery.
4.  Add filters (categories) and upload your images.
5.  Copy the generated shortcode and paste it into any Post or Page.

== Frequently Asked Questions ==

= How do I create a new filter? =
Go to the gallery edit page. You will see a "Filters" section where you can add new filter names. Once added, you can assign these filters to your uploaded images.

= Is the gallery responsive? =
Yes, the gallery grid is fully responsive and will adjust the number of columns based on the viewing device (mobile, tablet, or desktop).

= Can I link images to other pages? =
In the free version, images open in a lightbox. The Pro version allows you to link images to custom external or internal URLs.

= Does this modify my theme files? =
No, this plugin works independently of your theme. It uses its own styles and scripts which are loaded only when the shortcode is used.

= Can I place multiple galleries on one page? =
No, you can add only one gallery to a single page or post.

= Does this plugin work with page builders like Elementor, Divi, or Beaver Builder? =
Absolutely! Since the gallery uses shortcodes, you can easily paste the shortcode into any "Shortcode" or "Text" widget within your favorite page builder.

= How do I reorder my images? =
The plugin features a simple drag-and-drop interface. Just drag your images into your preferred order in the admin panel and save.

= How do I upgrade to the Pro version? =
You can visit our website at [wpfrank.com](https://wpfrank.com/) to purchase the Pro version.

== Screenshots ==

1.  Animal Gallery: A sample of how the filterable gallery looks on the frontend.
2.  Art Gallery
3.  Portrait Gallery
4.  Sports Gallery Preview
5.  How to create a filter gallery?**

== Changelog ==

= 1.1.2 =
* Implemented automatic data migration and settings normalization for legacy v0.2.3 galleries.
* Added responsive, large tab navigation controls and shortcode copy badge scaling.
* Resolved tab buttons and main header layout container overlapping on mobile screens by implementing a custom sticky floating-pill design.
* Prevented layout cuts on mobile screen widths (< 640px) by hiding tab navigation text and separators.
* Improved filters manager layout on mobile screens by allowing horizontal scrolling and wrapping.
* Optimized the "Confirm & Deploy Changes" save button to fit as a sleek single-line pill on mobile.
* Solved jQuery selector crash on frontend digit-starting DOM IDs and added defensive array checks.
* Fixed output escaping for UFG_VERSION, admin_url(), $indent, $prefix, and $get_count_html for security compliance.
* Added input sanitization for $_POST['ufg_gallery_id'] on direct assignment.
* Resolved various PHPCS and security guidelines warnings across all files.

= 1.1.1 =
* Restricted column layout selections to standard limits in Free version.
* Limited sorting options to ID-based ascending/descending and None/Manual.
* Fixed React rendering crash in the admin panel.

= 1.1.0 =
* Replaced legacy PHP admin templates with a modern React-based interface.
* Removed bundled third-party libraries (Bootstrap, Font Awesome) for 2026 guideline compliance.
* Implemented non-destructive data migration system for backward compatibility.
* Improved security with better sanitization and nonce verification.
* Updated minimum requirements to WordPress 6.0 and PHP 7.4.

= 1.0.0 =
* Initial alignment with modern architecture patterns.

= 0.2.3 =
* Fixed Requires PHP header mismatch between readme and plugin file
* Added proper input sanitization for POST data
* Added object caching for database queries
* Code improvements to meet WordPress coding standards

= 0.2.2 =
* Security patches implemented
* Updated plugin guidelines compliance

= 0.2.1 =
* Renamed plugin to comply with trademark policy
* Tested with WordPress 6.8.1

= 0.2.0 =
* New translations added:
    * Arabic (ar)
    * Chinese (zh_CN)
    * Finnish (fi)
    * French (fr_FR)
    * German (de_DE)
    * Hindi (hi_IN)
    * Hungarian (hu_HU)

= 0.1.9 =
* Fixed: Filters not showing on front end
* Fixed: WP Debug issue

= 0.1.8 =
* Fixed: Lightbox functionality issues

== Upgrade Notice ==

= 1.0.0 =
* This major update modernizes the admin interface and improves security. Your existing gallery data is preserved.
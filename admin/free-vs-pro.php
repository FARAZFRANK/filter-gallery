<?php
if (!defined('ABSPATH')) {
    exit;
}
$ufg_loader_title = __('Loading Free vs Pro Comparison...', 'filter-gallery');
$ufg_loader_subtitle = __('Please wait while features comparison list loads', 'filter-gallery');
require_once 'loader.php';
?>
<div class="wrap ufg-pricing-page" style="margin: 30px 20px 0 10px;">
    


    <div class="max-w-7xl">
        <!-- Header Section -->
        <div class="text-center mb-16" style="margin-top: 20px;">
            <h1 class="main-title">Supercharge Your Gallery</h1>
            <p class="main-subtitle">
                Unlock advanced grid sorting, nested filters, pre-built design templates, dynamic loading layouts, and premium developer tools by upgrading to Pro.
            </p>
        </div>

        <!-- Plan Cards -->
        <div class="grid lg:grid-cols-2">
            <!-- Free Plan -->
            <div class="pricing-card free-card rounded-3xl p-12 border">
                <div class="mb-8">
                    <span class="ufg-badge" style="background: #e2e8f0; color: #475569; padding: 4px 14px; border-radius: 99px; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">CURRENT PLAN</span>
                    <h2 class="font-black text-gray-900" style="font-size: 30px; margin-top: 16px; margin-bottom: 8px; color: #0f172a;">Free Version</h2>
                    <p class="text-gray-600" style="font-size: 14px; color: #64748b;">Essential features for building simple image displays.</p>
                </div>
                <div class="space-y-4 mb-10" style="margin-bottom: 40px; display: flex; flex-direction: column; gap: 14px;">
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> Unlimited Galleries & Shortcodes</div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> Responsive Columns Layouts</div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> Masonry Layout Grid Support</div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> Standard Lightbox Title Overlay</div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="cross-icon">✕</span> Justified & Presets Templates</div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="cross-icon">✕</span> Nested Categories & Icon Pickers</div>
                </div>
                <button class="cta-button free items-center justify-center flex">Active & Installed</button>
            </div>

            <!-- Pro Plan -->
            <div class="pricing-card pro-card rounded-3xl p-12">
                <div style="position: absolute; top: -14px; right: 32px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 6px 18px; border-radius: 99px; font-weight: 800; font-size: 11px; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(37,99,235,0.25);">MOST POPULAR</div>
                <div class="mb-8">
                    <span class="ufg-badge" style="background: #dbeafe; color: #2563eb; padding: 4px 14px; border-radius: 99px; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">PROFESSIONAL</span>
                    <h2 class="font-black text-gray-900" style="font-size: 30px; margin-top: 16px; margin-bottom: 8px; color: #0f172a;">Pro Version</h2>
                    <p class="text-gray-600" style="font-size: 14px; color: #64748b;">Complete styling control for developers and web creators.</p>
                </div>
                <div class="space-y-4 mb-10" style="margin-bottom: 40px; display: flex; flex-direction: column; gap: 14px;">
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> <strong>Atelier Luxury & Custom Presets (Coming Soon)</strong></div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> <strong>Nested Hierarchical Sub-Categories (5 Levels)</strong></div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> <strong>Custom Redirect Links & Read More Buttons</strong></div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> <strong>Searchable FontAwesome Filters Icons</strong></div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> <strong>AJAX Load More, Pagination & Loading Screens</strong></div>
                    <div class="flex items-center" style="font-size: 14px; color: #475569;"><span class="check-icon">✓</span> <strong>Import/Export Dashboard & Priority Support</strong></div>
                </div>
                <a href="https://wpfrank.com/wordpress-plugins/filter-gallery-pro/" target="_blank" class="cta-button pro items-center justify-center flex">Upgrade to Pro Now</a>
            </div>
        </div>

        <!-- Comparison Table -->
        <div class="table-responsive-container">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Features</th>
                        <th class="text-center" style="width: 25%;">Free Version</th>
                        <th class="text-center" style="width: 25%;">Pro Version</th>
                    </tr>
                </thead>
            <tbody>
                <!-- Section: Core Architecture -->
                <tr class="table-category-row">
                    <td colspan="3">Core & Architecture</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Unlimited Galleries</span>
                        <span class="feature-desc">Create and configure as many independent galleries as required.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Shortcodes Integration</span>
                        <span class="feature-desc">Place galleries in posts, pages, or widgets using shortcodes.</span>
                    </td>
                    <td class="text-center"><span class="text-bold-slate">[filter-gallery] & [ufg]</span></td>
                    <td class="text-center"><span class="text-bold-slate">[filter-gallery] & [ufg]</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Responsive Breakpoints</span>
                        <span class="feature-desc">Adapts grid layout and display columns automatically to device viewport widths.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">No Frontend Bloat / Speed Optimized</span>
                        <span class="feature-desc">Frontend script assets enqueued without Bootstrap or FontAwesome layout dependencies.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>

                <!-- Section: Layouts & Templates -->
                <tr class="table-category-row">
                    <td colspan="3">Gallery Layouts & Templates</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Standard Columns Selection</span>
                        <span class="feature-desc">Select grid columns count for Desktop, Tablet, Landscape, and Portrait.</span>
                    </td>
                    <td class="text-center">Locked to standard options<br><span class="feature-desc">(Desktop: 3, 4, 6 only)</span></td>
                    <td class="text-center">Fully customizable<br><span class="text-bold-slate">Any value 1 to 6</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Masonry Layout</span>
                        <span class="feature-desc">Auto-packs cards vertically to remove spacing gaps from multi-height items.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Justified Grid Layout</span>
                        <span class="feature-desc">Aligns cards dynamically to form straight edges on both margins.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Native Layout Presets</span>
                        <span class="feature-desc">Includes advanced, pre-styled template presets like Atelier Luxury.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center">Coming Soon</td>
                </tr>

                <!-- Section: Category Filtering -->
                <tr class="table-category-row">
                    <td colspan="3">Category Filtering & Controls</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Parent Categories Limits</span>
                        <span class="feature-desc">Number of main filter tags you can create.</span>
                    </td>
                    <td class="text-center">Maximum 5</td>
                    <td class="text-center"><span class="text-bold-slate">Unlimited</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Multi-level Nesting Hierarchy</span>
                        <span class="feature-desc">Nest filters deeply underneath parents to structure categories.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="text-bold-slate">Yes - Up to 5 Levels</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Category Menu Layout Styles</span>
                        <span class="feature-desc">Design of the filter tag listing (Dropdowns, Pill menus, etc.).</span>
                    </td>
                    <td class="text-center">Standard Buttons</td>
                    <td class="text-center"><span class="text-bold-slate">6 Styles</span><br><span class="feature-desc">(Buttons, Glow Slider, Dropdown, Hover, Flat, Text)</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Filter Active Image Counts</span>
                        <span class="feature-desc">Displays counter badge with count of assigned assets next to tags.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">FontAwesome Icons on Categories</span>
                        <span class="feature-desc">Assign specific searchable icons directly to filter tags.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="text-bold-slate">Yes - Searchable Picker</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">All Button Visibility & Icon</span>
                        <span class="feature-desc">Enable/disable "All" filter button, rename label, or assign custom icons.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">URL Filtering & Deep Linking</span>
                        <span class="feature-desc">Filter category automatically via URL parameters on load.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Default Loaded Category</span>
                        <span class="feature-desc">Define a custom category tag to display automatically on load instead of "All".</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>

                <!-- Section: Media Customization & Styling -->
                <tr class="table-category-row">
                    <td colspan="3">Media Customization & Styling</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Grid Padding & Border Spacings</span>
                        <span class="feature-desc">Style card sizes, borders, paddings, and background panels in editor.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Level-by-Level Custom Colors</span>
                        <span class="feature-desc">Dedicated styling colors panel for each of the 5 categories levels.</span>
                    </td>
                    <td class="text-center">Fallback Only</td>
                    <td class="text-center">Separate pickers for text, BG, hovers, and active states</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Image Title & Customization</span>
                        <span class="feature-desc">Show card titles, set custom title font sizes, and edit font colors.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Image Descriptions</span>
                        <span class="feature-desc">Add descriptive subtitles under images with text limit limits.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Redirect Link URLs</span>
                        <span class="feature-desc">Set custom redirection targets on cards pointing to external pages.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Read More Button Customizer</span>
                        <span class="feature-desc">Modify labels, background/hover colors, and set FontAwesome icons.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Custom CSS Box</span>
                        <span class="feature-desc">Inject custom raw CSS modifications directly in the settings panel.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Image Sorting Controls</span>
                        <span class="feature-desc">Sort images manually, by ID, random shuffle, or alphabetically by title.</span>
                    </td>
                    <td class="text-center">ID & Manual only</td>
                    <td class="text-center"><span class="text-bold-slate">Advanced Options</span><br><span class="feature-desc">(Random, Title, ID, Manual)</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Lightbox Settings</span>
                        <span class="feature-desc">Display full media metadata (descriptions, slide index numbering) inside modal.</span>
                    </td>
                    <td class="text-center">Title only</td>
                    <td class="text-center">Fully customizable</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Image Hover Animations</span>
                        <span class="feature-desc">Choose from premium overlay transitions and zoom layouts on hover.</span>
                    </td>
                    <td class="text-center">Only 1 Effect</td>
                    <td class="text-center"><span class="text-bold-slate">15+ Hover Animations</span></td>
                </tr>

                <!-- Section: Loading & AJAX -->
                <tr class="table-category-row">
                    <td colspan="3">Performance & AJAX Loading</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">AJAX Asynchronous Pagination</span>
                        <span class="feature-desc">Divides large galleries into pages with fast, non-refreshing AJAX loading.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">AJAX Asynchronous Load More</span>
                        <span class="feature-desc">Injects images to the bottom of the grid on button press asynchronously.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Frontend Preloader Animation</span>
                        <span class="feature-desc">Displays a rotating CSS spinner and loading screen during initialization.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>

                <!-- Section: Management & Support -->
                <tr class="table-category-row">
                    <td colspan="3">Gallery Management & Support</td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Filter Duplicator</span>
                        <span class="feature-desc">Clone filters alongside their sub-level structures in one click.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Bulk Deletion Modals</span>
                        <span class="feature-desc">"Delete Selected" and "Delete All" media options inside filter manager.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Import & Export Dashboard</span>
                        <span class="feature-desc">Export gallery layout options to migrate them to different setups.</span>
                    </td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="feature-name">Premium Customer Support</span>
                        <span class="feature-desc">Priority developer support desk assistance.</span>
                    </td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
            </tbody>
        </table>
        </div>
        
        <!-- Bottom Callout Section -->
        <div class="bottom-callout">
            <h3>Ready to Create Beautiful, High-Performance Portfolios?</h3>
            <p>
                Upgrade to Filter Gallery Pro today to unlock nested filters hierarchy, searchable FontAwesome category icons, custom redirect link URLs, and advanced AJAX page rendering.
            </p>
            <div class="callout-buttons">
                <a href="https://wpfrank.com/demo/filter-gallery-pro/" target="_blank" class="callout-btn demo">Pro Plugin Demo</a>
                <a href="https://wpfrank.com/contact/" target="_blank" class="callout-btn contact">Contact To Upgrade Pro</a>
            </div>
        </div>
        
        <div style="height: 60px;"></div>
    </div>
</div>

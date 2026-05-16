<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap ufg-docs-page" style="margin: 32px 20px 0 10px;">
    <!-- Tailwind Resets for this specific scope -->
    <style>
        .ufg-docs-page * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        .ufg-docs-page section { scroll-margin-top: 50px; }
        .ufg-docs-page .max-w-7xl { max-width: 80rem; margin-left: auto; margin-right: auto; }
        .ufg-docs-page .flex { display: flex; }
        .ufg-docs-page .flex-col { flex-direction: column; }
        .ufg-docs-page .items-center { align-items: center; }
        .ufg-docs-page .justify-between { justify-content: space-between; }
        .ufg-docs-page .grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
        @media (min-width: 1024px) {
            .ufg-docs-page .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .ufg-docs-page .lg\:col-span-1 { grid-column: span 1 / span 1; }
            .ufg-docs-page .lg\:col-span-2 { grid-column: span 2 / span 2; }
            .ufg-docs-page .lg\:flex-row { flex-direction: row; }
            .ufg-sticky-sidebar { position: sticky; top: 50px; }
        }
        .ufg-docs-page .bg-white { background-color: #ffffff; }
        .ufg-docs-page .rounded-2xl { border-radius: 1rem; }
        .ufg-docs-page .rounded-3xl { border-radius: 1.5rem; }
        .ufg-docs-page .shadow-sm { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
        .ufg-docs-page .p-6 { padding: 1.5rem; }
        .ufg-docs-page .p-8 { padding: 2rem; }
        .ufg-docs-page .border { border: 1px solid #e2e8f0; }
        .ufg-docs-page .text-gray-900 { color: #1a202c; }
        .ufg-docs-page .text-gray-600 { color: #4a5568; }
        .ufg-docs-page .text-blue-600 { color: #2563eb; }
        .ufg-docs-page .font-black { font-weight: 900; }
        .ufg-docs-page .font-bold { font-weight: 700; }
        .ufg-docs-page .tracking-tight { letter-spacing: -0.025em; }
        .ufg-docs-page .no-underline { text-decoration: none; }
        .ufg-docs-page .space-y-4 > * + * { margin-top: 1rem; }
        .ufg-docs-page .space-y-6 > * + * { margin-top: 1.5rem; }
        .ufg-docs-page .space-y-8 > * + * { margin-top: 2rem; }
        .ufg-docs-page .space-y-12 > * + * { margin-top: 3.5rem; }
        .ufg-docs-page .space-x-5 > * + * { margin-left: 1.25rem; }
        
        /* Section Styling */
        .ufg-docs-page section { overflow: hidden; position: relative; border-left: 4px solid #edf2f7; transition: border-color 0.3s ease; margin-bottom: 2rem; }
        .ufg-docs-page section:last-child { margin-bottom: 0; }
        .ufg-docs-page section:hover { border-left-color: #2563eb; }
        
        /* Header Icon Fix */
        .ufg-header-icon {
            width: 56px;
            height: 56px;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: white;
            box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
        }

        .ufg-docs-page a, .ufg-docs-page button {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ufg-docs-page a:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }

        .step-pill {
            display: inline-flex;
            width: 28px;
            height: 28px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .ufg-badge {
            background: #edf2f7;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 800;
            color: #2563eb;
            font-size: 11px;
            text-transform: uppercase;
        }
    </style>

    <div class="max-w-7xl">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-10 flex-wrap gap-6" style="border-bottom: 2px solid #edf2f7; padding-bottom: 32px;">
            <div class="flex items-center space-x-5">
                <div class="ufg-header-icon">
                    <span class="dashicons dashicons-format-gallery" style="font-size: 32px; width: 32px; height: 32px;"></span>
                </div>
                <div>
                    <h1 class="font-black text-gray-900 tracking-tight" style="font-size: 42px; margin: 0; line-height: 1;">Docs <span style="font-size: 14px; background: #2563eb; color: white; padding: 4px 8px; border-radius: 6px; vertical-align: middle; margin-left: 10px;">v1.1.0</span></h1>
                    <p class="text-gray-600 font-medium" style="margin-top: 8px; font-size: 16px;">Everything you need to build stunning filterable galleries.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4 flex-wrap">
                <a href="https://www.youtube.com/playlist?list=PLOeoids2h7nI63Ol923EMRsO8jWUzmDtz" target="_blank" class="no-underline" style="background: #ef4444; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);">
                    <span class="dashicons dashicons-video-alt3" style="margin-right: 10px; font-size: 20px; width: 20px; height: 20px;"></span>
                    Video Tutorial
                </a>
                <a href="<?php echo admin_url('admin.php?page=filter-gallery'); ?>" class="no-underline" style="background: #1a202c; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-3">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="ufg-sticky-sidebar space-y-6">
                    <div class="bg-white border rounded-2xl p-6 shadow-sm">
                        <h3 class="font-black text-gray-900" style="text-transform: uppercase; font-size: 11px; letter-spacing: 2px; margin-bottom: 20px; color: #a0aec0;">Documentation</h3>
                        <div class="flex flex-col space-y-4">
                            <a href="#getting-started" class="text-gray-900 no-underline font-bold hover:text-blue-600 flex items-center transition-all">
                                <span style="width: 6px; height: 6px; background: #2563eb; border-radius: 50%; margin-right: 12px;"></span>
                                1. Quick Start
                            </a>
                            <a href="#managing-filters" class="text-gray-900 no-underline font-bold hover:text-blue-600 flex items-center transition-all">
                                <span style="width: 6px; height: 6px; background: #cbd5e0; border-radius: 50%; margin-right: 12px;"></span>
                                2. Filter System
                            </a>
                            <a href="#adding-images" class="text-gray-900 no-underline font-bold hover:text-blue-600 flex items-center transition-all">
                                <span style="width: 6px; height: 6px; background: #cbd5e0; border-radius: 50%; margin-right: 12px;"></span>
                                3. Media Manager
                            </a>
                            <a href="#customizing" class="text-gray-900 no-underline font-bold hover:text-blue-600 flex items-center transition-all">
                                <span style="width: 6px; height: 6px; background: #cbd5e0; border-radius: 50%; margin-right: 12px;"></span>
                                4. Visual Styling
                            </a>
                            <a href="#shortcode-usage" class="text-gray-900 no-underline font-bold hover:text-blue-600 flex items-center transition-all">
                                <span style="width: 6px; height: 6px; background: #cbd5e0; border-radius: 50%; margin-right: 12px;"></span>
                                5. Shortcodes
                            </a>
                            <a href="#troubleshooting" class="text-gray-900 no-underline font-bold hover:text-blue-600 flex items-center transition-all">
                                <span style="width: 6px; height: 6px; background: #cbd5e0; border-radius: 50%; margin-right: 12px;"></span>
                                6. Troubleshooting
                            </a>
                        </div>
                    </div>

                    <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border-radius: 20px; padding: 28px; color: white; shadow-lg shadow-blue-200">
                        <h4 class="font-black" style="margin: 0 0 10px 0; font-size: 18px;">Frank Support</h4>
                        <p style="font-size: 14px; color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 20px;">Need a custom feature or expert help? Contact our premium support.</p>
                        <a href="https://wpfrank.com/contact" target="_blank" class="no-underline font-black bg-white text-blue-600 px-6 py-3 rounded-xl inline-block" style="font-size: 12px; text-transform: uppercase;">Contact Us →</a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- 1. Quick Start -->
                <section id="getting-started" class="bg-white border rounded-3xl p-8 shadow-sm">
                    <h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 24px 0;">1. Quick Start Guide</h2>
                    <div class="text-gray-600" style="line-height: 1.8;">
                        <div class="space-y-6">
                            <div class="flex">
                                <span class="step-pill">1</span>
                                <div><strong class="text-gray-900">Add New:</strong> Click the "Add New Gallery" button from the main list.</div>
                            </div>
                            <div class="flex">
                                <span class="step-pill">2</span>
                                <div><strong class="text-gray-900">Setup Filters:</strong> Create your filter tags in the <span class="ufg-badge">Filters</span> tab.</div>
                            </div>
                            <div class="flex">
                                <span class="step-pill">3</span>
                                <div><strong class="text-gray-900">Pick Images:</strong> Select media from your WordPress library in the <span class="ufg-badge">Images</span> tab.</div>
                            </div>
                            <div class="flex">
                                <span class="step-pill">4</span>
                                <div><strong class="text-gray-900">Assign:</strong> Click the tag icon on each image to tell it which filters to follow.</div>
                            </div>
                            <div class="flex">
                                <span class="step-pill">5</span>
                                <div><strong class="text-gray-900">Save & View:</strong> Hit "Save" and paste the shortcode into any page.</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2. Filter System -->
                <section id="managing-filters" class="bg-white border rounded-3xl p-8 shadow-sm">
                    <h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 20px 0;">2. Managing the Filter System</h2>
                    <p class="text-gray-600 mb-6">Filters are the core of your gallery. You can organize them in two levels:</p>
                    
                    <div style="background: #f8fafc; border: 1px dashed #cbd5e0; padding: 20px; border-radius: 12px;" class="space-y-4">
                        <div class="flex items-start">
                            <span class="dashicons dashicons-arrow-right-alt" style="color: #2563eb; margin-top: 4px; margin-right: 12px;"></span>
                            <div>
                                <strong class="text-gray-900">Parent Filters:</strong> These appear as the main filter buttons on your website.
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span class="dashicons dashicons-arrow-right-alt" style="color: #2563eb; margin-top: 4px; margin-right: 12px;"></span>
                            <div>
                                <strong class="text-gray-900">Child Filters (Sub-categories):</strong> You can assign a parent to any filter to create a dropdown or organized hierarchy.
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 mt-4 font-bold" style="color: #c53030; font-size: 13px;">
                        Note: Images assigned to a Child filter will automatically be counted towards the Parent filter total.
                    </p>
                </section>

                <!-- 3. Media Manager -->
                <section id="adding-images" class="bg-white border rounded-3xl p-8 shadow-sm">
                    <h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 20px 0;">3. Advanced Media Controls</h2>
                    <p class="text-gray-600 mb-4">Each image in your gallery allows for full custom metadata:</p>
                    <ul class="space-y-4" style="list-style: none; padding: 0;">
                        <li class="flex items-center">
                            <span class="dashicons dashicons-edit" style="margin-right: 12px; color: #a0aec0;"></span>
                            <strong class="text-gray-900" style="width: 140px; display: inline-block;">Custom Title:</strong> Use this to override the default attachment title.
                        </li>
                        <li class="flex items-center">
                            <span class="dashicons dashicons-editor-justify" style="margin-right: 12px; color: #a0aec0;"></span>
                            <strong class="text-gray-900" style="width: 140px; display: inline-block;">Description:</strong> High-resolution description that appears on hover.
                        </li>
                        <li class="flex items-center">
                            <span class="dashicons dashicons-admin-links" style="margin-right: 12px; color: #a0aec0;"></span>
                            <strong class="text-gray-900" style="width: 140px; display: inline-block;">Custom Link:</strong> Turn any image into a direct link to another page.
                        </li>
                    </ul>
                </section>

                <!-- 4. Visual Styling -->
                <section id="customizing" class="bg-white border rounded-3xl p-8 shadow-sm">
                    <h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 20px 0;">4. Visual Styling & Settings</h2>
                    <p class="text-gray-600">Head to the <span class="ufg-badge">Settings</span> tab to master the appearance:</p>
                    
                    <div class="grid lg:grid-cols-2 gap-4 mt-6">
                        <div class="p-4 border rounded-xl bg-gray-50">
                            <h4 class="font-bold text-gray-900 mb-1">Layout Grid</h4>
                            <p class="text-xs text-gray-600">Set columns for Desktop, Tablet, and Mobile independently.</p>
                        </div>
                        <div class="p-4 border rounded-xl bg-gray-50">
                            <h4 class="font-bold text-gray-900 mb-1">Color Tokens</h4>
                            <p class="text-xs text-gray-600">Customize button backgrounds, text colors, and hover overlays.</p>
                        </div>
                        <div class="p-4 border rounded-xl bg-gray-50">
                            <h4 class="font-bold text-gray-900 mb-1">Load More</h4>
                            <p class="text-xs text-gray-600">Enable infinite pagination with custom button text and colors.</p>
                        </div>
                        <div class="p-4 border rounded-xl bg-gray-50">
                            <h4 class="font-bold text-gray-900 mb-1">Animations</h4>
                            <p class="text-xs text-gray-600">Choose from CSS-driven hover effects and image entrance fades.</p>
                        </div>
                    </div>
                </section>

                <!-- 5. Shortcodes -->
                <section id="shortcode-usage" class="bg-white border rounded-3xl p-8 shadow-sm">
                    <h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 16px 0;">5. Published Shortcodes</h2>
                    <div style="background: #1a202c; color: #4ade80; padding: 24px; border-radius: 16px; font-family: 'JetBrains Mono', monospace; font-size: 18px; margin-bottom: 20px; position: relative; overflow: hidden; box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);">
                        <span style="position: absolute; top:0; right: 0; background: #2d3748; padding: 6px 14px; font-size: 11px; color: #94a3b8; border-bottom-left-radius: 10px; font-family: sans-serif; font-weight: 800;">PHP TAG</span>
                        [ufg-gallery id="1"]
                    </div>
                    <p class="text-gray-600">Paste this ID anywhere in your WordPress editor (Gutenberg, Elementor, Divi) to render the gallery. To change the gallery, just update the ID number.</p>
                </section>

                <!-- 6. Troubleshooting -->
                <section id="troubleshooting" class="bg-white border rounded-3xl p-8 shadow-sm" style="background: #fff5f5; border-color: #fed7d7;">
                    <h2 class="font-black text-gray-900" style="font-size: 28px; margin: 0 0 24px 0; color: #c53030;">6. Troubleshooting</h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <span class="dashicons dashicons-info" style="color: #c53030; font-size: 20px;"></span>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Double Counting?</h4>
                                <p class="text-sm text-gray-600 m-0">In version 6.0.1+ we have fixed recursive counting. Ensure your child images are properly assigned to parent groups for accurate counts.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="dashicons dashicons-info" style="color: #c53030; font-size: 20px;"></span>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Style not updating?</h4>
                                <p class="text-sm text-gray-600 m-0">Click the "Save Gallery" button twice to ensure the dynamic CSS cache refreshes, or clear your site's plugin cache.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<?php
// Add some spacing after the wrapper
echo '<div style="height: 100px;"></div>';
?>

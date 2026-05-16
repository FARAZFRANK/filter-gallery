<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap ufg-pricing-page" style="margin: 32px 20px 0 10px;">
    <style>
        .ufg-pricing-page * { box-sizing: border-box; }
        .ufg-pricing-page .max-w-7xl { max-width: 80rem; margin-left: auto; margin-right: auto; }
        .ufg-pricing-page .flex { display: flex; }
        .ufg-pricing-page .items-center { align-items: center; }
        .ufg-pricing-page .justify-center { justify-content: center; }
        .ufg-pricing-page .text-center { text-align: center; }
        .ufg-pricing-page .grid { display: grid; gap: 2rem; }
        @media (min-width: 1024px) {
            .ufg-pricing-page .lg\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        .ufg-pricing-page .bg-white { background-color: #ffffff; }
        .ufg-pricing-page .rounded-3xl { border-radius: 1.5rem; }
        .ufg-pricing-page .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .ufg-pricing-page .p-8 { padding: 2rem; }
        .ufg-pricing-page .p-12 { padding: 3rem; }
        .ufg-pricing-page .border { border: 1px solid #e2e8f0; }
        .ufg-pricing-page .text-gray-900 { color: #1a202c; }
        .ufg-pricing-page .text-gray-600 { color: #4a5568; }
        .ufg-pricing-page .text-blue-600 { color: #2563eb; }
        .ufg-pricing-page .font-black { font-weight: 900; }
        .ufg-pricing-page .font-bold { font-weight: 700; }
        
        .pricing-card { position: relative; transition: all 0.3s ease; border: 2px solid transparent; }
        .pricing-card.pro { border-color: #2563eb; background: #f8faff; }
        .pricing-card:hover { transform: translateY(-8px); }
        
        .check-icon { color: #10b981; margin-right: 12px; font-weight: bold; }
        .cross-icon { color: #ef4444; margin-right: 12px; font-weight: bold; }
        
        .cta-button {
            display: inline-block;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 900;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.2s ease;
        }
        .cta-button.free { background: #edf2f7; color: #4a5568; }
        .cta-button.pro { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4); }
        .cta-button:hover { transform: scale(1.05); }

        table.comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 50px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        table.comparison-table th { background: #f8fafc; padding: 24px; text-align: left; font-weight: 900; color: #1a202c; border-bottom: 2px solid #e2e8f0; }
        table.comparison-table td { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; color: #4a5568; font-weight: 500; }
        table.comparison-table tr:last-child td { border-bottom: none; }
        .feature-name { font-weight: 700; color: #1a202c; }
    </style>

    <div class="max-w-7xl">
        <div class="text-center mb-16">
            <h1 class="font-black text-gray-900 tracking-tight" style="font-size: 48px; margin-bottom: 16px;">Supercharge Your Gallery</h1>
            <p class="text-gray-600" style="font-size: 20px; max-width: 700px; margin: 0 auto;">Upgrade to Pro and unlock advanced filtering, stunning layouts, and premium support.</p>
        </div>

        <div class="grid lg:grid-cols-2">
            <!-- Free Plan -->
            <div class="pricing-card bg-white rounded-3xl p-12 border shadow-xl">
                <div class="mb-8">
                    <span class="ufg-badge" style="background: #edf2f7; color: #4a5568; padding: 4px 12px; border-radius: 99px; font-weight: 900; font-size: 12px;">CURRENT PLAN</span>
                    <h2 class="font-black text-gray-900" style="font-size: 32px; margin-top: 16px;">Free Version</h2>
                    <p class="text-gray-600">Essential features for simple galleries.</p>
                </div>
                <div class="space-y-4 mb-10">
                    <div class="flex items-center"><span class="check-icon">✓</span> Unlimited Galleries</div>
                    <div class="flex items-center"><span class="check-icon">✓</span> Isotope Filtering</div>
                    <div class="flex items-center"><span class="check-icon">✓</span> Basic Lightbox</div>
                    <div class="flex items-center"><span class="check-icon">✓</span> 4 Column Layout</div>
                    <div class="flex items-center"><span class="cross-icon">✕</span> Premium Layouts (Masonry, Justified)</div>
                    <div class="flex items-center"><span class="cross-icon">✕</span> Advanced Filtering (Search, Sorting)</div>
                </div>
                <a href="#" class="cta-button free items-center justify-center flex">Installed</a>
            </div>

            <!-- Pro Plan -->
            <div class="pricing-card pro rounded-3xl p-12 shadow-xl">
                <div style="position: absolute; top: -15px; right: 30px; background: #2563eb; color: white; padding: 6px 16px; border-radius: 99px; font-weight: 900; font-size: 12px; box-shadow: 0 4px 6px rgba(37,99,235,0.3);">MOST POPULAR</div>
                <div class="mb-8">
                    <span class="ufg-badge" style="background: #dbeafe; color: #2563eb; padding: 4px 12px; border-radius: 99px; font-weight: 900; font-size: 12px;">PROFESSIONAL</span>
                    <h2 class="font-black text-gray-900" style="font-size: 32px; margin-top: 16px;">Pro Version</h2>
                    <p class="text-gray-600">Complete control for professional websites.</p>
                </div>
                <div class="space-y-4 mb-10">
                    <div class="flex items-center"><span class="check-icon">✓</span> <strong>15+ Premium Layouts</strong></div>
                    <div class="flex items-center"><span class="check-icon">✓</span> <strong>Advanced Ajax Search</strong></div>
                    <div class="flex items-center"><span class="check-icon">✓</span> <strong>Multi-level Filters</strong></div>
                    <div class="flex items-center"><span class="check-icon">✓</span> <strong>Video Gallery Support</strong></div>
                    <div class="flex items-center"><span class="check-icon">✓</span> <strong>Infinite Load More</strong></div>
                    <div class="flex items-center"><span class="check-icon">✓</span> <strong>Premium Support</strong></div>
                </div>
                <a href="https://wpfrank.com/plugins/filter-gallery" target="_blank" class="cta-button pro items-center justify-center flex">Upgrade to Pro Now</a>
            </div>
        </div>

        <table class="comparison-table">
            <thead>
                <tr>
                    <th>Features</th>
                    <th class="text-center">Free</th>
                    <th class="text-center">Pro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="feature-name">Unlimited Galleries</td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Filterable Gallery (Isotope)</td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Parent Filters</td>
                    <td class="text-center">Up to 5</td>
                    <td class="text-center">Unlimited</td>
                </tr>
                <tr>
                    <td class="feature-name">Child / Multi-level Filters</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span> 5 Levels</td>
                </tr>
                <tr>
                    <td class="feature-name">Filter Icons</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Filter Count Display</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Responsive Columns (4 Breakpoints)</td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Thumbnail Border & Size Control</td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Image Title Customization</td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Image Description</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Image Sorting</td>
                    <td class="text-center"><span class="check-icon">✓</span> Basic</td>
                    <td class="text-center"><span class="check-icon">✓</span> Advanced</td>
                </tr>
                <tr>
                    <td class="feature-name">Lightbox</td>
                    <td class="text-center"><span class="check-icon">✓</span> Title Only</td>
                    <td class="text-center"><span class="check-icon">✓</span> Full</td>
                </tr>
                <tr>
                    <td class="feature-name">Image Hover Effects</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span> 15+ Effects</td>
                </tr>
                <tr>
                    <td class="feature-name">Load More / Pagination</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Image Search</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Read More Link</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Custom CSS</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Import / Export Galleries</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Masonry & Justified Layouts</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-name">Premium Support</td>
                    <td class="text-center"><span class="cross-icon">✕</span></td>
                    <td class="text-center"><span class="check-icon">✓</span></td>
                </tr>
            </tbody>
        </table>
        
        <div style="height: 100px;"></div>
    </div>
</div>

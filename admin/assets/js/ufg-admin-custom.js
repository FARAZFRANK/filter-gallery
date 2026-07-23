/**
 * ufg-admin-custom.js
 * Injects custom advanced styling settings for Multi-Level filters and the search box
 * into the React-based admin settings tab.
 */

jQuery(document).ready(function ($) {
    if (typeof window.ufgAdminData === 'undefined') return;

    // Check and inject the settings panel
    function checkAndInjectSettings() {
        var $navTitle = $('h3:contains("Filter Navigation")');
        if ($navTitle.length > 0) {
            var $card = $navTitle.closest('.break-inside-avoid');
            if ($card.length > 0 && $('#ufg-custom-style-setting').length === 0) {
                // Rename card header while preserving the icon span
                var $iconSpan = $navTitle.find('span');
                if ($iconSpan.length > 0) {
                    $navTitle.html($iconSpan.prop('outerHTML') + ' Filters & Search');
                } else {
                    $navTitle.text('Filters & Search');
                }

                // Hide specific native settings via injected CSS to prevent React from un-hiding them on re-renders
                if ($('#ufg-hide-native-settings-css').length === 0) {
                    var hideCss = `
                        .grid:has(label[for="all_button_bg_color"]),
                        .grid:has(label[for="parent_button_bg_color"]),
                        .pt-4:has(label[for="parent_filters_heading"]),
                        .pt-4:has(label[for="l1_filters_heading"]),
                        .pt-4:has(label[for="active_button_bg_color"]) {
                            display: none !important;
                        }
                    `;
                    $('head').append('<style id="ufg-hide-native-settings-css">' + hideCss + '</style>');
                }

                // Keep native settings visible, just append custom settings

                // Inject custom settings inside the card wrapper
                injectSettingsPanel($card);
            }
        }
    }

    // Periodically run check to handle async React rendering
    setInterval(checkAndInjectSettings, 500);

    function injectSettingsPanel($container) {
        $container.attr('id', 'ufg-filters-search-card');
        $container.css({ 'display': 'flex', 'flex-direction': 'column', 'gap': '18px' });

        // Add dynamic CSS to sort all elements
        if ($('#ufg-filters-search-order-css').length === 0) {
            var orderCss = `
                #ufg-filters-search-card { display: flex !important; flex-direction: column !important; gap: 18px !important; }
                #ufg-filters-search-card > div { order: 30; margin-bottom: 0px !important; } /* Default middle order */
                #ufg-filters-search-card > div.flex.items-center.gap-2.mb-4 { order: 1 !important; margin-bottom: 0px !important; } /* Card Title */
                #ufg-filter-header { order: 10 !important; }
                #ufg-custom-style-setting { order: 11 !important; }
                #ufg-filters-search-card > div:has(#default_filter) { order: 12 !important; }
                #ufg-filters-search-card > div:has(label[for=show_filters]) { order: 13 !important; }
                #ufg-filters-search-card > div:has(label[for=show_filters_icon]) { order: 14 !important; }
                #ufg-filters-search-card > div:has(label[for=enable_deep_linking]) { order: 15 !important; }
                #ufg-filters-search-card > div:has(label[for=show_all_button]) { order: 16 !important; }
                #ufg-filters-search-card > div:has(#child_filter_effect) { order: 17 !important; }
                #ufg-custom-spacing-setting { order: 21 !important; }
                #ufg-custom-colors-setting { order: 22 !important; }
                
                #ufg-search-header { order: 50 !important; }
                #ufg-filters-search-card > div:has(label[for=show_search_box]) { order: 51 !important; }
                #ufg-filters-search-card > div:has(#search_box_placeholder) { order: 52 !important; }
                #ufg-custom-combine-setting { order: 53 !important; }
            `;
            $('head').append('<style id="ufg-filters-search-order-css">' + orderCss + '</style>');
        }

        var settings = window.ufgAdminData.currentGalleryData ? (window.ufgAdminData.currentGalleryData.settings || {}) : {};

        // Define default fallbacks (Free plugin supports Buttons and Dropdown Select)
        var filterStyle = (settings.filter_style === 'dropdown') ? 'dropdown' : 'buttons';
        var combineFilterSearch = settings.combine_filter_search || '0';
        var filterPadding = settings.filter_padding || '10px 15px';
        var filterMargin = settings.filter_margin || '5px';

        var filterPaddingType = settings.filter_padding_type;
        var filterPaddingV = parseInt(settings.filter_padding_v);
        var filterPaddingH = parseInt(settings.filter_padding_h);

        // Parse legacy configurations if they exist
        if (!filterPaddingType) {
            var parts = filterPadding.trim().split(/\s+/);
            if (parts.length === 2) {
                var v = parseInt(parts[0]);
                var h = parseInt(parts[1]);
                filterPaddingV = isNaN(v) ? 12 : v;
                filterPaddingH = isNaN(h) ? 24 : h;

                if (filterPaddingV === 8 && filterPaddingH === 16) {
                    filterPaddingType = 'small';
                } else if (filterPaddingV === 12 && filterPaddingH === 24) {
                    filterPaddingType = 'medium';
                } else if (filterPaddingV === 16 && filterPaddingH === 32) {
                    filterPaddingType = 'large';
                } else {
                    filterPaddingType = 'custom';
                }
            } else {
                filterPaddingType = 'medium';
                filterPaddingV = 12;
                filterPaddingH = 24;
            }
        }

        var filterMarginVal = parseInt(settings.filter_margin_val);
        if (isNaN(filterMarginVal)) {
            filterMarginVal = parseInt(filterMargin) || 5;
        }

        var htmlHeaders = `
            <div id="ufg-filter-header" class="w-full" style="margin-top: 5px; margin-bottom: 0px;">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-widest border-b-2 border-gray-100 pb-2 flex items-center gap-2"><svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg> Filter Settings</h4>
            </div>
            <div id="ufg-search-header" class="w-full" style="margin-top: 15px; margin-bottom: 0px;">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-widest border-b-2 border-gray-100 pb-2 flex items-center gap-2"><svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Search Settings</h4>
            </div>
        `;

        var htmlStyle = `
            <div id="ufg-custom-style-setting" class="w-full" style="margin-bottom: 0px;">
                <div class="flex items-center mb-1">
                    <label class="block text-sm font-semibold text-gray-700">Filter Menu Style</label>
                    <div class="ufg-setting-tooltip">
                        <span class="ufg-setting-tooltip-icon">i</span>
                        <div class="ufg-setting-tooltip-content">
                            <div>Choose the visual style for filter navigation.</div>
                            <div class="ufg-setting-tooltip-default"><span>Default: </span>Buttons</div>
                        </div>
                    </div>
                </div>
                <select id="ufg_custom_filter_style" class="w-full border-gray-200 rounded-lg shadow-sm p-2.5 border bg-white text-sm transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%20%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236B7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.5rem_center] bg-no-repeat pr-10" style="box-sizing: border-box !important;">
                    <option value="buttons" ${filterStyle === 'buttons' ? 'selected' : ''}>Buttons</option>
                    <option value="dropdown" ${filterStyle === 'dropdown' ? 'selected' : ''}>Dropdown Select</option>
                    <option value="text" disabled style="color: #9ca3af; background-color: #f9fafb;">Minimalist Text (Pro)</option>
                    <option value="hover-dropdown" disabled style="color: #9ca3af; background-color: #f9fafb;">Hover Dropdown Menu (Pro)</option>
                    <option value="glow-slider" disabled style="color: #9ca3af; background-color: #f9fafb;">Glow Slider Pill (Pro)</option>
                    <option value="pulse-modern" disabled style="color: #9ca3af; background-color: #f9fafb;">Pulse Modern Soft (Pro)</option>
                    <option value="neon-grid" disabled style="color: #9ca3af; background-color: #f9fafb;">Neon Cyberpunk (Pro)</option>
                    <option value="atelier-luxury" disabled style="color: #9ca3af; background-color: #f9fafb;">Atelier Luxury (Pro)</option>
                </select>
            </div>
        `;

        var htmlCombine = `
            <div id="ufg-custom-combine-setting" class="flex items-center justify-between p-3 bg-gray-50/50 rounded-xl border border-gray-100 opacity-75 group w-full" style="margin-bottom: 0px;">
                <div class="flex-1 pr-4 text-left">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-700 transition-colors">Combine Filters & Search in One Row</span>
                        <span style="background-color: #f59e0b; color: #ffffff; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 900; letter-spacing: 0.05em; display: inline-block; line-height: 1.2;">PRO</span>
                        <div class="ufg-setting-tooltip">
                            <span class="ufg-setting-tooltip-icon">i</span>
                            <div class="ufg-setting-tooltip-content">
                                <div>Place the search box and filter buttons on the same row.</div>
                                <div class="ufg-setting-tooltip-default"><span>Default: </span>Not combined</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Toggle Button -->
                <div id="ufg_custom_combine_filter_search_toggle" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-not-allowed opacity-60 shadow-inner overflow-hidden shrink-0" style="background-color: #e5e7eb;">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-md" style="transform: translateX(4px);"></span>
                </div>
                <!-- Hidden input to store value for saving -->
                <input type="hidden" id="ufg_custom_combine_filter_search" value="0">
            </div>
        `;

        var htmlSpacing = `
            <div id="ufg-custom-spacing-setting" class="pt-4 border-t border-gray-50 w-full" style="margin-bottom: 0px;">
                <!-- Padding Presets & Sliders -->
                <div class="mb-5">
                    <div class="flex items-center mb-2">
                        <label class="block text-sm font-semibold text-gray-700">Filter Button Padding (Size)</label>
                        <div class="ufg-setting-tooltip">
                            <span class="ufg-setting-tooltip-icon">i</span>
                            <div class="ufg-setting-tooltip-content">
                                <div>Choose a preset size or custom padding for filter buttons.</div>
                            </div>
                        </div>
                    </div>
                    <!-- Option Buttons for Preset -->
                    <div class="flex gap-2 mb-3">
                        <button type="button" data-preset="small" class="ufg-padding-preset-btn px-4 py-2 border rounded-lg text-sm font-medium transition-all focus:outline-none">Small</button>
                        <button type="button" data-preset="medium" class="ufg-padding-preset-btn px-4 py-2 border rounded-lg text-sm font-medium transition-all focus:outline-none">Medium</button>
                        <button type="button" data-preset="large" class="ufg-padding-preset-btn px-4 py-2 border rounded-lg text-sm font-medium transition-all focus:outline-none">Large</button>
                        <button type="button" data-preset="custom" class="ufg-padding-preset-btn px-4 py-2 border rounded-lg text-sm font-medium transition-all focus:outline-none">Custom</button>
                    </div>
                    <!-- Sliders (visible only when custom is active) -->
                    <div id="ufg-custom-padding-sliders" class="hidden grid grid-cols-2 gap-4 mt-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-semibold text-gray-600">Vertical Space</label>
                                <span id="ufg_custom_filter_padding_v_val" class="text-xs font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 border border-gray-200/60">12px</span>
                            </div>
                            <input type="range" id="ufg_custom_filter_padding_v" min="4" max="30" value="${filterPaddingV}" class="ufg-range-input cursor-pointer">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-xs font-semibold text-gray-600">Horizontal Space</label>
                                <span id="ufg_custom_filter_padding_h_val" class="text-xs font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 border border-gray-200/60">24px</span>
                            </div>
                            <input type="range" id="ufg_custom_filter_padding_h" min="8" max="50" value="${filterPaddingH}" class="ufg-range-input cursor-pointer">
                        </div>
                    </div>
                    <!-- Hidden input to store standard CSS padding value -->
                    <input type="hidden" id="ufg_custom_filter_padding" value="${filterPadding}">
                    <input type="hidden" id="ufg_custom_filter_padding_type" value="${filterPaddingType}">
                </div>

                <!-- Margin Spacing Slider -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <label class="block text-sm font-semibold text-gray-700">Filter Button Spacing (Gap)</label>
                            <div class="ufg-setting-tooltip" style="margin-left: 6px;">
                                <span class="ufg-setting-tooltip-icon">i</span>
                                <div class="ufg-setting-tooltip-content">
                                    <div>Outer spacing (gap) between filter buttons.</div>
                                </div>
                            </div>
                        </div>
                        <span id="ufg_custom_filter_margin_val" class="text-xs font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 border border-gray-200/60">${filterMarginVal}px</span>
                    </div>
                    <input type="range" id="ufg_custom_filter_margin_slider" min="0" max="30" value="${filterMarginVal}" class="ufg-range-input cursor-pointer">
                    <!-- Hidden input to store standard CSS margin value -->
                    <input type="hidden" id="ufg_custom_filter_margin" value="${filterMargin}">
                </div>
            </div>
        `;

        var htmlColors = `
            <div id="ufg-custom-colors-setting" class="border-t border-gray-100 pt-4 w-full">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Level-by-Level Custom Colors</h4>
                <div style="display: flex; flex-direction: column; gap: 12px;">
        `;

        var levels = [
            { id: 'parent', label: 'Level 1 (Parent)' },
            { id: 'l1', label: 'Level 2 (Sublevel 1)' },
            { id: 'l2', label: 'Level 3 (Sublevel 2)' },
            { id: 'l3', label: 'Level 4 (Sublevel 3)' },
            { id: 'l4', label: 'Level 5 (Sublevel 4)' }
        ];

        var defaultColors = {
            parent: { text: '#4F46E5', bg: '#EEF2FF', hover: '#000000', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l1: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l2: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l3: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l4: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' }
        };

        levels.forEach(function (lvl) {
            var isPro = (lvl.id !== 'parent');
            var defs = defaultColors[lvl.id];
            var normalColor = settings[lvl.id + '_button_color'] || defs.text;
            var normalBg = settings[lvl.id + '_button_bg_color'] || defs.bg;
            var hoverColor = settings[lvl.id + '_button_hover_color'] || defs.hover;
            var activeColor = settings[lvl.id + '_active_button_color'] || defs.activeText;
            var activeBg = settings[lvl.id + '_active_button_bg_color'] || defs.activeBg;

            var proBadge = isPro ? '<span style="background-color: #f59e0b; color: #ffffff; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 900; margin-left: 6px; display: inline-block; line-height: 1.2;">PRO</span>' : '';
            var disabledAttr = isPro ? 'disabled' : '';
            var disabledClass = isPro ? 'opacity-60 cursor-not-allowed' : '';

            htmlColors += `
                <details class="group bg-white border border-gray-200 rounded-xl shadow-sm p-3 ${disabledClass}" style="margin-bottom: 12px;" ${isPro ? 'onclick="return false;"' : ''}>
                    <summary class="flex justify-between items-center font-medium text-xs text-gray-700 ${isPro ? 'cursor-not-allowed' : 'cursor-pointer'} list-none">
                        <div class="flex items-center">
                            <div class="rounded-full mr-2" style="width: 12px; height: 12px; background-color: ${normalBg}; border: 1px solid rgba(0,0,0,0.1);"></div>
                            <span>${lvl.label} Colors</span>
                            ${proBadge}
                        </div>
                        <span class="transition group-open:rotate-180 text-gray-400">
                            <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24" width="16"><path d="M6 9l6 6 6-6"></path></svg>
                        </span>
                    </summary>
                    <div class="mt-3 pt-3 border-t border-gray-100 animate-in fade-in duration-200">
                        <div class="grid grid-cols-5 gap-2">
                            <div>
                                <label class="block text-[8px] font-bold text-gray-500 uppercase mb-1">Text</label>
                                <input type="color" ${disabledAttr} id="ufg_custom_${lvl.id}_button_color" class="w-full h-8 rounded border border-gray-200 ${isPro ? 'cursor-not-allowed' : 'cursor-pointer'} bg-transparent" value="${normalColor}" style="padding: 2px;">
                            </div>
                            <div>
                                <label class="block text-[8px] font-bold text-gray-500 uppercase mb-1">BG</label>
                                <input type="color" ${disabledAttr} id="ufg_custom_${lvl.id}_button_bg_color" class="w-full h-8 rounded border border-gray-200 ${isPro ? 'cursor-not-allowed' : 'cursor-pointer'} bg-transparent" value="${normalBg}" style="padding: 2px;">
                            </div>
                            <div>
                                <label class="block text-[8px] font-bold text-gray-500 uppercase mb-1">Hover</label>
                                <input type="color" ${disabledAttr} id="ufg_custom_${lvl.id}_button_hover_color" class="w-full h-8 rounded border border-gray-200 ${isPro ? 'cursor-not-allowed' : 'cursor-pointer'} bg-transparent" value="${hoverColor}" style="padding: 2px;">
                            </div>
                            <div>
                                <label class="block text-[8px] font-bold text-gray-500 uppercase mb-1">Active</label>
                                <input type="color" ${disabledAttr} id="ufg_custom_${lvl.id}_active_button_color" class="w-full h-8 rounded border border-gray-200 ${isPro ? 'cursor-not-allowed' : 'cursor-pointer'} bg-transparent" value="${activeColor}" style="padding: 2px;">
                            </div>
                            <div>
                                <label class="block text-[8px] font-bold text-gray-500 uppercase mb-1">Active BG</label>
                                <input type="color" ${disabledAttr} id="ufg_custom_${lvl.id}_active_button_bg_color" class="w-full h-8 rounded border border-gray-200 ${isPro ? 'cursor-not-allowed' : 'cursor-pointer'} bg-transparent" value="${activeBg}" style="padding: 2px;">
                            </div>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-gray-50 flex justify-between items-center">
                            <span class="text-[9px] text-gray-400">Restore level default colors</span>
                            <button type="button" ${disabledAttr} class="ufg-reset-level-colors text-[10px] font-bold text-blue-600 hover:text-blue-800 transition ${disabledClass}" data-level="${lvl.id}">Reset to Default</button>
                        </div>
                    </div>
                </details>
            `;
        });

        htmlColors += `
                    </div>
                </div>
        `;

        $('#ufg-custom-advanced-settings-panel').remove();
        $container.append(htmlHeaders + htmlStyle + htmlCombine + htmlSpacing + htmlColors);
        $(document).trigger('ufg_settings_rendered');
    }

    // Intercept fetch API calls to include our custom settings in the save request
    const originalFetch = window.fetch;
    window.fetch = async function (...args) {
        const [resource, config] = args;
        if (config && config.method === 'POST' && config.body instanceof URLSearchParams) {
            if (config.body.get('action') === 'ufg_save_setting') {
                if ($('#ufg-custom-style-setting').length > 0) {
                    var selectedStyle = $('#ufg_custom_filter_style').val();
                    if (selectedStyle !== 'dropdown') { selectedStyle = 'buttons'; }
                    config.body.set('filter_style', selectedStyle);
                    config.body.set('combine_filter_search', '0');
                    config.body.set('filter_padding', $('#ufg_custom_filter_padding').val());
                    config.body.set('filter_margin', $('#ufg_custom_filter_margin').val());
                    config.body.set('filter_padding_type', $('#ufg_custom_filter_padding_type').val());
                    config.body.set('filter_padding_v', $('#ufg_custom_filter_padding_v').val());
                    config.body.set('filter_padding_h', $('#ufg_custom_filter_padding_h').val());
                    config.body.set('filter_margin_val', $('#ufg_custom_filter_margin_slider').val());

                    var suffixes = ['parent'];
                    suffixes.forEach(function (suffix) {
                        config.body.set(`${suffix}_button_color`, $(`#ufg_custom_${suffix}_button_color`).val());
                        config.body.set(`${suffix}_button_bg_color`, $(`#ufg_custom_${suffix}_button_bg_color`).val());
                        config.body.set(`${suffix}_button_hover_color`, $(`#ufg_custom_${suffix}_button_hover_color`).val());
                        config.body.set(`${suffix}_active_button_color`, $(`#ufg_custom_${suffix}_active_button_color`).val());
                        config.body.set(`${suffix}_active_button_bg_color`, $(`#ufg_custom_${suffix}_active_button_bg_color`).val());
                    });
                }
            }
        }
        return originalFetch.apply(this, args);
    };

    // Delegated click event handler for reset buttons
    $(document).on('click', '.ufg-reset-level-colors', function (e) {
        e.preventDefault();
        var level = $(this).data('level');
        var defaultColors = {
            parent: { text: '#4F46E5', bg: '#EEF2FF', hover: '#000000', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l1: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l2: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l3: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' },
            l4: { text: '#4F46E5', bg: '#EEF2FF', hover: '#4338CA', activeText: '#FFFFFF', activeBg: '#4F46E5' }
        };
        var defs = defaultColors[level];
        if (defs) {
            $(`#ufg_custom_${level}_button_color`).val(defs.text);
            $(`#ufg_custom_${level}_button_bg_color`).val(defs.bg);
            $(`#ufg_custom_${level}_button_hover_color`).val(defs.hover);
            $(`#ufg_custom_${level}_active_button_color`).val(defs.activeText);
            $(`#ufg_custom_${level}_active_button_bg_color`).val(defs.activeBg);

            // Update the preview color dot next to the heading
            var colorDot = $(this).closest('details').find('.rounded-full');
            if (colorDot.length > 0) {
                colorDot.css('background-color', defs.bg);
            }
        }
    });

    // Delegated click event handler for Combine Filters & Search toggle button (PRO feature - disabled)
    $(document).on('click', '#ufg_custom_combine_filter_search_toggle', function (e) {
        e.preventDefault();
        return false;
    });

    // Delegated input/change event handler for manual color changes to update the dot preview
    $(document).on('input change', '[id^="ufg_custom_"][id$="_button_bg_color"]', function () {
        var val = $(this).val();
        var colorDot = $(this).closest('details').find('.rounded-full');
        if (colorDot.length > 0) {
            colorDot.css('background-color', val);
        }
    });

    // Preset selection logic for padding
    $(document).on('click', '.ufg-padding-preset-btn', function () {
        var preset = $(this).data('preset');
        $('#ufg_custom_filter_padding_type').val(preset);

        $('.ufg-padding-preset-btn').addClass('bg-white border-gray-200 text-gray-700 hover:bg-gray-50').removeClass('bg-blue-50 border-blue-500 text-blue-700 ring-2 ring-blue-500/20');
        $(this).addClass('bg-blue-50 border-blue-500 text-blue-700 ring-2 ring-blue-500/20').removeClass('bg-white border-gray-200 text-gray-700 hover:bg-gray-50');

        if (preset === 'custom') {
            $('#ufg-custom-padding-sliders').removeClass('hidden');
            updateCompiledPadding();
        } else {
            $('#ufg-custom-padding-sliders').addClass('hidden');
            var cssVal = '12px 24px';
            if (preset === 'small') cssVal = '8px 16px';
            if (preset === 'large') cssVal = '16px 32px';
            $('#ufg_custom_filter_padding').val(cssVal);
        }
    });

    // Handle range slider updates for custom vertical padding
    $(document).on('input', '#ufg_custom_filter_padding_v', function () {
        var val = $(this).val();
        $('#ufg_custom_filter_padding_v_val').text(val + 'px');
        updateCompiledPadding();
    });

    // Handle range slider updates for custom horizontal padding
    $(document).on('input', '#ufg_custom_filter_padding_h', function () {
        var val = $(this).val();
        $('#ufg_custom_filter_padding_h_val').text(val + 'px');
        updateCompiledPadding();
    });

    // Handle button spacing slider updates
    $(document).on('input', '#ufg_custom_filter_margin_slider', function () {
        var val = $(this).val();
        $('#ufg_custom_filter_margin_val').text(val + 'px');
        $('#ufg_custom_filter_margin').val(val + 'px');
    });

    function updateCompiledPadding() {
        var v = $('#ufg_custom_filter_padding_v').val();
        var h = $('#ufg_custom_filter_padding_h').val();
        $('#ufg_custom_filter_padding').val(v + 'px ' + h + 'px');
    }

    // Initialize custom padding / margin defaults visually after HTML append
    $(document).on('ufg_settings_rendered', function () {
        // Set active preset button
        var activePreset = $('#ufg_custom_filter_padding_type').val() || 'medium';
        $('.ufg-padding-preset-btn').each(function () {
            var preset = $(this).data('preset');
            if (preset === activePreset) {
                $(this).addClass('bg-blue-50 border-blue-500 text-blue-700 ring-2 ring-blue-500/20').removeClass('bg-white border-gray-200 text-gray-700 hover:bg-gray-50');
            } else {
                $(this).addClass('bg-white border-gray-200 text-gray-700 hover:bg-gray-50').removeClass('bg-blue-50 border-blue-500 text-blue-700 ring-2 ring-blue-500/20');
            }
        });

        // Toggle custom padding sliders visibility
        if (activePreset === 'custom') {
            $('#ufg-custom-padding-sliders').removeClass('hidden');
        } else {
            $('#ufg-custom-padding-sliders').addClass('hidden');
        }

        // Set range slider values and text markers
        var pV = parseInt($('#ufg_custom_filter_padding_v').val()) || 12;
        var pH = parseInt($('#ufg_custom_filter_padding_h').val()) || 24;
        var mV = parseInt($('#ufg_custom_filter_margin_slider').val()) || 5;

        $('#ufg_custom_filter_padding_v_val').text(pV + 'px');
        $('#ufg_custom_filter_padding_h_val').text(pH + 'px');
        $('#ufg_custom_filter_margin_val').text(mV + 'px');
    });
});

<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div id="ufg-admin-root">
    <div class="ufg-admin-preloader">
        <style>
            .ufg-admin-preloader {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: calc(100vh - 120px);
                padding: 60px 20px;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                background: #f8fafc;
                border-radius: 12px;
                margin-top: 20px;
                box-sizing: border-box;
            }
            .ufg-loader-card {
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.025);
                border: 1px solid #f1f5f9;
                padding: 48px 40px;
                text-align: center;
                max-width: 440px;
                width: 100%;
                box-sizing: border-box;
            }
            .ufg-spinner-wrapper {
                position: relative;
                width: 64px;
                height: 64px;
                margin: 0 auto 24px auto;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .ufg-loader-bg-circle {
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background-color: #eff6ff;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .ufg-spinner-ring {
                position: absolute;
                inset: -4px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #2563eb;
                border-right-color: #2563eb;
                animation: ufg-spin 0.9s linear infinite;
                box-sizing: border-box;
            }
            .ufg-preloader-title {
                font-size: 20px;
                font-weight: 700;
                color: #0f172a;
                margin: 0 0 8px 0;
                line-height: 1.3;
            }
            .ufg-preloader-subtitle {
                font-size: 14px;
                color: #64748b;
                margin: 0;
                font-weight: 400;
                line-height: 1.4;
            }
            @keyframes ufg-spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
        <div class="ufg-loader-card">
            <div class="ufg-spinner-wrapper">
                <div class="ufg-spinner-ring"></div>
                <div class="ufg-loader-bg-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                        <circle cx="9" cy="9" r="2"/>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                    </svg>
                </div>
            </div>
            <h3 class="ufg-preloader-title"><?php esc_html_e('Loading Filter Gallery...', 'filter-gallery'); ?></h3>
            <p class="ufg-preloader-subtitle"><?php esc_html_e('Please wait while the dashboard is initializing', 'filter-gallery'); ?></p>
        </div>
    </div>
</div>
<div style="position: fixed; bottom: 20px; right: 20px; background: #fff; padding: 5px 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); font-weight: bold; color: #2563eb; z-index: 9999;">
    v<?php echo UFG_VERSION; ?>
</div>
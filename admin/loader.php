<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="ufg-loader-wrapper" id="ufg-page-preloader">
    <style>
        .ufg-loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100vw;
            height: 100vh;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background: #f8fafc;
            opacity: 1;
            transition: opacity 0.25s ease-out;
        }
        .ufg-loader-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.025);
            border: 1px solid #f1f5f9;
            padding: 48px 40px;
            text-align: center;
            max-width: 440px;
            width: 90%;
            box-sizing: border-box;
        }
        .ufg-loader-spinner-box {
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
        .ufg-loader-ring {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #2563eb;
            border-right-color: #2563eb;
            animation: ufg-spin 0.9s linear infinite;
        }
        @keyframes ufg-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .ufg-loader-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }
        .ufg-loader-subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0;
            font-weight: 400;
            line-height: 1.4;
        }
    </style>
    <div class="ufg-loader-card">
        <div class="ufg-loader-spinner-box">
            <div class="ufg-loader-ring"></div>
            <div class="ufg-loader-bg-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                    <circle cx="9" cy="9" r="2"/>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                </svg>
            </div>
        </div>
        <h2 class="ufg-loader-title"><?php echo esc_html(isset($ufg_loader_title) ? $ufg_loader_title : __('Loading Filter Gallery...', 'filter-gallery')); ?></h2>
        <p class="ufg-loader-subtitle"><?php echo esc_html(isset($ufg_loader_subtitle) ? $ufg_loader_subtitle : __('Please wait while the page is initializing', 'filter-gallery')); ?></p>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var preloader = document.getElementById("ufg-page-preloader");
        if (preloader) {
            preloader.style.opacity = "0";
            setTimeout(function() {
                preloader.remove();
            }, 250);
        }
    });
</script>

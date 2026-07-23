<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Plugin Name:       Filter Gallery
 * Plugin URI:        https://wpfrank.com/
 * Description:       Filter Gallery is a lightweight and powerful WordPress plugin to create beautiful filterable galleries.
 * Version:           1.1.3
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            FARAZFRANK
 * Author URI:        https://profiles.wordpress.org/farazfrank/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       filter-gallery
 * Domain Path:       /languages
 */

if (!defined('UFG_VERSION')) {
	define('UFG_VERSION', '1.1.3');
}

require_once plugin_dir_path(__FILE__) . 'includes/class-ufg-migration.php';
UFG_Migration::init();

if (!function_exists('ufg_normalize_filters_recursive')) {
	function ufg_normalize_filters_recursive(&$filters) {
		if (!is_array($filters)) return;
		foreach ($filters as &$item) {
			$is_obj = is_object($item);
			$item_arr = $is_obj ? (array)$item : $item;
			if (is_array($item_arr)) {
				$legacy_title = isset($item_arr['title']) ? $item_arr['title'] : '';
				$legacy_text = isset($item_arr['text']) ? $item_arr['text'] : '';
				$has_filterkey = isset($item_arr['filterkey']);

				if (!$has_filterkey) {
					if (!empty($legacy_title)) {
						$item_arr['filterkey'] = strtolower(str_replace(' ', '-', $legacy_title));
					} else if (!empty($legacy_text)) {
						$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
						$rand_str = '';
						for ($i = 0; $i < 5; $i++) {
							$rand_str .= $characters[wp_rand(0, 35)];
						}
						$item_arr['filterkey'] = strtolower(str_replace(' ', '-', $legacy_text)) . '-' . $rand_str;
					} else {
						$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
						$rand_str = '';
						for ($i = 0; $i < 5; $i++) {
							$rand_str .= $characters[wp_rand(0, 35)];
						}
						$item_arr['filterkey'] = 'filter-' . $rand_str;
					}
					$display_text = !empty($legacy_text) ? $legacy_text : (!empty($legacy_title) ? $legacy_title : 'Filter');
					$item_arr['title'] = $display_text;
					$item_arr['text'] = $display_text;
				}
				if (!isset($item_arr['children'])) {
					$item_arr['children'] = array();
				} else if (is_array($item_arr['children'])) {
					ufg_normalize_filters_recursive($item_arr['children']);
				}
				if (!isset($item_arr['color'])) {
					$item_arr['color'] = '#38B2F6';
				}

				$item = $is_obj ? (object)$item_arr : $item_arr;
			}
		}
	}
}

// custom image size
add_image_size('ufg_200_200', 200, 200, true);
add_image_size('ufg_300_300', 300, 300, true);
add_image_size('ufg_400_400', 400, 400, true);

// FG activation
function ufg_activation()
{
	// update current plugin version via migration class rather than activation hook to ensure migrations run
	/*
	if (is_admin()) {
		if (!function_exists('get_plugin_data')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		$ufg_plugin_data = get_plugin_data(__FILE__);
		if (isset($ufg_plugin_data['Version'])) {
			$ufg_plugin_version = $ufg_plugin_data['Version'];
			update_option('ufg_current_version', $ufg_plugin_version);
		}
	}
	*/
}
register_activation_hook(__FILE__, 'ufg_activation');

// FG deactivation
function ufg_deactivation()
{
	// update last active plugin version
	$ufg_last_version = get_option('ufg_current_version');
	if ($ufg_last_version !== "") {
		update_option('ufg_last_version', $ufg_last_version);
	}
}
register_deactivation_hook(__FILE__, 'ufg_deactivation');

// FG uninstall
function ufg_uninstall()
{
}
register_uninstall_hook(__FILE__, 'ufg_uninstall');

// load translation
function ufg_load_translation()
{
	if (did_action('plugins_loaded')) {
		// load_plugin_textdomain is discouraged since WP 4.6. 
		// WordPress automatically loads translations from the languages directory.
		// load_plugin_textdomain('filter-gallery', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}
}
add_action('init', 'ufg_load_translation');

// FG menu
function ufg_menu_page()
{
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page(
		__('Filter Gallery', 'filter-gallery'),
		'Filter Gallery',
		'manage_options',
		'filter-gallery',
		'ufg_main',
		'dashicons-format-gallery',
		65
	);

	//add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position )
	add_submenu_page('filter-gallery', 'Manage Gallery', 'Manage Gallery', 'manage_options', 'ufg-manage-gallery', 'ufg_manage_gallery');
	add_submenu_page('filter-gallery', __('Import / Export', 'filter-gallery'), __('Import / Export', 'filter-gallery'), 'manage_options', 'ufg-import-export', 'ufg_import_export_page');
	add_submenu_page('filter-gallery', __('Docs', 'filter-gallery'), __('Docs', 'filter-gallery'), 'manage_options', 'ufg-docs', 'ufg_docs_page');
	add_submenu_page('filter-gallery', __('Free vs Pro', 'filter-gallery'), __('Free vs Pro', 'filter-gallery'), 'manage_options', 'ufg-free-vs-pro', 'ufg_free_vs_pro_page');
}
add_action('admin_menu', 'ufg_menu_page');

// FG main page body
function ufg_main()
{
	ufg_enqueue_react_app();
	require 'admin/galleries.php';
}

// FG Docs page body
function ufg_docs_page()
{
	ufg_enqueue_react_app();
	require 'admin/docs.php';
}

// Free vs Pro page body
function ufg_free_vs_pro_page()
{
	wp_enqueue_style(
		'ufg-fontawesome-admin',
		plugins_url('admin/assets/fontawesome-free-6.5.2-web/css/all.min.css', __FILE__),
		array(),
		'6.5.2'
	);
	wp_enqueue_style(
		'ufg-pricing-css',
		plugins_url('admin/assets/css/ufg-pricing.css', __FILE__),
		array(),
		UFG_VERSION
	);
	require 'admin/free-vs-pro.php';
}

// FG sub menu filters page body
function ufg_manage_gallery()
{
	ufg_enqueue_react_app();
	require 'admin/manage-gallery.php';
}

function ufg_enqueue_react_app()
{
	$asset_file = include(plugin_dir_path(__FILE__) . 'build/index.asset.php');
	wp_register_script(
		'ufg-react-app',
		plugins_url('build/index.js', __FILE__),
		array('jquery', 'wp-element', 'wp-components', 'wp-api-fetch', 'wp-media-utils'),
		'5.3.1',
		true
	);
	wp_enqueue_style(
		'ufg-react-app-style',
		plugins_url('build/index.css', __FILE__),
		array('wp-components'),
		$asset_file['version']
	);

	// Enqueue FontAwesome for the Icon Picker to render correctly in the admin React UI
	wp_enqueue_style(
		'ufg-fontawesome-admin',
		plugins_url('admin/assets/fontawesome-free-6.5.2-web/css/all.min.css', __FILE__),
		array(),
		'6.5.2'
	);

	// Enqueue custom admin fixes for the React UI
	wp_enqueue_style(
		'ufg-admin-fixes',
		plugins_url('admin/assets/css/ufg-admin-fixes.css', __FILE__),
		array('ufg-react-app-style'),
		time()
	);


	// Fetch galleries for the dashboard
	global $wpdb;
	$ufg_gallery_key = "ufg_filters_";

	$cache_key = 'ufg_all_galleries';
	$ufg_all_galleries = wp_cache_get($cache_key, 'ufg_galleries');

	if (false === $ufg_all_galleries) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$ufg_all_galleries = $wpdb->get_results(
			$wpdb->prepare("SELECT option_name FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE %s ORDER BY option_id ASC", '%' . $wpdb->esc_like($ufg_gallery_key) . '%')
		);
		wp_cache_set($cache_key, $ufg_all_galleries, 'ufg_galleries', 3600);
	}

	$galleries = array();
	if (count($ufg_all_galleries)) {
		foreach ($ufg_all_galleries as $gallery) {
			$ufg_gallery_key_name = $gallery->option_name;
			$ufg_underscore_pos = strrpos($ufg_gallery_key_name, '_');
			$ufg_gallery_id = substr($ufg_gallery_key_name, ($ufg_underscore_pos + 1));
			$details = get_option("ufg_details_" . $ufg_gallery_id);
			$g_filters = get_option("ufg_filters_" . $ufg_gallery_id);
			ufg_normalize_filters_recursive($g_filters);
			$galleries[] = array(
				'id' => $ufg_gallery_id,
				'name' => isset($details['gallery_name']) ? $details['gallery_name'] : '',
				'gallery' => get_option("ufg_gallery_" . $ufg_gallery_id),
				'filters' => $g_filters,
			);
		}
	}

	// Fetch single gallery data if editing
	$currentGalleryData = null;
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if (isset($_GET['page']) && $_GET['page'] === 'ufg-manage-gallery' && isset($_GET['id'])) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$id = sanitize_text_field(wp_unslash($_GET['id']));
		$filters = get_option("ufg_filters_" . $id);
		ufg_normalize_filters_recursive($filters);
		$gallery = get_option("ufg_gallery_" . $id);
		$settings = get_option("ufg_settings_" . $id);
		$details = get_option("ufg_details_" . $id);

		$parsedImages = array();
		if (is_array($gallery) && isset($gallery['ufg-attachment-id']) && is_array($gallery['ufg-attachment-id'])) {
			foreach ($gallery['ufg-attachment-id'] as $k => $v) {
				$att_id = $v;
				$parsedImages[] = array(
					'id' => (int) $att_id,
					'url' => wp_get_attachment_image_url($att_id, 'medium'),
					'link_url' => isset($gallery['ufg-url'][$att_id]) ? $gallery['ufg-url'][$att_id] : (isset($gallery['ufg-url'][$k]) ? $gallery['ufg-url'][$k] : ''),
					'title' => isset($gallery['ufg-title'][$att_id]) ? $gallery['ufg-title'][$att_id] : '',
					'alt' => isset($gallery['ufg-alt'][$att_id]) ? $gallery['ufg-alt'][$att_id] : '',
					'description' => isset($gallery['ufg-description'][$att_id]) ? $gallery['ufg-description'][$att_id] : '',
					'filters' => isset($gallery['ufg-image-filters'][$att_id]) ? $gallery['ufg-image-filters'][$att_id] : (isset($gallery['ufg-image-filters'][$k]) ? $gallery['ufg-image-filters'][$k] : array())
				);
			}
		}

		$default_5_filters = array(
			(object) array('title' => 'One', 'text' => 'One', 'filterkey' => 'one', 'icon' => 'fas fa-camera', 'color' => '#38B2F6', 'children' => array()),
			(object) array('title' => 'Two', 'text' => 'Two', 'filterkey' => 'two', 'icon' => 'fas fa-image', 'color' => '#38B2F6', 'children' => array()),
			(object) array('title' => 'Three', 'text' => 'Three', 'filterkey' => 'three', 'icon' => 'fas fa-star', 'color' => '#38B2F6', 'children' => array()),
			(object) array('title' => 'Four', 'text' => 'Four', 'filterkey' => 'four', 'icon' => 'fas fa-heart', 'color' => '#38B2F6', 'children' => array()),
			(object) array('title' => 'Five', 'text' => 'Five', 'filterkey' => 'five', 'icon' => 'fas fa-shield-alt', 'color' => '#38B2F6', 'children' => array()),
		);
		if (empty($filters) || !is_array($filters)) {
			$filters = $default_5_filters;
		}

		$currentGalleryData = array(
			'id' => $id,
			'name' => isset($details['gallery_name']) ? $details['gallery_name'] : '',
			'filters' => $filters,
			'images' => $parsedImages,
			'settings' => is_array($settings) ? array_merge(array(
				'show_filters' => 1,
				'show_filters_icon' => 1,
				'enable_deep_linking' => 0,
				'show_filters_count' => 1,
				'show_search_box' => 0,
				'search_box_placeholder' => 'Type here to search images',
				'show_all_button' => 1,
				'all_button_text' => 'All',
				'all_button_icon' => 'fas fa-filter',
				'all_button_color' => '#ffffff',
				'all_button_bg_color' => '#0A85ED',
				'parent_button_color' => '#4F46E5',
				'parent_button_bg_color' => '#EEF2FF',
				'parent_button_hover_color' => '#000000',
				'parent_active_button_color' => '#FFFFFF',
				'parent_active_button_bg_color' => '#4F46E5',
				'parent_filters_heading' => '',
				'l1_filters_heading' => '',
				'l1_button_color' => '#4F46E5',
				'l1_button_bg_color' => '#EEF2FF',
				'child_filter_effect' => 'show_hide',
				'active_button_color' => '#FFFFFF',
				'active_button_bg_color' => '#4F46E5',
				'l2_button_color' => '#4F46E5',
				'l2_button_bg_color' => '#EEF2FF',
				'l3_button_color' => '#4F46E5',
				'l3_button_bg_color' => '#EEF2FF',
				'l4_button_color' => '#4F46E5',
				'l4_button_bg_color' => '#EEF2FF',
				'columns_desktop' => 4,
				'columns_tab' => 3,
				'columns_mobile_landscape' => 3,
				'columns_mobile_portrait' => 2,
				'thumbnail_image' => 1,
				'thumbnail_image_size' => 'full',
				'thumbnail_border' => 1,
				'thumbnail_border_thickness' => 1,
				'thumbnail_border_color' => '#ffffff',
				'thumbnail_bg_color' => '#222a33',
				'image_title' => 1,
				'image_title_font_size' => 18,
				'image_title_color' => '#FFFFFF',
				'image_description' => 1,
				'image_description_font_size' => 14,
				'image_description_color' => '#FFFFFF',
				'image_description_text_limit' => 60,
				'image_hover_effect' => 'border_overlay',
				'read_more_link_sh' => 0,
				'read_more_link' => 1,
				'read_more_button_text' => 'Read More Link',
				'read_more_button_icon' => 'fas fa-link',
				'read_more_button_color' => '#ffffff',
				'read_more_button_bg_color' => '#0080ff',
				'read_more_button_target' => '_self',
				'image_sorting' => 5,
				'image_search' => 1,
				'lightbox' => 1,
				'lightbox_title' => 1,
				'lightbox_description' => 0,
				'lightbox_numbering' => 0,
				'custom_css' => '',
				'load_more' => 'off',
				'load_limit' => 10,
				'load_color' => '#0080ff',
				'load_txt_color' => '#FFFFFF',
				'load_btn_txt' => 'Load More',
				'filter_style' => 'buttons',
				'combine_filter_search' => '0',
				'filter_padding' => '8px 16px',
				'filter_margin' => '5px',
				'filter_padding_type' => 'small',
				'filter_padding_v' => '8',
				'filter_padding_h' => '16',
				'filter_margin_val' => '5',
				'l1_button_hover_color' => '#059669',
				'l1_active_button_color' => '#FFFFFF',
				'l1_active_button_bg_color' => '#059669',
				'l2_button_hover_color' => '#4F46E5',
				'l2_active_button_color' => '#FFFFFF',
				'l2_active_button_bg_color' => '#4F46E5',
				'l3_button_hover_color' => '#D97706',
				'l3_active_button_color' => '#FFFFFF',
				'l3_active_button_bg_color' => '#D97706',
				'l4_button_hover_color' => '#E11D48',
				'l4_active_button_color' => '#FFFFFF',
				'l4_active_button_bg_color' => '#E11D48'
			), $settings) : array(),
		);
	}

	wp_enqueue_media();

	wp_localize_script('ufg-react-app', 'ufgAdminData', array(
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'galleries' => $galleries,
		'currentGalleryData' => $currentGalleryData,
		'nextId' => ufg_get_next_id(),
		'defaultSettings' => array(
			'show_filters' => 1,
			'show_filters_icon' => 1,
			'enable_deep_linking' => 0,
			'show_filters_count' => 1,
			'show_search_box' => 0,
			'search_box_placeholder' => 'Type here to search images',
			'show_all_button' => 1,
			'all_button_text' => 'All',
			'all_button_icon' => 'fas fa-filter',

			'all_button_color' => '#ffffff',
			'all_button_bg_color' => '#0A85ED',
			'parent_button_color' => '#4F46E5',
			'parent_button_bg_color' => '#EEF2FF',
			'parent_button_hover_color' => '#000000',
			'parent_active_button_color' => '#FFFFFF',
			'parent_active_button_bg_color' => '#4F46E5',
			'parent_filters_heading' => '',
			'l1_filters_heading' => '',
			'l1_button_color' => '#4F46E5',
			'l1_button_bg_color' => '#EEF2FF',
			'child_filter_effect' => 'show_hide',
			'active_button_color' => '#FFFFFF',
			'active_button_bg_color' => '#4F46E5',
			'l2_button_color' => '#4F46E5',
			'l2_button_bg_color' => '#EEF2FF',
			'l3_button_color' => '#4F46E5',
			'l3_button_bg_color' => '#EEF2FF',
			'l4_button_color' => '#4F46E5',
			'l4_button_bg_color' => '#EEF2FF',
			'columns_desktop' => 4,
			'columns_tab' => 3,
			'columns_mobile_landscape' => 3,
			'columns_mobile_portrait' => 2,
			'thumbnail_image' => 1,
			'thumbnail_image_size' => 'full',
			'thumbnail_border' => 1,
			'thumbnail_border_thickness' => 1,
			'thumbnail_border_color' => '#ffffff',
			'thumbnail_bg_color' => '#222a33',
			'image_title' => 1,
			'image_title_font_size' => 18,
			'image_title_color' => '#FFFFFF',
			'image_description' => 1,
			'image_description_font_size' => 14,
			'image_description_color' => '#FFFFFF',
			'image_description_text_limit' => 60,
			'image_hover_effect' => 'border_overlay',
			'read_more_link_sh' => 0,
			'read_more_link' => 1,
			'read_more_button_text' => 'Read More Link',
			'read_more_button_icon' => 'fas fa-link',
			'read_more_button_color' => '#ffffff',
			'read_more_button_bg_color' => '#0080ff',
			'read_more_button_target' => '_self',
			'image_sorting' => 5,
			'image_search' => 1,
			'lightbox' => 1,
			'lightbox_title' => 1,
			'lightbox_description' => 0,
			'lightbox_numbering' => 0,
			'custom_css' => '',
			'load_more' => 'off',
			'load_limit' => 10,
			'load_color' => '#0080ff',
			'load_txt_color' => '#FFFFFF',
			'load_btn_txt' => 'Load More',
			'filter_style' => 'buttons',
			'combine_filter_search' => '0',
			'filter_padding' => '8px 16px',
			'filter_margin' => '5px',
			'filter_padding_type' => 'small',
			'filter_padding_v' => '8',
			'filter_padding_h' => '16',
			'filter_margin_val' => '5',
			'parent_button_hover_color' => '#000000',
			'parent_active_button_color' => '#FFFFFF',
			'parent_active_button_bg_color' => '#4F46E5',
			'l1_button_hover_color' => '#059669',
			'l1_active_button_color' => '#FFFFFF',
			'l1_active_button_bg_color' => '#059669',
			'l2_button_hover_color' => '#4F46E5',
			'l2_active_button_color' => '#FFFFFF',
			'l2_active_button_bg_color' => '#4F46E5',
			'l3_button_hover_color' => '#D97706',
			'l3_active_button_color' => '#FFFFFF',
			'l3_active_button_bg_color' => '#D97706',
			'l4_button_hover_color' => '#E11D48',
			'l4_active_button_color' => '#FFFFFF',
			'l4_active_button_bg_color' => '#E11D48'
		),
		'nonces' => array(
			'clone' => wp_create_nonce('ufg-clone-gallery'),
			'remove' => wp_create_nonce('ufg-remove-gallery'),
			'addFilters' => wp_create_nonce('add-filters'),
			'saveGallery' => wp_create_nonce('save-gallery'),
			'saveSetting' => wp_create_nonce('save-setting'),
			'addImage' => wp_create_nonce('add-image')
		),
		'version' => UFG_VERSION
	));
	wp_enqueue_script('ufg-react-app');
	wp_enqueue_script('ufg-admin-custom-js', plugins_url('admin/assets/js/ufg-admin-custom.js', __FILE__), array('jquery', 'ufg-react-app'), UFG_VERSION, true);
}

//get / create next gallery id
function ufg_get_next_id()
{
	global $wpdb;
	$ufg_options_table_name = "{$wpdb->prefix}options";
	$ufg_gallery_key = "ufg_gallery_";
	// reference : https://wordpress.stackexchange.com/questions/8825/how-do-you-properly-prepare-a-like-sql-statement
	$cache_key = 'ufg_next_gallery_id';
	$ufg_gallery_count_res = wp_cache_get($cache_key, 'ufg_galleries');

	if (false === $ufg_gallery_count_res) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$ufg_gallery_count_res = $wpdb->get_row(
			$wpdb->prepare("SELECT option_name FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE %s ORDER BY option_id DESC LIMIT 1", '%' . $wpdb->esc_like($ufg_gallery_key) . '%'),
			ARRAY_N
		);
		wp_cache_set($cache_key, $ufg_gallery_count_res, 'ufg_galleries', 3600);
	}

	if ($wpdb->num_rows) {
		$ufg_gallery_last_key = $ufg_gallery_count_res[0];
		$ufg_underscore_pos = strrpos($ufg_gallery_last_key, '_');
		$ufg_last_slider_id = (int) substr($ufg_gallery_last_key, ($ufg_underscore_pos + 1));
		return ($ufg_last_slider_id + 1);
	} else {
		return 1;
	}
}

// 1. save filters ajax
function ufg_gallery_filters_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'add-filters')) {
		die;
	} else {
		// save filters

		$ufg_gallery_id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';
		$ufg_gallery_name = isset($_POST['gallery_name']) ? sanitize_text_field(wp_unslash($_POST['gallery_name'])) : '';

		// gerate random unique key string start
		if (!function_exists('UFGgenerateRandomString')) {
			function UFGgenerateRandomString($length = 7)
			{
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[wp_rand(0, $charactersLength - 1)];
				}
				return $randomString;
			}
		}

		// add filterkey index into newly added filers start
		if (!function_exists('UFGaddMissingFilterKeys')) {
			function UFGaddMissingFilterKeys(&$array)
			{
				foreach ($array as &$item) {
					// Check for missing filterkey in parent
					if (!isset($item->filterkey)) {
						$item->filterkey = strtolower(str_replace(' ', '-', $item->title)) . '-' . UFGgenerateRandomString();
					}

					// Check for children
					if (isset($item->children) && is_array($item->children)) {
						foreach ($item->children as &$child) {
							if (!isset($child->filterkey)) {
								$child->filterkey = strtolower(str_replace(' ', '-', $child->title)) . '-' . UFGgenerateRandomString();
							}
						}
					}
				}
			}
		}

		$filters = array();
		if (isset($_POST['filters'])) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized in loop below
			$filters = json_decode(stripslashes(wp_unslash($_POST['filters'])), false);
		}

		if (is_array($filters)) {
			// Sanitize filter data
			foreach ($filters as &$item) {
				$item->text = sanitize_text_field($item->text);
				if (isset($item->children) && is_array($item->children)) {
					foreach ($item->children as &$child) {
						$child->text = sanitize_text_field($child->text);
					}
				}
			}
			UFGaddMissingFilterKeys($filters);
		}

		update_option("ufg_filters_" . $ufg_gallery_id, $filters);

		$ufg_details = array('ufg_gallery_id' => $ufg_gallery_id, 'gallery_name' => $ufg_gallery_name);
		update_option("ufg_details_" . $ufg_gallery_id, $ufg_details);
	}
	wp_send_json_success();
}
add_action('wp_ajax_ufg_gallery_filters', 'ufg_gallery_filters_callback');

// 2. add images to the gallery
function ufg_li_generate_ajax_callback()
{
	if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'add-image')) {
		wp_die('Nonce verification failed');
	}

	if (isset($_POST['attachment_id']) && isset($_POST['ufg_gallery_id'])) {
		//defaults
		$ufg_title = $ufg_alt = $ufg_description = $ufg_url = "";
		//load values
		$ufg_attachment_id = isset($_POST['attachment_id']) ? sanitize_text_field(wp_unslash($_POST['attachment_id'])) : 0;
		$ufg_title = get_the_title($ufg_attachment_id);
		$ufg_alt = get_post_meta($ufg_attachment_id, '_wp_attachment_image_alt', TRUE);
		//wp_get_attachment_image_src ( int $ufg_attachment_id, string|array $size = 'thumbnail', bool $icon = false )
		//thumb, thumbnail, medium, large, post-thumbnail
		$medium = wp_get_attachment_image_src($ufg_attachment_id, 'medium', true); // attachment medium URL
		$attachment = get_post($ufg_attachment_id);
		$ufg_description = $attachment->post_content; // attachment description
		//get saved filters
		$ufg_gallery_id = isset($_POST['ufg_gallery_id']) ? sanitize_text_field(wp_unslash($_POST['ufg_gallery_id'])) : 0;
		$filters = get_option("ufg_filters_" . $ufg_gallery_id);
		?>
		<script>
			jQuery(document).ready(function () {
				jQuery(function (jQuery) {
					jQuery('.ufg-image-filters').multiselect({
						buttonWidth: '100%',
						enableFiltering: true,
						nonSelectedText: "<?php echo esc_js(__('Select Filters', 'filter-gallery-pro')); ?>"
					});
				});
			});
		</script>
		<li class="ufg-admin-image-item ufg-image-<?php echo intval($ufg_attachment_id); ?>"
			data-position="<?php echo intval($ufg_attachment_id); ?>">
			<div class="ufg-admin-form-group">
				<input type="hidden" class="ufg-admin-form-control ufg-attachment-id"
					name="ufg-attachment-id[<?php echo intval($ufg_attachment_id); ?>]"
					value="<?php echo intval($ufg_attachment_id); ?>">
				<img loading="lazy" src="<?php echo esc_url($medium[0]); ?>" class="ufg-admin-img" alt="" width="150px"
					height="150px">
			</div>
			<div class="ufg-admin-form-group">
				<input type="text" class="ufg-admin-form-control ufg-title"
					name="ufg-title[<?php echo intval($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_title); ?>"
					placeholder="<?php esc_attr_e('Image Title', 'filter-gallery-pro'); ?>">
			</div>
			<div class="ufg-admin-form-group">
				<input type="text" class="ufg-admin-form-control ufg-alt"
					name="ufg-alt[<?php echo intval($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_alt); ?>"
					placeholder="<?php esc_attr_e('Image Alternative Text', 'filter-gallery-pro'); ?>">
			</div>
			<div class="ufg-admin-form-group">
				<textarea class="ufg-admin-form-control ufg-description"
					name="ufg-description[<?php echo intval($ufg_attachment_id); ?>]"
					placeholder="<?php esc_attr_e('Image Description', 'filter-gallery-pro'); ?>"><?php echo esc_textarea($ufg_description); ?></textarea>
			</div>
			<div class="ufg-admin-form-group">
				<input type="url" disabled readonly class="ufg-admin-form-control ufg-url"
					name="ufg-url[<?php echo intval($ufg_attachment_id); ?>]" value=""
					placeholder="<?php esc_attr_e('Link URL (Pro Only)', 'filter-gallery'); ?>">
			</div>
			<div class="ufg-admin-form-group">
				<?php
				$ufg_gallery_id_num = (int) $ufg_gallery_id;
				$ufg_gallery_data = get_option("ufg_gallery_" . $ufg_gallery_id_num);
				$selected_filters = (isset($ufg_gallery_data['ufg-image-filters'][$ufg_attachment_id])) ? $ufg_gallery_data['ufg-image-filters'][$ufg_attachment_id] : array();
				echo wp_kses_post(ufg_get_filter_list($ufg_attachment_id, $filters, $selected_filters));
				?>
			</div>
			<div class="ufg-admin-form-group ufg-admin-text-center">
				<button type="button" id="ufg-remove-image"
					onclick="return removeImage('<?php echo intval($ufg_attachment_id); ?>');"
					class="ufg-admin-btn ufg-admin-btn-remove"><?php esc_html_e('Remove', 'filter-gallery-pro'); ?></button>
			</div>
		</li>
		<?php
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
add_action('wp_ajax_ufg_image_id', 'ufg_li_generate_ajax_callback');

// generate filter select for image
function ufg_get_filter_list($ufg_attachment_id, $filters, $selected_filters)
{

	if (is_array($filters) && $filters_count = count($filters)) {

		/* echo "<pre>";
		echo "Filters";
		echo "<hr>";
		print_r(($filters));
		echo "</pre>";
		echo "<hr>"; */

		/* echo "<pre>";
		echo "Selected Filters";
		echo "<hr>";
		print_r(($selected_filters));
		echo "</pre>";
		echo "<hr>"; */

		/* foreach ($selected_filters as $key => $value) {
			echo '<input type="text" class="ufg-filter-image" name="ufg-filter-image['.$value.'][]" id="ufg-filter-image[]" style="width: 98%;" value="'.$ufg_attachment_id.'" >';
		} */
		// zero = Parent Filters | level_one = Child Filters
		echo '<select name="ufg-image-filters[' . intval($ufg_attachment_id) . '][]" class="ufg-image-filters" data-max="" multiple="multiple">';
		for ($i = 0; $i < $filters_count; $i++) {
			$text_zero = $filters[$i]->text;
			$value_zero = str_replace(" ", "-", strtolower($filters[$i]->filterkey));
			if (is_array($selected_filters)) {
				if (in_array($value_zero, $selected_filters) === TRUE)
					$selected = "selected=selected";
				else
					$selected = "";
			} else {
				$selected_filters = array();
			}
			echo "<option value='" . esc_attr($value_zero) . "' " . esc_attr($selected) . ">" . esc_html($text_zero) . "</option>";

			//check level one children
			$child_count_level_one = 0;
			$child_count_level_two = 0;
			if (is_array($filters[$i]->children)) {
				$child_count_level_one = count($filters[$i]->children);
			}
			if ($child_count_level_one) {
				$level_one_array = $filters[$i]->children;
				for ($j = 0; $j < $child_count_level_one; $j++) {
					$value_level_one = str_replace(" ", "-", strtolower($level_one_array[$j]->filterkey));
					if (in_array($value_level_one, $selected_filters) === TRUE)
						$selected_one = "selected=selected";
					else
						$selected_one = "";
					echo "<option value='" . esc_attr($value_level_one) . "' " . esc_attr($selected_one) . ">&#10148; " . esc_html($level_one_array[$j]->text) . "</option>";

					/*
					//check level 2 children
					$child_count_level_two = count($level_one_array[$j]->children);
					if($child_count_level_two) {
						$level_two_array = $level_one_array[$j]->children;
						for($k = 0; $k < $child_count_level_two; $k++){
							$value_level_two = str_replace(" ","-", strtolower($level_two_array[$k]->text));
							if(in_array($value_level_two, $selected_filters) === TRUE) $selected_two = "selected=selected"; else $selected_two = "";
							echo "<option value='$value_level_two' $selected_two>&#10148; &#10148; ".$level_two_array[$k]->text."</option>";


							//check level 3 children
							$child_count_level_three = count($level_two_array[$k]->children);
							if($child_count_level_three) {
								$level_three_array = $level_two_array[$k]->children;
								for($l = 0; $l < $child_count_level_three; $l++){
									$value_level_three = str_replace(" ","-", strtolower($level_three_array[$l]->text));
									if(in_array($value_level_three, $selected_filters) === TRUE) $selected_three = "selected=selected"; else $selected_three = "";
									echo "<option value='$value_level_three' $selected_three>&#10148; &#10148; &#10148; ".$level_three_array[$l]->text."</option>";


									//check level 4 children
									$child_count_level_four = count($level_three_array[$l]->children);
									if($child_count_level_four) {
										$level_four_array = $level_three_array[$l]->children;
										for($m = 0; $m < $child_count_level_four; $m++){
											$value_level_four = str_replace(" ","-", strtolower($level_four_array[$m]->text));
											if(in_array($value_level_four, $selected_filters) === TRUE) $selected_four = "selected=selected"; else $selected_four = "";
											echo "<option value='$value_level_four' $selected_four>&#10148; &#10148; &#10148; &#10148; ".$level_four_array[$m]->text."</option>";


											//check level 5 children
											$child_count_level_five = count($level_four_array[$m]->children);
											if($child_count_level_five) {
												$level_five_array = $level_four_array[$m]->children;
												for($n = 0; $n < $child_count_level_five; $n++){
													$value_level_five = str_replace(" ","-", strtolower($level_five_array[$n]->text));
													if(in_array($value_level_five, $selected_filters) === TRUE) $selected_five = "selected=selected"; else $selected_five = "";
													echo "<option value='$value_level_five' $selected_five>&#10148; &#10148; &#10148; &#10148; &#10148; ".$level_five_array[$n]->text."</option>";
												}
											}
											//check level 5 children end

										}
									}
									//check level 4 children end

								}
							}
							//check level 3 children end

						}
					}
					//check level 2 children end
					*/
				}
			}
		}
		echo '</select>';
	}
}

// 3. save gallery images
function ufg_save_gallery_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'save-gallery')) {
		wp_send_json_error('Nonce verification failed');
	} else {
		$ufg_gallery_id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- data is sanitized after parse_str below
		$image_id_raw = isset($_POST['image_id']) ? wp_unslash($_POST['image_id']) : '';
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- data is sanitized after parse_str below
		$image_title_raw = isset($_POST['image_title']) ? wp_unslash($_POST['image_title']) : '';
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- data is sanitized after parse_str below
		$image_alt_raw = isset($_POST['image_alt']) ? wp_unslash($_POST['image_alt']) : '';
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- data is sanitized after parse_str below
		$image_description_raw = isset($_POST['image_description']) ? wp_unslash($_POST['image_description']) : '';
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- data is sanitized after parse_str below
		$image_url_raw = isset($_POST['image_url']) ? wp_unslash($_POST['image_url']) : '';
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- data is sanitized after parse_str below
		$image_filters_raw = isset($_POST['image_filters']) ? wp_unslash($_POST['image_filters']) : '';

		$ufg_image_id = $ufg_image_title = $ufg_image_alt = $ufg_image_description = $ufg_image_url = $ufg_image_filters = array();

		parse_str($image_id_raw, $ufg_image_id);
		parse_str($image_title_raw, $ufg_image_title);
		parse_str($image_alt_raw, $ufg_image_alt);
		parse_str($image_description_raw, $ufg_image_description);
		parse_str($image_url_raw, $ufg_image_url);
		parse_str($image_filters_raw, $ufg_image_filters);

		// update attachment meta - title, alt, description
		if (isset($ufg_image_id['ufg-attachment-id']) && is_array($ufg_image_id['ufg-attachment-id'])) {
			foreach ($ufg_image_id['ufg-attachment-id'] as $ufg_id) {
				$ufg_title = isset($ufg_image_title['ufg-title'][$ufg_id]) ? sanitize_text_field($ufg_image_title['ufg-title'][$ufg_id]) : '';
				$ufg_description = isset($ufg_image_description['ufg-description'][$ufg_id]) ? sanitize_textarea_field($ufg_image_description['ufg-description'][$ufg_id]) : '';
				$ufg_alt = isset($ufg_image_alt['ufg-alt'][$ufg_id]) ? sanitize_text_field($ufg_image_alt['ufg-alt'][$ufg_id]) : '';

				$ufg_image_update = array(
					'ID' => $ufg_id,
					'post_title' => $ufg_title,
					'post_content' => $ufg_description,
				);
				wp_update_post($ufg_image_update);
				update_post_meta($ufg_id, '_wp_attachment_image_alt', $ufg_alt);
			}
		}

		$ufg_gallery = array(
			'ufg-attachment-id' => isset($ufg_image_id['ufg-attachment-id']) ? $ufg_image_id['ufg-attachment-id'] : array(),
			'ufg-title' => isset($ufg_image_title['ufg-title']) ? $ufg_image_title['ufg-title'] : array(),
			'ufg-alt' => isset($ufg_image_alt['ufg-alt']) ? $ufg_image_alt['ufg-alt'] : array(),
			'ufg-description' => isset($ufg_image_description['ufg-description']) ? $ufg_image_description['ufg-description'] : array(),
			'ufg-url' => isset($ufg_image_url['ufg-url']) ? $ufg_image_url['ufg-url'] : array(),
			'ufg-image-filters' => isset($ufg_image_filters['ufg-image-filters']) ? $ufg_image_filters['ufg-image-filters'] : array(),
		);

		update_option("ufg_gallery_" . $ufg_gallery_id, $ufg_gallery);
		wp_send_json_success();
	}
}
add_action('wp_ajax_ufg_save_gallery', 'ufg_save_gallery_callback');

// 4. load gallery images
function ufg_load_gallery_callback($ufg_gallery_id)
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'load-gallery')) {
		die;
	} else {
		//get / create next gallery id
		if (isset($_POST['id'])) {
			$ufg_gallery_id = sanitize_text_field(wp_unslash($_POST['id']));

			// load filters and gallery options
			$ufg_filters = get_option("ufg_filters_" . $ufg_gallery_id);
			$ufg_gallery = get_option("ufg_gallery_" . $ufg_gallery_id);
			//defaults
			$ufg_title = $ufg_alt = $ufg_description = $ufg_url = "";
			foreach ($ufg_gallery['ufg-attachment-id'] as $key => $value) {
				$ufg_attachment_id = $value;
				//load values
				$ufg_title = get_the_title($ufg_attachment_id);
				$ufg_alt = get_post_meta($ufg_attachment_id, '_wp_attachment_image_alt', TRUE);
				//wp_get_attachment_image_src ( int $ufg_attachment_id, string|array $size = 'thumbnail', bool $icon = false )
				//thumb, thumbnail, medium, large, post-thumbnail
				$medium = wp_get_attachment_image_src($ufg_attachment_id, 'medium', true); // attachment medium URL
				$attachment = get_post($ufg_attachment_id);
				$ufg_description = $attachment->post_content; // attachment description
				//get saved filters
				$filters = get_option("ufg_filters_" . $ufg_gallery_id);
				?>
				<li class="ufg-admin-image-item ufg-image-<?php echo intval($ufg_attachment_id); ?>"
					data-position="<?php echo intval($ufg_attachment_id); ?>">
					<div class="ufg-admin-form-group">
						<input type="hidden" class="ufg-admin-form-control ufg-attachment-id" id="ufg-attachment-id"
							name="ufg-attachment-id[<?php echo intval($ufg_attachment_id); ?>]"
							value="<?php echo intval($ufg_attachment_id); ?>">
						<img src="<?php echo esc_url($medium[0]); ?>" class="ufg-admin-img" alt="" width="150px" height="150px">
						<span class="ufg-admin-badge">Image ID: <?php echo intval($ufg_attachment_id); ?></span>
					</div>
					<div class="ufg-admin-form-group">
						<input type="text" class="ufg-admin-form-control ufg-title"
							name="ufg-title[<?php echo intval($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_title); ?>"
							placeholder="<?php esc_attr_e('Image Title', 'filter-gallery-pro'); ?>">
					</div>
					<div class="ufg-admin-form-group">
						<input type="text" class="ufg-admin-form-control ufg-alt"
							name="ufg-alt[<?php echo intval($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_alt); ?>"
							placeholder="<?php esc_attr_e('Image Alternative Text', 'filter-gallery-pro'); ?>">
					</div>
					<div class="ufg-admin-form-group">
						<textarea class="ufg-admin-form-control ufg-description"
							name="ufg-description[<?php echo intval($ufg_attachment_id); ?>]"
							placeholder="<?php esc_attr_e('Image Description', 'filter-gallery-pro'); ?>"><?php echo esc_textarea($ufg_description); ?></textarea>
					</div>
					<div class="ufg-admin-form-group">
						<input type="url" disabled readonly class="ufg-admin-form-control ufg-url"
							name="ufg-url[<?php echo intval($ufg_attachment_id); ?>]"
							value=""
							placeholder="<?php esc_attr_e('Link URL (Pro Only)', 'filter-gallery'); ?>">
					</div>
					<div class="ufg-admin-form-group">
						<?php $selected_filters = isset($ufg_gallery['ufg-image-filters'][$value]) ? $ufg_gallery['ufg-image-filters'][$value] : array();

						echo wp_kses_post(ufg_get_filter_list($ufg_attachment_id, $filters, $selected_filters));
						?>
					</div>
					<div class="ufg-admin-form-group ufg-admin-text-center">
						<button type="button" id="ufg-remove-image"
							onclick="return removeImage('<?php echo intval($ufg_attachment_id); ?>');"
							class="ufg-admin-btn ufg-admin-btn-remove"><?php esc_html_e('Remove', 'filter-gallery-pro'); ?></button>
					</div>
				</li>
				<?php
			}
			?>
			<script>
				jQuery(document).ready(function () {
					jQuery(function (jQuery) {
						jQuery('.ufg-image-filters').multiselect({
							buttonWidth: '100%',
							enableFiltering: true,
							nonSelectedText: "<?php echo esc_js(__('Select Filters', 'filter-gallery-pro')); ?>",
						});
					});
				});
			</script>
			<?php
			die;
		}
	}
}
add_action('wp_ajax_ufg_load_gallery', 'ufg_load_gallery_callback');

// 5. save gallery settings
function ufg_save_setting_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'save-setting')) {
		die;
	} else {
		$ufg_gallery_id = isset($_POST['ufg_gallery_id']) ? sanitize_text_field(wp_unslash($_POST['ufg_gallery_id'])) : 0;
		$settings = array(
			//gallery details
			'ufg_gallery_id' => $ufg_gallery_id,

			//filters settings
			'default_filter' => isset($_POST['default_filter']) ? sanitize_text_field(wp_unslash($_POST['default_filter'])) : '',
			'filter_style' => (isset($_POST['filter_style']) && sanitize_text_field(wp_unslash($_POST['filter_style'])) === 'dropdown') ? 'dropdown' : 'buttons',
			'combine_filter_search' => '0',
			'filter_padding' => isset($_POST['filter_padding']) ? sanitize_text_field(wp_unslash($_POST['filter_padding'])) : '',
			'filter_margin' => isset($_POST['filter_margin']) ? sanitize_text_field(wp_unslash($_POST['filter_margin'])) : '',
			'filter_padding_type' => isset($_POST['filter_padding_type']) ? sanitize_text_field(wp_unslash($_POST['filter_padding_type'])) : '',
			'filter_padding_v' => isset($_POST['filter_padding_v']) ? sanitize_text_field(wp_unslash($_POST['filter_padding_v'])) : '',
			'filter_padding_h' => isset($_POST['filter_padding_h']) ? sanitize_text_field(wp_unslash($_POST['filter_padding_h'])) : '',
			'filter_margin_val' => isset($_POST['filter_margin_val']) ? sanitize_text_field(wp_unslash($_POST['filter_margin_val'])) : '',
			'parent_button_hover_color' => isset($_POST['parent_button_hover_color']) ? sanitize_text_field(wp_unslash($_POST['parent_button_hover_color'])) : '',
			'parent_active_button_color' => isset($_POST['parent_active_button_color']) ? sanitize_text_field(wp_unslash($_POST['parent_active_button_color'])) : '',
			'parent_active_button_bg_color' => isset($_POST['parent_active_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['parent_active_button_bg_color'])) : '',
			'show_filters' => isset($_POST['show_filters']) ? sanitize_text_field(wp_unslash($_POST['show_filters'])) : '',
			'show_search_box' => isset($_POST['show_search_box']) ? sanitize_text_field(wp_unslash($_POST['show_search_box'])) : '',
			'search_box_placeholder' => isset($_POST['search_box_placeholder']) ? sanitize_text_field(wp_unslash($_POST['search_box_placeholder'])) : '',
			'show_filters_icon' => isset($_POST['show_filters_icon']) ? sanitize_text_field(wp_unslash($_POST['show_filters_icon'])) : '',
			'enable_deep_linking' => '0',
			//'show_filters_count' => isset($_POST['show_filters_count']) ? sanitize_text_field(wp_unslash($_POST['show_filters_count'])) : '',
			'show_all_button' => isset($_POST['show_all_button']) ? sanitize_text_field(wp_unslash($_POST['show_all_button'])) : '',
			'all_button_text' => isset($_POST['all_button_text']) ? sanitize_text_field(wp_unslash($_POST['all_button_text'])) : '',
			'all_button_icon' => isset($_POST['all_button_icon']) ? sanitize_text_field(wp_unslash($_POST['all_button_icon'])) : '',

			'all_button_color' => isset($_POST['all_button_color']) ? sanitize_text_field(wp_unslash($_POST['all_button_color'])) : '',
			'all_button_bg_color' => isset($_POST['all_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['all_button_bg_color'])) : '',
			'parent_filters_heading' => isset($_POST['parent_filters_heading']) ? sanitize_text_field(wp_unslash($_POST['parent_filters_heading'])) : '',
			'parent_button_color' => isset($_POST['parent_button_color']) ? sanitize_text_field(wp_unslash($_POST['parent_button_color'])) : '',
			'parent_button_bg_color' => isset($_POST['parent_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['parent_button_bg_color'])) : '',
			'l1_filters_heading' => isset($_POST['l1_filters_heading']) ? sanitize_text_field(wp_unslash($_POST['l1_filters_heading'])) : '',
			'l1_button_color' => isset($_POST['l1_button_color']) ? sanitize_text_field(wp_unslash($_POST['l1_button_color'])) : '',
			'l1_button_bg_color' => isset($_POST['l1_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['l1_button_bg_color'])) : '',
			'l2_button_color' => isset($_POST['l2_button_color']) ? sanitize_text_field(wp_unslash($_POST['l2_button_color'])) : '',
			'l2_button_bg_color' => isset($_POST['l2_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['l2_button_bg_color'])) : '',
			'l3_button_color' => isset($_POST['l3_button_color']) ? sanitize_text_field(wp_unslash($_POST['l3_button_color'])) : '',
			'l3_button_bg_color' => isset($_POST['l3_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['l3_button_bg_color'])) : '',
			'l4_button_color' => isset($_POST['l4_button_color']) ? sanitize_text_field(wp_unslash($_POST['l4_button_color'])) : '',
			'l4_button_bg_color' => isset($_POST['l4_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['l4_button_bg_color'])) : '',
			'child_filter_effect' => isset($_POST['child_filter_effect']) ? sanitize_text_field(wp_unslash($_POST['child_filter_effect'])) : '',
			'active_button_color' => isset($_POST['active_button_color']) ? sanitize_text_field(wp_unslash($_POST['active_button_color'])) : '',
			'active_button_bg_color' => isset($_POST['active_button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['active_button_bg_color'])) : '',

			//gallery settings
			'columns_desktop' => (isset($_POST['columns_desktop']) && in_array($_POST['columns_desktop'], array('3', '4', '6'), true)) ? sanitize_text_field(wp_unslash($_POST['columns_desktop'])) : '4',
			'columns_tab' => (isset($_POST['columns_tab']) && in_array($_POST['columns_tab'], array('1', '2', '3'), true)) ? sanitize_text_field(wp_unslash($_POST['columns_tab'])) : '3',
			'columns_mobile_landscape' => (isset($_POST['columns_mobile_landscape']) && in_array($_POST['columns_mobile_landscape'], array('1', '2', '3'), true)) ? sanitize_text_field(wp_unslash($_POST['columns_mobile_landscape'])) : '3',
			'columns_mobile_portrait' => (isset($_POST['columns_mobile_portrait']) && in_array($_POST['columns_mobile_portrait'], array('1', '2'), true)) ? sanitize_text_field(wp_unslash($_POST['columns_mobile_portrait'])) : '2',
			'thumbnail_image' => isset($_POST['thumbnail_image']) ? sanitize_text_field(wp_unslash($_POST['thumbnail_image'])) : '',
			'thumbnail_image_size' => isset($_POST['thumbnail_image_size']) ? sanitize_text_field(wp_unslash($_POST['thumbnail_image_size'])) : '',
			'thumbnail_border' => isset($_POST['thumbnail_border']) ? sanitize_text_field(wp_unslash($_POST['thumbnail_border'])) : '',
			'thumbnail_border_thickness' => isset($_POST['thumbnail_border_thickness']) ? sanitize_text_field(wp_unslash($_POST['thumbnail_border_thickness'])) : '',
			'thumbnail_border_color' => isset($_POST['thumbnail_border_color']) ? sanitize_text_field(wp_unslash($_POST['thumbnail_border_color'])) : '',
			'thumbnail_bg_color' => isset($_POST['thumbnail_bg_color']) ? sanitize_text_field(wp_unslash($_POST['thumbnail_bg_color'])) : '',
			'image_title' => isset($_POST['image_title']) ? sanitize_text_field(wp_unslash($_POST['image_title'])) : '',
			'image_title_font_size' => isset($_POST['image_title_font_size']) ? sanitize_text_field(wp_unslash($_POST['image_title_font_size'])) : '',
			'image_title_color' => isset($_POST['image_title_color']) ? sanitize_text_field(wp_unslash($_POST['image_title_color'])) : '',
			'image_description' => isset($_POST['image_description']) ? sanitize_text_field(wp_unslash($_POST['image_description'])) : '',
			'image_description_font_size' => isset($_POST['image_description_font_size']) ? sanitize_text_field(wp_unslash($_POST['image_description_font_size'])) : '',
			'image_description_color' => isset($_POST['image_description_color']) ? sanitize_text_field(wp_unslash($_POST['image_description_color'])) : '',
			'image_description_text_limit' => isset($_POST['image_description_text_limit']) ? sanitize_text_field(wp_unslash($_POST['image_description_text_limit'])) : '',
			'image_hover_effect' => isset($_POST['image_hover_effect']) ? sanitize_text_field(wp_unslash($_POST['image_hover_effect'])) : '',
			'read_more_link_sh' => '0',
			'read_more_link' => '1',
			'read_more_button_text' => 'Read More Link',
			'read_more_button_icon' => 'fas fa-link',
			'read_more_button_color' => '#ffffff',
			'read_more_button_bg_color' => '#0080ff',
			'read_more_button_target' => '_self',
			'image_sorting' => (isset($_POST['image_sorting']) && in_array($_POST['image_sorting'], array('5', '1', '2'), true)) ? sanitize_text_field(wp_unslash($_POST['image_sorting'])) : '5',
			'custom_css' => '',

			//lightbox settings
			'lightbox' => isset($_POST['lightbox']) ? sanitize_text_field(wp_unslash($_POST['lightbox'])) : '',
			'lightbox_title' => isset($_POST['lightbox_title']) ? sanitize_text_field(wp_unslash($_POST['lightbox_title'])) : '',
			'lightbox_description' => '0',
			'lightbox_numbering' => '0',

			// Load More
			'load_more' => 'off',
			'load_limit' => isset($_POST['load_limit']) ? sanitize_text_field(wp_unslash($_POST['load_limit'])) : '',
			'load_color' => isset($_POST['load_color']) ? sanitize_text_field(wp_unslash($_POST['load_color'])) : '',
			'load_txt_color' => isset($_POST['load_txt_color']) ? sanitize_text_field(wp_unslash($_POST['load_txt_color'])) : '',
			'load_btn_txt' => isset($_POST['load_btn_txt']) ? sanitize_text_field(wp_unslash($_POST['load_btn_txt'])) : '',
		);

		update_option("ufg_settings_" . $ufg_gallery_id, $settings);
		die;
	}
}
add_action('wp_ajax_ufg_save_setting', 'ufg_save_setting_callback');

// 6. remove gallery/galleries start
function ufg_remove_gallery_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-remove-gallery')) {
		echo "Nonce not verified.";
		die;
	} else {
		// verified action
		if (isset($_POST['ufg_gallery_id']) && isset($_POST['do_action'])) {

			$raw_id = is_array($_POST['ufg_gallery_id']) ? array_map('sanitize_text_field', wp_unslash($_POST['ufg_gallery_id'])) : sanitize_text_field(wp_unslash($_POST['ufg_gallery_id']));
			$ufg_gallery_id = $raw_id;
			$ufg_do_action = sanitize_text_field(wp_unslash($_POST['do_action']));

			//single gallery delete
			if ($ufg_do_action == 'single') {
				delete_option("ufg_filters_" . $ufg_gallery_id);
				delete_option("ufg_gallery_" . $ufg_gallery_id);
				delete_option("ufg_settings_" . $ufg_gallery_id);
				delete_option("ufg_details_" . $ufg_gallery_id);
			}

			//multiple gallery delete
			if ($ufg_do_action == 'multiple' && is_array($ufg_gallery_id)) {
				foreach ($ufg_gallery_id as $ufg_single_id) {
					delete_option("ufg_filters_" . $ufg_single_id);
					delete_option("ufg_gallery_" . $ufg_single_id);
					delete_option("ufg_settings_" . $ufg_single_id);
					delete_option("ufg_details_" . $ufg_single_id);
				}
			}
		}
		wp_die();
	}
}
add_action('wp_ajax_ufg_remove_gallery', 'ufg_remove_gallery_callback');
// 6. remove gallery/galleries end

// 7. clone gallery start
function ufg_clone_gallery_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-clone-gallery')) {
		echo "Nonce not verified action.";
		die;
	} else {
		// verified action
		if (isset($_POST['ufg_gallery_id']) && isset($_POST['ufg_gallery_counter'])) {
			$ufg_gallery_id = sanitize_text_field(wp_unslash($_POST['ufg_gallery_id']));
			$ufg_gallery_counter = sanitize_text_field(wp_unslash($_POST['ufg_gallery_counter']));

			//get cloning gallery data
			$ufg_cloning_filters = get_option("ufg_filters_" . $ufg_gallery_id);
			$ufg_cloning_gallery = get_option("ufg_gallery_" . $ufg_gallery_id);
			$ufg_cloning_setting = get_option("ufg_settings_" . $ufg_gallery_id);
			$ufg_cloning_details = get_option("ufg_details_" . $ufg_gallery_id);

			//print_r($ufg_cloning_gallery);

			//generate new gallery id for clone
			$new_ufg_gallery_id = ufg_get_next_id();
			$new_ufg_gallery_name = $ufg_cloning_details['gallery_name'] . ' - Clone';

			// update clone id into gallery data
			foreach ($ufg_cloning_gallery as $key => $value) {
				$ufg_cloning_setting['ufg_gallery_id'] = $new_ufg_gallery_id;
			}
			// update gallery details
			$ufg_cloning_details = array('ufg_gallery_id' => $ufg_gallery_id, 'gallery_name' => $new_ufg_gallery_name);

			//print_r($ufg_cloning_gallery);
			if ($new_ufg_gallery_id > $ufg_gallery_id) {
				add_option('ufg_filters_' . $new_ufg_gallery_id, $ufg_cloning_filters);
				add_option('ufg_gallery_' . $new_ufg_gallery_id, $ufg_cloning_gallery);
				add_option('ufg_settings_' . $new_ufg_gallery_id, $ufg_cloning_setting);
				update_option('ufg_details_' . $new_ufg_gallery_id, $ufg_cloning_details);
				$ufg_do_action = "'single'";

				echo '
				<tr id=' . intval($new_ufg_gallery_id) . '>
					<th scope="row">' . intval($ufg_gallery_counter) . '</th>
					<td>' . esc_html($new_ufg_gallery_name) . '</td>
					<td>
						<input type="text" id="ufg-shortcode-' . intval($new_ufg_gallery_id) . '" class="btn btn-info btn-sm" value="[ufg id=' . intval($new_ufg_gallery_id) . ']">
						<button type="button" id="ufg-shortcode-' . intval($new_ufg_gallery_id) . '" class="btn btn-info btn-sm" title="Click To Copy Gallery Shortcode" onclick="return UFGCopyShortcode(' . intval($new_ufg_gallery_id) . ')">Copy</button>
						<button class="btn btn-sm btn-success d-none ufg-copied-' . intval($new_ufg_gallery_id) . '">Copied</button>
					</td>
					<td>
						<button type="button" id="ufg-clone" class="btn btn-warning btn-sm" title="Clone Gallery" value="' . intval($new_ufg_gallery_id) . '" onclick="return UFGCloneGallery(' . intval($new_ufg_gallery_id) . ', ' . intval($ufg_gallery_counter) . ');"><i class="fas fa-copy"></i></button>
						<a href="?page=ufg-manage-gallery&id=' . intval($new_ufg_gallery_id) . '" class="btn btn-primary btn-sm" href="#"><i class="fas fa-edit"></i></a>
						<button id="ufg-delete-gallery" class="btn btn-danger btn-sm" title="Delete Gallery" value="' . intval($new_ufg_gallery_id) . '" onclick="return UFGRemoveGallery(\'' . intval($new_ufg_gallery_id) . '\', \'' . esc_js($ufg_do_action) . '\');"><i class="fas fa-trash-alt"></i></button>
					</td>
					<td class="text-center">
						<input type="checkbox" id="ufg-gallery-id" name="ufg-gallery-id" value="' . intval($new_ufg_gallery_id) . '" title="Select Gallery">
					</td>
				</tr>
				';
			}
		}
		wp_die();
	}
}
add_action('wp_ajax_ufg_clone_gallery', 'ufg_clone_gallery_callback');
// 7. clone gallery end

// 8. Import / Export page
function ufg_import_export_page()
{
	require plugin_dir_path(__FILE__) . 'admin/import-export.php';
}

// 8a. Export galleries AJAX
function ufg_export_galleries_callback()
{
	if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-import-export')) {
		wp_send_json_error('Nonce verification failed.');
	}

	if (!current_user_can('manage_options')) {
		wp_send_json_error('Permission denied.');
	}

	$gallery_ids = isset($_POST['gallery_ids']) ? array_map('absint', $_POST['gallery_ids']) : array();
	if (empty($gallery_ids)) {
		wp_send_json_error('No galleries selected.');
	}

	$export_data = array(
		'plugin' => 'filter-gallery-pro',
		'version' => '6.0.5',
		'export_date' => gmdate('Y-m-d'),
		'source_url' => home_url(),
		'galleries' => array(),
	);

	foreach ($gallery_ids as $gid) {
		$details = get_option("ufg_details_" . $gid);
		$filters = get_option("ufg_filters_" . $gid);
		$gallery = get_option("ufg_gallery_" . $gid);
		$settings = get_option("ufg_settings_" . $gid);

		if (!$gallery && !$filters) {
			continue; // skip if gallery doesn't exist
		}

		// Build image URL map: attachment_id => full URL
		$image_urls = array();
		if (is_array($gallery) && isset($gallery['ufg-attachment-id']) && is_array($gallery['ufg-attachment-id'])) {
			foreach ($gallery['ufg-attachment-id'] as $att_id) {
				$url = wp_get_attachment_url($att_id);
				if ($url) {
					$image_urls[$att_id] = $url;
				}
			}
		}

		$export_data['galleries'][] = array(
			'original_id' => $gid,
			'details' => $details ? $details : array(),
			'filters' => $filters ? $filters : array(),
			'gallery' => $gallery ? $gallery : array(),
			'settings' => $settings ? $settings : array(),
			'image_urls' => $image_urls,
		);
	}

	wp_send_json_success($export_data);
}
add_action('wp_ajax_ufg_export_galleries', 'ufg_export_galleries_callback');

// 8b. Import single gallery AJAX
function ufg_import_gallery_callback()
{
	if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-import-export')) {
		wp_send_json_error('Nonce verification failed.');
	}

	if (!current_user_can('manage_options')) {
		wp_send_json_error('Permission denied.');
	}

	if (!isset($_POST['gallery_data'])) {
		wp_send_json_error('No gallery data provided.');
	}

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$raw = wp_unslash($_POST['gallery_data']);
	$data = json_decode($raw, true);
	if (!$data || !is_array($data)) {
		wp_send_json_error('Invalid gallery data format.');
	}

	$skip_images = isset($_POST['skip_images']) && $_POST['skip_images'] === '1';

	// Get a new gallery ID
	$new_id = ufg_get_next_id();

	$details = isset($data['details']) ? $data['details'] : array();
	$filters = isset($data['filters']) ? $data['filters'] : array();
	$gallery = isset($data['gallery']) ? $data['gallery'] : array();
	$settings = isset($data['settings']) ? $data['settings'] : array();
	$image_urls = isset($data['image_urls']) ? $data['image_urls'] : array();

	$images_imported = 0;
	$images_failed = 0;

	// Remap attachment IDs if we have images to import
	if (!$skip_images && !empty($image_urls) && is_array($gallery) && isset($gallery['ufg-attachment-id'])) {
		// Need media sideload function
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$id_map = array(); // old_id => new_id

		foreach ($image_urls as $old_id => $url) {
			// Download and sideload the image
			$new_att_id = media_sideload_image($url, 0, null, 'id');
			if (!is_wp_error($new_att_id)) {
				$id_map[$old_id] = $new_att_id;
				$images_imported++;
			} else {
				$images_failed++;
			}
		}

		// Remap attachment IDs in gallery data
		if (!empty($id_map)) {
			// Remap ufg-attachment-id array
			$new_att_ids = array();
			foreach ($gallery['ufg-attachment-id'] as $old_id) {
				if (isset($id_map[$old_id])) {
					$new_att_ids[] = $id_map[$old_id];
				}
			}
			$gallery['ufg-attachment-id'] = $new_att_ids;

			// Remap keyed arrays: ufg-url, ufg-title, ufg-alt, ufg-description, ufg-image-filters
			$keyed_fields = array('ufg-url', 'ufg-title', 'ufg-alt', 'ufg-description', 'ufg-image-filters');
			foreach ($keyed_fields as $field) {
				if (isset($gallery[$field]) && is_array($gallery[$field])) {
					$new_data = array();
					foreach ($gallery[$field] as $old_key => $val) {
						$new_key = isset($id_map[$old_key]) ? $id_map[$old_key] : $old_key;
						$new_data[$new_key] = $val;
					}
					$gallery[$field] = $new_data;
				}
			}
		}
	} elseif ($skip_images) {
		// Clear image data when skipping
		$gallery['ufg-attachment-id'] = array();
		$gallery['ufg-url'] = array();
		$gallery['ufg-title'] = array();
		$gallery['ufg-alt'] = array();
		$gallery['ufg-description'] = array();
		$gallery['ufg-image-filters'] = array();
	}

	// Update gallery name for imported gallery
	$gallery_name = isset($details['gallery_name']) ? $details['gallery_name'] : 'Imported Gallery';
	$details = array(
		'ufg_gallery_id' => $new_id,
		'gallery_name' => $gallery_name . ' (Imported)',
	);

	// Save all 4 option entries
	add_option('ufg_filters_' . $new_id, $filters);
	add_option('ufg_gallery_' . $new_id, $gallery);
	add_option('ufg_settings_' . $new_id, $settings);
	update_option('ufg_details_' . $new_id, $details);

	// Clear cache so new gallery shows up
	wp_cache_delete('ufg_all_galleries', 'ufg_galleries');
	wp_cache_delete('ufg_next_gallery_id', 'ufg_galleries');

	wp_send_json_success(array(
		'new_id' => $new_id,
		'gallery_name' => $details['gallery_name'],
		'images_imported' => $images_imported,
		'images_failed' => $images_failed,
	));
}
add_action('wp_ajax_ufg_import_gallery', 'ufg_import_gallery_callback');

// Register and enqueue sf scripts
function ufg_register_scripts()
{
	// Register and enqueue jQuery
	wp_register_script('jquery', false, array(), '3.6.0', true);
	wp_enqueue_script('jquery');

	// Register and enqueue styles with versions
	wp_register_style('ufg-frontend-css', plugin_dir_url(__FILE__) . 'admin/assets/css/ufg-frontend.css', array(), UFG_VERSION);

	wp_register_style('ufg-fontawesome-css', plugin_dir_url(__FILE__) . 'admin/assets/fontawesome-free-6.5.2-web/css/all.min.css', array(), '6.5.2');

	wp_register_style('ufg-lightbox-css', plugin_dir_url(__FILE__) . 'admin/assets/lightbox/lokesh/css/ufg-lightbox-min.css', array(), '4.5.2');

	// Register and enqueue scripts with versions and dependencies
	wp_register_script('ufg-lightbox-js', plugin_dir_url(__FILE__) . 'admin/assets/lightbox/lokesh/js/ufg.lightbox.min.js', array('jquery'), '2.11.2', true);

	wp_register_script('ufg-isotope-js', plugin_dir_url(__FILE__) . 'admin/assets/js/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);

	// Register a custom script to attach inline script
	wp_register_script('ufg-custom-js', plugin_dir_url(__FILE__) . 'admin/assets/js/ufg-custom.js', array('jquery', 'ufg-isotope-js'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'ufg_register_scripts');

include('shortcode.php');

// Gallery Text Widget Support
add_filter('widget_text', 'do_shortcode');

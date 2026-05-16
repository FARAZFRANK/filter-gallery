<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Plugin Name:       Filter Gallery
 * Plugin URI:        https://wpfrank.com/
 * Description:       Filter Gallery is a lightweight and powerful WordPress plugin to create beautiful filterable galleries.
 * Version:           1.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            FARAZFRANK
 * Author URI:        https://profiles.wordpress.org/farazfrank/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       filter-gallery
 * Domain Path:       /languages
 */

// custom image size
add_image_size('ufg_200_200', 200, 200, true);
add_image_size('ufg_300_300', 300, 300, true);
add_image_size('ufg_400_400', 400, 400, true);

// FG activation
function ufg_activation()
{
	// update current plugin version
	if (is_admin()) {
		if (!function_exists('get_plugin_data')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		$ufg_plugin_data = get_plugin_data(__FILE__);
		if (isset($ufg_plugin_data['Version'])) {
			$ufg_plugin_version = $ufg_plugin_data['Version'];
			
			// Migration detection
			$old_version = get_option('ufg_current_version', '0.0.0');
			if (version_compare($old_version, $ufg_plugin_version, '<')) {
				require_once plugin_dir_path(__FILE__) . 'includes/class-ufg-migration.php';
				UFG_Migration::migrate($old_version, $ufg_plugin_version);
			}

			update_option('ufg_current_version', $ufg_plugin_version);
		}
	}
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
	load_plugin_textdomain('filter-gallery', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'ufg_load_translation');

// FG menu
function ufg_menu_page()
{
	add_menu_page(
		__('Filter Gallery', 'filter-gallery'),
		'Filter Gallery',
		'manage_options',
		'filter-gallery',
		'ufg_main',
		'dashicons-format-gallery',
		65
	);

	add_submenu_page('filter-gallery', __('Manage Gallery', 'filter-gallery'), __('Manage Gallery', 'filter-gallery'), 'manage_options', 'ufg-manage-gallery', 'ufg_manage_gallery');
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

// FG sub menu filters page body
function ufg_manage_gallery()
{
	ufg_enqueue_react_app();
	require 'admin/manage-gallery.php';
}

// Import / Export page body
function ufg_import_export_page()
{
	require 'admin/import-export.php';
}

// Free vs Pro page body
function ufg_free_vs_pro_page()
{
	require 'admin/free-vs-pro.php';
}

function ufg_enqueue_react_app()
{
	$asset_file = include(plugin_dir_path(__FILE__) . 'build/index.asset.php');
	wp_register_script(
		'ufg-react-app',
		plugins_url('build/index.js', __FILE__),
		array('jquery', 'wp-element', 'wp-components', 'wp-api-fetch', 'wp-media-utils'),
		$asset_file['version'],
		true
	);
	wp_enqueue_style(
		'ufg-react-app-style',
		plugins_url('build/index.css', __FILE__),
		array('wp-components'),
		$asset_file['version']
	);

	// Enqueue custom admin fixes for the React UI
	wp_enqueue_style(
		'ufg-admin-fixes',
		plugins_url('admin/assets/css/ufg-admin-fixes.css', __FILE__),
		array('ufg-react-app-style'),
		'1.0.0'
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
			$galleries[] = array(
				'id' => $ufg_gallery_id,
				'name' => isset($details['gallery_name']) ? $details['gallery_name'] : '',
				'gallery' => get_option("ufg_gallery_" . $ufg_gallery_id),
				'filters' => get_option("ufg_filters_" . $ufg_gallery_id),
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
		$gallery = get_option("ufg_gallery_" . $id);
		$settings = get_option("ufg_settings_" . $id);
		$details = get_option("ufg_details_" . $id);

		$parsedImages = array();
		if (is_array($gallery) && isset($gallery['ufg-attachment-id']) && is_array($gallery['ufg-attachment-id'])) {
			foreach ($gallery['ufg-attachment-id'] as $k => $v) {
				$att_id = $v;
				$parsedImages[] = array(
					'id' => $att_id,
					'url' => wp_get_attachment_image_url($att_id, 'medium'),
					'link_url' => isset($gallery['ufg-url'][$att_id]) ? $gallery['ufg-url'][$att_id] : (isset($gallery['ufg-url'][$k]) ? $gallery['ufg-url'][$k] : ''),
					'title' => isset($gallery['ufg-title'][$att_id]) ? $gallery['ufg-title'][$att_id] : '',
					'alt' => isset($gallery['ufg-alt'][$att_id]) ? $gallery['ufg-alt'][$att_id] : '',
					'description' => isset($gallery['ufg-description'][$att_id]) ? $gallery['ufg-description'][$att_id] : '',
					'filters' => isset($gallery['ufg-image-filters'][$att_id]) ? $gallery['ufg-image-filters'][$att_id] : (isset($gallery['ufg-image-filters'][$k]) ? $gallery['ufg-image-filters'][$k] : array())
				);
			}
		}

		$currentGalleryData = array(
			'id' => $id,
			'name' => isset($details['gallery_name']) ? $details['gallery_name'] : '',
			'filters' => is_array($filters) ? $filters : array(),
			'images' => $parsedImages,
			'settings' => is_array($settings) ? $settings : array(),
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
			'show_filters_count' => 1,
			'show_all_button' => 1,
			'all_button_text' => 'All',
			'all_button_icon' => 'fas fa-image',
			'all_button_color' => '#ffffff',
			'all_button_bg_color' => '#0A85ED',
			'parent_button_color' => '#ffffff',
			'parent_button_bg_color' => '#09A6F3',
			'parent_filters_heading' => '',
			'l1_filters_heading' => '',
			'l1_button_color' => '#ffffff',
			'l1_button_bg_color' => '#07C8F9',
			'child_filter_effect' => 'show_hide',
			'active_button_color' => '#FFFFFF',
			'active_button_bg_color' => '#0C63E7',
			'l2_button_color' => '#ffffff',
			'l2_button_bg_color' => '#07C8F9',
			'l3_button_color' => '#ffffff',
			'l3_button_bg_color' => '#07C8F9',
			'l4_button_color' => '#ffffff',
			'l4_button_bg_color' => '#07C8F9',
			'l5_button_color' => '#ffffff',
			'l5_button_bg_color' => '#07C8F9',
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
			'image_sorting' => 3,
			'image_search' => 1,
			'lightbox' => 1,
			'lightbox_title' => 1,
			'lightbox_description' => 1,
			'lightbox_numbering' => 1,
			'custom_css' => '',
			'load_more' => 'off',
			'load_limit' => 10,
			'load_color' => '#0080ff',
			'load_txt_color' => '#FFFFFF',
			'load_btn_txt' => 'Load More'
		),
		'nonces' => array(
			'clone' => wp_create_nonce('ufg-clone-gallery'),
			'remove' => wp_create_nonce('ufg-remove-gallery'),
			'addFilters' => wp_create_nonce('add-filters'),
			'saveGallery' => wp_create_nonce('save-gallery'),
			'saveSetting' => wp_create_nonce('save-setting'),
			'addImage' => wp_create_nonce('add-image')
		),
		'version' => '1.0.0'
	));
	wp_enqueue_script('ufg-react-app');
}

//get / create next gallery id
function ufg_get_next_id()
{
	global $wpdb;
	$ufg_gallery_key = "ufg_gallery_";
	
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

	if ($ufg_gallery_count_res) {
		$ufg_gallery_last_key = $ufg_gallery_count_res[0];
		$ufg_underscore_pos = strrpos($ufg_gallery_last_key, '_');
		$ufg_last_slider_id = (int) substr($ufg_gallery_last_key, ($ufg_underscore_pos + 1));
		return ($ufg_last_slider_id + 1);
	} else {
		return 1;
	}
}

// AJAX callbacks (Save filters, images, gallery, settings, clone, remove)
// Ported from Pro version but adapted for Free

// 1. save filters ajax
function ufg_gallery_filters_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'add-filters')) {
		die;
	} else {
		$ufg_gallery_id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';
		$ufg_gallery_name = isset($_POST['gallery_name']) ? sanitize_text_field(wp_unslash($_POST['gallery_name'])) : '';

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

		if (!function_exists('UFGaddMissingFilterKeys')) {
			function UFGaddMissingFilterKeys(&$array)
			{
				foreach ($array as &$item) {
					if (!isset($item->filterkey)) {
						$item->filterkey = strtolower(str_replace(' ', '-', $item->title)) . '-' . UFGgenerateRandomString();
					}
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
			$filters = json_decode(stripslashes(wp_unslash($_POST['filters'])), false);
		}

		if (is_array($filters)) {
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
		
		wp_cache_delete('ufg_all_galleries', 'ufg_galleries');
		wp_cache_delete('ufg_next_gallery_id', 'ufg_galleries');
	}
	wp_send_json_success();
}
add_action('wp_ajax_ufg_gallery_filters', 'ufg_gallery_filters_callback');

// 2. save gallery images
function ufg_save_gallery_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'save-gallery')) {
		wp_send_json_error('Nonce verification failed');
	} else {
		$ufg_gallery_id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';

		$image_id_raw = isset($_POST['image_id']) ? wp_unslash($_POST['image_id']) : '';
		$image_title_raw = isset($_POST['image_title']) ? wp_unslash($_POST['image_title']) : '';
		$image_alt_raw = isset($_POST['image_alt']) ? wp_unslash($_POST['image_alt']) : '';
		$image_description_raw = isset($_POST['image_description']) ? wp_unslash($_POST['image_description']) : '';
		$image_url_raw = isset($_POST['image_url']) ? wp_unslash($_POST['image_url']) : '';
		$image_filters_raw = isset($_POST['image_filters']) ? wp_unslash($_POST['image_filters']) : '';

		$ufg_image_id = $ufg_image_title = $ufg_image_alt = $ufg_image_description = $ufg_image_url = $ufg_image_filters = array();

		parse_str($image_id_raw, $ufg_image_id);
		parse_str($image_title_raw, $ufg_image_title);
		parse_str($image_alt_raw, $ufg_image_alt);
		parse_str($image_description_raw, $ufg_image_description);
		parse_str($image_url_raw, $ufg_image_url);
		parse_str($image_filters_raw, $ufg_image_filters);

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

// 3. save gallery settings
function ufg_save_setting_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'save-setting')) {
		die;
	} else {
		$ufg_gallery_id = isset($_POST['ufg_gallery_id']) ? sanitize_text_field(wp_unslash($_POST['ufg_gallery_id'])) : 0;
		// Ported settings from Pro but matching Free keys if necessary
		// Actually, we want to match Pro's settings schema now.
		
		$settings = array();
		$allowed_keys = array(
			'ufg_gallery_id', 'show_filters', 'show_filters_icon', 'show_filters_count', 'show_all_button',
			'all_button_text', 'all_button_icon', 'all_button_color', 'all_button_bg_color',
			'parent_filters_heading', 'parent_button_color', 'parent_button_bg_color',
			'l1_filters_heading', 'l1_button_color', 'l1_button_bg_color', 'child_filter_effect',
			'active_button_color', 'active_button_bg_color', 'l2_button_color', 'l2_button_bg_color',
			'l3_button_color', 'l3_button_bg_color', 'l4_button_color', 'l4_button_bg_color',
			'l5_button_color', 'l5_button_bg_color', 'columns_desktop', 'columns_tab',
			'columns_mobile_landscape', 'columns_mobile_portrait', 'thumbnail_image',
			'thumbnail_image_size', 'thumbnail_border', 'thumbnail_border_thickness',
			'thumbnail_border_color', 'thumbnail_bg_color', 'image_title', 'image_title_font_size',
			'image_title_color', 'image_description', 'image_description_font_size',
			'image_description_color', 'image_description_text_limit', 'image_hover_effect',
			'read_more_link_sh', 'read_more_link', 'read_more_button_text', 'read_more_button_icon',
			'read_more_button_color', 'read_more_button_bg_color', 'read_more_button_target',
			'image_sorting', 'image_search', 'lightbox', 'lightbox_title', 'lightbox_description',
			'lightbox_numbering', 'custom_css', 'load_more', 'load_limit', 'load_color',
			'load_txt_color', 'load_btn_txt'
		);

		foreach ($allowed_keys as $key) {
			if (isset($_POST[$key])) {
				$settings[$key] = sanitize_text_field(wp_unslash($_POST[$key]));
			}
		}

		update_option("ufg_settings_" . $ufg_gallery_id, $settings);
		wp_send_json_success();
	}
}
add_action('wp_ajax_ufg_save_setting', 'ufg_save_setting_callback');

// 4. remove gallery/galleries
function ufg_remove_gallery_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-remove-gallery')) {
		die;
	} else {
		if (isset($_POST['ufg_gallery_id']) && isset($_POST['do_action'])) {
			$raw_id = wp_unslash($_POST['ufg_gallery_id']);
			$ufg_gallery_id = is_array($raw_id) ? array_map('sanitize_text_field', $raw_id) : sanitize_text_field($raw_id);
			$ufg_do_action = sanitize_text_field(wp_unslash($_POST['do_action']));

			if ($ufg_do_action == 'single') {
				delete_option("ufg_filters_" . $ufg_gallery_id);
				delete_option("ufg_gallery_" . $ufg_gallery_id);
				delete_option("ufg_settings_" . $ufg_gallery_id);
				delete_option("ufg_details_" . $ufg_gallery_id);
			}

			if ($ufg_do_action == 'multiple' && is_array($ufg_gallery_id)) {
				foreach ($ufg_gallery_id as $ufg_single_id) {
					delete_option("ufg_filters_" . $ufg_single_id);
					delete_option("ufg_gallery_" . $ufg_single_id);
					delete_option("ufg_settings_" . $ufg_single_id);
					delete_option("ufg_details_" . $ufg_single_id);
				}
			}
			wp_cache_delete('ufg_all_galleries', 'ufg_galleries');
			wp_cache_delete('ufg_next_gallery_id', 'ufg_galleries');
		}
		wp_send_json_success();
	}
}
add_action('wp_ajax_ufg_remove_gallery', 'ufg_remove_gallery_callback');

// 5. clone gallery
function ufg_clone_gallery_callback()
{
	if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ufg-clone-gallery')) {
		die;
	} else {
		if (isset($_POST['ufg_gallery_id'])) {
			$ufg_gallery_id = sanitize_text_field(wp_unslash($_POST['ufg_gallery_id']));

			$ufg_cloning_filters = get_option("ufg_filters_" . $ufg_gallery_id);
			$ufg_cloning_gallery = get_option("ufg_gallery_" . $ufg_gallery_id);
			$ufg_cloning_setting = get_option("ufg_settings_" . $ufg_gallery_id);
			$ufg_cloning_details = get_option("ufg_details_" . $ufg_gallery_id);

			$new_ufg_gallery_id = ufg_get_next_id();
			$new_ufg_gallery_name = (isset($ufg_cloning_details['gallery_name']) ? $ufg_cloning_details['gallery_name'] : 'Gallery') . ' - Clone';

			if (is_array($ufg_cloning_setting)) {
				$ufg_cloning_setting['ufg_gallery_id'] = $new_ufg_gallery_id;
			}
			
			$new_ufg_cloning_details = array('ufg_gallery_id' => $new_ufg_gallery_id, 'gallery_name' => $new_ufg_gallery_name);

			add_option('ufg_filters_' . $new_ufg_gallery_id, $ufg_cloning_filters);
			add_option('ufg_gallery_' . $new_ufg_gallery_id, $ufg_cloning_gallery);
			add_option('ufg_settings_' . $new_ufg_gallery_id, $ufg_cloning_setting);
			update_option('ufg_details_' . $new_ufg_gallery_id, $new_ufg_cloning_details);
			
			wp_cache_delete('ufg_all_galleries', 'ufg_galleries');
			wp_cache_delete('ufg_next_gallery_id', 'ufg_galleries');
		}
		wp_send_json_success();
	}
}
add_action('wp_ajax_ufg_clone_gallery', 'ufg_clone_gallery_callback');

// Import / Export AJAX handlers
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
		'plugin'      => 'filter-gallery',
		'version'     => '1.0.0',
		'export_date' => gmdate('Y-m-d'),
		'source_url'  => home_url(),
		'galleries'   => array(),
	);

	foreach ($gallery_ids as $gid) {
		$details  = get_option("ufg_details_" . $gid);
		$filters  = get_option("ufg_filters_" . $gid);
		$gallery  = get_option("ufg_gallery_" . $gid);
		$settings = get_option("ufg_settings_" . $gid);

		if (!$gallery && !$filters) {
			continue;
		}

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
			'details'     => $details ? $details : array(),
			'filters'     => $filters ? $filters : array(),
			'gallery'     => $gallery ? $gallery : array(),
			'settings'    => $settings ? $settings : array(),
			'image_urls'  => $image_urls,
		);
	}

	wp_send_json_success($export_data);
}
add_action('wp_ajax_ufg_export_galleries', 'ufg_export_galleries_callback');

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

	$raw = wp_unslash($_POST['gallery_data']);
	$data = json_decode($raw, true);
	if (!$data || !is_array($data)) {
		wp_send_json_error('Invalid gallery data format.');
	}

	$skip_images = isset($_POST['skip_images']) && $_POST['skip_images'] === '1';
	$new_id = ufg_get_next_id();

	$details  = isset($data['details']) ? $data['details'] : array();
	$filters  = isset($data['filters']) ? $data['filters'] : array();
	$gallery  = isset($data['gallery']) ? $data['gallery'] : array();
	$settings = isset($data['settings']) ? $data['settings'] : array();
	$image_urls = isset($data['image_urls']) ? $data['image_urls'] : array();

	$images_imported = 0;
	$images_failed   = 0;

	if (!$skip_images && !empty($image_urls) && is_array($gallery) && isset($gallery['ufg-attachment-id'])) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$id_map = array();
		foreach ($image_urls as $old_id => $url) {
			$new_att_id = media_sideload_image($url, 0, null, 'id');
			if (!is_wp_error($new_att_id)) {
				$id_map[$old_id] = $new_att_id;
				$images_imported++;
			} else {
				$images_failed++;
			}
		}

		if (!empty($id_map)) {
			$new_att_ids = array();
			foreach ($gallery['ufg-attachment-id'] as $old_id) {
				if (isset($id_map[$old_id])) {
					$new_att_ids[] = $id_map[$old_id];
				}
			}
			$gallery['ufg-attachment-id'] = $new_att_ids;

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
		$gallery['ufg-attachment-id'] = array();
		$gallery['ufg-url'] = array();
		$gallery['ufg-title'] = array();
		$gallery['ufg-alt'] = array();
		$gallery['ufg-description'] = array();
		$gallery['ufg-image-filters'] = array();
	}

	$gallery_name = isset($details['gallery_name']) ? $details['gallery_name'] : 'Imported Gallery';
	$details = array(
		'ufg_gallery_id' => $new_id,
		'gallery_name'   => $gallery_name . ' (Imported)',
	);

	add_option('ufg_filters_' . $new_id, $filters);
	add_option('ufg_gallery_' . $new_id, $gallery);
	add_option('ufg_settings_' . $new_id, $settings);
	update_option('ufg_details_' . $new_id, $details);

	wp_cache_delete('ufg_all_galleries', 'ufg_galleries');
	wp_cache_delete('ufg_next_gallery_id', 'ufg_galleries');

	wp_send_json_success(array(
		'new_id'          => $new_id,
		'gallery_name'    => $details['gallery_name'],
		'images_imported' => $images_imported,
		'images_failed'   => $images_failed,
	));
}
add_action('wp_ajax_ufg_import_gallery', 'ufg_import_gallery_callback');

// Register and enqueue frontend scripts
function ufg_register_scripts()
{
	wp_register_style('ufg-frontend-css', plugin_dir_url(__FILE__) . 'admin/assets/css/ufg-frontend.css', array(), '1.0.0');
	wp_register_style('ufg-lightbox-css', plugin_dir_url(__FILE__) . 'admin/assets/lightbox/lokesh/css/ufg-lightbox-min.css', array(), '4.5.2');
	
	wp_register_script('ufg-lightbox-js', plugin_dir_url(__FILE__) . 'admin/assets/lightbox/lokesh/js/ufg.lightbox.min.js', array('jquery'), '2.11.2', true);
	wp_register_script('ufg-isotope-js', plugin_dir_url(__FILE__) . 'admin/assets/js/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);
	wp_register_script('ufg-custom-js', plugin_dir_url(__FILE__) . 'admin/assets/js/ufg-custom.js', array('jquery', 'ufg-isotope-js'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'ufg_register_scripts');

include('shortcode.php');

// Gallery Text Widget Support
add_filter('widget_text', 'do_shortcode');

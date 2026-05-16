<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Filter Gallery Migration Class
 * Handles non-destructive version migrations and schema updates.
 */
class UFG_Migration {

	/**
	 * Initialize the migration check.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'check_version' ) );
	}

	/**
	 * Check the current DB version against the plugin version.
	 */
	public static function check_version() {
		$db_version = get_option( 'ufg_current_version', '0.0.0' );
		
		if (!function_exists('get_plugin_data')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		$plugin_data = get_plugin_data(plugin_dir_path(__FILE__) . '../filter-gallery.php');
		$current_version = $plugin_data['Version'];

		if ( version_compare( $db_version, $current_version, '<' ) ) {
			self::migrate( $db_version, $current_version );
			update_option( 'ufg_current_version', $current_version );
		}
	}

	/**
	 * Run the migration logic.
	 *
	 * @param string $old_version The previous version stored in the DB.
	 * @param string $new_version The version we are migrating to.
	 */
	public static function migrate( $old_version, $new_version ) {
		// Log migration start
		error_log( "UFG Migration: Starting migration from $old_version to $new_version" );

		// Migration from v0.2.3 or older
		if ( version_compare( $old_version, '1.1.0', '<' ) ) {
			self::migrate_from_legacy();
		}

		// Ensure global cache is cleared
		wp_cache_delete( 'ufg_all_galleries', 'ufg_galleries' );
		
		error_log( "UFG Migration: Migration completed." );
	}

	/**
	 * Specific logic for migrating from legacy v0.2.3 or older.
	 */
	private static function migrate_from_legacy() {
		global $wpdb;

		// The core data (filters, images, settings) in v0.2.3 is stored in wp_options 
		// as ufg_filters_{id}, ufg_gallery_{id}, ufg_settings_{id}, and ufg_details_{id}.
		// This schema remains compatible with the new React UI.
		
		// We might need to ensure all legacy galleries have 'gallery_name' in ufg_details_{id}
		$ufg_gallery_key = "ufg_filters_";
		$results = $wpdb->get_results(
			$wpdb->prepare("SELECT option_name FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE %s", '%' . $wpdb->esc_like($ufg_gallery_key) . '%')
		);

		if ($results) {
			foreach ($results as $row) {
				$ufg_gallery_key_name = $row->option_name;
				$ufg_underscore_pos = strrpos($ufg_gallery_key_name, '_');
				$id = substr($ufg_gallery_key_name, ($ufg_underscore_pos + 1));
				
				$details = get_option("ufg_details_" . $id);
				if (!$details || !isset($details['gallery_name'])) {
					$new_details = array(
						'ufg_gallery_id' => $id,
						'gallery_name'   => 'Gallery #' . $id,
					);
					update_option("ufg_details_" . $id, $new_details);
				}
				
				// Ensure default settings are present if missing
				$settings = get_option("ufg_settings_" . $id);
				if (!$settings || !is_array($settings)) {
					$default_settings = array(
						'show_filters' => 1,
						'columns_desktop' => 4,
						'columns_tab' => 3,
						'columns_mobile_portrait' => 2,
						'image_title' => 1,
						'lightbox' => 1,
					);
					update_option("ufg_settings_" . $id, $default_settings);
				}
			}
		}
	}
}

UFG_Migration::init();

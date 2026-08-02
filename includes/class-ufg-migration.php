<?php
/**
 * Handles non-destructive version migrations and schema updates.
 *
 * @package Filter_Gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
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
		add_action( 'init', array( __CLASS__, 'check_version' ) );
	}

	/**
	 * Check the current DB version against the plugin version.
	 */
	public static function check_version() {
		$db_version = get_option( 'ufg_current_version', '0.0.0' );

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_data     = get_plugin_data( plugin_dir_path( __FILE__ ) . '../filter-gallery.php' );
		$current_version = $plugin_data['Version'];

		if ( version_compare( $db_version, $current_version, '<' ) ) {
			self::migrate( $db_version, $current_version );
			update_option( 'ufg_current_version', $current_version );
		}

		// Run legacy migration once if it has not been run yet.
		if ( get_option( 'ufg_legacy_migrated_v1' ) !== 'yes' ) {
			self::migrate_from_legacy();
			update_option( 'ufg_legacy_migrated_v1', 'yes' );
		}
	}

	/**
	 * Run the migration logic.
	 *
	 * @param string $old_version The previous version stored in the DB.
	 * @param string $new_version The version we are migrating to.
	 */
	public static function migrate( $old_version, $new_version ) {
		// Log migration start.
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- logging migration status is intentional for debugging upgrade issues
		error_log( "UFG Migration: Starting migration from $old_version to $new_version" );

		// Migration from v0.2.3 or older.
		if ( version_compare( $old_version, '1.1.0', '<' ) ) {
			self::migrate_from_legacy();
		}

		// Ensure global cache is cleared.
		wp_cache_delete( 'ufg_all_galleries', 'ufg_galleries' );

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- logging migration status is intentional for debugging upgrade issues
		error_log( 'UFG Migration: Migration completed.' );
	}

	/**
	 * Specific logic for migrating from legacy v0.2.3 or older.
	 */
	private static function migrate_from_legacy() {
		global $wpdb;

		// The core data (filters, images, settings) in v0.2.3 is stored in wp_options
		// as ufg_filters_{id}, ufg_gallery_{id}, ufg_settings_{id}, and ufg_details_{id}.
		// This schema remains compatible with the new React UI.

		// We might need to ensure all legacy galleries have 'gallery_name' in ufg_details_{id}.
		$ufg_gallery_key = 'ufg_filters_';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- direct query is required here to scan for option patterns during database migration
		$results = $wpdb->get_results(
			$wpdb->prepare( "SELECT option_name FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE %s", '%' . $wpdb->esc_like( $ufg_gallery_key ) . '%' )
		);

		if ( $results ) {
			foreach ( $results as $row ) {
				$ufg_gallery_key_name = $row->option_name;
				$ufg_underscore_pos   = strrpos( $ufg_gallery_key_name, '_' );
				$id                   = substr( $ufg_gallery_key_name, ( $ufg_underscore_pos + 1 ) );

				$details = get_option( 'ufg_details_' . $id );
				if ( ! $details || ! isset( $details['gallery_name'] ) ) {
					$new_details = array(
						'ufg_gallery_id' => $id,
						'gallery_name'   => 'Gallery #' . $id,
					);
					update_option( 'ufg_details_' . $id, $new_details );
				}

				// Ensure default settings are present if missing.
				$settings = get_option( 'ufg_settings_' . $id );
				if ( ! is_array( $settings ) ) {
					$settings = array();
				}

				$default_settings = array(
					'show_filters'                  => 1,
					'show_filters_icon'             => 1,
					'enable_deep_linking'           => 0,
					'show_filters_count'            => 1,
					'show_search_box'               => 0,
					'search_box_placeholder'        => __( 'Type here to search images', 'filter-gallery' ),
					'show_all_button'               => 1,
					'all_button_text'               => __( 'All', 'filter-gallery' ),
					'all_button_icon'               => 'fas fa-filter',
					'all_button_color'              => '#ffffff',
					'all_button_bg_color'           => '#0A85ED',
					'parent_button_color'           => '#4F46E5',
					'parent_button_bg_color'        => '#EEF2FF',
					'parent_button_hover_color'     => '#000000',
					'parent_active_button_color'    => '#FFFFFF',
					'parent_active_button_bg_color' => '#4F46E5',
					'parent_filters_heading'        => '',
					'l1_filters_heading'            => '',
					'l1_button_color'               => '#4F46E5',
					'l1_button_bg_color'            => '#EEF2FF',
					'child_filter_effect'           => 'show_hide',
					'active_button_color'           => '#FFFFFF',
					'active_button_bg_color'        => '#4F46E5',
					'l2_button_color'               => '#4F46E5',
					'l2_button_bg_color'            => '#EEF2FF',
					'l3_button_color'               => '#4F46E5',
					'l3_button_bg_color'            => '#EEF2FF',
					'l4_button_color'               => '#4F46E5',
					'l4_button_bg_color'            => '#EEF2FF',
					'columns_desktop'               => 4,
					'columns_tab'                   => 3,
					'columns_mobile_landscape'      => 3,
					'columns_mobile_portrait'       => 2,
					'thumbnail_image'               => 1,
					'thumbnail_image_size'          => 'full',
					'thumbnail_border'              => 1,
					'thumbnail_border_thickness'    => 1,
					'thumbnail_border_color'        => '#ffffff',
					'thumbnail_bg_color'            => '#222a33',
					'image_title'                   => 1,
					'image_title_font_size'         => 18,
					'image_title_color'             => '#FFFFFF',
					'image_description'             => 0,
					'image_description_font_size'   => 14,
					'image_description_color'       => '#FFFFFF',
					'image_description_text_limit'  => 60,
					'image_hover_effect'            => 'border_overlay',
					'read_more_link_sh'             => 0,
					'read_more_link'                => 1,
					'read_more_button_text'         => __( 'Read More Link', 'filter-gallery' ),
					'read_more_button_icon'         => 'fas fa-link',
					'read_more_button_color'        => '#ffffff',
					'read_more_button_bg_color'     => '#0080ff',
					'read_more_button_target'       => '_self',
					'image_sorting'                 => 5,
					'image_search'                  => 1,
					'lightbox'                      => 1,
					'lightbox_title'                => 1,
					'lightbox_description'          => 0,
					'lightbox_numbering'            => 0,
					'custom_css'                    => '',
					'load_more'                     => 'off',
					'load_limit'                    => 10,
					'load_color'                    => '#0080ff',
					'load_txt_color'                => '#FFFFFF',
					'load_btn_txt'                  => __( 'Load More', 'filter-gallery' ),
					'filter_style'                  => 'buttons',
					'combine_filter_search'         => '0',
					'filter_padding'                => '8px 16px',
					'filter_margin'                 => '5px',
					'filter_padding_type'           => 'small',
					'filter_padding_v'              => '8',
					'filter_padding_h'              => '16',
					'filter_margin_val'             => '5',
					'l1_button_hover_color'         => '#059669',
					'l1_active_button_color'        => '#FFFFFF',
					'l1_active_button_bg_color'     => '#059669',
					'l2_button_hover_color'         => '#4F46E5',
					'l2_active_button_color'        => '#FFFFFF',
					'l2_active_button_bg_color'     => '#4F46E5',
					'l3_button_hover_color'         => '#D97706',
					'l3_active_button_color'        => '#FFFFFF',
					'l3_active_button_bg_color'     => '#D97706',
					'l4_button_hover_color'         => '#E11D48',
					'l4_active_button_color'        => '#FFFFFF',
					'l4_active_button_bg_color'     => '#E11D48',
				);

				$migrated_settings = array_merge( $default_settings, $settings );

				// Restrict columns to maximum free limits to prevent PRO locks on migrated galleries.
				if ( ! in_array( (string) $migrated_settings['columns_desktop'], array( '3', '4', '6' ), true ) ) {
					$migrated_settings['columns_desktop'] = 4;
				}
				if ( intval( $migrated_settings['columns_tab'] ) > 3 ) {
					$migrated_settings['columns_tab'] = 3;
				}
				if ( intval( $migrated_settings['columns_mobile_landscape'] ) > 3 ) {
					$migrated_settings['columns_mobile_landscape'] = 3;
				}
				if ( intval( $migrated_settings['columns_mobile_portrait'] ) > 2 ) {
					$migrated_settings['columns_mobile_portrait'] = 2;
				}

				// Force the requested purple palette for migrated galleries.
				$migrated_settings['parent_button_color']           = '#4F46E5';
				$migrated_settings['parent_button_bg_color']        = '#EEF2FF';
				$migrated_settings['parent_button_hover_color']     = '#000000';
				$migrated_settings['parent_active_button_color']    = '#FFFFFF';
				$migrated_settings['parent_active_button_bg_color'] = '#4F46E5';

				// Ensure Title is enabled and Description is disabled for migrated galleries.
				$migrated_settings['image_title']       = 1;
				$migrated_settings['image_description'] = 0;

				update_option( 'ufg_settings_' . $id, $migrated_settings );

				// Migrate legacy filters structure.
				$filters = get_option( 'ufg_filters_' . $id );
				if ( is_array( $filters ) ) {
					$modified = false;
					self::normalize_filters( $filters, $modified );
					if ( $modified ) {
						update_option( 'ufg_filters_' . $id, $filters );
					}
				}
			}
		}
	}

	/**
	 * Recursively normalize filters array for modern React UI.
	 *
	 * @param array $filters  The filter tree, passed by reference.
	 * @param bool  $modified Set to true if any filter was modified, passed by reference.
	 * @return void
	 */
	private static function normalize_filters( &$filters, &$modified ) {
		if ( ! is_array( $filters ) ) {
			return;
		}
		foreach ( $filters as &$item ) {
			$is_obj   = is_object( $item );
			$item_arr = $is_obj ? (array) $item : $item;
			if ( is_array( $item_arr ) ) {
				$legacy_title  = isset( $item_arr['title'] ) ? $item_arr['title'] : '';
				$legacy_text   = isset( $item_arr['text'] ) ? $item_arr['text'] : '';
				$has_filterkey = isset( $item_arr['filterkey'] );

				if ( ! $has_filterkey ) {
					if ( ! empty( $legacy_title ) ) {
						$item_arr['filterkey'] = strtolower( str_replace( ' ', '-', $legacy_title ) );
					} elseif ( ! empty( $legacy_text ) ) {
						$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
						$rand_str   = '';
						for ( $i = 0; $i < 5; $i++ ) {
							$rand_str .= $characters[ wp_rand( 0, 35 ) ];
						}
						$item_arr['filterkey'] = strtolower( str_replace( ' ', '-', $legacy_text ) ) . '-' . $rand_str;
					} else {
						$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
						$rand_str   = '';
						for ( $i = 0; $i < 5; $i++ ) {
							$rand_str .= $characters[ wp_rand( 0, 35 ) ];
						}
						$item_arr['filterkey'] = 'filter-' . $rand_str;
					}
					$display_text      = ! empty( $legacy_text ) ? $legacy_text : ( ! empty( $legacy_title ) ? $legacy_title : 'Filter' );
					$item_arr['title'] = $display_text;
					$item_arr['text']  = $display_text;
					$modified          = true;
				}
				if ( ! isset( $item_arr['children'] ) ) {
					$item_arr['children'] = array();
					$modified             = true;
				} elseif ( is_array( $item_arr['children'] ) ) {
					self::normalize_filters( $item_arr['children'], $modified );
				}
				if ( ! isset( $item_arr['color'] ) ) {
					$item_arr['color'] = '#38B2F6';
					$modified          = true;
				}

				$item = $is_obj ? (object) $item_arr : $item_arr;
			}
		}
	}

	/**
	 * Generates a random alphanumeric string.
	 *
	 * @param int $length Length of the string to generate.
	 * @return string The generated random string.
	 */
	private static function generate_random_string( $length = 7 ) {
		$characters        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen( $characters );
		$random_string     = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
		}
		return $random_string;
	}
}

UFG_Migration::init();

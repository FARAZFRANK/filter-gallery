<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (!function_exists('ufg_hex2rgba')) {
	function ufg_hex2rgba($hex, $opacity = 0.4) {
		$hex = str_replace("#", "", $hex);
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		return "rgba($r, $g, $b, $opacity)";
	}
}

add_shortcode('ufg', 'ufg_shortcode_callback');
add_shortcode('filter-gallery', 'ufg_shortcode_callback');
function ufg_shortcode_callback($atts){
	ob_start();
	//echo "<hr>";
	//defaults
	$ufg_filters = array();
	$ufg_gallery = array();
	
	// Get plugin version
	$ufg_last_version = get_option('ufg_current_version');
	//get gallery id
	if (is_array($atts) && isset($atts['id'])) {
		$ufg_gallery_id = $atts['id'];
		$ufg_selected_filter_btn_id = "";
		
		//get dynamic select filter button id via shortcode parameter
		if (is_array($atts) && array_key_exists('selected-filter', $atts)) {
			$ufg_selected_filter_btn_id = sanitize_text_field($atts['selected-filter']);
		}
		//get dynamic select filter button id via URL parameter
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['selected-filter'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$ufg_selected_filter_btn_id = sanitize_text_field(wp_unslash($_GET['selected-filter']));
		}
		
		$ufg_details = get_option("ufg_details_".$ufg_gallery_id);
		$ufg_filters = get_option("ufg_filters_".$ufg_gallery_id);
		$ufg_gallery = get_option("ufg_gallery_".$ufg_gallery_id);
		$ufg_setting = get_option("ufg_settings_".$ufg_gallery_id);

		if (!is_array($ufg_details)) $ufg_details = array();
		if (!is_array($ufg_filters)) $ufg_filters = array();
		if (!is_array($ufg_gallery)) $ufg_gallery = array();
		if (!is_array($ufg_setting)) $ufg_setting = array();

		// Normalize filters
		if (function_exists('ufg_normalize_filters_recursive')) {
			ufg_normalize_filters_recursive($ufg_filters);
		}

		// Merge settings with default settings to prevent undefined key notices
		$default_settings = array(
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
			'filter_padding' => '10px 15px',
			'filter_margin' => '5px',
			'filter_padding_type' => 'medium',
			'filter_padding_v' => '12',
			'filter_padding_h' => '24',
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
		);
		$ufg_setting = array_merge($default_settings, $ufg_setting);
		if(empty($ufg_selected_filter_btn_id)) {
			if(isset($ufg_setting['default_filter']) && $ufg_setting['default_filter'] != '') {
				if($ufg_setting['default_filter'] == 'none') {
					$ufg_selected_filter_btn_id = 'none'; 
				} elseif($ufg_setting['default_filter'] == 'all') {
					$ufg_selected_filter_btn_id = '1evel1-all';
				} else {
					$target_class = $ufg_setting['default_filter'];
					if (!function_exists('ufg_find_filter_level_id')) {
						function ufg_find_filter_level_id($filters, $target, $level = 1) {
							if(is_array($filters) || is_object($filters)) {
								foreach($filters as $f) {
									if(!isset($f->filterkey)) continue;
									$f_class = str_replace(" ", "-", strtolower($f->filterkey));
									if ($f_class === $target) {
										$prefix = $level == 1 ? "1evel1-" : "level" . $level . "-";
										return $prefix . $f_class;
									}
									if (!empty($f->children)) {
										$res = ufg_find_filter_level_id($f->children, $target, $level + 1);
										if ($res) return $res;
									}
								}
							}
							return "";
						}
					}
					$found_id = ufg_find_filter_level_id($ufg_filters, $target_class);
					if($found_id) {
						$ufg_selected_filter_btn_id = $found_id;
					} else {
						$ufg_selected_filter_btn_id = '1evel1-all';
					}
				}
			} else {
				$ufg_selected_filter_btn_id = '1evel1-all'; // Default to all if not set
			}
		}
		//if(isset($ufg_gallery['ufg-filter-image'])) $filter_image = $ufg_gallery['ufg-filter-image']; else  $filter_image = array();
		if(isset($ufg_gallery['ufg-attachment-id'])) $ufg_total_images = count($ufg_gallery['ufg-attachment-id']); else  $ufg_total_images = '';
		//$ufg_total_images = count($ufg_gallery['ufg-attachment-id']);
		 // loading saved settings and shortcode supported settings
		include_once('setting.php');

		// modifiing fiters array for load more
		$ufg_modified_array = array();

		//$ufg_selected_array = $ufg_gallery['ufg-image-filters'];
		$ufg_selected_array = array();
		foreach($ufg_selected_array as $key1 => $value1){
			if(is_array($value1) == true){
				foreach($value1 as $key2 => $value2) {
					$value2;
					if(array_key_exists($value2, $ufg_modified_array)){
						// do nothing
					} else {
						$ufg_modified_array[$value2] = array();
					}
				}
			}
		}

		foreach($ufg_selected_array as $key1 => $value1){
			if(is_array($value1) == true){
				foreach($value1 as $key2 => $value2) {
					$value2;
					//check filter key exist in modified array
					if(array_key_exists($value2, $ufg_modified_array)){
						array_push( $ufg_modified_array[$value2], $key1 );
					} else {
						// do nothing
					}
				}
			}
		}
		$filter_image = $ufg_modified_array;
		
	
		// print filters
		include_once('filters.php');
		
		$ufg_setting_f = get_option("ufg_settings_".$ufg_gallery_id); // separate load for filters scope
		
		$j = 0;
		$new_array = array();
		$new_array_final = array();
		if (is_array($ufg_gallery) && array_key_exists('ufg-image-filters', $ufg_gallery)) {
			foreach($ufg_gallery['ufg-image-filters'] as $key => $array) {
				if (is_array($array)) {
					foreach($array as $key2  => $val) {
						//array_push($new_array[], $val);
						$new_array[$val][$j] = $key;
					}
				}
				$j++;
			}
			foreach($new_array as $new_key => $new_val) {
				$new_re_in = array_values($new_val);
				$new_array_final[$new_key] = $new_re_in;
			}
			$filter_image = $new_array_final;
			
			if (!function_exists('ufg_expand_filter_images_hierarchy')) {
				function ufg_expand_filter_images_hierarchy($filters, &$filter_images) {
					$all_images = array();
					if (is_array($filters)) {
						foreach ($filters as $f) {
							if (!isset($f->filterkey)) continue;
							$key = str_replace(" ", "-", strtolower(trim($f->filterkey)));
							
							$my_images = isset($filter_images[$key]) ? $filter_images[$key] : array();
							
							if (isset($f->children) && is_array($f->children)) {
								ufg_expand_filter_images_hierarchy($f->children, $filter_images);
							}
							
							$my_images = array_values(array_unique($my_images));
							if (!empty($my_images)) {
								$filter_images[$key] = $my_images;
							}
							$all_images = array_merge($all_images, $my_images);
						}
					}
					return array_unique($all_images);
				}
			}
			if (!empty($ufg_filters)) {
				ufg_expand_filter_images_hierarchy($ufg_filters, $filter_image);
			}
		}		

		include_once('gallery.php');
		if($ufg_lightbox_numbering) $ufg_lightbox_numbering = "true"; else $ufg_lightbox_numbering = "false";
		
		// load required resource
		//CSS and JS
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'ufg-isotope-js', plugins_url( '/admin/assets/js/isotope.pkgd.min.js' , __FILE__ ), array( 'jquery', 'imagesloaded' ), '1.0', true );
		wp_enqueue_style( 'ufg-frontend-css', plugins_url( '/admin/assets/css/ufg-frontend.css' , __FILE__ ), array(), UFG_VERSION );
		wp_enqueue_style( 'ufg-lightbox-css', plugins_url( '/admin/assets/lightbox/lokesh/css/lightbox.css' , __FILE__ ), array(), '1.0' );
		
		wp_enqueue_style( 'ufg-fontawesome-css', plugins_url( '/admin/assets/fontawesome-free-6.5.2-web/css/all.min.css' , __FILE__ ), array(), '6.5.2' );
		wp_enqueue_script( 'ufg-custom-js', plugins_url( '/admin/assets/js/ufg-custom.js' , __FILE__ ), array( 'jquery', 'imagesloaded', 'ufg-isotope-js' ), UFG_VERSION, true );
		wp_enqueue_script( 'ufg-lightbox-js', plugins_url( '/admin/assets/lightbox/lokesh/js/lightbox.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_add_inline_script( 'ufg-custom-js', 'const UFGJS = ' . wp_json_encode( array(
		    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		    'LoadMoreNonce' => wp_create_nonce( 'ufg_load_more_nonce' ),
		    'GalleryId' => $ufg_gallery_id,
		    'FiterImage' => $filter_image,
		    'TotalImages' => $ufg_total_images,
		    'LoadBtnText' => $load_btn_txt,
		    'LoadMore' => 'off',
		    'LoadLimit' => $load_limit,
		    'ChildFilterEffect' => $ufg_child_filter_effect,
		    'Lightbox' => ($ufg_lightbox === 'on' || $ufg_lightbox == 1 || $ufg_lightbox === '1' || $ufg_lightbox === true),
		    'LightboxNumbering' => false,
		    'LightboxTitle' => ($ufg_lightbox_title === "on" || $ufg_lightbox_title === "1" || $ufg_lightbox_title == 1 || $ufg_lightbox_title === true),
		    'LightboxDescription' => false,
		    'SelectedFltrBtnId' => $ufg_selected_filter_btn_id,
		    'EnableDeepLinking' => false,
		)), 'before' );
		?>
		<!-- printing filters start-->
			<div class="fg-content-wrapper" version="<?php echo esc_attr($ufg_last_version); ?>">
			<?php
			$ufg_has_images = !empty($ufg_gallery['ufg-attachment-id']);
			$ufg_has_filters = !empty($ufg_filters);
			$ufg_images_have_filters = false;
			if ($ufg_has_images && !empty($ufg_gallery['ufg-image-filters']) && is_array($ufg_gallery['ufg-image-filters'])) {
				foreach ($ufg_gallery['ufg-image-filters'] as $img_id => $img_fltrs) {
					if (!empty($img_fltrs) && is_array($img_fltrs)) {
						foreach ($img_fltrs as $f_val) {
							if (trim($f_val) !== '') {
								$ufg_images_have_filters = true;
								break 2;
							}
						}
					}
				}
			}

			if (current_user_can('manage_options')) {
				if (!$ufg_has_images || !$ufg_has_filters) {
					$ufg_manage_link = admin_url('admin.php?page=ufg-manage-gallery&id=' . $ufg_gallery_id);
					?>
					<div class="ufg-admin-notice-box">
						<div class="ufg-notice-flex">
							<i class="fas fa-exclamation-triangle ufg-notice-icon"></i>
							<div class="ufg-notice-content">
								<strong class="ufg-notice-title"><?php esc_html_e('Filter Gallery Configuration Notice', 'filter-gallery'); ?></strong>
								<span class="ufg-notice-text"><?php esc_html_e('please add images to gallery and assign filter on it for proper working.', 'filter-gallery'); ?></span>
								<div class="ufg-notice-actions">
									<a href="<?php echo esc_url($ufg_manage_link); ?>" class="ufg-notice-btn">
										<?php esc_html_e('Go to Admin Dashboard to Redetect / Configure', 'filter-gallery'); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			} else {
				if (!$ufg_has_images) {
					?>
					<div class="ufg-visitor-notice-box">
						<i class="far fa-images ufg-visitor-icon"></i>
						<span class="ufg-visitor-text"><?php esc_html_e('No images added into gallery.', 'filter-gallery'); ?></span>
					</div>
					<?php
				}
			}
			?>
			<?php 
			$show_search_box = (isset($ufg_setting['show_search_box']) && $ufg_setting['show_search_box'] == '1');
			$ufg_combine_filter_search = '0';
			?>
			<?php if($show_search_box) { ?>
			<div class="ufg-row">
				<div class="ufg-search-container ufg-uncombined-search">
					<input type="text" class="ufg-search-input" placeholder="<?php echo esc_attr(isset($ufg_setting['search_box_placeholder']) ? $ufg_setting['search_box_placeholder'] : 'Type here to search images'); ?>" data-gallery-id="<?php echo esc_attr($ufg_gallery_id); ?>">
				</div>
			</div>
			<?php } ?>
			<?php if($ufg_show_filters) { ?>
			<div class="ufg-row">
				<div class="ufg-filter-container ufg-uncombined-filter ufg-filters-<?php echo esc_attr($ufg_gallery_id); ?> ufg-filter-style-<?php echo esc_attr($ufg_filter_style); ?>">
					<?php ufg_filters($ufg_gallery_id, $ufg_filters, $ufg_gallery, $atts); ?>
				</div>
			</div>
			<?php } ?>

			<input id="ufg_current_clicked_filter_id" name="ufg_current_clicked_filter_id" value="" class="ufg-hidden" placeholder="Current Filter">
			<input id="ufg_current_clicked_filter_level" name="ufg_current_clicked_filter_level" value="" class="ufg-hidden" placeholder="Current Level">
			<input id="ufg_last_clicked_filter_id" name="ufg_last_clicked_filter_id" value="" class="ufg-hidden" placeholder="Last Filter">
			<input id="ufg_last_clicked_filter_level" name="ufg_last_clicked_filter_level" value="" class="ufg-hidden" placeholder="Last Level">
			<input id="ufg_current_clicked_parent_filter_id" name="ufg_current_clicked_parent_filter_id" value="" class="ufg-hidden" placeholder="Current Parent Filter">
			<input id="ufg_last_clicked_filter_parent_id" name="ufg_last_clicked_filter_parent_id" value="" class="ufg-hidden" placeholder="Last Parent Filter">
			
			<!-- printing gallery start-->
			<div class="ufg-row ufg-gallery-container ufg-gallery-<?php echo esc_attr($ufg_gallery_id); ?>">
				<div class="ufg-gallery-loader">
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
						<h3 class="ufg-loader-title"><?php esc_html_e('Loading Filter Gallery...', 'filter-gallery'); ?></h3>
						<p class="ufg-loader-subtitle"><?php esc_html_e('Please wait while the gallery is initializing', 'filter-gallery'); ?></p>
					</div>
				</div>
				<?php ufg_gallery($ufg_gallery_id, $ufg_gallery, $ufg_images_per_page, $atts); ?>
			</div>
			<!-- printing gallery end-->
		</div>
		
		<style>
			/* Load more color overrides */
			.fg-load-more button {
				color: <?php echo esc_html($load_txt_color); ?> !important;
				background-color: <?php echo esc_html($load_color); ?> !important;
				border-color: <?php echo esc_html($load_color); ?> !important;
			}
			
			/* Combined Row CSS */
			.fg-content-wrapper .ufg-combined-row {
				display: flex !important;
				flex-wrap: wrap !important;
				align-items: flex-start !important;
				justify-content: space-between !important;
				gap: 20px !important;
				margin-bottom: 20px !important;
				padding-left: 15px !important;
				padding-right: 15px !important;
				box-sizing: border-box !important;
			}
			.fg-content-wrapper .ufg-combined-row .ufg-filter-group-inner {
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
			.fg-content-wrapper .ufg-combined-row .ufg-filter-container {
				flex: 1 1 0% !important;
				min-width: 250px !important;
				margin-bottom: 0 !important;
			}
			.fg-content-wrapper .ufg-combined-row .ufg-search-container {
				flex: 0 0 300px !important;
				width: 300px !important;
				margin-top: 0 !important;
				margin-bottom: 0 !important;
			}
			.fg-content-wrapper .ufg-combined-row .ufg-search-input {
				width: 100% !important;
				max-width: 100% !important;
				box-sizing: border-box !important;
				padding: <?php echo esc_html($ufg_filter_padding); ?> !important;
				margin-top: <?php echo esc_html($ufg_filter_margin); ?> !important;
				margin-bottom: <?php echo esc_html($ufg_filter_margin); ?> !important;
				margin-left: 0 !important;
				margin-right: 0 !important;
				line-height: 1.5 !important;
				font-size: inherit !important;
				height: auto !important;
				min-height: 0 !important;
			}
			.fg-content-wrapper .ufg-uncombined-search {
				margin-bottom: 20px !important;
				padding-left: 15px !important;
				padding-right: 15px !important;
				box-sizing: border-box !important;
				width: 100% !important;
			}
			.fg-content-wrapper .ufg-uncombined-search .ufg-search-input {
				width: 100% !important;
				max-width: 100% !important;
				box-sizing: border-box !important;
				padding: <?php echo esc_html($ufg_filter_padding); ?> !important;
				margin-top: <?php echo esc_html($ufg_filter_margin); ?> !important;
				margin-bottom: <?php echo esc_html($ufg_filter_margin); ?> !important;
				line-height: 1.5 !important;
				font-size: inherit !important;
				height: auto !important;
				min-height: 0 !important;
			}
			.fg-content-wrapper .ufg-uncombined-filter {
				margin-bottom: 20px !important;
				padding-left: 15px !important;
				padding-right: 15px !important;
				box-sizing: border-box !important;
				width: 100% !important;
			}
			.fg-content-wrapper .ufg-filter-group-inner {
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
			
			/* Dropdown select container & select box styles matching search input */
			.fg-content-wrapper .ufg-filter-dropdown-container {
				width: 100% !important;
				box-sizing: border-box !important;
			}
			.fg-content-wrapper select.ufg-filter-dropdown {
				padding: <?php echo esc_html($ufg_filter_padding); ?> !important;
				padding-right: 40px !important;
				margin-top: <?php echo esc_html($ufg_filter_margin); ?> !important;
				margin-bottom: <?php echo esc_html($ufg_filter_margin); ?> !important;
				margin-left: 0 !important;
				margin-right: 0 !important;
				line-height: 1.5 !important;
				font-size: inherit !important;
				height: auto !important;
				min-height: 0 !important;
				border: 1px solid #e5e7eb !important;
				border-radius: 6px !important;
				background-color: #fff !important;
				background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e") !important;
				background-repeat: no-repeat !important;
				background-position: right 15px center !important;
				background-size: 16px !important;
				color: #374151 !important;
				box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
				outline: none !important;
				width: 100% !important;
				max-width: 100% !important;
				box-sizing: border-box !important;
				cursor: pointer !important;
				display: block !important;
				-webkit-appearance: none !important;
				-moz-appearance: none !important;
				appearance: none !important;
			}

			/* Level-by-Level Custom Colors for Dropdown Options */
			.fg-content-wrapper select.ufg-filter-dropdown option.ufg-opt-all {
				color: <?php echo esc_html($ufg_all_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_all_button_bg_color); ?> !important;
			}
			.fg-content-wrapper select.ufg-filter-dropdown option.ufg-opt-parent {
				color: <?php echo esc_html($ufg_parent_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_parent_button_bg_color); ?> !important;
			}

			/* filters CSS and Custom Spacings */
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> button.filters {
				<?php if(!empty($ufg_filter_padding)) { ?>
				padding: <?php echo esc_html($ufg_filter_padding); ?> !important;
				<?php } ?>
				<?php if(!empty($ufg_filter_margin)) { ?>
				margin: <?php echo esc_html($ufg_filter_margin); ?> !important;
				<?php } ?>
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-filter-group-inner > button:first-of-type {
				margin-left: 0 !important;
			}
			/* All Button Style */
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-all-filter-button {
				color: <?php echo esc_html($ufg_parent_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_parent_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_parent_button_bg_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-all-filter-button:hover {
				color: <?php echo esc_html($parent_button_hover_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-all-filter-button.active-filter,
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-all-filter-button.active {
				color: <?php echo esc_html(!empty($parent_active_button_color) ? $parent_active_button_color : $ufg_all_button_color); ?> !important;
				background-color: <?php echo esc_html(!empty($parent_active_button_bg_color) ? $parent_active_button_bg_color : $ufg_all_button_bg_color); ?> !important;
				border-color: <?php echo esc_html(!empty($parent_active_button_bg_color) ? $parent_active_button_bg_color : $ufg_all_button_bg_color); ?> !important;
			}
			
			/* Level 1 (Parent) */
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-parent-filter-button {
				color: <?php echo esc_html($ufg_parent_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_parent_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_parent_button_bg_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-parent-filter-button:hover {
				color: <?php echo esc_html($parent_button_hover_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-parent-filter-button.active-filter,
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-parent-filter-button.active {
				color: <?php echo esc_html($parent_active_button_color); ?> !important;
				background-color: <?php echo esc_html($parent_active_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($parent_active_button_bg_color); ?> !important;
			}

			/* Dropdown Select Style */
			.ufg-filter-dropdown-container {
				width: 100% !important;
				max-width: 320px !important;
				display: inline-block !important;
				margin-bottom: 15px !important;
			}
			.ufg-filter-dropdown,
			.ufg-filter-dropdown option {
				font-family: 'Font Awesome 5 Free', 'FontAwesome', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
				font-weight: 900 !important;
			}
			.ufg-filter-dropdown {
				width: 100% !important;
				padding: 10px 15px !important;
				border: 1px solid #e2e8f0 !important;
				border-radius: 8px !important;
				font-size: 14px !important;
				color: #334155 !important;
				background-color: #ffffff !important;
				background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23475569%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E") !important;
				background-repeat: no-repeat !important;
				background-position: right 12px center !important;
				background-size: 16px !important;
				-webkit-appearance: none !important;
				-moz-appearance: none !important;
				appearance: none !important;
				cursor: pointer !important;
				transition: border-color 0.2s, box-shadow 0.2s !important;
				box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
			}
			.ufg-filter-dropdown:focus {
				border-color: #0c63e7 !important;
				outline: none !important;
				box-shadow: 0 0 0 3px rgba(12, 99, 231, 0.15) !important;
			}

			/* Mobile Responsive Scroll Menu */
			@media (max-width: 767px) {
				.ufg-filter-group-inner {
					display: flex !important;
					overflow-x: auto !important;
					white-space: nowrap !important;
					flex-wrap: nowrap !important;
					-webkit-overflow-scrolling: touch !important;
					padding-bottom: 8px !important;
					margin-bottom: 5px !important;
				}
				.ufg-filter-group-inner::-webkit-scrollbar {
					display: none !important;
				}
				.ufg-filter-group-inner button.filters {
					flex: 0 0 auto !important;
					display: inline-block !important;
				}
			}
			
			.ufg-grid-sizer, .ufg-thumbnail {
				width: calc( (100% / (12/<?php echo intval($ufg_columns_mobile_portrait); ?>)) - 1px ) !important;
				max-width: none !important;
				box-sizing: border-box !important;
				margin: 0 !important;
				padding: 0; /* Internal padding is handled by ufg-thumbnail class below */
			}

			@media (min-width: 576px) {
				.ufg-grid-sizer, .ufg-thumbnail { width: calc( (100% / (12/<?php echo intval($ufg_columns_mobile_landscape); ?>)) - 1px ) !important; }
			}
			@media (min-width: 768px) {
				.ufg-grid-sizer, .ufg-thumbnail { width: calc( (100% / (12/<?php echo intval($ufg_columns_tab); ?>)) - 1px ) !important; }
			}
			@media (min-width: 992px) {
				.ufg-grid-sizer, .ufg-thumbnail { width: calc( (100% / (12/<?php echo intval($ufg_columns_desktop); ?>)) - 1px ) !important; }
			}

			/* gallery-specific overrides */
			<?php if($ufg_image_hover_effect == 'border_overlay') {  ?>
			.ufg-thumbnail .border-expand-one, .ufg-thumbnail .border-expand-two {
				position: absolute;
				top: 30px;
				right: 30px;
				bottom: 30px;
				left: 30px;
				/*border: 2px solid #fff;*/
				/*box-shadow: 0 0 0 30px rgb(255 255 255 / 20%);*/
				content: '';
				opacity: 0;
				-webkit-transition: opacity 0.35s, -webkit-transform 0.35s;
				transition: opacity 0.35s, transform 0.35s;
				-webkit-transform: scale3d(1.4,1.4,1);
				transform: scale3d(1.4,1.4,1);
			}
			.ufg-thumbnail:hover .border-expand-one, .ufg-thumbnail:hover .border-expand-two {
				opacity: 1;
				-webkit-transform: scale3d(1,1,1);
				transform: scale3d(1,1,1);
				z-index: 9;
			}
			.border-expand-one:after, .border-expand-one:before {
				content: " ";
				width: 16px;
				height: 25px;
				position: absolute;
			}.border-expand-two:after, .border-expand-two:before {
				content: " ";
				width: 16px;
				height: 25px;
				position: absolute;
			}
			.border-expand-one:before {
				bottom: 0;
				right: 0;
				border-bottom: 3px solid white;
				border-right: 3px solid white;
			}
			.border-expand-two:before {
				bottom: 0;
				left: 0;
				border-bottom: 3px solid white;
				border-left: 3px solid white;
			}
			.border-expand-one:after {
				top: 0;
				left: 0;
				border-top: 3px solid white;
				border-left: 3px solid white;
			}
			.border-expand-two:after {
				top: 0;
				right: 0;
				border-top: 3px solid white;
				border-right: 3px solid white;
			}
			<?php } ?>

			<?php if($ufg_image_hover_effect == 'border_overlay') {  ?>
			/* The Transformation */
			.ufg-thumbnail:hover img {
				transform: scale(1.1);
				opacity:0.7;
			}
			<?php } ?>

			.ufg-gallery-<?php echo esc_html($ufg_gallery_id); ?> .ufg-thumbnail-border {
				background-color: <?php echo esc_html($ufg_thumbnail_bg_color); ?> !important;
				<?php if($ufg_thumbnail_border) { ?>
				border: <?php echo intval($ufg_thumbnail_border_thickness); ?>px solid <?php echo esc_html($ufg_thumbnail_border_color); ?> !important;
				<?php } ?>
				border-radius: 0.25rem !important;
			}
			.ufg-gallery-<?php echo esc_html($ufg_gallery_id); ?> .ufg-image-title {
				font-size: <?php echo intval($ufg_image_title_font_size); ?>px !important;
				font-weight: bold !important;
				color: <?php echo esc_html($ufg_image_title_color); ?> !important;
			}
			.ufg-gallery-<?php echo esc_html($ufg_gallery_id); ?> .ufg-image-description {
				font-size: <?php echo intval($ufg_image_description_font_size); ?>px !important;
				color: <?php echo esc_html($ufg_image_description_color); ?> !important;
			}
			.ufg-gallery-<?php echo esc_html($ufg_gallery_id); ?> .ufg-read-more-button {
				color: <?php echo esc_html($ufg_read_more_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_read_more_button_bg_color); ?> !important;
			}
			/* =======================================================
			   Gallery Loading Overlay & Spinner styles
			   ======================================================= */
			.fg-content-wrapper .ufg-gallery-container {
				position: relative !important;
				min-height: 280px !important;
			}
			.fg-content-wrapper .ufg-gallery-loader {
				position: absolute !important;
				top: 0 !important;
				left: 0 !important;
				right: 0 !important;
				bottom: 0 !important;
				background: rgba(248, 250, 252, 0.85) !important;
				backdrop-filter: blur(4px) !important;
				z-index: 999 !important;
				display: flex;
				align-items: center !important;
				justify-content: center !important;
				padding: 20px !important;
				border-radius: 12px !important;
				transition: opacity 0.4s ease, visibility 0.4s ease;
			}
			.fg-content-wrapper .ufg-gallery-loader.ufg-loader-hidden {
				display: none !important;
				opacity: 0 !important;
				visibility: hidden !important;
				pointer-events: none !important;
			}
			.fg-content-wrapper .ufg-loader-card {
				background: #ffffff !important;
				border-radius: 16px !important;
				box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 4px 12px -2px rgba(0, 0, 0, 0.03) !important;
				border: 1px solid #f1f5f9 !important;
				padding: 36px 32px !important;
				text-align: center !important;
				max-width: 380px !important;
				width: 100% !important;
				box-sizing: border-box !important;
			}
			.fg-content-wrapper .ufg-loader-spinner-box {
				position: relative !important;
				width: 64px !important;
				height: 64px !important;
				margin: 0 auto 20px auto !important;
				display: flex !important;
				align-items: center !important;
				justify-content: center !important;
			}
			.fg-content-wrapper .ufg-loader-bg-circle {
				position: absolute !important;
				inset: 0 !important;
				border-radius: 50% !important;
				background-color: #eff6ff !important;
				display: flex !important;
				align-items: center !important;
				justify-content: center !important;
			}
			.fg-content-wrapper .ufg-loader-ring {
				position: absolute !important;
				inset: -4px !important;
				border-radius: 50% !important;
				border: 3px solid transparent !important;
				border-top-color: #2563eb !important;
				border-right-color: #2563eb !important;
				animation: ufg-spin 0.9s linear infinite !important;
			}
			.fg-content-wrapper .ufg-loader-title {
				font-family: inherit !important;
				font-size: 18px !important;
				font-weight: 700 !important;
				color: #0f172a !important;
				margin: 0 0 6px 0 !important;
				line-height: 1.3 !important;
			}
			.fg-content-wrapper .ufg-loader-subtitle {
				font-family: inherit !important;
				font-size: 13px !important;
				font-weight: 400 !important;
				color: #64748b !important;
				margin: 0 !important;
				line-height: 1.4 !important;
			}
			@keyframes ufg-spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}
		</style>
		<?php
		//require('filter-ajax.php');
	} else {
		echo "<h4>Error! invalid shortcode.</h4>";
	}
	return ob_get_clean();
}

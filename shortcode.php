<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

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
	if(isset($atts['id'])){
		$ufg_gallery_id = $atts['id'];
		$ufg_selected_filter_btn_id = "";
		
		//get dynamic select filter button id via shortcode parameter
		if(array_key_exists('selected-filter', $atts)){
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
		if (array_key_exists('ufg-image-filters', $ufg_gallery)) {
			foreach($ufg_gallery['ufg-image-filters'] as $key => $array) {
				foreach($array as $key2  => $val) {
					//array_push($new_array[], $val);
					$new_array[$val][$j] = $key;
				}
				$j++;
			}
			foreach($new_array as $new_key => $new_val) {
				$new_re_in = array_values($new_val);
				$new_array_final[$new_key] = $new_re_in;
			}
			$filter_image = $new_array_final;
		}		

		include_once('gallery.php');
		if($ufg_lightbox_numbering) $ufg_lightbox_numbering = "true"; else $ufg_lightbox_numbering = "false";
		// load required resource
		//CSS and JS
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'ufg-isotope-js', plugins_url( '/admin/assets/js/isotope.pkgd.min.js' , __FILE__ ), array( 'jquery', 'imagesloaded' ), '1.0', true );
		wp_enqueue_style( 'ufg-frontend-css', plugins_url( '/admin/assets/css/ufg-frontend.css' , __FILE__ ), array(), '6.0.5' );
		wp_enqueue_style( 'ufg-lightbox-css', plugins_url( '/admin/assets/lightbox/lokesh/css/lightbox.css' , __FILE__ ), array(), '1.0' );
		

		wp_enqueue_script( 'ufg-custom-js', plugins_url( '/admin/assets/js/ufg-custom.js' , __FILE__ ), array( 'jquery', 'imagesloaded', 'ufg-isotope-js' ), '1.0', true );
		wp_enqueue_script( 'ufg-lightbox-js', plugins_url( '/admin/assets/lightbox/lokesh/js/lightbox.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_add_inline_script( 'ufg-custom-js', 'const UFGJS = ' . wp_json_encode( array(
		    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		    'LoadMoreNonce' => wp_create_nonce( 'ufg_load_more_nonce' ),
		    'GalleryId' => $ufg_gallery_id,
		    'FiterImage' => $filter_image,
		    'TotalImages' => $ufg_total_images,
		    'LoadBtnText' => $load_btn_txt,
		    'LoadMore' => $load_more,
		    'LoadLimit' => $load_limit,
		    'ChildFilterEffect' => $ufg_child_filter_effect,
		    'Lightbox' => ($ufg_lightbox === 'on' || $ufg_lightbox == 1 || $ufg_lightbox === '1' || $ufg_lightbox === true),
		    'LightboxNumbering' => ($ufg_lightbox_numbering === "on" || $ufg_lightbox_numbering === "true" || $ufg_lightbox_numbering == 1 || $ufg_lightbox_numbering === '1' || $ufg_lightbox_numbering === true),
		    'LightboxTitle' => ($ufg_lightbox_title === "on" || $ufg_lightbox_title === "1" || $ufg_lightbox_title == 1 || $ufg_lightbox_title === true),
		    'LightboxDescription' => ($ufg_lightbox_description === "on" || $ufg_lightbox_description === "1" || $ufg_lightbox_description == 1 || $ufg_lightbox_description === true),
		    'SelectedFltrBtnId' => $ufg_selected_filter_btn_id,
		)), 'before' );
		?>
		<!-- printing filters start-->
			<div class="fg-content-wrapper" version="<?php echo esc_attr($ufg_last_version); ?>">
			<?php if($ufg_show_filters) { ?>
			<div class="ufg-row">
				<div class="ufg-filter-container ufg-filters-<?php echo esc_attr($ufg_gallery_id); ?>">
					<?php ufg_filters($ufg_gallery_id, $ufg_filters, $ufg_gallery, $atts); ?>
				</div>
			</div>
			<?php } ?>
			<!-- printing filters end-->
			<input id="ufg_current_clicked_filter_id" name="ufg_current_clicked_filter_id" value="" class="ufg-hidden" placeholder="Current Filter">
			<input id="ufg_current_clicked_filter_level" name="ufg_current_clicked_filter_level" value="" class="ufg-hidden" placeholder="Current Level">
			<input id="ufg_last_clicked_filter_id" name="ufg_last_clicked_filter_id" value="" class="ufg-hidden" placeholder="Last Filter">
			<input id="ufg_last_clicked_filter_level" name="ufg_last_clicked_filter_level" value="" class="ufg-hidden" placeholder="Last Level">
			<input id="ufg_current_clicked_parent_filter_id" name="ufg_current_clicked_parent_filter_id" value="" class="ufg-hidden" placeholder="Current Parent Filter">
			<input id="ufg_last_clicked_filter_parent_id" name="ufg_last_clicked_filter_parent_id" value="" class="ufg-hidden" placeholder="Last Parent Filter">
			
			<!-- printing gallery start-->
			<div class="ufg-row ufg-gallery-container ufg-gallery-<?php echo esc_attr($ufg_gallery_id); ?>">
				<?php ufg_gallery($ufg_gallery_id, $ufg_gallery, $ufg_images_per_page, $atts); ?>
			</div>
			<!-- printing gallery end-->
			<?php if($load_more == 'on') { ?>
			<!--Load More--->
			<div class="fg-load-more">
				<button id="fg-load-btn" class="ufg-btn ufg-btn-3"> <?php echo esc_html($load_btn_txt); ?> <span class="ufg-loader"></span> </button>
			</div>
			<?php } ?>
		</div>
		
		<style>
			/* Load more color overrides */
			.fg-load-more button {
				color: <?php echo esc_html($load_txt_color); ?> !important;
				background-color: <?php echo esc_html($load_color); ?> !important;
			}
			
			.ufg-loader {
				display: none;
				width: 16px;
				height: 16px;
				border: 2px solid rgba(255,255,255,0.3);
				border-radius: 50%;
				border-top-color: #fff;
				animation: ufg-spin 1s ease-in-out infinite;
				margin-left: 8px;
				vertical-align: middle;
			}
			@keyframes ufg-spin {
				to { transform: rotate(360deg); }
			}
			#fg-load-btn.loading .ufg-loader {
				display: inline-block;
			}
			
			/* filters CSS */
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-all-filter-button {
				color: <?php echo esc_html($ufg_all_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_all_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_all_button_bg_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-parent-filter-button {
				color: <?php echo esc_html($ufg_parent_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_parent_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_parent_button_bg_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-level-one-button {
				color: <?php echo esc_html($ufg_l1_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_l1_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_l1_button_bg_color); ?> !important;
			}
			
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .active-filter {
				color: <?php echo esc_html($ufg_active_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_active_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_active_button_bg_color); ?> !important;
			}
			
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-level-two-button {
				color: <?php echo esc_html($ufg_l2_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_l2_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_l2_button_bg_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-level-three-button {
				color: <?php echo esc_html($ufg_l3_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_l3_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_l3_button_bg_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-level-four-button {
				color: <?php echo esc_html($ufg_l4_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_l4_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_l4_button_bg_color); ?> !important;
			}
			.ufg-filters-<?php echo esc_html($ufg_gallery_id); ?> .ufg-level-five-button {
				color: <?php echo esc_html($ufg_l5_button_color); ?> !important;
				background-color: <?php echo esc_html($ufg_l5_button_bg_color); ?> !important;
				border-color: <?php echo esc_html($ufg_l5_button_bg_color); ?> !important;
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
			<?php echo esc_html(wp_strip_all_tags($ufg_custom_css)); ?>
		</style>
		<?php
		//require('filter-ajax.php');
	} else {
		echo "<h4>Error! invalid shortcode.</h4>";
	}
	return ob_get_clean();
}

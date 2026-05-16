<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

// this file print gallery - gallery callback
if (!function_exists('ufg_gallery')) {
	function ufg_gallery($ufg_gallery_id, $ufg_gallery, $ufg_images_per_page, $atts = array())
	{
		if (is_array($ufg_gallery)) {
			$load_class = '';
			$new_array = array();
			$new_array_final = array();

			$ufg_setting = get_option("ufg_settings_" . $ufg_gallery_id);
			include('setting.php');

			echo "<div id='ufg-gallery-" . esc_attr($ufg_gallery_id) . "' class='ufg-gallery'>";
			echo "<div class='ufg-grid-sizer'></div>";
			$j = 0;
			$new_array = array();
			$new_array_final = array();
			if (array_key_exists('ufg-image-filters', $ufg_gallery)) {
				foreach ($ufg_gallery['ufg-image-filters'] as $key => $array) {
					foreach ($array as $key2 => $val) {
						if (strpos($val, ',') !== false) {
							$parts = explode(',', $val);
							foreach ($parts as $p) {
								$p = trim($p);
								if (!empty($p)) {
									$new_array[$p][$j] = $key;
								}
							}
						} else {
							//array_push($new_array[], $val);
							$new_array[$val][$j] = $key;
						}
					}
					$j++;
				}
				foreach ($new_array as $new_key => $new_val) {
					$new_re_in = array_values($new_val);
					$new_array_final[$new_key] = $new_re_in;
				}
				$filter_image = $new_array_final;
			}

			//image sorting
			if (array_key_exists('ufg-title', $ufg_gallery)) {
				if ($ufg_image_sorting == 1)
					ksort($ufg_gallery['ufg-title']); //ascending image id
				if ($ufg_image_sorting == 2)
					krsort($ufg_gallery['ufg-title']); //descending image id
				if ($ufg_image_sorting == 3)
					asort($ufg_gallery['ufg-title']); //ascending image title
				if ($ufg_image_sorting == 4)
					arsort($ufg_gallery['ufg-title']); //descending image title
				//if($ufg_image_sorting == 5) array_reverse($ufg_gallery['ufg-title']); //none
			}

			if (!function_exists('ufg_shuffle_assoc')) {
				function ufg_shuffle_assoc(&$array)
				{
					$keys = array_keys($array);
					shuffle($keys);
					foreach ($keys as $key) {
						$new[$key] = $array[$key];
					}
					$array = $new;
					return true;
				}
			}

			if ($ufg_image_sorting == 6)
				ufg_shuffle_assoc($ufg_gallery['ufg-title']); //random
			$ufg_total_images = count($ufg_gallery['ufg-attachment-id']);

			// load more array
			$load_id_array = array();
			if ($ufg_image_sorting == 5) { //if sorting is OFF
				foreach ($ufg_gallery['ufg-attachment-id'] as $value) {
					$load_id_array[] = $value;
				}
			} else { //if sorting is ON
				foreach ($ufg_gallery['ufg-title'] as $key => $value) {
					$load_id_array[] = $key;
				}
			}

			//echo $ufg_total_images;

			//keys: ufg-attachment-id / ufg-title / ufg-alt / ufg-description / ufg-url / ufg-image-filters
			//defaults
			$ufg_title = $ufg_alt = $ufg_description = $ufg_url = "";

			// Load more var
			//$ufg_limit = 2;
			//$ufg_load_more = 'on';
			//$ufg_total_images = 12;
			$count = 0;
			$no = 1;
			//******************** Load Image With Limit [Shortcode] *******************//
			if ($load_more != 'on') {

				if ($ufg_image_sorting == 5) { //if sorting is OFF
					$reversed_attachment_ids = $ufg_gallery['ufg-attachment-id'];
					//$reversed_attachment_ids = array_reverse($ufg_gallery['ufg-attachment-id'], true);
					foreach ($reversed_attachment_ids as $value) {
						$attachment_id = $value;
						// Load Gallery Content
						include('gallery-content.php');
					}
				} else { //if sorting is ON
					$reversed_attachment_ids = $ufg_gallery['ufg-title'];
					foreach ($reversed_attachment_ids as $key => $value) {
						$attachment_id = $key;
						// Load Gallery Content
						include('gallery-content.php');
					}
				}
			}


			//******************** Load More Is ON *******************//
			if ($load_more == 'on') {
				// run loop according to remaining images
				//$load_limit = 5;
				$load_limit_int = (int) $load_limit;
				if ($load_limit_int <= 0) {
					$load_limit_int = 4; // Fallback default if empty or 0
				}

				$remain_images = $ufg_total_images - $load_limit_int;
				if ($remain_images < 0) {
					$load_limit_int = $load_limit_int + $remain_images;
				}

				for ($i = 0; $i < $load_limit_int; $i++) {
					$attachment_id = $load_id_array[$i];
					// Load Gallery Data & Content
					include('gallery-content.php');

					$no++;
					$count++;
				}

				if (isset($_POST['ufg_security'])) {
					$ufg_security = sanitize_text_field(wp_unslash($_POST['ufg_security']));
					if (wp_verify_nonce($ufg_security, 'ufg_load_more_nonce')) {
						$ufg_limit_start = isset($_POST['ufg_limit_start']) ? intval(wp_unslash($_POST['ufg_limit_start'])) : 0;
						$ufg_limit_end = isset($_POST['ufg_limit_end']) ? intval(wp_unslash($_POST['ufg_limit_end'])) : 0;
						$targetFilter = isset($_POST['targetFilter']) ? sanitize_text_field(wp_unslash($_POST['targetFilter'])) : '';
						$CalTotalLoadedItem = isset($_POST['CalTotalLoadedItem']) ? intval(wp_unslash($_POST['CalTotalLoadedItem'])) : 0;

						// get already loaded images id
						$get_all_items = isset($_POST['get_all_items']) ? sanitize_text_field(wp_unslash($_POST['get_all_items'])) : '';
						$get_all_items_val = explode(",", $get_all_items);
						$img_ids_diff = array_diff($load_id_array, $get_all_items_val);
						$img_ids_diff2 = array_values($img_ids_diff);
						$no = 0;

						if ($targetFilter != '*' && isset($filter_image[$targetFilter]) && is_array($filter_image[$targetFilter])) {
							$targetFilterD = explode(",", $targetFilter);
							foreach ($targetFilterD as $key => $targetFilter_r) {
								if (isset($filter_image[$targetFilter_r]) && is_array($filter_image[$targetFilter_r])) {
									foreach ($filter_image[$targetFilter_r] as $key => $filter_image_r) {
										$attached[] = $filter_image_r;
									}
								}
							}
							// unset already load images
							$img_ids_diff_filter = array_diff($attached, $get_all_items_val);
							$img_ids_diff_val = array_values($img_ids_diff_filter);

							$img_ids_diff_val_count = count($img_ids_diff_val);
						}

						/* echo "<pre>";
						print_r($ufg_limit_start);
						echo "</pre>";
						echo "<br>";
						echo "<pre>";
						print_r($ufg_limit_start);
						echo "</pre>";
						echo "<br>";
						echo "<pre>";
						print_r($ufg_limit_end);
						echo "</pre>"; */

						for ($i = $ufg_limit_start; $i < $ufg_limit_end; $i++) {

							if ($targetFilter != '*') {
								if (!isset($img_ids_diff_val[$no])) {
									break;
								}
								$attachment_id = $img_ids_diff_val[$no];
							} else {
								if (!isset($img_ids_diff2[$no])) {
									break;
								}
								$attachment_id = $img_ids_diff2[$no];
							}

							if ($load_more == 'on') {
								$load_class = 'ufg_result';
							} else {
								$load_class = '';
							}

							// Load Gallery Data & Content
							include('gallery-content.php');

							$CalTotalLoadedItem++;
							$count++;
							$no++;
						}
					}
				}
			}
			echo "</div>";
		}
	}
}
?>
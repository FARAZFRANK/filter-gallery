<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

// this file print filters - filters callback
if (!function_exists('ufg_filters')) {
	function ufg_filters($ufg_gallery_id, $ufg_filters, $ufg_gallery, $atts = array())
	{
		if (is_array($ufg_filters) && $filters_count = count($ufg_filters)) {

			//echo $filters_count; 
			//load settings
			$ufg_setting = get_option("ufg_settings_" . $ufg_gallery_id);
			include('setting.php');

			// filters image count
			$ufg_total_image_count = 0;
			if (isset($ufg_gallery['ufg-attachment-id']) && is_array($ufg_gallery['ufg-attachment-id'])) {
				$ufg_total_image_count = count($ufg_gallery['ufg-attachment-id']);
			}

			// Pre-calculate filter image mapped IDs
			$ufg_filter_images = array();
			if (isset($ufg_gallery['ufg-image-filters']) && is_array($ufg_gallery['ufg-image-filters'])) {
				foreach ($ufg_gallery['ufg-image-filters'] as $att_id => $item_filters) {
					if (is_array($item_filters)) {
						foreach ($item_filters as $filter_key) {
							$clean_key = strtolower(trim($filter_key));
							if (!isset($ufg_filter_images[$clean_key])) {
								$ufg_filter_images[$clean_key] = array();
							}
							$ufg_filter_images[$clean_key][] = $att_id;
						}
					}
				}
			}

			// Add parent-child image count sum logic using unique image IDs
			$ufg_filter_counts = array();
			if (!function_exists('ufg_compute_filter_counts')) {
				function ufg_compute_filter_counts($filters, $images_map, &$counts) {
					$all_assigned = array();
					if (is_array($filters)) {
						foreach ($filters as $filter) {
							if (!isset($filter->filterkey)) continue;
							$key = strtolower(trim($filter->filterkey));
							$my_images = isset($images_map[$key]) ? $images_map[$key] : array();
							
							if (isset($filter->children) && is_array($filter->children)) {
								ufg_compute_filter_counts($filter->children, $images_map, $counts);
							}
							$my_images = array_unique($my_images);
							$counts[$key] = count($my_images);
							$all_assigned = array_merge($all_assigned, $my_images);
						}
					}
					return array_unique($all_assigned);
				}
			}
			
			if (is_array($ufg_filters)) {
				ufg_compute_filter_counts($ufg_filters, $ufg_filter_images, $ufg_filter_counts);
			}

			// Define count display logic
			$get_count_html = function ($filter_key) use ($ufg_show_filters_count, $ufg_filter_counts) {
				$clean_key = strtolower(trim($filter_key));
				if ($ufg_show_filters_count && isset($ufg_filter_counts[$clean_key])) {
					return " (" . intval($ufg_filter_counts[$clean_key]) . ")";
				}
				return "";
			};

			$ufg_filter_style = (isset($ufg_setting['filter_style']) && $ufg_setting['filter_style'] === 'dropdown') ? 'dropdown' : 'buttons';
			
			if (!function_exists('ufg_fa_class_to_unicode')) {
				function ufg_fa_class_to_unicode($class) {
					if (empty($class)) return '';
					$hex_map = array(
						'fa-filter' => '\uf0b0',
						'fa-camera' => '\uf030',
						'fa-image' => '\uf03e',
						'fa-star' => '\uf005',
						'fa-heart' => '\uf004',
						'fa-shield-alt' => '\uf3ed',
						'fa-shield' => '\uf132',
						'fa-search' => '\uf002',
						'fa-link' => '\uf0c1',
						'fa-tag' => '\uf02b',
						'fa-tags' => '\uf02c',
						'fa-folder' => '\uf07b',
						'fa-folder-open' => '\uf07c',
						'fa-user' => '\uf007',
						'fa-users' => '\uf0c0',
						'fa-eye' => '\uf06e',
						'fa-check' => '\uf00c',
						'fa-bookmark' => '\uf02e',
						'fa-cog' => '\uf013',
						'fa-cogs' => '\uf085',
						'fa-layer-group' => '\uf5fd',
						'fa-palette' => '\uf53f',
						'fa-images' => '\uf302',
						'fa-calendar' => '\uf133',
						'fa-clock' => '\uf017',
						'fa-map-marker-alt' => '\uf3c5',
						'fa-globe' => '\uf0ac',
						'fa-thumbs-up' => '\uf164',
						'fa-fire' => '\uf06d',
						'fa-bolt' => '\uf0e7',
						'fa-gem' => '\uf3a5',
						'fa-trophy' => '\uf091',
						'fa-gift' => '\uf06b',
						'fa-music' => '\uf001',
						'fa-video' => '\uf03d',
						'fa-film' => '\uf008',
						'fa-compass' => '\uf14e',
						'fa-sun' => '\uf185',
						'fa-moon' => '\uf186',
						'fa-leaf' => '\uf06c',
						'fa-circle' => '\uf111',
						'fa-square' => '\uf0c8',
						'fa-play' => '\uf04b',
						'fa-pause' => '\uf04c',
						'fa-stop' => '\uf04d',
						'fa-info-circle' => '\uf05a',
						'fa-question-circle' => '\uf059',
						'fa-exclamation-circle' => '\uf06a',
						'fa-exclamation-triangle' => '\uf071',
						'fa-arrow-right' => '\uf061',
						'fa-arrow-left' => '\uf060',
						'fa-arrow-up' => '\uf062',
						'fa-arrow-down' => '\uf063',
						'fa-envelope' => '\uf0e0',
						'fa-phone' => '\uf095',
						'fa-home' => '\uf015',
						'fa-shopping-cart' => '\uf07a',
						'fa-th' => '\uf00a',
						'fa-th-large' => '\uf009',
						'fa-list' => '\uf03a',
					);
					foreach ($hex_map as $key => $hex) {
						if (strpos($class, $key) !== false) {
							return json_decode('"' . $hex . '"') . ' ';
						}
					}
					return '';
				}
			}

			// Dropdown Style Rendering
			if ($ufg_filter_style === 'dropdown') {
				echo "<div class='ufg-filter-dropdown-container'>";
				echo "<select class='ufg-filter-dropdown filters' data-gallery-id='" . esc_attr($ufg_gallery_id) . "'>";
				if ($ufg_show_all_button) {
					$all_icon_unicode = '';
					if ($ufg_show_filters_icon != 0) {
						$icon_to_use = !empty($ufg_all_button_icon) ? $ufg_all_button_icon : 'fas fa-filter';
						$all_icon_unicode = ufg_fa_class_to_unicode($icon_to_use);
					}
					echo "<option class='ufg-opt-parent ufg-opt-all' value='*'>" . esc_html($all_icon_unicode . $ufg_all_button_text) . " (" . intval($ufg_total_image_count) . ")</option>";
				}
				if (!function_exists('ufg_render_dropdown_options')) {
					function ufg_render_dropdown_options($filters, $get_count_html, $ufg_show_filters_icon, $depth = 0) {
						if (!is_array($filters)) return;
						$level_classes = array(0 => 'parent', 1 => 'l1', 2 => 'l2', 3 => 'l3', 4 => 'l4');
						$class_suffix = isset($level_classes[$depth]) ? $level_classes[$depth] : 'parent';
						$option_class = 'ufg-opt-' . $class_suffix;

						foreach ($filters as $filter) {
							if (!isset($filter->filterkey)) continue;
							$key = str_replace(" ", "-", strtolower(trim($filter->filterkey)));
							$text = $filter->text;
							$indent = str_repeat(chr(194) . chr(160) . chr(194) . chr(160) . chr(194) . chr(160) . chr(194) . chr(160), $depth);
							$prefix = $depth > 0 ? '— ' : '';
							$count_html = $get_count_html($filter->filterkey);
							
							$icon_unicode = '';
							if ($ufg_show_filters_icon != 0 && isset($filter->icon) && !empty($filter->icon)) {
								$icon_unicode = ufg_fa_class_to_unicode($filter->icon);
							}

							echo '<option class="' . esc_attr($option_class) . '" value="' . esc_attr($key) . '">' . esc_html($indent) . esc_html($prefix) . esc_html($icon_unicode . $text) . esc_html($count_html) . '</option>';
							
							if (isset($filter->children) && is_array($filter->children)) {
								ufg_render_dropdown_options($filter->children, $get_count_html, $ufg_show_filters_icon, $depth + 1);
							}
						}
					}
				}
				ufg_render_dropdown_options($ufg_filters, $get_count_html, $ufg_show_filters_icon, 0);
				echo "</select>";
				echo "</div>";
				return;
			}
			
			// print parent filters
			echo "<!-- UFG DEBUG: " . esc_html(json_encode($ufg_filter_counts)) . " -->";
			echo "<div class='filter-group ufg-parent-filters'>";
			echo "<div class='ufg-filter-group-inner'>";

			if (isset($ufg_setting['parent_filters_heading']))
				$parent_filters_heading = $ufg_setting['parent_filters_heading'];
			else
				$parent_filters_heading = "";
			if ($parent_filters_heading) {
				echo "<p class='parent-filters-label'>" . esc_html($parent_filters_heading) . "</p>";
			}

			if ($ufg_show_all_button) {
				$ufg_all_icon_html = "";
				$icon_to_use = !empty($ufg_all_button_icon) ? $ufg_all_button_icon : 'fas fa-filter';
				if ($ufg_show_filters_icon != 0) {
					$ufg_all_icon_html = "<i class='" . esc_attr($icon_to_use) . "'></i>";
				}
				echo "<button data-filter='*' data-fname='none' id='1evel1-all' class='ufg-btn ufg-btn-3 filters ufg-all-filter-button ufg-parent-filters ufg-all-filter all ' onclick='return filter(this.id, this.value)' value='*'>" . wp_kses_post($ufg_all_icon_html) . " " . esc_html($ufg_all_button_text) . " (" . intval($ufg_total_image_count) . ") <span class='ufg-active-dot'></span></button>";
			}



			for ($i = 0; $i <= $filters_count; $i++) {
				/* echo "<pre>";
				print_r($ufg_filters);
				echo "</pre>"; */
				if (isset($ufg_filters[$i]->text)) {
					$parent_filter_name = $ufg_filters[$i]->text;
					//filter icon
					$parent_filter_icon = "";
					if (isset($ufg_filters[$i]->icon))
						$parent_filter_icon = $ufg_filters[$i]->icon;
					if ($parent_filter_icon != "")
						$parent_icon_html = "<i class='" . esc_attr($parent_filter_icon) . "'></i>";
					else
						$parent_icon_html = "";
					if ($ufg_show_filters_icon == 0) {
						$parent_icon_html = "";
					}
					$parent_filter_class = str_replace(" ", "-", strtolower($ufg_filters[$i]->filterkey));
					//echo "<button data-filter='.$parent_filter_class' data-fname='.$parent_filter_class' id='1evel1-$parent_filter_class' class='ufg-btn ufg-btn-3 filters ufg-parent-filter-button ufg-parent-filters $parent_filter_class' onclick='return filter(this.id, this.value)' value='$parent_filter_class'>$parent_icon_html $parent_filter_name <span class='ufg-active-dot'></span></button>";
					echo "<button data-filter='." . esc_attr($parent_filter_class) . "' data-fname='." . esc_attr($parent_filter_class) . "' id='1evel1-" . esc_attr($parent_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-parent-filter-button ufg-parent-filters " . esc_attr($parent_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($parent_filter_class) . "'>" . wp_kses_post($parent_icon_html) . " " . esc_html($parent_filter_name) . wp_kses_post($get_count_html($ufg_filters[$i]->filterkey)) . " <span class='ufg-active-dot'></span></button>";
				}
			}
			echo "</div>";
			echo "</div>";
			// print parent filters end

			// print child level one filters
			echo "<div class='filter-group ufg-level-one-filters'>";
			echo "<div class='ufg-filter-group-inner'>";
			if (isset($ufg_setting['l1_filters_heading']))
				$l1_filters_heading = $ufg_setting['l1_filters_heading'];
			else
				$l1_filters_heading = "";
			if ($l1_filters_heading) {
				echo "<p class='level-one-filters-label'>" . esc_html($l1_filters_heading) . "</p>";
			}
			for ($i = 0; $i <= $filters_count; $i++) {

				//check level one children 
				if (isset($ufg_filters[$i]->children)) {
					$parent_filter_class = str_replace(" ", "-", strtolower($ufg_filters[$i]->filterkey));
					$child_count_level_one = count($ufg_filters[$i]->children);
					if ($child_count_level_one) {
						$level_one_array = $ufg_filters[$i]->children;
						for ($j = 0; $j < $child_count_level_one; $j++) {
							$level_one_filter_name = $level_one_array[$j]->text;
							//filter icon
							$level_one_filter_icon = isset($level_one_array[$j]->icon) ? $level_one_array[$j]->icon : "";
							if ($level_one_filter_icon != "")
								$level_one_filter_icon_html = "<i class='" . esc_attr($level_one_filter_icon) . "'></i>";
							else
								$level_one_filter_icon_html = "";
							if ($ufg_show_filters_icon == 0) {
								$level_one_filter_icon_html = "";
							}
							$level_one_filter_class = str_replace(" ", "-", strtolower($level_one_array[$j]->filterkey));
							//echo "<button data-filter='.$parent_filter_class .$level_one_filter_class' data-fname='.$level_one_filter_class' id='level2-$level_one_filter_class' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-one-button $parent_filter_class $level_one_filter_class' onclick='return filter(this.id, this.value)' value='$level_one_filter_class'>$level_one_filter_icon_html $level_one_filter_name <span class='ufg-active-dot'></span></button>";
							echo "<button data-filter='." . esc_attr($parent_filter_class) . " ." . esc_attr($level_one_filter_class) . "' data-fname='." . esc_attr($level_one_filter_class) . "' id='level2-" . esc_attr($level_one_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-one-button " . esc_attr($parent_filter_class) . " " . esc_attr($level_one_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_one_filter_class) . "'>" . wp_kses_post($level_one_filter_icon_html) . " " . esc_html($level_one_filter_name) . wp_kses_post($get_count_html($level_one_array[$j]->filterkey)) . " <span class='ufg-active-dot'></span></button>";
						}
					}
				}
			}
			echo "</div>";
			echo "</div>";
			// print child level one filters end




			// print child level two filters
			echo "<div class='filter-group ufg-level-two-filters'>";
			echo "<div class='ufg-filter-group-inner'>";
			if (isset($ufg_setting['l2_filters_heading']))
				$l2_filters_heading = $ufg_setting['l2_filters_heading'];
			else
				$l2_filters_heading = "";
			if ($l2_filters_heading) {
				echo "<p class='level-two-filters-label'>" . esc_html($l2_filters_heading) . "</p>";
			}
			for ($i = 0; $i <= $filters_count; $i++) {
				//check level one children
				if (isset($ufg_filters[$i]->children)) {
					$parent_filter_class = str_replace(" ", "-", strtolower($ufg_filters[$i]->filterkey));
					$child_count_level_one = count($ufg_filters[$i]->children);
					$level_one_array = $ufg_filters[$i]->children;
					for ($j = 0; $j < $child_count_level_one; $j++) {
						$level_one_filter_class = str_replace(" ", "-", strtolower($level_one_array[$j]->filterkey));

						//check level two children
						if (isset($level_one_array[$j]->children)) {
							$child_count_level_two = count($level_one_array[$j]->children);
							$level_two_array = $level_one_array[$j]->children;
							for ($k = 0; $k < $child_count_level_two; $k++) {
								$level_two_filter_name = $level_two_array[$k]->text;
								//filter icon
								$level_two_filter_icon = isset($level_two_array[$k]->icon) ? $level_two_array[$k]->icon : "";
								if ($level_two_filter_icon != "")
									$level_two_filter_icon_html = "<i class='" . esc_attr($level_two_filter_icon) . "'></i>";
								else
									$level_two_filter_icon_html = "";
								if ($ufg_show_filters_icon == 0) {
									$level_two_filter_icon_html = "";
								}
								$level_two_filter_class = str_replace(" ", "-", strtolower($level_two_array[$k]->filterkey));
								echo "<button data-filter='." . esc_attr($parent_filter_class) . " ." . esc_attr($level_one_filter_class) . " ." . esc_attr($level_two_filter_class) . "' data-fname='." . esc_attr($level_two_filter_class) . "' id='level3-" . esc_attr($level_two_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-two-button " . esc_attr($parent_filter_class) . " " . esc_attr($level_one_filter_class) . " " . esc_attr($level_two_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_two_filter_class) . "'>" . wp_kses_post($level_two_filter_icon_html) . " " . esc_html($level_two_filter_name) . wp_kses_post($get_count_html($level_two_array[$k]->filterkey)) . " <span class='ufg-active-dot'></span></button>";
							}
						}
						// level two children check end
					}

				}
			}
			echo "</div>";
			echo "</div>";
			// print child level two filters end


			// print child level three filters
			echo "<div class='filter-group ufg-level-three-filters'>";
			echo "<div class='ufg-filter-group-inner'>";

			if (isset($ufg_setting['l3_filters_heading']))
				$l3_filters_heading = $ufg_setting['l3_filters_heading'];
			else
				$l3_filters_heading = "";
			if ($l3_filters_heading) {
				echo "<p class='level-three-filters-label'>" . esc_html($l3_filters_heading) . "</p>";
			}

			for ($i = 0; $i <= $filters_count; $i++) {
				//check level one children
				if (isset($ufg_filters[$i]->children)) {
					$child_count_level_one = count($ufg_filters[$i]->children);
					$level_one_array = $ufg_filters[$i]->children;
					for ($j = 0; $j < $child_count_level_one; $j++) {

						//check level two children
						if (isset($level_one_array[$j]->children)) {
							$child_count_level_two = count($level_one_array[$j]->children);
							$level_two_array = $level_one_array[$j]->children;
							for ($k = 0; $k < $child_count_level_two; $k++) {

								//check level three children
								if (isset($level_two_array[$k]->children)) {
									$child_count_level_three = count($level_two_array[$k]->children);
									$level_three_array = $level_two_array[$k]->children;
									for ($l = 0; $l < $child_count_level_three; $l++) {
										$parent_filter_class = str_replace(" ", "-", strtolower($ufg_filters[$i]->filterkey));
										$level_one_filter_class = str_replace(" ", "-", strtolower($level_one_array[$j]->filterkey));
										$level_two_filter_class = str_replace(" ", "-", strtolower($level_two_array[$k]->filterkey));
										$level_three_filter_name = $level_three_array[$l]->text;
										//filter icon
										$level_three_filter_icon = isset($level_three_array[$l]->icon) ? $level_three_array[$l]->icon : "";
										if ($level_three_filter_icon != "")
											$level_three_filter_icon_html = "<i class='" . esc_attr($level_three_filter_icon) . "'></i>";
										else
											$level_three_filter_icon_html = "";
										if ($ufg_show_filters_icon == 0) {
											$level_three_filter_icon_html = "";
										}
										$level_three_filter_class = str_replace(" ", "-", strtolower($level_three_array[$l]->filterkey));
										echo "<button data-filter='." . esc_attr($parent_filter_class) . " ." . esc_attr($level_one_filter_class) . " ." . esc_attr($level_two_filter_class) . " ." . esc_attr($level_three_filter_class) . "' data-fname='." . esc_attr($level_three_filter_class) . "' id='level4-" . esc_attr($level_three_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-three-button " . esc_attr($parent_filter_class) . " " . esc_attr($level_one_filter_class) . " " . esc_attr($level_two_filter_class) . " " . esc_attr($level_three_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_three_filter_class) . "'>" . wp_kses_post($level_three_filter_icon_html) . " " . esc_html($level_three_filter_name) . wp_kses_post($get_count_html($level_three_array[$l]->filterkey)) . " <span class='ufg-active-dot'></span></button>";
									}
								}
								// level three children check end


							}
						}
						// level two children check end
					}

				}
			}
			echo "</div>";
			echo "</div>";
			// print child level three filters end





			// print child level four filters
			echo "<div class='filter-group ufg-level-four-filters'>";
			echo "<div class='ufg-filter-group-inner'>";

			if (isset($ufg_setting['l4_filters_heading']))
				$l4_filters_heading = $ufg_setting['l4_filters_heading'];
			else
				$l4_filters_heading = "";
			if ($l4_filters_heading) {
				echo "<p class='level-four-filters-label'>" . esc_html($l4_filters_heading) . "</p>";
			}

			for ($i = 0; $i <= $filters_count; $i++) {
				//check level one children
				if (isset($ufg_filters[$i]->children)) {
					$child_count_level_one = count($ufg_filters[$i]->children);
					$level_one_array = $ufg_filters[$i]->children;
					for ($j = 0; $j < $child_count_level_one; $j++) {

						//check level two children
						if (isset($level_one_array[$j]->children)) {
							$child_count_level_two = count($level_one_array[$j]->children);
							$level_two_array = $level_one_array[$j]->children;
							for ($k = 0; $k < $child_count_level_two; $k++) {

								//check level three children
								if (isset($level_two_array[$k]->children)) {
									$child_count_level_three = count($level_two_array[$k]->children);
									$level_three_array = $level_two_array[$k]->children;
									for ($l = 0; $l < $child_count_level_three; $l++) {
										$level_three_filter_name = $level_three_array[$l]->text;
										$level_three_filter_class = str_replace(" ", "-", strtolower($level_three_array[$l]->filterkey));

										//check level four children
										if (isset($level_three_array[$l]->children)) {
											$child_count_level_four = count($level_three_array[$l]->children);
											$level_four_array = $level_three_array[$l]->children;
											for ($m = 0; $m < $child_count_level_four; $m++) {
												$parent_filter_class = str_replace(" ", "-", strtolower($ufg_filters[$i]->filterkey));
												$level_one_filter_class = str_replace(" ", "-", strtolower($level_one_array[$j]->filterkey));
												$level_two_filter_class = str_replace(" ", "-", strtolower($level_two_array[$k]->filterkey));
												$level_three_filter_class = str_replace(" ", "-", strtolower($level_three_array[$l]->filterkey));
												$level_four_filter_name = $level_four_array[$m]->text;
												//filter icon
												$level_four_filter_icon = isset($level_four_array[$m]->icon) ? $level_four_array[$m]->icon : "";
												if ($level_four_filter_icon != "")
													$level_four_filter_icon_html = "<i class='" . esc_attr($level_four_filter_icon) . "'></i>";
												else
													$level_four_filter_icon_html = "";
												if ($ufg_show_filters_icon == 0) {
													$level_four_filter_icon_html = "";
												}
												$level_four_filter_class = str_replace(" ", "-", strtolower($level_four_array[$m]->filterkey));
												echo "<button data-filter='." . esc_attr($parent_filter_class) . " ." . esc_attr($level_one_filter_class) . " ." . esc_attr($level_two_filter_class) . " ." . esc_attr($level_three_filter_class) . " ." . esc_attr($level_four_filter_class) . "' data-fname='." . esc_attr($level_four_filter_class) . "' id='level5-" . esc_attr($level_four_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-four-button sub-filter sub-filter-4 " . esc_attr($parent_filter_class) . " " . esc_attr($level_one_filter_class) . " " . esc_attr($level_two_filter_class) . " " . esc_attr($level_three_filter_class) . " " . esc_attr($level_four_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_four_filter_class) . "'>" . wp_kses_post($level_four_filter_icon_html) . " " . esc_html($level_four_filter_name) . wp_kses_post($get_count_html($level_four_array[$m]->filterkey)) . " <span class='ufg-active-dot'></span></button>";
											}
										}
										// level four children check end


									}
								}
								// level three children check end


							}
						}
						// level two children check end
					}

				}
			}
			echo "</div>";
			echo "</div>";
			// print child level four filters end




			
		}
	}
}
?>
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
			if (isset($ufg_gallery['ufg-attachment-id'])) {
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
								$child_images = ufg_compute_filter_counts($filter->children, $images_map, $counts);
								$my_images = array_merge($my_images, $child_images);
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

				echo "<button data-filter='*' data-fname='none' id='1evel1-all' class='ufg-btn ufg-btn-3 filters ufg-all-filter-button ufg-parent-filters ufg-all-filter all ' onclick='return filter(this.id, this.value)' value='*'>" . esc_html($ufg_all_button_text) . " (" . intval($ufg_total_image_count) . ") <span class='ufg-active-dot'></span></button>";

			// Define count display logic
			$get_count_html = function ($filter_key) use ($ufg_show_filters_count, $ufg_filter_counts) {
				$clean_key = strtolower(trim($filter_key));
				if ($ufg_show_filters_count && isset($ufg_filter_counts[$clean_key])) {
					return " (" . intval($ufg_filter_counts[$clean_key]) . ")";
				}
				return "";
			};

			for ($i = 0; $i <= $filters_count; $i++) {
				/* echo "<pre>";
				print_r($ufg_filters);
				echo "</pre>"; */
				if (isset($ufg_filters[$i]->text)) {
					$parent_filter_name = $ufg_filters[$i]->text;
					echo "<button data-filter='." . esc_attr($parent_filter_class) . "' data-fname='." . esc_attr($parent_filter_class) . "' id='1evel1-" . esc_attr($parent_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-parent-filter-button ufg-parent-filters " . esc_attr($parent_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($parent_filter_class) . "'>" . esc_html($parent_filter_name) . $get_count_html($ufg_filters[$i]->filterkey) . " <span class='ufg-active-dot'></span></button>";
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
							echo "<button data-filter='." . esc_attr($parent_filter_class) . " ." . esc_attr($level_one_filter_class) . "' data-fname='." . esc_attr($level_one_filter_class) . "' id='level2-" . esc_attr($level_one_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-one-button " . esc_attr($parent_filter_class) . " " . esc_attr($level_one_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_one_filter_class) . "'>" . esc_html($level_one_filter_name) . $get_count_html($level_one_array[$j]->filterkey) . " <span class='ufg-active-dot'></span></button>";
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
								echo "<button data-filter='." . esc_attr($parent_filter_class) . " ." . esc_attr($level_one_filter_class) . " ." . esc_attr($level_two_filter_class) . "' data-fname='." . esc_attr($level_two_filter_class) . "' id='level3-" . esc_attr($level_two_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-two-button " . esc_attr($parent_filter_class) . " " . esc_attr($level_one_filter_class) . " " . esc_attr($level_two_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_two_filter_class) . "'>" . esc_html($level_two_filter_name) . $get_count_html($level_two_array[$k]->filterkey) . " <span class='ufg-active-dot'></span></button>";
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
										echo "<button data-filter='." . esc_attr($level_three_filter_class) . "' data-fname='." . esc_attr($level_three_filter_class) . "' id='level4-" . esc_attr($level_three_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-three-button " . esc_attr($level_three_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_three_filter_class) . "'>" . esc_html($level_three_filter_name) . $get_count_html($level_three_array[$l]->filterkey) . " <span class='ufg-active-dot'></span></button>";
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
												echo "<button data-filter='." . esc_attr($level_four_filter_class) . "' data-fname='." . esc_attr($level_four_filter_class) . "' id='level5-" . esc_attr($level_four_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-four-button sub-filter sub-filter-4 " . esc_attr($level_four_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_four_filter_class) . "'>" . esc_html($level_four_filter_name) . $get_count_html($level_four_array[$m]->filterkey) . " <span class='ufg-active-dot'></span></button>";
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




			// print child level five filters
			echo "<div class='filter-group ufg-level-five-filters'>";
			echo "<div class='ufg-filter-group-inner'>";
			if (isset($ufg_setting['l5_filters_heading']))
				$l5_filters_heading = $ufg_setting['l5_filters_heading'];
			else
				$l5_filters_heading = "";
			if ($l5_filters_heading) {
				echo "<p class='level-five-filters-label'>" . esc_html($l5_filters_heading) . "</p>";
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
												$level_four_filter_name = $level_four_array[$m]->text;
												$level_four_filter_class = str_replace(" ", "-", strtolower($level_four_array[$m]->filterkey));

												//check level five children
												if (isset($level_four_array[$m]->children)) {
													$child_count_level_five = count($level_four_array[$m]->children);
													$level_five_array = $level_four_array[$m]->children;
													for ($n = 0; $n < $child_count_level_five; $n++) {
														echo "<button data-filter='." . esc_attr($level_five_filter_class) . "' data-fname='." . esc_attr($level_five_filter_class) . "' id='level6-" . esc_attr($level_five_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-filter-button ufg-level-five-button sub-filter sub-filter-5 " . esc_attr($level_five_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($level_five_filter_class) . "'>" . esc_html($level_five_filter_name) . $get_count_html($level_five_array[$n]->filterkey) . " <span class='ufg-active-dot'></span></button>";
													}
												}
												// level five children check end

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
			// print child level five filters end

		}
	}
}
?>
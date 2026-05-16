<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

// this file print filters - filters callback
if (!function_exists('ufg_filters')) {
	function ufg_filters($ufg_gallery_id, $ufg_filters, $ufg_gallery, $atts = array())
	{
		if (is_array($ufg_filters) && $filters_count = count($ufg_filters)) {

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

			// Calculate filter counts (non-recursive for free version)
			$ufg_filter_counts = array();
			if (is_array($ufg_filters)) {
				foreach ($ufg_filters as $filter) {
					if (!isset($filter->filterkey)) continue;
					$key = strtolower(trim($filter->filterkey));
					$my_images = isset($ufg_filter_images[$key]) ? $ufg_filter_images[$key] : array();
					$ufg_filter_counts[$key] = count(array_unique($my_images));
				}
			}

			// print parent filters
			echo "<div class='filter-group ufg-parent-filters'>";
			echo "<div class='ufg-filter-group-inner'>";

			if (isset($ufg_setting['parent_filters_heading']) && !empty($ufg_setting['parent_filters_heading'])) {
				echo "<p class='parent-filters-label'>" . esc_html($ufg_setting['parent_filters_heading']) . "</p>";
			}

			if ($ufg_show_all_button === 'on') {
				echo "<button data-filter='*' data-fname='none' id='1evel1-all' class='ufg-btn ufg-btn-3 filters ufg-all-filter-button ufg-parent-filters ufg-all-filter all ' onclick='return filter(this.id, this.value)' value='*'>" . esc_html($ufg_all_button_text) . " (" . intval($ufg_total_image_count) . ") <span class='ufg-active-dot'></span></button>";
			}

			// Define count display logic
			$get_count_html = function ($filter_key) use ($ufg_show_filters_count, $ufg_filter_counts) {
				$clean_key = strtolower(trim($filter_key));
				if ($ufg_show_filters_count === 'on' && isset($ufg_filter_counts[$clean_key])) {
					return " (" . intval($ufg_filter_counts[$clean_key]) . ")";
				}
				return "";
			};

			for ($i = 0; $i < min($filters_count, 5); $i++) {
				if (isset($ufg_filters[$i]->text)) {
					$parent_filter_name = $ufg_filters[$i]->text;
					$parent_filter_class = str_replace(" ", "-", strtolower($ufg_filters[$i]->filterkey));

					echo "<button data-filter='." . esc_attr($parent_filter_class) . "' data-fname='." . esc_attr($parent_filter_class) . "' id='1evel1-" . esc_attr($parent_filter_class) . "' class='ufg-btn ufg-btn-3 filters ufg-parent-filter-button ufg-parent-filters " . esc_attr($parent_filter_class) . "' onclick='return filter(this.id, this.value)' value='" . esc_attr($parent_filter_class) . "'>" . esc_html($parent_filter_name) . $get_count_html($ufg_filters[$i]->filterkey) . " <span class='ufg-active-dot'></span></button>";
				}
			}
			echo "</div>";
			echo "</div>";
			// print parent filters end
		}
	}
}
?>
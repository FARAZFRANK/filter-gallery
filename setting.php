<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

// load gallery settings
if (isset($atts['show_filters']) && $atts['show_filters'] !== '') {
	$ufg_show_filters = $atts['show_filters']; //shortcode
} else {
	if (isset($ufg_setting['show_filters']) && $ufg_setting['show_filters'] !== '') {
		$ufg_show_filters = $ufg_setting['show_filters']; //saved
	} else {
		$ufg_show_filters = 1; // default
	}
}

if (isset($atts['show_filters_icon'])) {
	$ufg_show_filters_icon = $atts['show_filters_icon']; //shortcode
} else {
	if (isset($ufg_setting['show_filters_icon'])) {
		$ufg_show_filters_icon = $ufg_setting['show_filters_icon']; //saved
	} else {
		$ufg_show_filters_icon = 1; // default
	}
}

if (isset($atts['show_filters_count'])) {
	$ufg_show_filters_count = $atts['show_filters_count']; //shortcode
} else {
	if (isset($ufg_setting['show_filters_count'])) {
		$ufg_show_filters_count = $ufg_setting['show_filters_count']; //saved
	} else {
		$ufg_show_filters_count = 1; // default (was 0)
	}
}

if (isset($atts['show_all_button'])) {
	$ufg_show_all_button = $atts['show_all_button']; //shortcode
} else {
	if (isset($ufg_setting['show_all_button'])) {
		$ufg_show_all_button = $ufg_setting['show_all_button']; //saved
	} else {
		$ufg_show_all_button = 1; // default (was 0)
	}
}

if (isset($atts['all_button_text'])) {
	$ufg_all_button_text = $atts['all_button_text']; //shortcode
} else {
	if (isset($ufg_setting['all_button_text'])) {
		$ufg_all_button_text = $ufg_setting['all_button_text']; //saved
	} else {
		$ufg_all_button_text = __('All', 'filter-gallery'); // default
	}
}
if (isset($atts['child_filter_effect'])) {
	$ufg_child_filter_effect = $atts['child_filter_effect']; //shortcode
} else {
	if (isset($ufg_setting['child_filter_effect'])) {
		$ufg_child_filter_effect = $ufg_setting['child_filter_effect']; //saved
	} else {
		$ufg_child_filter_effect = 'show_hide'; // default
	}
}

if (isset($atts['all_button_icon'])) {
	$ufg_all_button_icon = $atts['all_button_icon']; //shortcode
} else {
	if (isset($ufg_setting['all_button_icon'])) {
		$ufg_all_button_icon = $ufg_setting['all_button_icon']; //saved
	} else {
		$ufg_all_button_icon = "fas fa-image"; // default
	}
}

if (isset($atts['all_button_color'])) {
	$ufg_all_button_color = $atts['all_button_color']; //shortcode
} else {
	if (isset($ufg_setting['all_button_color']) && $ufg_setting['all_button_color'] !== '') {
		$ufg_all_button_color = $ufg_setting['all_button_color']; //saved
	} else {
		$ufg_all_button_color = "#ffffff"; // default
	}
}

if (isset($atts['all_button_bg_color'])) {
	$ufg_all_button_bg_color = $atts['all_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['all_button_bg_color']) && $ufg_setting['all_button_bg_color'] !== '') {
		$ufg_all_button_bg_color = $ufg_setting['all_button_bg_color']; //saved
	} else {
		$ufg_all_button_bg_color = "#0A85ED"; // default
	}
}

if (isset($atts['parent_button_color'])) {
	$ufg_parent_button_color = $atts['parent_button_color']; //shortcode
} else {
	if (isset($ufg_setting['parent_button_color']) && $ufg_setting['parent_button_color'] !== '') {
		$ufg_parent_button_color = $ufg_setting['parent_button_color']; //saved
	} else {
		$ufg_parent_button_color = "#ffffff"; // default
	}
}

if (isset($atts['parent_button_bg_color'])) {
	$ufg_parent_button_bg_color = $atts['parent_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['parent_button_bg_color']) && $ufg_setting['parent_button_bg_color'] !== '') {
		$ufg_parent_button_bg_color = $ufg_setting['parent_button_bg_color']; //saved
	} else {
		$ufg_parent_button_bg_color = "#09A6F3"; // default
	}
}

if (isset($atts['l1_button_color'])) {
	$ufg_l1_button_color = $atts['l1_button_color']; //shortcode
} else {
	if (isset($ufg_setting['l1_button_color']) && $ufg_setting['l1_button_color'] !== '') {
		$ufg_l1_button_color = $ufg_setting['l1_button_color']; //saved
	} else {
		$ufg_l1_button_color = "#ffffff"; // default
	}
}

if (isset($atts['l1_button_bg_color'])) {
	$ufg_l1_button_bg_color = $atts['l1_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['l1_button_bg_color']) && $ufg_setting['l1_button_bg_color'] !== '') {
		$ufg_l1_button_bg_color = $ufg_setting['l1_button_bg_color']; //saved
	} else {
		$ufg_l1_button_bg_color = "#07C8F9"; // default
	}
}

if (isset($atts['active_button_color'])) {
	$ufg_active_button_color = $atts['active_button_color']; //shortcode
} else {
	if (isset($ufg_setting['active_button_color']) && $ufg_setting['active_button_color'] !== '') {
		$ufg_active_button_color = $ufg_setting['active_button_color']; //saved
	} else {
		$ufg_active_button_color = "#FFFFFF"; // default
	}
}

if (isset($atts['active_button_bg_color'])) {
	$ufg_active_button_bg_color = $atts['active_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['active_button_bg_color']) && $ufg_setting['active_button_bg_color'] !== '') {
		$ufg_active_button_bg_color = $ufg_setting['active_button_bg_color']; //saved
	} else {
		$ufg_active_button_bg_color = "#0C63E7"; // default
	}
}

if (isset($atts['l2_button_color'])) {
	$ufg_l2_button_color = $atts['l2_button_color']; //shortcode
} else {
	if (isset($ufg_setting['l2_button_color']) && $ufg_setting['l2_button_color'] !== '') {
		$ufg_l2_button_color = $ufg_setting['l2_button_color']; //saved
	} else {
		$ufg_l2_button_color = "#ffffff"; // default
	}
}

if (isset($atts['l2_button_bg_color'])) {
	$ufg_l2_button_bg_color = $atts['l2_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['l2_button_bg_color']) && $ufg_setting['l2_button_bg_color'] !== '') {
		$ufg_l2_button_bg_color = $ufg_setting['l2_button_bg_color']; //saved
	} else {
		$ufg_l2_button_bg_color = "#07C8F9"; // default
	}
}

if (isset($atts['l3_button_color'])) {
	$ufg_l3_button_color = $atts['l3_button_color']; //shortcode
} else {
	if (isset($ufg_setting['l3_button_color']) && $ufg_setting['l3_button_color'] !== '') {
		$ufg_l3_button_color = $ufg_setting['l3_button_color']; //saved
	} else {
		$ufg_l3_button_color = "#ffffff"; // default
	}
}

if (isset($atts['l3_button_bg_color'])) {
	$ufg_l3_button_bg_color = $atts['l3_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['l3_button_bg_color']) && $ufg_setting['l3_button_bg_color'] !== '') {
		$ufg_l3_button_bg_color = $ufg_setting['l3_button_bg_color']; //saved
	} else {
		$ufg_l3_button_bg_color = "#07C8F9"; // default
	}
}

if (isset($atts['l4_button_color'])) {
	$ufg_l4_button_color = $atts['l4_button_color']; //shortcode
} else {
	if (isset($ufg_setting['l4_button_color']) && $ufg_setting['l4_button_color'] !== '') {
		$ufg_l4_button_color = $ufg_setting['l4_button_color']; //saved
	} else {
		$ufg_l4_button_color = "#ffffff"; // default
	}
}

if (isset($atts['l4_button_bg_color'])) {
	$ufg_l4_button_bg_color = $atts['l4_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['l4_button_bg_color']) && $ufg_setting['l4_button_bg_color'] !== '') {
		$ufg_l4_button_bg_color = $ufg_setting['l4_button_bg_color']; //saved
	} else {
		$ufg_l4_button_bg_color = "#07C8F9"; // default
	}
}

if (isset($atts['l5_button_color'])) {
	$ufg_l5_button_color = $atts['l5_button_color']; //shortcode
} else {
	if (isset($ufg_setting['l5_button_color']) && $ufg_setting['l5_button_color'] !== '') {
		$ufg_l5_button_color = $ufg_setting['l5_button_color']; //saved
	} else {
		$ufg_l5_button_color = "#ffffff"; // default
	}
}

if (isset($atts['l5_button_bg_color'])) {
	$ufg_l5_button_bg_color = $atts['l5_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['l5_button_bg_color']) && $ufg_setting['l5_button_bg_color'] !== '') {
		$ufg_l5_button_bg_color = $ufg_setting['l5_button_bg_color']; //saved
	} else {
		$ufg_l5_button_bg_color = "#07C8F9"; // default
	}
}

if (!empty($atts['columns_desktop'])) {
	$ufg_columns_desktop_raw = $atts['columns_desktop'];
} else {
	if (!empty($ufg_setting['columns_desktop'])) {
		$ufg_columns_desktop_raw = $ufg_setting['columns_desktop'];
	} else {
		$ufg_columns_desktop_raw = 4; // default 4 columns (col-3)
	}
}
// Map count to span: 1->12, 2->6, 3->4, 4->3, 6->2, 12->1
$col_map = array('1' => '12', '2' => '6', '3' => '4', '4' => '3', '6' => '2', '12' => '1');
$ufg_columns_desktop = isset($col_map[$ufg_columns_desktop_raw]) ? $col_map[$ufg_columns_desktop_raw] : $ufg_columns_desktop_raw;

if (!empty($atts['columns_tab'])) {
	$ufg_columns_tab_raw = $atts['columns_tab'];
} else {
	if (!empty($ufg_setting['columns_tab'])) {
		$ufg_columns_tab_raw = $ufg_setting['columns_tab'];
	} else {
		$ufg_columns_tab_raw = 3;
	}
}
$ufg_columns_tab = isset($col_map[$ufg_columns_tab_raw]) ? $col_map[$ufg_columns_tab_raw] : $ufg_columns_tab_raw;

if (!empty($atts['columns_mobile_landscape'])) {
	$ufg_columns_mobile_landscape_raw = $atts['columns_mobile_landscape'];
} else {
	if (!empty($ufg_setting['columns_mobile_landscape'])) {
		$ufg_columns_mobile_landscape_raw = $ufg_setting['columns_mobile_landscape'];
	} else {
		$ufg_columns_mobile_landscape_raw = 3;
	}
}
$ufg_columns_mobile_landscape = isset($col_map[$ufg_columns_mobile_landscape_raw]) ? $col_map[$ufg_columns_mobile_landscape_raw] : $ufg_columns_mobile_landscape_raw;

if (!empty($atts['columns_mobile_portrait'])) {
	$ufg_columns_mobile_portrait_raw = $atts['columns_mobile_portrait'];
} else {
	if (!empty($ufg_setting['columns_mobile_portrait'])) {
		$ufg_columns_mobile_portrait_raw = $ufg_setting['columns_mobile_portrait'];
	} else {
		$ufg_columns_mobile_portrait_raw = 2;
	}
}
$ufg_columns_mobile_portrait = isset($col_map[$ufg_columns_mobile_portrait_raw]) ? $col_map[$ufg_columns_mobile_portrait_raw] : $ufg_columns_mobile_portrait_raw;

$ufg_thumbnail_image = 1; // Always show thumbnails

if (isset($atts['thumbnail_image_size'])) {
	$ufg_thumbnail_image_size = $atts['thumbnail_image_size']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_image_size'])) {
		$ufg_thumbnail_image_size = $ufg_setting['thumbnail_image_size']; //saved
	} else {
		$ufg_thumbnail_image_size = "full"; // default
	}
}

if (isset($atts['thumbnail_border'])) {
	$ufg_thumbnail_border = $atts['thumbnail_border']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_border'])) {
		$ufg_thumbnail_border = $ufg_setting['thumbnail_border']; //saved
	} else {
		$ufg_thumbnail_border = 1; // default
	}
}

if (isset($atts['thumbnail_border_thickness'])) {
	$ufg_thumbnail_border_thickness = $atts['thumbnail_border_thickness']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_border_thickness']) && $ufg_setting['thumbnail_border_thickness'] !== '') {
		$ufg_thumbnail_border_thickness = $ufg_setting['thumbnail_border_thickness']; //saved
	} else {
		$ufg_thumbnail_border_thickness = 1; // default
	}
}

if (isset($atts['thumbnail_border_color'])) {
	$ufg_thumbnail_border_color = $atts['thumbnail_border_color']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_border_color']) && $ufg_setting['thumbnail_border_color'] !== '') {
		$ufg_thumbnail_border_color = $ufg_setting['thumbnail_border_color']; //saved
	} else {
		$ufg_thumbnail_border_color = "#ffffff"; // default
	}
}

if (isset($atts['thumbnail_bg_color'])) {
	$ufg_thumbnail_bg_color = $atts['thumbnail_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_bg_color']) && $ufg_setting['thumbnail_bg_color'] !== '') {
		$ufg_thumbnail_bg_color = $ufg_setting['thumbnail_bg_color']; //saved
	} else {
		$ufg_thumbnail_bg_color = "#222a33"; // default
	}
}

if (isset($atts['image_title']) && $atts['image_title'] !== '') {
	$ufg_image_title = $atts['image_title']; //shortcode
} else {
	if (isset($ufg_setting['image_title']) && $ufg_setting['image_title'] !== '') {
		$ufg_image_title = $ufg_setting['image_title']; //saved
	} else {
		$ufg_image_title = 1; // default
	}
}

if (isset($atts['image_title_font_size'])) {
	$ufg_image_title_font_size = $atts['image_title_font_size']; //shortcode
} else {
	if (isset($ufg_setting['image_title_font_size'])) {
		$ufg_image_title_font_size = $ufg_setting['image_title_font_size']; //saved
	} else {
		$ufg_image_title_font_size = 18; // default (was 45)
	}
}

if (isset($atts['image_title_color'])) {
	$ufg_image_title_color = $atts['image_title_color']; //shortcode
} else {
	if (isset($ufg_setting['image_title_color']) && $ufg_setting['image_title_color'] !== '') {
		$ufg_image_title_color = $ufg_setting['image_title_color']; //saved
	} else {
		$ufg_image_title_color = "#FFFFFF"; // default
	}
}

if (isset($atts['image_description']) && $atts['image_description'] !== '') {
	$ufg_image_description = $atts['image_description']; //shortcode
} else {
	if (isset($ufg_setting['image_description']) && $ufg_setting['image_description'] !== '') {
		$ufg_image_description = $ufg_setting['image_description']; //saved
	} else {
		$ufg_image_description = 1; // default
	}
}

if (isset($atts['image_description_font_size'])) {
	$ufg_image_description_font_size = $atts['image_description_font_size']; //shortcode
} else {
	if (isset($ufg_setting['image_description_font_size']) && $ufg_setting['image_description_font_size'] !== '') {
		$ufg_image_description_font_size = $ufg_setting['image_description_font_size']; //saved
	} else {
		$ufg_image_description_font_size = 14; // default (was 1)
	}
}

if (isset($atts['image_description_color'])) {
	$ufg_image_description_color = $atts['image_description_color']; //shortcode
} else {
	if (isset($ufg_setting['image_description_color']) && $ufg_setting['image_description_color'] !== '') {
		$ufg_image_description_color = $ufg_setting['image_description_color']; //saved
	} else {
		$ufg_image_description_color = "#FFFFFF"; // default
	}
}

if (isset($atts['image_description_text_limit'])) {
	$ufg_image_description_text_limit = $atts['image_description_text_limit']; //shortcode
} else {
	if (isset($ufg_setting['image_description_text_limit']) && $ufg_setting['image_description_text_limit'] !== '') {
		$ufg_image_description_text_limit = $ufg_setting['image_description_text_limit']; //saved
	} else {
		$ufg_image_description_text_limit = 60; // default
	}
}

if (isset($atts['read_more_link_sh'])) {
	$ufg_read_more_link_sh = $atts['read_more_link_sh']; //shortcode
} else {
	if (isset($ufg_setting['read_more_link_sh']) && $ufg_setting['read_more_link_sh'] !== '') {
		$ufg_read_more_link_sh = $ufg_setting['read_more_link_sh']; //saved
	} else {
		$ufg_read_more_link_sh = 0; // default
	}
}

if (isset($atts['read_more_link'])) {
	$ufg_read_more_link = $atts['read_more_link']; //shortcode
} else {
	if (isset($ufg_setting['read_more_link']) && $ufg_setting['read_more_link'] !== '') {
		$ufg_read_more_link = $ufg_setting['read_more_link']; //saved
	} else {
		$ufg_read_more_link = 1; // default
	}
}

if (isset($atts['image_hover_effect'])) {
	$ufg_image_hover_effect = $atts['image_hover_effect']; //shortcode
} else {
	if (isset($ufg_setting['image_hover_effect']) && $ufg_setting['image_hover_effect'] !== '') {
		$ufg_image_hover_effect = $ufg_setting['image_hover_effect']; //saved
	} else {
		$ufg_image_hover_effect = 'border_overlay'; // default (was none)
	}
}

if (isset($atts['read_more_button_text'])) {
	$ufg_read_more_button_text = $atts['read_more_button_text']; //shortcode
} else {
	if (isset($ufg_setting['read_more_button_text']) && $ufg_setting['read_more_button_text'] !== '') {
		$ufg_read_more_button_text = $ufg_setting['read_more_button_text']; //saved
	} else {
		$ufg_read_more_button_text = __('Read More Link', 'filter-gallery'); // default
	}
}

if (isset($atts['read_more_button_icon'])) {
	$ufg_read_more_button_icon = $atts['read_more_button_icon']; //shortcode
} else {
	if (isset($ufg_setting['read_more_button_icon']) && $ufg_setting['read_more_button_icon'] !== '') {
		$ufg_read_more_button_icon = $ufg_setting['read_more_button_icon']; //saved
	} else {
		$ufg_read_more_button_icon = "fas fa-link"; // default
	}
}

if (isset($atts['read_more_button_color'])) {
	$ufg_read_more_button_color = $atts['read_more_button_color']; //shortcode
} else {
	if (isset($ufg_setting['read_more_button_color']) && $ufg_setting['read_more_button_color'] !== '') {
		$ufg_read_more_button_color = $ufg_setting['read_more_button_color']; //saved
	} else {
		$ufg_read_more_button_color = "#ffffff"; // default
	}
}

if (isset($atts['read_more_button_bg_color'])) {
	$ufg_read_more_button_bg_color = $atts['read_more_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['read_more_button_bg_color']) && $ufg_setting['read_more_button_bg_color'] !== '') {
		$ufg_read_more_button_bg_color = $ufg_setting['read_more_button_bg_color']; //saved
	} else {
		$ufg_read_more_button_bg_color = "#0080ff"; // default
	}
}

if (isset($atts['read_more_button_target'])) {
	$ufg_read_more_button_target = $atts['read_more_button_target']; //shortcode
} else {
	if (isset($ufg_setting['read_more_button_target']) && $ufg_setting['read_more_button_target'] !== '') {
		$ufg_read_more_button_target = $ufg_setting['read_more_button_target']; //saved
	} else {
		$ufg_read_more_button_target = "_self"; // default
	}
}

if (isset($atts['pagination'])) {
	$ufg_pagination = $atts['pagination']; //shortcode
} else {
	if (isset($ufg_setting['pagination'])) {
		$ufg_pagination = $ufg_setting['pagination']; //saved
	} else {
		$ufg_pagination = 1; // default
	}
}

if (isset($atts['images_per_page'])) {
	$ufg_images_per_page = $atts['images_per_page']; //shortcode
} else {
	if (isset($ufg_setting['images_per_page'])) {
		$ufg_images_per_page = $ufg_setting['images_per_page']; //saved
	} else {
		$ufg_images_per_page = 16; // default
	}
}

if (isset($atts['load_more'])) {
	$ufg_load_more = $atts['load_more']; //shortcode
} else {
	if (isset($ufg_setting['load_more'])) {
		$ufg_load_more = $ufg_setting['load_more']; //saved
	} else {
		$ufg_load_more = 1; // default
	}
}

if (isset($atts['load_more_button_text'])) {
	$ufg_load_more_button_text = $atts['load_more_button_text']; //shortcode
} else {
	if (isset($ufg_setting['load_more_button_text'])) {
		$ufg_load_more_button_text = $ufg_setting['load_more_button_text']; //saved
	} else {
		$ufg_load_more_button_text = __('Load More', 'filter-gallery'); // default
	}
}

if (isset($atts['load_more_button_color'])) {
	$ufg_load_more_button_color = $atts['load_more_button_color']; //shortcode
} else {
	if (isset($ufg_setting['load_more_button_color']) && $ufg_setting['load_more_button_color'] !== '') {
		$ufg_load_more_button_color = $ufg_setting['load_more_button_color']; //saved
	} else {
		$ufg_load_more_button_color = "#FFFFFF"; // default
	}
}

if (isset($atts['load_more_images_per_call'])) {
	$ufg_load_more_images_per_call = $atts['load_more_images_per_call']; //shortcode
} else {
	if (isset($ufg_setting['load_more_images_per_call']) && $ufg_setting['load_more_images_per_call'] !== '') {
		$ufg_load_more_images_per_call = $ufg_setting['load_more_images_per_call']; //saved
	} else {
		$ufg_load_more_images_per_call = 8; // default (was 4)
	}
}

if (isset($atts['image_sorting'])) {
	$ufg_image_sorting = $atts['image_sorting']; //shortcode
} else {
	if (isset($ufg_setting['image_sorting'])) {
		$ufg_image_sorting = $ufg_setting['image_sorting']; //saved
	} else {
		$ufg_image_sorting = 3; // default
	}
}

if (isset($atts['image_search'])) {
	$ufg_image_search = $atts['image_search']; //shortcode
} else {
	if (isset($ufg_setting['image_search'])) {
		$ufg_image_search = $ufg_setting['image_search']; //saved
	} else {
		$ufg_image_search = 1; // default
	}
}

if (isset($atts['lightbox'])) {
	$ufg_lightbox = $atts['lightbox']; //shortcode
} else {
	if (isset($ufg_setting['lightbox']) && $ufg_setting['lightbox'] !== '') {
		$ufg_lightbox = $ufg_setting['lightbox']; //saved
	} else {
		$ufg_lightbox = 1; // default
	}
}

if (isset($atts['lightbox_title'])) {
	$ufg_lightbox_title = $atts['lightbox_title']; //shortcode
} else {
	if (isset($ufg_setting['lightbox_title']) && $ufg_setting['lightbox_title'] !== '') {
		$ufg_lightbox_title = $ufg_setting['lightbox_title']; //saved
	} else {
		$ufg_lightbox_title = 1; // default
	}
}

if (isset($atts['lightbox_description'])) {
	$ufg_lightbox_description = $atts['lightbox_description']; //shortcode
} else {
	if (isset($ufg_setting['lightbox_description']) && $ufg_setting['lightbox_description'] !== '') {
		$ufg_lightbox_description = $ufg_setting['lightbox_description']; //saved
	} else {
		$ufg_lightbox_description = 1; // default
	}
}

if (isset($atts['lightbox_numbering'])) {
	$ufg_lightbox_numbering = $atts['lightbox_numbering']; //shortcode
} else {
	if (isset($ufg_setting['lightbox_numbering'])) {
		$ufg_lightbox_numbering = $ufg_setting['lightbox_numbering']; //saved
	} else {
		$ufg_lightbox_numbering = 1; // default
	}
}

if (isset($atts['custom_css'])) {
	$ufg_custom_css = $atts['custom_css']; //shortcode
} else {
	if (isset($ufg_setting['custom_css'])) {
		$ufg_custom_css = $ufg_setting['custom_css']; //saved
	} else {
		$ufg_custom_css = ""; // default
	}
}

if (isset($atts['load_more'])) {
	$load_more = $atts['load_more']; //shortcode
} else {
	if (isset($ufg_setting['load_more'])) {
		$load_more = $ufg_setting['load_more']; //saved
	} else {
		$load_more = "off"; // default
	}
}

// Normalize free version's format (1 / 0) to pro layout ('on' / 'off')
if ($load_more === 1 || $load_more === '1')
	$load_more = 'on';
if ($load_more === 0 || $load_more === '0')
	$load_more = 'off';

if (isset($atts['load_limit'])) {
	$load_limit = $atts['load_limit']; //shortcode
} else {
	if (isset($ufg_setting['load_limit']) && $ufg_setting['load_limit'] !== '') {
		$load_limit = $ufg_setting['load_limit']; //saved
	} elseif (isset($ufg_setting['load_more_images_per_call']) && $ufg_setting['load_more_images_per_call'] !== '') {
		$load_limit = $ufg_setting['load_more_images_per_call']; //fallback to free
	} else {
		$load_limit = 10; // default
	}
}
if (isset($atts['load_color'])) {
	$load_color = $atts['load_color']; //shortcode
} else {
	if (isset($ufg_setting['load_color']) && $ufg_setting['load_color'] !== '') {
		$load_color = $ufg_setting['load_color']; //saved
	} elseif (isset($ufg_setting['load_more_button_color']) && $ufg_setting['load_more_button_color'] !== '') {
		$load_color = $ufg_setting['load_more_button_color']; //fallback to free
	} else {
		$load_color = '#0080ff'; // default
	}
}
if (isset($atts['load_txt_color'])) {
	$load_txt_color = $atts['load_txt_color']; //shortcode
} else {
	if (isset($ufg_setting['load_txt_color']) && $ufg_setting['load_txt_color'] !== '') {
		$load_txt_color = $ufg_setting['load_txt_color']; //saved
	} else {
		$load_txt_color = '#FFFFFF'; // default
	}
}
if (isset($atts['load_btn_txt'])) {
	$load_btn_txt = $atts['load_btn_txt']; //shortcode
} else {
	if (isset($ufg_setting['load_btn_txt'])) {
		$load_btn_txt = $ufg_setting['load_btn_txt']; //saved
	} elseif (isset($ufg_setting['load_more_button_text'])) {
		$load_btn_txt = $ufg_setting['load_more_button_text']; //fallback to free
	} else {
		$load_btn_txt = 'Load More'; // default
	}
}
?>

<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

// Normalization function for boolean settings
if (!function_exists('ufg_normalize_on_off')) {
	function ufg_normalize_on_off($value, $default = 'off') {
		if ($value === 'on' || $value === 1 || $value === '1' || $value === true) {
			return 'on';
		}
		if ($value === 'off' || $value === 0 || $value === '0' || $value === false) {
			return 'off';
		}
		return $default;
	}
}

/*=============================================================
 * CONFIGURABLE SETTINGS (matches old Free Plugin v0.2.3)
 *============================================================*/

/* --- Filter Settings --- */

if (isset($atts['show_filters']) && $atts['show_filters'] !== '') {
	$ufg_show_filters = ufg_normalize_on_off($atts['show_filters']); //shortcode
} else {
	if (isset($ufg_setting['show_filters']) && $ufg_setting['show_filters'] !== '') {
		$ufg_show_filters = ufg_normalize_on_off($ufg_setting['show_filters']); //saved
	} else {
		$ufg_show_filters = 'on'; // default
	}
}

if (isset($atts['show_all_button'])) {
	$ufg_show_all_button = ufg_normalize_on_off($atts['show_all_button']); //shortcode
} else {
	if (isset($ufg_setting['show_all_button'])) {
		$ufg_show_all_button = ufg_normalize_on_off($ufg_setting['show_all_button']); //saved
	} else {
		$ufg_show_all_button = 'on'; // default
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

if (isset($atts['all_button_color'])) {
	$ufg_all_button_color = $atts['all_button_color']; //shortcode
} else {
	if (isset($ufg_setting['all_button_color']) && $ufg_setting['all_button_color'] !== '') {
		$ufg_all_button_color = $ufg_setting['all_button_color']; //saved
	} else {
		$ufg_all_button_color = '#FFFFFF'; // default
	}
}

if (isset($atts['all_button_bg_color'])) {
	$ufg_all_button_bg_color = $atts['all_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['all_button_bg_color']) && $ufg_setting['all_button_bg_color'] !== '') {
		$ufg_all_button_bg_color = $ufg_setting['all_button_bg_color']; //saved
	} else {
		$ufg_all_button_bg_color = '#C82333'; // default
	}
}

if (isset($atts['parent_button_color'])) {
	$ufg_parent_button_color = $atts['parent_button_color']; //shortcode
} else {
	if (isset($ufg_setting['parent_button_color']) && $ufg_setting['parent_button_color'] !== '') {
		$ufg_parent_button_color = $ufg_setting['parent_button_color']; //saved
	} else {
		$ufg_parent_button_color = '#FFFFFF'; // default
	}
}

if (isset($atts['parent_button_bg_color'])) {
	$ufg_parent_button_bg_color = $atts['parent_button_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['parent_button_bg_color']) && $ufg_setting['parent_button_bg_color'] !== '') {
		$ufg_parent_button_bg_color = $ufg_setting['parent_button_bg_color']; //saved
	} else {
		$ufg_parent_button_bg_color = '#007BFF'; // default
	}
}

/* --- Column Settings --- */

if (!empty($atts['columns_desktop'])) {
	$ufg_columns_desktop_raw = $atts['columns_desktop'];
} else {
	if (!empty($ufg_setting['columns_desktop'])) {
		$ufg_columns_desktop_raw = $ufg_setting['columns_desktop'];
	} else {
		$ufg_columns_desktop_raw = 4; // default 4 columns
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

/* --- Thumbnail Settings --- */

if (isset($atts['thumbnail_image_size'])) {
	$ufg_thumbnail_image_size = $atts['thumbnail_image_size']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_image_size'])) {
		$ufg_thumbnail_image_size = $ufg_setting['thumbnail_image_size']; //saved
	} else {
		$ufg_thumbnail_image_size = 'large'; // default
	}
}

if (isset($atts['thumbnail_border'])) {
	$ufg_thumbnail_border = ufg_normalize_on_off($atts['thumbnail_border']); //shortcode
} else {
	if (isset($ufg_setting['thumbnail_border'])) {
		$ufg_thumbnail_border = ufg_normalize_on_off($ufg_setting['thumbnail_border']); //saved
	} else {
		$ufg_thumbnail_border = 'on'; // default
	}
}

if (isset($atts['thumbnail_border_thickness'])) {
	$ufg_thumbnail_border_thickness = $atts['thumbnail_border_thickness']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_border_thickness']) && $ufg_setting['thumbnail_border_thickness'] !== '') {
		$ufg_thumbnail_border_thickness = $ufg_setting['thumbnail_border_thickness']; //saved
	} else {
		$ufg_thumbnail_border_thickness = 0; // default
	}
}

if (isset($atts['thumbnail_border_color'])) {
	$ufg_thumbnail_border_color = $atts['thumbnail_border_color']; //shortcode
} else {
	if (isset($ufg_setting['thumbnail_border_color']) && $ufg_setting['thumbnail_border_color'] !== '') {
		$ufg_thumbnail_border_color = $ufg_setting['thumbnail_border_color']; //saved
	} else {
		$ufg_thumbnail_border_color = '#0069D9'; // default
	}
}

/* --- Image Title Settings --- */

if (isset($atts['image_title']) && $atts['image_title'] !== '') {
	$ufg_image_title = ufg_normalize_on_off($atts['image_title']); //shortcode
} else {
	if (isset($ufg_setting['image_title']) && $ufg_setting['image_title'] !== '') {
		$ufg_image_title = ufg_normalize_on_off($ufg_setting['image_title']); //saved
	} else {
		$ufg_image_title = 'on'; // default
	}
}

if (isset($atts['image_title_font_size'])) {
	$ufg_image_title_font_size = $atts['image_title_font_size']; //shortcode
} else {
	if (isset($ufg_setting['image_title_font_size'])) {
		$ufg_image_title_font_size = $ufg_setting['image_title_font_size']; //saved
	} else {
		$ufg_image_title_font_size = 24; // default
	}
}

if (isset($atts['image_title_color'])) {
	$ufg_image_title_color = $atts['image_title_color']; //shortcode
} else {
	if (isset($ufg_setting['image_title_color']) && $ufg_setting['image_title_color'] !== '') {
		$ufg_image_title_color = $ufg_setting['image_title_color']; //saved
	} else {
		$ufg_image_title_color = '#FFFFFF'; // default
	}
}

if (isset($atts['image_title_bg_color'])) {
	$ufg_image_title_bg_color = $atts['image_title_bg_color']; //shortcode
} else {
	if (isset($ufg_setting['image_title_bg_color']) && $ufg_setting['image_title_bg_color'] !== '') {
		$ufg_image_title_bg_color = $ufg_setting['image_title_bg_color']; //saved
	} else {
		$ufg_image_title_bg_color = '#000000'; // default
	}
}

/* --- Image Sorting --- */

if (isset($atts['image_sorting'])) {
	$ufg_image_sorting = $atts['image_sorting']; //shortcode
} else {
	if (isset($ufg_setting['image_sorting'])) {
		$ufg_image_sorting = $ufg_setting['image_sorting']; //saved
	} else {
		$ufg_image_sorting = 5; // default (None)
	}
}

/* --- Lightbox Settings --- */

if (isset($atts['lightbox'])) {
	$ufg_lightbox = ufg_normalize_on_off($atts['lightbox']); //shortcode
} else {
	if (isset($ufg_setting['lightbox']) && $ufg_setting['lightbox'] !== '') {
		$ufg_lightbox = ufg_normalize_on_off($ufg_setting['lightbox']); //saved
	} else {
		$ufg_lightbox = 'on'; // default
	}
}

if (isset($atts['lightbox_title'])) {
	$ufg_lightbox_title = ufg_normalize_on_off($atts['lightbox_title']); //shortcode
} else {
	if (isset($ufg_setting['lightbox_title']) && $ufg_setting['lightbox_title'] !== '') {
		$ufg_lightbox_title = ufg_normalize_on_off($ufg_setting['lightbox_title']); //saved
	} else {
		$ufg_lightbox_title = 'on'; // default
	}
}

/*=============================================================
 * HARDCODED SETTINGS (Pro-only, not in Free v0.2.3)
 * These variables are required by shortcode.php, gallery.php,
 * gallery-content.php, and filters.php for rendering.
 *============================================================*/

// Filter display - Pro only
$ufg_show_filters_icon  = 'off';
$ufg_show_filters_count = 'off';
$ufg_child_filter_effect = '';
$ufg_all_button_icon    = '';

// L1-L5 child filter button colors - hardcoded defaults (not configurable in Free)
$ufg_l1_button_color    = '#6C757D';
$ufg_l1_button_bg_color = '#0069D9';
$ufg_active_button_color    = '#FFFFFF';
$ufg_active_button_bg_color = '#0C63E7';

// Thumbnail
$ufg_thumbnail_image    = 1; // Always show thumbnails
$ufg_thumbnail_bg_color = '#222a33';

// Image Description - hardcoded off in Free
$ufg_image_description           = 'off';
$ufg_image_description_font_size = 14;
$ufg_image_description_color     = '#FFFFFF';
$ufg_image_description_text_limit = 60;

// Image hover effect - hardcoded
$ufg_image_hover_effect = 'border_overlay';

// Read More - hardcoded off in Free
$ufg_read_more_link_sh       = 'off';
$ufg_read_more_link          = 0;
$ufg_read_more_button_text   = '';
$ufg_read_more_button_icon   = '';
$ufg_read_more_button_color  = '#ffffff';
$ufg_read_more_button_bg_color = '#0080ff';
$ufg_read_more_button_target = '_self';

// Pagination - not in Free
$ufg_pagination       = 'off';
$ufg_images_per_page  = 100;

// Image Search - not in Free
$ufg_image_search = 'off';

// Lightbox extras - not in Free
$ufg_lightbox_description = 'off';
$ufg_lightbox_numbering   = 'off';

// Custom CSS - not in Free
$ufg_custom_css = '';

// Load More - not in old Free v0.2.3 but required by gallery.php
$load_more     = 'off';
$ufg_load_more = 'off';
$load_limit    = 100;
$load_color    = '#0080ff';
$load_txt_color = '#FFFFFF';
$load_btn_txt  = __('Load More', 'filter-gallery');
?>

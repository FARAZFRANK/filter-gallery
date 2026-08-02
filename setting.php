<?php
/**
 * Resolves gallery display settings from shortcode attributes and saved options.
 *
 * @package Filter_Gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// load gallery settings.
// Whether to display the filter navigation (buttons or dropdown).
if ( isset( $atts['show_filters'] ) && '' !== $atts['show_filters'] ) {
	$ufg_show_filters = $atts['show_filters']; // shortcode.
} elseif ( isset( $ufg_setting['show_filters'] ) && '' !== $ufg_setting['show_filters'] ) {
	$ufg_show_filters = $ufg_setting['show_filters']; // saved.
} else {
	$ufg_show_filters = 1; // default.
}

// Whether to show an icon next to each filter label.
if ( isset( $atts['show_filters_icon'] ) ) {
	$ufg_show_filters_icon = $atts['show_filters_icon']; // shortcode.
} elseif ( isset( $ufg_setting['show_filters_icon'] ) ) {
	$ufg_show_filters_icon = $ufg_setting['show_filters_icon']; // saved.
} else {
	$ufg_show_filters_icon = 1; // default.
}

$ufg_enable_deep_linking = 0;

// Whether to show each filter's matching image count next to its label.
if ( isset( $atts['show_filters_count'] ) ) {
	$ufg_show_filters_count = $atts['show_filters_count']; // shortcode.
} elseif ( isset( $ufg_setting['show_filters_count'] ) ) {
	$ufg_show_filters_count = $ufg_setting['show_filters_count']; // saved.
} else {
	$ufg_show_filters_count = 1; // default (was 0).
}

// Whether to show the "All" button that clears the active filter.
if ( isset( $atts['show_all_button'] ) ) {
	$ufg_show_all_button = $atts['show_all_button']; // shortcode.
} elseif ( isset( $ufg_setting['show_all_button'] ) ) {
	$ufg_show_all_button = $ufg_setting['show_all_button']; // saved.
} else {
	$ufg_show_all_button = 1; // default (was 0).
}

// Label text for the "All" button.
if ( isset( $atts['all_button_text'] ) ) {
	$ufg_all_button_text = $atts['all_button_text']; // shortcode.
} elseif ( isset( $ufg_setting['all_button_text'] ) ) {
	$ufg_all_button_text = $ufg_setting['all_button_text']; // saved.
} else {
	$ufg_all_button_text = __( 'All', 'filter-gallery' ); // default.
}

// Icon class for the "All" button.
if ( isset( $atts['all_button_icon'] ) ) {
	$ufg_all_button_icon = $atts['all_button_icon']; // shortcode.
} elseif ( isset( $ufg_setting['all_button_icon'] ) && '' !== $ufg_setting['all_button_icon'] ) {
	$ufg_all_button_icon = $ufg_setting['all_button_icon']; // saved.
} else {
	$ufg_all_button_icon = 'fas fa-filter'; // default.
}

// How child filters are revealed when a parent filter is selected.
if ( isset( $atts['child_filter_effect'] ) ) {
	$ufg_child_filter_effect = $atts['child_filter_effect']; // shortcode.
} elseif ( isset( $ufg_setting['child_filter_effect'] ) ) {
	$ufg_child_filter_effect = $ufg_setting['child_filter_effect']; // saved.
} else {
	$ufg_child_filter_effect = 'show_hide'; // default.
}

// Text color for the "All" button.
if ( isset( $atts['all_button_color'] ) ) {
	$ufg_all_button_color = $atts['all_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['all_button_color'] ) && '' !== $ufg_setting['all_button_color'] ) {
	$ufg_all_button_color = $ufg_setting['all_button_color']; // saved.
} else {
	$ufg_all_button_color = '#ffffff'; // default.
}

// Background color for the "All" button.
if ( isset( $atts['all_button_bg_color'] ) ) {
	$ufg_all_button_bg_color = $atts['all_button_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['all_button_bg_color'] ) && '' !== $ufg_setting['all_button_bg_color'] ) {
	$ufg_all_button_bg_color = $ufg_setting['all_button_bg_color']; // saved.
} else {
	$ufg_all_button_bg_color = '#0A85ED'; // default.
}

// Text color for top-level (parent) filter buttons.
if ( isset( $atts['parent_button_color'] ) ) {
	$ufg_parent_button_color = $atts['parent_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['parent_button_color'] ) && '' !== $ufg_setting['parent_button_color'] ) {
	$ufg_parent_button_color = $ufg_setting['parent_button_color']; // saved.
} else {
	$ufg_parent_button_color = '#4F46E5'; // default.
}

// Background color for top-level (parent) filter buttons.
if ( isset( $atts['parent_button_bg_color'] ) ) {
	$ufg_parent_button_bg_color = $atts['parent_button_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['parent_button_bg_color'] ) && '' !== $ufg_setting['parent_button_bg_color'] ) {
	$ufg_parent_button_bg_color = $ufg_setting['parent_button_bg_color']; // saved.
} else {
	$ufg_parent_button_bg_color = '#EEF2FF'; // default.
}

// Hover text color for top-level (parent) filter buttons.
if ( isset( $atts['parent_button_hover_color'] ) ) {
	$parent_button_hover_color = $atts['parent_button_hover_color'];
} elseif ( isset( $ufg_setting['parent_button_hover_color'] ) && '' !== $ufg_setting['parent_button_hover_color'] ) {
	$parent_button_hover_color = $ufg_setting['parent_button_hover_color'];
} else {
	$parent_button_hover_color = '#000000'; // default.
}

// Text color for the active top-level filter button.
if ( isset( $atts['parent_active_button_color'] ) ) {
	$parent_active_button_color = $atts['parent_active_button_color'];
} elseif ( isset( $ufg_setting['parent_active_button_color'] ) && '' !== $ufg_setting['parent_active_button_color'] ) {
	$parent_active_button_color = $ufg_setting['parent_active_button_color'];
} else {
	$parent_active_button_color = '#FFFFFF'; // default.
}

// Background color for the active top-level filter button.
if ( isset( $atts['parent_active_button_bg_color'] ) ) {
	$parent_active_button_bg_color = $atts['parent_active_button_bg_color'];
} elseif ( isset( $ufg_setting['parent_active_button_bg_color'] ) && '' !== $ufg_setting['parent_active_button_bg_color'] ) {
	$parent_active_button_bg_color = $ufg_setting['parent_active_button_bg_color'];
} else {
	$parent_active_button_bg_color = '#4F46E5'; // default.
}

// Text color for level-1 child filter buttons.
if ( isset( $atts['l1_button_color'] ) ) {
	$ufg_l1_button_color = $atts['l1_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['l1_button_color'] ) && '' !== $ufg_setting['l1_button_color'] ) {
	$ufg_l1_button_color = $ufg_setting['l1_button_color']; // saved.
} else {
	$ufg_l1_button_color = '#4F46E5'; // default.
}

// Background color for level-1 child filter buttons.
if ( isset( $atts['l1_button_bg_color'] ) ) {
	$ufg_l1_button_bg_color = $atts['l1_button_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['l1_button_bg_color'] ) && '' !== $ufg_setting['l1_button_bg_color'] ) {
	$ufg_l1_button_bg_color = $ufg_setting['l1_button_bg_color']; // saved.
} else {
	$ufg_l1_button_bg_color = '#EEF2FF'; // default.
}

// Text color for the active filter button.
if ( isset( $atts['active_button_color'] ) ) {
	$ufg_active_button_color = $atts['active_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['active_button_color'] ) && '' !== $ufg_setting['active_button_color'] ) {
	$ufg_active_button_color = $ufg_setting['active_button_color']; // saved.
} else {
	$ufg_active_button_color = '#FFFFFF'; // default.
}

// Background color for the active filter button.
if ( isset( $atts['active_button_bg_color'] ) ) {
	$ufg_active_button_bg_color = $atts['active_button_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['active_button_bg_color'] ) && '' !== $ufg_setting['active_button_bg_color'] ) {
	$ufg_active_button_bg_color = $ufg_setting['active_button_bg_color']; // saved.
} else {
	$ufg_active_button_bg_color = '#0C63E7'; // default.
}

// Text color for level-2 child filter buttons.
if ( isset( $atts['l2_button_color'] ) ) {
	$ufg_l2_button_color = $atts['l2_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['l2_button_color'] ) && '' !== $ufg_setting['l2_button_color'] ) {
	$ufg_l2_button_color = $ufg_setting['l2_button_color']; // saved.
} else {
	$ufg_l2_button_color = '#4F46E5'; // default.
}

// Background color for level-2 child filter buttons.
if ( isset( $atts['l2_button_bg_color'] ) ) {
	$ufg_l2_button_bg_color = $atts['l2_button_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['l2_button_bg_color'] ) && '' !== $ufg_setting['l2_button_bg_color'] ) {
	$ufg_l2_button_bg_color = $ufg_setting['l2_button_bg_color']; // saved.
} else {
	$ufg_l2_button_bg_color = '#EEF2FF'; // default.
}

// Text color for level-3 child filter buttons.
if ( isset( $atts['l3_button_color'] ) ) {
	$ufg_l3_button_color = $atts['l3_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['l3_button_color'] ) && '' !== $ufg_setting['l3_button_color'] ) {
	$ufg_l3_button_color = $ufg_setting['l3_button_color']; // saved.
} else {
	$ufg_l3_button_color = '#4F46E5'; // default.
}

// Background color for level-3 child filter buttons.
if ( isset( $atts['l3_button_bg_color'] ) ) {
	$ufg_l3_button_bg_color = $atts['l3_button_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['l3_button_bg_color'] ) && '' !== $ufg_setting['l3_button_bg_color'] ) {
	$ufg_l3_button_bg_color = $ufg_setting['l3_button_bg_color']; // saved.
} else {
	$ufg_l3_button_bg_color = '#EEF2FF'; // default.
}

// Text color for level-4 child filter buttons.
if ( isset( $atts['l4_button_color'] ) ) {
	$ufg_l4_button_color = $atts['l4_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['l4_button_color'] ) && '' !== $ufg_setting['l4_button_color'] ) {
	$ufg_l4_button_color = $ufg_setting['l4_button_color']; // saved.
} else {
	$ufg_l4_button_color = '#4F46E5'; // default.
}

// Background color for level-4 child filter buttons.
if ( isset( $atts['l4_button_bg_color'] ) ) {
	$ufg_l4_button_bg_color = $atts['l4_button_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['l4_button_bg_color'] ) && '' !== $ufg_setting['l4_button_bg_color'] ) {
	$ufg_l4_button_bg_color = $ufg_setting['l4_button_bg_color']; // saved.
} else {
	$ufg_l4_button_bg_color = '#EEF2FF'; // default.
}

// Number of grid columns on desktop viewports.
if ( ! empty( $atts['columns_desktop'] ) ) {
	$ufg_columns_desktop_raw = $atts['columns_desktop'];
} elseif ( ! empty( $ufg_setting['columns_desktop'] ) ) {
	$ufg_columns_desktop_raw = $ufg_setting['columns_desktop'];
} else {
	$ufg_columns_desktop_raw = 4; // default 4 columns (col-3).
}

// Clamp to the allowed desktop column counts.
if ( ! in_array( (string) $ufg_columns_desktop_raw, array( '3', '4', '6' ), true ) ) {
	$ufg_columns_desktop_raw = 4;
}

// Map count to span: 1->12, 2->6, 3->4, 4->3, 6->2, 12->1.
$col_map             = array(
	'1'  => '12',
	'2'  => '6',
	'3'  => '4',
	'4'  => '3',
	'6'  => '2',
	'12' => '1',
);
$ufg_columns_desktop = isset( $col_map[ $ufg_columns_desktop_raw ] ) ? $col_map[ $ufg_columns_desktop_raw ] : $ufg_columns_desktop_raw;

// Number of grid columns on tablet viewports.
if ( ! empty( $atts['columns_tab'] ) ) {
	$ufg_columns_tab_raw = $atts['columns_tab'];
} elseif ( ! empty( $ufg_setting['columns_tab'] ) ) {
	$ufg_columns_tab_raw = $ufg_setting['columns_tab'];
} else {
	$ufg_columns_tab_raw = 3;
}

// Clamp to the allowed tablet column counts.
if ( ! in_array( (string) $ufg_columns_tab_raw, array( '1', '2', '3' ), true ) ) {
	$ufg_columns_tab_raw = 3;
}
$ufg_columns_tab = isset( $col_map[ $ufg_columns_tab_raw ] ) ? $col_map[ $ufg_columns_tab_raw ] : $ufg_columns_tab_raw;

// Number of grid columns on mobile landscape viewports.
if ( ! empty( $atts['columns_mobile_landscape'] ) ) {
	$ufg_columns_mobile_landscape_raw = $atts['columns_mobile_landscape'];
} elseif ( ! empty( $ufg_setting['columns_mobile_landscape'] ) ) {
	$ufg_columns_mobile_landscape_raw = $ufg_setting['columns_mobile_landscape'];
} else {
	$ufg_columns_mobile_landscape_raw = 3;
}

// Clamp to the allowed mobile landscape column counts.
if ( ! in_array( (string) $ufg_columns_mobile_landscape_raw, array( '1', '2', '3' ), true ) ) {
	$ufg_columns_mobile_landscape_raw = 3;
}
$ufg_columns_mobile_landscape = isset( $col_map[ $ufg_columns_mobile_landscape_raw ] ) ? $col_map[ $ufg_columns_mobile_landscape_raw ] : $ufg_columns_mobile_landscape_raw;

// Number of grid columns on mobile portrait viewports.
if ( ! empty( $atts['columns_mobile_portrait'] ) ) {
	$ufg_columns_mobile_portrait_raw = $atts['columns_mobile_portrait'];
} elseif ( ! empty( $ufg_setting['columns_mobile_portrait'] ) ) {
	$ufg_columns_mobile_portrait_raw = $ufg_setting['columns_mobile_portrait'];
} else {
	$ufg_columns_mobile_portrait_raw = 2;
}

// Clamp to the allowed mobile portrait column counts.
if ( ! in_array( (string) $ufg_columns_mobile_portrait_raw, array( '1', '2' ), true ) ) {
	$ufg_columns_mobile_portrait_raw = 2;
}
$ufg_columns_mobile_portrait = isset( $col_map[ $ufg_columns_mobile_portrait_raw ] ) ? $col_map[ $ufg_columns_mobile_portrait_raw ] : $ufg_columns_mobile_portrait_raw;

$ufg_thumbnail_image = 1; // Always show thumbnails.

// Registered image size used for gallery thumbnails.
if ( isset( $atts['thumbnail_image_size'] ) ) {
	$ufg_thumbnail_image_size = $atts['thumbnail_image_size']; // shortcode.
} elseif ( isset( $ufg_setting['thumbnail_image_size'] ) ) {
	$ufg_thumbnail_image_size = $ufg_setting['thumbnail_image_size']; // saved.
} else {
	$ufg_thumbnail_image_size = 'full'; // default.
}

// Whether thumbnails have a border.
if ( isset( $atts['thumbnail_border'] ) ) {
	$ufg_thumbnail_border = $atts['thumbnail_border']; // shortcode.
} elseif ( isset( $ufg_setting['thumbnail_border'] ) ) {
	$ufg_thumbnail_border = $ufg_setting['thumbnail_border']; // saved.
} else {
	$ufg_thumbnail_border = 1; // default.
}

// Thumbnail border thickness, in pixels.
if ( isset( $atts['thumbnail_border_thickness'] ) ) {
	$ufg_thumbnail_border_thickness = $atts['thumbnail_border_thickness']; // shortcode.
} elseif ( isset( $ufg_setting['thumbnail_border_thickness'] ) && '' !== $ufg_setting['thumbnail_border_thickness'] ) {
	$ufg_thumbnail_border_thickness = $ufg_setting['thumbnail_border_thickness']; // saved.
} else {
	$ufg_thumbnail_border_thickness = 1; // default.
}

// Thumbnail border color.
if ( isset( $atts['thumbnail_border_color'] ) ) {
	$ufg_thumbnail_border_color = $atts['thumbnail_border_color']; // shortcode.
} elseif ( isset( $ufg_setting['thumbnail_border_color'] ) && '' !== $ufg_setting['thumbnail_border_color'] ) {
	$ufg_thumbnail_border_color = $ufg_setting['thumbnail_border_color']; // saved.
} else {
	$ufg_thumbnail_border_color = '#ffffff'; // default.
}

// Thumbnail background color (shown while the image loads).
if ( isset( $atts['thumbnail_bg_color'] ) ) {
	$ufg_thumbnail_bg_color = $atts['thumbnail_bg_color']; // shortcode.
} elseif ( isset( $ufg_setting['thumbnail_bg_color'] ) && '' !== $ufg_setting['thumbnail_bg_color'] ) {
	$ufg_thumbnail_bg_color = $ufg_setting['thumbnail_bg_color']; // saved.
} else {
	$ufg_thumbnail_bg_color = '#222a33'; // default.
}

// Whether to show the image title beneath each thumbnail.
if ( isset( $atts['image_title'] ) && '' !== $atts['image_title'] ) {
	$ufg_image_title = $atts['image_title']; // shortcode.
} elseif ( isset( $ufg_setting['image_title'] ) && '' !== $ufg_setting['image_title'] ) {
	$ufg_image_title = $ufg_setting['image_title']; // saved.
} else {
	$ufg_image_title = 1; // default.
}

// Font size for the image title, in pixels.
if ( isset( $atts['image_title_font_size'] ) ) {
	$ufg_image_title_font_size = $atts['image_title_font_size']; // shortcode.
} elseif ( isset( $ufg_setting['image_title_font_size'] ) ) {
	$ufg_image_title_font_size = $ufg_setting['image_title_font_size']; // saved.
} else {
	$ufg_image_title_font_size = 18; // default (was 45).
}

// Text color for the image title.
if ( isset( $atts['image_title_color'] ) ) {
	$ufg_image_title_color = $atts['image_title_color']; // shortcode.
} elseif ( isset( $ufg_setting['image_title_color'] ) && '' !== $ufg_setting['image_title_color'] ) {
	$ufg_image_title_color = $ufg_setting['image_title_color']; // saved.
} else {
	$ufg_image_title_color = '#FFFFFF'; // default.
}

// Whether to show the image description beneath each thumbnail.
if ( isset( $atts['image_description'] ) && '' !== $atts['image_description'] ) {
	$ufg_image_description = $atts['image_description']; // shortcode.
} elseif ( isset( $ufg_setting['image_description'] ) && '' !== $ufg_setting['image_description'] ) {
	$ufg_image_description = $ufg_setting['image_description']; // saved.
} else {
	$ufg_image_description = 1; // default.
}

// Font size for the image description, in pixels.
if ( isset( $atts['image_description_font_size'] ) ) {
	$ufg_image_description_font_size = $atts['image_description_font_size']; // shortcode.
} elseif ( isset( $ufg_setting['image_description_font_size'] ) && '' !== $ufg_setting['image_description_font_size'] ) {
	$ufg_image_description_font_size = $ufg_setting['image_description_font_size']; // saved.
} else {
	$ufg_image_description_font_size = 14; // default (was 1).
}

// Text color for the image description.
if ( isset( $atts['image_description_color'] ) ) {
	$ufg_image_description_color = $atts['image_description_color']; // shortcode.
} elseif ( isset( $ufg_setting['image_description_color'] ) && '' !== $ufg_setting['image_description_color'] ) {
	$ufg_image_description_color = $ufg_setting['image_description_color']; // saved.
} else {
	$ufg_image_description_color = '#FFFFFF'; // default.
}

// Maximum character length for the image description before truncating.
if ( isset( $atts['image_description_text_limit'] ) ) {
	$ufg_image_description_text_limit = $atts['image_description_text_limit']; // shortcode.
} elseif ( isset( $ufg_setting['image_description_text_limit'] ) && '' !== $ufg_setting['image_description_text_limit'] ) {
	$ufg_image_description_text_limit = $ufg_setting['image_description_text_limit']; // saved.
} else {
	$ufg_image_description_text_limit = 60; // default.
}

$ufg_read_more_link_sh         = 0;
$ufg_read_more_link            = 1;
$ufg_read_more_button_text     = __( 'Read More Link', 'filter-gallery' );
$ufg_read_more_button_icon     = 'fas fa-link';
$ufg_read_more_button_color    = '#ffffff';
$ufg_read_more_button_bg_color = '#0080ff';
$ufg_read_more_button_target   = '_self';

// Hover effect applied to thumbnails.
if ( isset( $atts['image_hover_effect'] ) ) {
	$ufg_image_hover_effect = $atts['image_hover_effect']; // shortcode.
} elseif ( isset( $ufg_setting['image_hover_effect'] ) && '' !== $ufg_setting['image_hover_effect'] ) {
	$ufg_image_hover_effect = $ufg_setting['image_hover_effect']; // saved.
} else {
	$ufg_image_hover_effect = 'border_overlay'; // default (was none).
}

// Whether pagination is enabled for the gallery grid.
if ( isset( $atts['pagination'] ) ) {
	$ufg_pagination = $atts['pagination']; // shortcode.
} elseif ( isset( $ufg_setting['pagination'] ) ) {
	$ufg_pagination = $ufg_setting['pagination']; // saved.
} else {
	$ufg_pagination = 1; // default.
}

// Number of images shown per page when pagination is enabled.
if ( isset( $atts['images_per_page'] ) ) {
	$ufg_images_per_page = $atts['images_per_page']; // shortcode.
} elseif ( isset( $ufg_setting['images_per_page'] ) ) {
	$ufg_images_per_page = $ufg_setting['images_per_page']; // saved.
} else {
	$ufg_images_per_page = 16; // default.
}

// Whether the "Load More" button is enabled instead of showing all images at once.
if ( isset( $atts['load_more'] ) ) {
	$ufg_load_more = $atts['load_more']; // shortcode.
} elseif ( isset( $ufg_setting['load_more'] ) ) {
	$ufg_load_more = $ufg_setting['load_more']; // saved.
} else {
	$ufg_load_more = 1; // default.
}

// Label text for the "Load More" button.
if ( isset( $atts['load_more_button_text'] ) ) {
	$ufg_load_more_button_text = $atts['load_more_button_text']; // shortcode.
} elseif ( isset( $ufg_setting['load_more_button_text'] ) ) {
	$ufg_load_more_button_text = $ufg_setting['load_more_button_text']; // saved.
} else {
	$ufg_load_more_button_text = __( 'Load More', 'filter-gallery' ); // default.
}

// Background color for the "Load More" button.
if ( isset( $atts['load_more_button_color'] ) ) {
	$ufg_load_more_button_color = $atts['load_more_button_color']; // shortcode.
} elseif ( isset( $ufg_setting['load_more_button_color'] ) && '' !== $ufg_setting['load_more_button_color'] ) {
	$ufg_load_more_button_color = $ufg_setting['load_more_button_color']; // saved.
} else {
	$ufg_load_more_button_color = '#FFFFFF'; // default.
}

// Number of images fetched per "Load More" click.
if ( isset( $atts['load_more_images_per_call'] ) ) {
	$ufg_load_more_images_per_call = $atts['load_more_images_per_call']; // shortcode.
} elseif ( isset( $ufg_setting['load_more_images_per_call'] ) && '' !== $ufg_setting['load_more_images_per_call'] ) {
	$ufg_load_more_images_per_call = $ufg_setting['load_more_images_per_call']; // saved.
} else {
	$ufg_load_more_images_per_call = 8; // default (was 4).
}

// Image sort order (manual, ID ascending, or ID descending).
if ( isset( $atts['image_sorting'] ) ) {
	$ufg_image_sorting = $atts['image_sorting']; // shortcode.
} elseif ( isset( $ufg_setting['image_sorting'] ) ) {
	$ufg_image_sorting = $ufg_setting['image_sorting']; // saved.
} else {
	$ufg_image_sorting = 5; // default None/Manual.
}

// Clamp to the allowed sort modes.
if ( ! in_array( (string) $ufg_image_sorting, array( '5', '1', '2' ), true ) ) {
	$ufg_image_sorting = 5;
}

// Whether the image search box is enabled.
if ( isset( $atts['image_search'] ) ) {
	$ufg_image_search = $atts['image_search']; // shortcode.
} elseif ( isset( $ufg_setting['image_search'] ) ) {
	$ufg_image_search = $ufg_setting['image_search']; // saved.
} else {
	$ufg_image_search = 1; // default.
}

// Whether clicking a thumbnail opens it in a lightbox.
if ( isset( $atts['lightbox'] ) ) {
	$ufg_lightbox = $atts['lightbox']; // shortcode.
} elseif ( isset( $ufg_setting['lightbox'] ) && '' !== $ufg_setting['lightbox'] ) {
	$ufg_lightbox = $ufg_setting['lightbox']; // saved.
} else {
	$ufg_lightbox = 1; // default.
}

// Whether the lightbox shows the image title/caption.
if ( isset( $atts['lightbox_title'] ) ) {
	$ufg_lightbox_title = $atts['lightbox_title']; // shortcode.
} elseif ( isset( $ufg_setting['lightbox_title'] ) && '' !== $ufg_setting['lightbox_title'] ) {
	$ufg_lightbox_title = $ufg_setting['lightbox_title']; // saved.
} else {
	$ufg_lightbox_title = 1; // default.
}

$ufg_lightbox_description = 0;
$ufg_lightbox_numbering   = 0;

$ufg_custom_css = '';

$load_more     = 'off';
$ufg_load_more = 'off';

// Number of images to show initially before "Load More" is used.
if ( isset( $atts['load_limit'] ) ) {
	$load_limit = $atts['load_limit']; // shortcode.
} elseif ( isset( $ufg_setting['load_limit'] ) && '' !== $ufg_setting['load_limit'] ) {
	$load_limit = $ufg_setting['load_limit']; // saved.
} elseif ( isset( $ufg_setting['load_more_images_per_call'] ) && '' !== $ufg_setting['load_more_images_per_call'] ) {
	$load_limit = $ufg_setting['load_more_images_per_call']; // fallback to free.
} else {
	$load_limit = 10; // default.
}

// Background color for the frontend "Load More" button.
if ( isset( $atts['load_color'] ) ) {
	$load_color = $atts['load_color']; // shortcode.
} elseif ( isset( $ufg_setting['load_color'] ) && '' !== $ufg_setting['load_color'] ) {
	$load_color = $ufg_setting['load_color']; // saved.
} elseif ( isset( $ufg_setting['load_more_button_color'] ) && '' !== $ufg_setting['load_more_button_color'] ) {
	$load_color = $ufg_setting['load_more_button_color']; // fallback to free.
} else {
	$load_color = '#0080ff'; // default.
}

// Text color for the frontend "Load More" button.
if ( isset( $atts['load_txt_color'] ) ) {
	$load_txt_color = $atts['load_txt_color']; // shortcode.
} elseif ( isset( $ufg_setting['load_txt_color'] ) && '' !== $ufg_setting['load_txt_color'] ) {
	$load_txt_color = $ufg_setting['load_txt_color']; // saved.
} else {
	$load_txt_color = '#FFFFFF'; // default.
}

// Label text for the frontend "Load More" button.
if ( isset( $atts['load_btn_txt'] ) ) {
	$load_btn_txt = $atts['load_btn_txt']; // shortcode.
} elseif ( isset( $ufg_setting['load_btn_txt'] ) ) {
	$load_btn_txt = $ufg_setting['load_btn_txt']; // saved.
} elseif ( isset( $ufg_setting['load_more_button_text'] ) ) {
	$load_btn_txt = $ufg_setting['load_more_button_text']; // fallback to free.
} else {
	$load_btn_txt = __( 'Load More', 'filter-gallery' ); // default.
}

// Custom advanced settings.
$ufg_filter_style = isset( $atts['filter_style'] ) ? $atts['filter_style'] : ( isset( $ufg_setting['filter_style'] ) ? $ufg_setting['filter_style'] : 'buttons' );

// Only "dropdown" or "buttons" are valid filter styles.
if ( 'dropdown' !== $ufg_filter_style ) {
	$ufg_filter_style = 'buttons';
}

$ufg_combine_filter_search = '0';
$ufg_filter_padding        = isset( $atts['filter_padding'] ) ? $atts['filter_padding'] : ( isset( $ufg_setting['filter_padding'] ) ? $ufg_setting['filter_padding'] : '8px 16px' );
$ufg_filter_margin         = isset( $atts['filter_margin'] ) ? $atts['filter_margin'] : ( isset( $ufg_setting['filter_margin'] ) ? $ufg_setting['filter_margin'] : '5px' );

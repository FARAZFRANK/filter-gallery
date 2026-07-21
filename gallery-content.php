<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

// Load Gallery Content
// Prioritize plugin specific meta, fallback to WordPress defaults
$ufg_title = isset($ufg_gallery['ufg-title'][$attachment_id]) ? $ufg_gallery['ufg-title'][$attachment_id] : get_the_title($attachment_id);
$ufg_alt = isset($ufg_gallery['ufg-alt'][$attachment_id]) ? $ufg_gallery['ufg-alt'][$attachment_id] : get_post_meta($attachment_id, '_wp_attachment_image_alt', TRUE);
$ufg_description = isset($ufg_gallery['ufg-description'][$attachment_id]) ? $ufg_gallery['ufg-description'][$attachment_id] : '';

// If plugin description is empty, fallback to WP attachment content
if (empty($ufg_description)) {
	$attachment = get_post($attachment_id);
	$ufg_description = $attachment ? $attachment->post_content : '';
}

// Fetch image URLs - Cast ID to int and remove icon flag for stability
$attachment_id_int = (int) $attachment_id;
$medium_size = !empty($ufg_thumbnail_image_size) ? $ufg_thumbnail_image_size : 'medium';
$medium = wp_get_attachment_image_src($attachment_id_int, $medium_size);
$full = wp_get_attachment_image_src($attachment_id_int, 'full');

$ufg_url = '';

if (!function_exists('ufg_get_filter_ancestors')) {
	function ufg_get_filter_ancestors($filters, $target_key, $current_path = array()) {
		if (is_array($filters)) {
			foreach ($filters as $f) {
				if (!isset($f->filterkey)) continue;
				$clean_key = strtolower(trim($f->filterkey));
				$f_class = str_replace(" ", "-", $clean_key);
				
				$new_path = $current_path;
				$new_path[] = $f_class;
				
				if ($clean_key === strtolower(trim($target_key))) {
					return $new_path;
				}
				
				if (isset($f->children) && is_array($f->children)) {
					$res = ufg_get_filter_ancestors($f->children, $target_key, $new_path);
					if (!empty($res)) {
						return $res;
					}
				}
			}
		}
		return array();
	}
}

if (isset($ufg_gallery['ufg-image-filters'][$attachment_id]) && is_array($ufg_gallery['ufg-image-filters'][$attachment_id]) && count($ufg_gallery['ufg-image-filters'][$attachment_id])) {
	$filters_raw = $ufg_gallery['ufg-image-filters'][$attachment_id];
	$filters = array();
	foreach ($filters_raw as $fr) {
		if (strpos($fr, ',') !== false) {
			$parts = explode(',', $fr);
			foreach ($parts as $p) {
				$p = trim($p);
				if (!empty($p)) {
					$filters[] = $p;
				}
			}
		} else {
			$filters[] = trim($fr);
		}
	}
	
	$expanded_filters = array();
	foreach ($filters as $f) {
		$expanded_filters[] = str_replace(" ", "-", strtolower($f));
	}
	$filters = array_values(array_unique($expanded_filters));
} else {
	$filters = array();
}
?>
<div
	class="ufg-thumbnail <?php echo esc_attr($load_class); ?> <?php echo esc_attr(implode(" ", $filters)); ?>">
	<div class="ufg-thumbnail-border">
		<!-- Lightbox = Show -->
		<?php
		$is_lightbox_enabled = ($ufg_lightbox === 'on' || $ufg_lightbox == 1 || $ufg_lightbox === '1' || $ufg_lightbox === true);
		if ($is_lightbox_enabled) { ?>

			<!-- Read more link on: Image(2) = Link On Image -->
			<?php if ($ufg_read_more_link == 2 && $ufg_url != "") { ?>
				<a href="<?php echo esc_url($ufg_url); ?>" target="<?php echo esc_attr($ufg_read_more_button_target); ?>"
					class="ufg-no-underline">
					<div class="ufg-img-wrap">
						<img loading="lazy" src="<?php echo esc_url($medium[0]); ?>" width="<?php echo esc_attr($medium[1]); ?>" height="<?php echo esc_attr($medium[2]); ?>"
							class="ufg-thumbnail-img ufg-img-responsive" alt="<?php echo esc_attr($ufg_alt); ?>">
					</div>
				</a>
			<?php } else { ?>
				<!-- Read more link on: Button(1) = Lightbox -->
				<a href="<?php echo esc_url($full[0]); ?>" class="ufg-lightbox <?php echo esc_attr(implode(" ", $filters)); ?>"
					data-title="<?php
					$caption_parts = array();
					$show_lb_title = ($ufg_lightbox_title === 'on' || $ufg_lightbox_title == 1 || $ufg_lightbox_title === '1' || $ufg_lightbox_title === true);
					$show_lb_description = false;

					if ($show_lb_title && !empty($ufg_title)) {
						$caption_parts[] = $ufg_title;
					}
					if ($show_lb_description && !empty($ufg_description)) {
						$caption_parts[] = substr($ufg_description, 0, (int) $ufg_image_description_text_limit);
					}
					echo esc_attr(implode(" - ", $caption_parts));
					?>" data-lightbox="ufg-lightbox" data-alt="<?php echo esc_attr($ufg_alt); ?>">
					<div class="border-expand-one"></div>
					<div class="ufg-img-wrap">
						<img loading="lazy" src="<?php echo esc_url($medium[0]); ?>" width="<?php echo esc_attr($medium[1]); ?>" height="<?php echo esc_attr($medium[2]); ?>"
							class="ufg-thumbnail-img ufg-img-responsive" alt="<?php echo esc_attr($ufg_alt); ?>">
					</div>
					<div class="border-expand-two"></div>
				</a>
			<?php } ?>

		<?php } else { ?>

			<!-- Lightbox = Hide -->
			<!-- Read more link on: Image(2) = Link On Image -->
			<?php if ($ufg_read_more_link == 2 && $ufg_url != "") { ?>
				<a href="<?php echo esc_url($ufg_url); ?>" target="<?php echo esc_attr($ufg_read_more_button_target); ?>"
					class="ufg-no-underline">
					<div class="ufg-img-wrap">
						<img loading="lazy" src="<?php echo esc_url($medium[0]); ?>" width="<?php echo esc_attr($medium[1]); ?>" height="<?php echo esc_attr($medium[2]); ?>"
							class="ufg-thumbnail-img ufg-img-responsive" alt="<?php echo esc_attr($ufg_alt); ?>">
					</div>
				</a>
			<?php } else { ?>
				<div class="ufg-img-wrap">
					<img loading="lazy" src="<?php echo esc_url($medium[0]); ?>" width="<?php echo esc_attr($medium[1]); ?>" height="<?php echo esc_attr($medium[2]); ?>"
						class="ufg-thumbnail-img ufg-img-responsive" alt="<?php echo esc_attr($ufg_alt); ?>">
				</div>
			<?php } ?>

		<?php } ?>
		<?php
		$show_grid_title = ($ufg_image_title === 'on' || $ufg_image_title == 1 || $ufg_image_title === '1' || $ufg_image_title === true);
		$show_grid_description = ($ufg_image_description === 'on' || $ufg_image_description == 1 || $ufg_image_description === '1' || $ufg_image_description === true);
		$show_read_more = ($ufg_read_more_link_sh == 1);
		if ($show_grid_title || $show_grid_description || $show_read_more) { ?>
		<div class="ufg-image-content">
			<?php if ($show_grid_title) { ?>
				<?php if (strlen($ufg_title) > 0) { ?>
					<div class="ufg-image-title"><?php echo esc_html($ufg_title); ?></div>
				<?php } ?>
			<?php } ?>

			<?php if ($show_grid_description) { ?>
				<?php if (strlen($ufg_description) > 0) { ?>
					<div class="ufg-image-description">
						<?php echo esc_html(substr($ufg_description, 0, (int) $ufg_image_description_text_limit)); ?>
					</div>
				<?php } ?>
			<?php } ?>

			<?php if ($show_read_more) { ?>
				<?php if (($ufg_read_more_link == 1 || empty($ufg_read_more_link)) && !empty($ufg_url)) { ?>
					<a href="<?php echo esc_url($ufg_url); ?>" target="<?php echo esc_attr($ufg_read_more_button_target); ?>"
						class="ufg-read-more-button ufg-no-underline">
						<?php if (!empty($ufg_read_more_button_icon)) { ?>
							<i class="<?php echo esc_attr($ufg_read_more_button_icon); ?>"></i>
						<?php } ?>
						<?php echo esc_html($ufg_read_more_button_text); ?>
						<?php if ($ufg_read_more_button_icon != "") { ?> 		<?php } ?></a>
				<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<input type="hidden" class="count_attached" value="<?php echo intval($attachment_id); ?>">
</div>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

/* if uninstall.php is not called by WordPress, die */
if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

global $wpdb;

// Delete all options starting with ufg_
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'ufg_%'");

// Delete all transients starting with ufg_
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_ufg_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_ufg_%'");
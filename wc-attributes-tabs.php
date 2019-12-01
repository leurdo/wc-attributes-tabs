<?php
/**
 * Plugin Name: WC Attributes Tabs
 * Plugin URI:
 * Description: Move selected product attributes to separate tabs
 * Version: 1.0.0
 * Author Name: Katya Leurdo
 * Author URI:
 * Textdomain: wc-attributes-tabs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'plugins_loaded', function() {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';

	load_plugin_textdomain( 'wc-attributes-tabs', false, dirname(plugin_basename(__FILE__))  );

	// admin hooks
	$admin = new Admin();
	$admin->load();

	// frontend hooks
	$main = new Main();
	$main->load();
});
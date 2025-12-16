<?php
/**
 * Plugin Name: TPO Fitment Core
 * Plugin URI: https://treasurepointonline.com
 * Description: Core fitment engine for TreasurePointOnline.com. Implements Sidecar Architecture for automotive YMM lookups.
 * Version: 1.0.0
 * Author: Treasure Point Online
 * Text Domain: tpo-fitment-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants
define( 'TPO_FITMENT_VERSION', '1.0.0' );
define( 'TPO_FITMENT_PATH', plugin_dir_path( __FILE__ ) );
define( 'TPO_FITMENT_URL', plugin_dir_url( __FILE__ ) );

// Include Core Classes
require_once TPO_FITMENT_PATH . 'includes/class-tpo-schema-installer.php';
require_once TPO_FITMENT_PATH . 'includes/class-tpo-session-handler.php';
require_once TPO_FITMENT_PATH . 'includes/class-tpo-ajax-handler.php';
require_once TPO_FITMENT_PATH . 'includes/class-tpo-query-modifier.php';
require_once TPO_FITMENT_PATH . 'includes/class-tpo-csv-importer.php';
require_once TPO_FITMENT_PATH . 'public/class-tpo-frontend.php';

/**
 * Main Plugin Class
 */
class TPO_Fitment_Core {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		// Initialize Session Handler
		add_action( 'init', array( 'TPO_Session_Handler', 'init' ) );

		// Initialize AJAX Handler
		add_action( 'init', array( 'TPO_Ajax_Handler', 'init' ) );

		// Initialize Query Modifier
		$query_modifier = new TPO_Query_Modifier();
		add_action( 'pre_get_posts', array( $query_modifier, 'filter_products' ) );

		// Initialize Frontend
		$frontend = new TPO_Frontend();
		add_action( 'wp_enqueue_scripts', array( $frontend, 'enqueue_scripts' ) );
		// Hook for YMM selector might go here or in frontend class
		add_action( 'storefront_before_header', array( $frontend, 'render_selector' ) );
		
		// Admin/Importer hooks would go here
	}
}

// Activation Hook
register_activation_hook( __FILE__, array( 'TPO_Schema_Installer', 'install' ) );

// Initialize Plugin
add_action( 'plugins_loaded', array( 'TPO_Fitment_Core', 'get_instance' ) );

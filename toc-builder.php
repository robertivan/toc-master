<?php
/**
 * Plugin Name: TOC Builder by RobertIvan
 * Plugin URI:  
 * Description: An ultra-advanced Table of Contents generator with smooth scroll, Gutenberg support, and high configurability.
 * Version:     1.2.1
 * Author:      Robert Ivan
 * Author URI:  https://github.com/robertivan/
 * License:     GPLv2 or later
 * Text Domain: tbrv
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants
define( 'TBRV_VERSION', '1.2.1' );
define( 'TBRV_PATH', plugin_dir_path( __FILE__ ) );
define( 'TBRV_URL', plugin_dir_url( __FILE__ ) );

// Include Classes
require_once TBRV_PATH . 'includes/class-toc-settings.php';
require_once TBRV_PATH . 'includes/class-toc-generator.php';
require_once TBRV_PATH . 'includes/class-toc-block.php';

/**
 * Main Plugin Class
 */
class TBRV_Plugin {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Initialize Settings
		$this->settings = new TBRV_Settings();

		// Initialize Generator (Frontend)
		$this->generator = new TBRV_Generator();

		// Initialize Block (Gutenberg)
		$this->block = new TBRV_Block();

		// Load Assets
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets() {
		if ( is_singular() ) {
			wp_enqueue_style( 'tbrv-style', TBRV_URL . 'assets/css/style.css', array(), TBRV_VERSION );
			wp_enqueue_script( 'tbrv-script', TBRV_URL . 'assets/js/script.js', array(), TBRV_VERSION, true );
		}
	}
}

// Initialize Plugin
function tbrv_init() {
	TBRV_Plugin::get_instance();
}
add_action( 'plugins_loaded', 'tbrv_init' );

// Migration function for settings
function tbrv_migrate_settings() {
	$old_options = get_option( 'toc_master_options' );
	$new_options = get_option( 'tbrv_options' );
	
	// Only migrate if old options exist and new options don't
	if ( $old_options && ! $new_options ) {
		update_option( 'tbrv_options', $old_options );
	}
}
register_activation_hook( __FILE__, 'tbrv_migrate_settings' );

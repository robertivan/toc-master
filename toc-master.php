<?php
/**
 * Plugin Name: TOC Master
 * Plugin URI:  
 * Description: An ultra-advanced Table of Contents generator with smooth scroll, Gutenberg support, and high configurability.
 * Version:     1.2.0
 * Author:      Robert Ivan
 * Author URI:  https://github.com/robertivan/
 * License:     GPLv2 or later
 * Text Domain: toc-master
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants
define( 'TOC_MASTER_VERSION', '1.2.0' );
define( 'TOC_MASTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'TOC_MASTER_URL', plugin_dir_url( __FILE__ ) );

// Include Classes
require_once TOC_MASTER_PATH . 'includes/class-toc-settings.php';
require_once TOC_MASTER_PATH . 'includes/class-toc-generator.php';
require_once TOC_MASTER_PATH . 'includes/class-toc-block.php';

/**
 * Main Plugin Class
 */
class TOC_Auto_Generator_Ultra {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Initialize Settings
		$this->settings = new TOC_Master_Settings();

		// Initialize Generator (Frontend)
		$this->generator = new TOC_Master_Generator();

		// Initialize Block (Gutenberg)
		$this->block = new TOC_Master_Block();

		// Load Assets
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets() {
		if ( is_singular() ) {
			wp_enqueue_style( 'toc-master-style', TOC_MASTER_URL . 'assets/css/style.css', array(), TOC_MASTER_VERSION );
			wp_enqueue_script( 'toc-master-script', TOC_MASTER_URL . 'assets/js/script.js', array(), TOC_MASTER_VERSION, true );
		}
	}
}

// Initialize Plugin
function toc_master_init() {
	TOC_Auto_Generator_Ultra::get_instance();
}
add_action( 'plugins_loaded', 'toc_master_init' );

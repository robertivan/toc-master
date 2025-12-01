<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TBRV_Block {

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
	}

	public function register_block() {
		register_block_type( 'tbrv/toc', array(
			'render_callback' => array( $this, 'render_block' ),
			'attributes'      => array(
				'headings' => array(
					'type'    => 'array',
					'default' => array( 'h2', 'h3' ),
				),
			),
		) );
	}

	public function enqueue_editor_assets() {
		wp_enqueue_script(
			'tbrv-block',
			TBRV_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data' ),
			TBRV_VERSION,
			true
		);
        
        wp_enqueue_style(
            'tbrv-style',
            TBRV_URL . 'assets/css/style.css',
            array(),
            TBRV_VERSION
        );
	}

	public function render_block( $attributes, $content ) {


        
        global $post;
        if ( ! $post ) {
            return '';
        }
        

        
        $generator = new TBRV_Generator();

        
        $content_to_parse = $post->post_content;
        

        

        
        $instance = TBRV_Plugin::get_instance();

        

        return '[toc]';
	}
}

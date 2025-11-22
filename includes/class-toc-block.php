<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TOC_Master_Block {

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
	}

	public function register_block() {
		register_block_type( 'toc-master/toc', array(
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
			'toc-master-block',
			TOC_MASTER_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data' ),
			TOC_MASTER_VERSION,
			true
		);
        
        wp_enqueue_style(
            'toc-master-style',
            TOC_MASTER_URL . 'assets/css/style.css',
            array(),
            TOC_MASTER_VERSION
        );
	}

	public function render_block( $attributes, $content ) {
        // This render callback is for the frontend if the block is used.
        // However, we already have auto-insertion logic.
        // If the user manually inserts the block, we should render the TOC here.
        // But wait, the Generator class hooks into 'the_content' and parses the WHOLE content.
        // If we render here, we need to parse the content of the current post.
        
        // Problem: 'the_content' filter runs on the whole content. If we return the TOC here,
        // it will be part of the content.
        // We need to make sure we don't duplicate logic.
        
        // If this block is present, we should probably disable auto-insertion for this post?
        // Or just let the user place it.
        
        // For simplicity in this "render_block", we can instantiate the Generator and ask it to generate the TOC.
        // But the Generator expects the full content to parse.
        // In a block render callback, we don't easily have the full content *after* it's been processed, 
        // or rather, we are *inside* the processing.
        
        // A better approach for the PHP render:
        // Return a placeholder div that the Generator class looks for and replaces?
        // OR, just return the TOC based on the global $post.
        
        global $post;
        if ( ! $post ) {
            return '';
        }
        
        // We need to parse the post content. 
        // WARNING: Infinite loop risk if we apply filters to content here.
        // We should just get raw content and parse headings.
        
        $generator = new TOC_Master_Generator();
        // We need to expose a public method in Generator to get TOC from string.
        // I'll need to modify Generator to allow public access to 'extract_headings' and 'generate_toc_html' 
        // or a wrapper method.
        
        // For now, let's assume I'll add a public method `get_toc_for_content($content)` to Generator.
        // But wait, the block render doesn't receive the full content of the post, it receives the block content (empty).
        
        $content_to_parse = $post->post_content;
        
        // We need to remove the TOC block itself from the content to avoid self-reference if we were parsing blocks?
        // No, headings are what matters.
        
        // Let's use a helper method.
        // I will modify Class Generator to have a static or public helper.
        
        // Actually, since I can't easily modify the Generator instance from here without a singleton or global,
        // I'll use the singleton instance.
        
        $instance = TOC_Auto_Generator_Ultra::get_instance();
        // I need to make generator public or add a getter.
        // Let's modify the main class to expose generator.
        
        // For now, I will just duplicate the extraction logic or make it static. 
        // Making it static in Generator is best.
        
        // Return the shortcode so the Generator class can pick it up and replace it with the actual TOC.
        // This ensures we use the same logic for generation and don't duplicate code.
        return '[toc]';
	}
}

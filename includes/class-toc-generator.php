<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TBRV_Generator {

	private $options;

	public function __construct() {
		$this->options = get_option( 'tbrv_options' );
		
		// Hook into content
		add_filter( 'the_content', array( $this, 'process_content' ), 20 );
		
		// Shortcode
		add_shortcode( 'toc', array( $this, 'shortcode_toc' ) );
	}

	public function process_content( $content ) {
		if ( ! is_singular() || ! is_main_query() ) {
			return $content;
		}

		// Check if enabled globally
		if ( ! isset( $this->options['enable_toc'] ) || $this->options['enable_toc'] !== '1' ) {

		}

		// Parse Headings
		$headings = $this->extract_headings( $content );

		if ( empty( $headings ) ) {
			return $content;
		}

		// Generate TOC HTML
		$toc_html = $this->generate_toc_html( $headings );

		// Auto Insert
		if ( isset( $this->options['enable_toc'] ) && $this->options['enable_toc'] === '1' ) {
			// Check position
			$position = isset( $this->options['position'] ) ? $this->options['position'] : 'before';
			
			if ( $position === 'top' ) {
				$content = $toc_html . $content;
			} elseif ( $position === 'before' && ! empty( $headings ) ) {
				// Insert before first heading
				$first_heading = $headings[0]['full_match'];
				$content = substr_replace( $content, $toc_html . $first_heading, strpos( $content, $first_heading ), strlen( $first_heading ) );
			} elseif ( $position === 'after' && ! empty( $headings ) ) {
				// Insert after first heading
				$first_heading = $headings[0]['full_match'];
				$content = substr_replace( $content, $first_heading . $toc_html, strpos( $content, $first_heading ), strlen( $first_heading ) );
			}
		}

		// Replace Shortcode if exists
		if ( has_shortcode( $content, 'toc' ) ) {
			$content = str_replace( '[toc]', $toc_html, $content );
		}

		return $content;
	}

	public function shortcode_toc( $atts ) {

		return '[toc]'; 
	}

	private function extract_headings( &$content ) {
		$allowed_levels = isset( $this->options['headings'] ) ? $this->options['headings'] : array( 'h2', 'h3' );
		if ( empty( $allowed_levels ) ) {
			return array();
		}

		$pattern = '/<h([' . implode( '', str_replace( 'h', '', $allowed_levels ) ) . '])(.*?)>(.*?)<\/h\1>/i';
		

		if ( preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ) {
			$headings = array();
			$offset_correction = 0;
			
			foreach ( $matches as $match ) {
				$full_match_str = $match[0][0];
				$offset         = $match[0][1];
				$level          = $match[1][0];
				$attrs          = $match[2][0];
				$title          = wp_strip_all_tags( $match[3][0] );
				
				// Adjust offset based on previous replacements
				$current_offset = $offset + $offset_correction;
				
				// Generate ID
				$id = $this->generate_id( $title );
				
				// Initialize final_heading for this iteration
				$final_heading = $full_match_str;
				
				// Check if ID exists
				if ( strpos( $attrs, 'id=' ) !== false ) {
					preg_match( '/id=["\'](.*?)["\']/', $attrs, $id_match );
					if ( ! empty( $id_match[1] ) ) {
						$id = $id_match[1];
					}

					$final_heading = $full_match_str;
				} else {

					$new_attrs = $attrs . ' id="' . $id . '"';
					$new_heading = "<h{$level}{$new_attrs}>{$match[3][0]}</h{$level}>";
					
					// Replace in content
					$content = substr_replace( $content, $new_heading, $current_offset, strlen( $full_match_str ) );
					
					// Update correction
					$offset_correction += strlen( $new_heading ) - strlen( $full_match_str );
					
					$final_heading = $new_heading;
				}

				$headings[] = array(
					'level'      => $level,
					'title'      => $title,
					'id'         => $id,
					'full_match' => $final_heading,
				);
			}
			return $headings;
		}

		return array();
	}

	private function generate_id( $title ) {
		$id = sanitize_title( $title );
		if ( empty( $id ) ) {
			$id = 'section-' . wp_rand( 1000, 9999 );
		}
		return $id;
	}

	private function generate_toc_html( $headings ) {
		if ( empty( $headings ) ) {
			return '';
		}

		$html = '<div class="tbrv-container">';
		
		// Header with toggle
		$collapsible = isset( $this->options['collapsible'] ) && $this->options['collapsible'] === '1';
		$html .= '<div class="tbrv-header">';
		$html .= '<span class="tbrv-title">' . esc_html__( 'Table of Contents', 'tbrv' ) . '</span>';
		if ( $collapsible ) {
			$html .= '<span class="tbrv-toggle">[<a href="#" class="tbrv-toggle-link">' . esc_html__( 'hide', 'tbrv' ) . '</a>]</span>';
		}
		$html .= '</div>';

		$html .= '<ul class="tbrv-list">';
		
		$current_depth = 0;
		$min_level = min( array_column( $headings, 'level' ) );

		foreach ( $headings as $heading ) {
			$level = $heading['level'];
			// Normalize level (start at 0)
			$depth = $level - $min_level;

			if ( $depth > $current_depth ) {
				$html .= str_repeat( '<ul>', $depth - $current_depth );
			} elseif ( $depth < $current_depth ) {
				$html .= str_repeat( '</ul>', $current_depth - $depth );
			}

			$html .= '<li><a href="#' . esc_attr( $heading['id'] ) . '" class="tbrv-link">' . esc_html( $heading['title'] ) . '</a></li>';
			
			$current_depth = $depth;
		}
		
		// Close remaining tags
		if ( $current_depth > 0 ) {
			$html .= str_repeat( '</ul>', $current_depth );
		}

		$html .= '</ul>';
		$html .= '</div>';

		return $html;
	}
}

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TOC_Master_Settings {

	private $option_group = 'toc_master_settings_group';
	private $option_name  = 'toc_master_options';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	public function add_admin_menu() {
		// Main menu item
		add_menu_page(
			'TOC Master',                    // Page title
			'TOC Master',                    // Menu title
			'manage_options',                // Capability
			'toc-master',                    // Menu slug
			array( $this, 'render_general_page' ),  // Callback
			'dashicons-list-view',           // Icon
			30                               // Position
		);

		// Submenu: General Settings (default)
		add_submenu_page(
			'toc-master',
			'General Settings',
			'General',
			'manage_options',
			'toc-master',
			array( $this, 'render_general_page' )
		);

		// Submenu: Appearance
		add_submenu_page(
			'toc-master',
			'Appearance Settings',
			'Appearance',
			'manage_options',
			'toc-master-appearance',
			array( $this, 'render_appearance_page' )
		);

		// Submenu: Advanced
		add_submenu_page(
			'toc-master',
			'Advanced Settings',
			'Advanced',
			'manage_options',
			'toc-master-advanced',
			array( $this, 'render_advanced_page' )
		);

		// Submenu: Premium
		add_submenu_page(
			'toc-master',
			'Premium Features',
			'Premium',
			'manage_options',
			'toc-master-premium',
			array( $this, 'render_premium_page' )
		);
	}

    public function enqueue_admin_scripts( $hook ) {
        // Check if we're on any of our admin pages
        $our_pages = array(
            'toplevel_page_toc-master',
            'toc-master_page_toc-master-appearance',
            'toc-master_page_toc-master-advanced',
            'toc-master_page_toc-master-premium'
        );
        
        if ( ! in_array( $hook, $our_pages ) ) {
            return;
        }
        
        wp_enqueue_style( 'toc-master-style', TOC_MASTER_URL . 'assets/css/style.css', array(), TOC_MASTER_VERSION );
        wp_enqueue_style( 'toc-master-admin-premium', TOC_MASTER_URL . 'assets/css/admin-premium.css', array(), TOC_MASTER_VERSION );
        
        // Enqueue JavaScript only for General page (needs live preview)
        if ( $hook === 'toplevel_page_toc-master' ) {
            wp_enqueue_script( 'toc-master-admin', TOC_MASTER_URL . 'assets/js/admin-settings.js', array(), TOC_MASTER_VERSION, true );
        }
    }

	public function register_settings() {
		register_setting( $this->option_group, $this->option_name, array( $this, 'sanitize_settings' ) );

		add_settings_section(
			'toc_master_general_section',
			'General Settings',
			null,
			'toc-master-settings'
		);

		add_settings_field(
			'enable_toc',
			'Enable TOC',
			array( $this, 'render_checkbox_field' ),
			'toc-master-settings',
			'toc_master_general_section',
			array( 'id' => 'enable_toc', 'label' => 'Enable Table of Contents automatically' )
		);

		add_settings_field(
			'headings',
			'Headings to Include',
			array( $this, 'render_headings_field' ),
			'toc-master-settings',
			'toc_master_general_section',
			array( 'id' => 'headings' )
		);

		add_settings_field(
			'position',
			'Position',
			array( $this, 'render_select_field' ),
			'toc-master-settings',
			'toc_master_general_section',
			array(
				'id' => 'position',
				'options' => array(
					'before' => 'Before First Heading',
					'after'  => 'After First Heading',
					'top'    => 'Top of Content',
				)
			)
		);

		add_settings_field(
			'smooth_scroll',
			'Smooth Scroll',
			array( $this, 'render_checkbox_field' ),
			'toc-master-settings',
			'toc_master_general_section',
			array( 'id' => 'smooth_scroll', 'label' => 'Enable Smooth Scroll' )
		);
        
        add_settings_field(
			'collapsible',
			'Collapsible',
			array( $this, 'render_checkbox_field' ),
			'toc-master-settings',
			'toc_master_general_section',
			array( 'id' => 'collapsible', 'label' => 'Allow users to collapse TOC' )
		);
	}

	public function sanitize_settings( $input ) {
		$new_input = array();
		$new_input['enable_toc'] = isset( $input['enable_toc'] ) ? '1' : '0';
		$new_input['smooth_scroll'] = isset( $input['smooth_scroll'] ) ? '1' : '0';
        $new_input['collapsible'] = isset( $input['collapsible'] ) ? '1' : '0';
		$new_input['position'] = sanitize_text_field( $input['position'] );
		
		if ( isset( $input['headings'] ) && is_array( $input['headings'] ) ) {
			$new_input['headings'] = array_map( 'sanitize_text_field', $input['headings'] );
		} else {
			$new_input['headings'] = array();
		}

		return $new_input;
	}

	public function render_general_page() {
		?>
		<div class="toc-master-settings-wrap">
			<!-- Premium Header -->
			<div class="toc-premium-header">
				<h1>
					<span class="toc-header-icon">⚡</span>
					TOC Master					
				</h1>
				<p>Configure the complete Table of Contents experience</p>
			</div>

			<!-- Content Area -->
			<div class="toc-premium-content">
				<form method="post" action="options.php" id="toc-settings-form">
					<?php settings_fields( $this->option_group ); ?>

					<!-- General Tab Content (now main content) -->
					<div class="toc-tab-pane active" data-tab-content="general">
						<!-- Left Column: Settings -->
						<div class="toc-general-settings">
							<!-- Core Settings Card -->
							<div class="toc-settings-card">
								<div class="toc-card-header">
									<div class="toc-card-icon">⚡</div>
									<div>
										<h3>Core Settings</h3>
									</div>
								</div>
								<p class="toc-card-description">Configure basic settings for automatic Table of Contents generation</p>

								<?php
								$options = get_option( $this->option_name );
								$enable_toc = isset( $options['enable_toc'] ) && $options['enable_toc'] === '1' ? 'checked' : '';
								$smooth_scroll = isset( $options['smooth_scroll'] ) && $options['smooth_scroll'] === '1' ? 'checked' : '';
								$collapsible = isset( $options['collapsible'] ) && $options['collapsible'] === '1' ? 'checked' : '';
								$position = isset( $options['position'] ) ? $options['position'] : 'before';
								?>

								<!-- Enable TOC -->
								<div class="toc-settings-row">
									<div class="toc-settings-label">Enable TOC</div>
									<div class="toc-settings-control">
										<div class="toc-checkbox-wrapper">
											<input type="checkbox" id="enable_toc" name="<?php echo esc_attr( $this->option_name ); ?>[enable_toc]" value="1" <?php checked( $enable_toc, 'checked' ); ?>>
											<label for="enable_toc">Enable Table of Contents automatically</label>
										</div>
										<p class="toc-helper-text">When enabled, TOC will be automatically generated on all posts and pages.</p>
									</div>
								</div>

								<!-- Smooth Scroll -->
								<div class="toc-settings-row">
									<div class="toc-settings-label">Smooth Scroll</div>
									<div class="toc-settings-control">
										<div class="toc-checkbox-wrapper">
											<input type="checkbox" id="smooth_scroll" name="<?php echo esc_attr( $this->option_name ); ?>[smooth_scroll]" value="1" <?php checked( $smooth_scroll, 'checked' ); ?>>
											<label for="smooth_scroll">Enable Smooth Scroll</label>
										</div>
										<p class="toc-helper-text">Enable smooth animation when navigating between sections.</p>
									</div>
								</div>

								<!-- Collapsible -->
								<div class="toc-settings-row">
									<div class="toc-settings-label">Collapsible</div>
									<div class="toc-settings-control">
										<div class="toc-checkbox-wrapper">
											<input type="checkbox" id="collapsible" name="<?php echo esc_attr( $this->option_name ); ?>[collapsible]" value="1" <?php checked( $collapsible, 'checked' ); ?>>
											<label for="collapsible">Allow users to collapse TOC</label>
										</div>
										<p class="toc-helper-text">Users will be able to hide/show the TOC with a click.</p>
									</div>
								</div>
							</div>

							<!-- Content Settings Card -->
							<div class="toc-settings-card">
								<div class="toc-card-header">
									<div class="toc-card-icon">📝</div>
									<div>
										<h3>Content Settings</h3>
									</div>
								</div>
								<p class="toc-card-description">Customize content and positioning of Table of Contents</p>

								<!-- Headings to Include -->
								<div class="toc-settings-row">
									<div class="toc-settings-label">Headings to Include</div>
									<div class="toc-settings-control">
										<div class="toc-headings-grid">
											<?php
											$current_headings = isset( $options['headings'] ) ? $options['headings'] : array( 'h2', 'h3' );
											foreach ( range( 1, 6 ) as $i ) {
												$h = 'h' . $i;
												$checked = in_array( $h, $current_headings ) ? 'checked' : '';
												?>
												<div class="toc-heading-option">
													<input type="checkbox" id="heading_<?php echo esc_attr( $h ); ?>" name="<?php echo esc_attr( $this->option_name ); ?>[headings][]" value="<?php echo esc_attr( $h ); ?>" <?php checked( $checked, 'checked' ); ?>>
													<label for="heading_<?php echo esc_attr( $h ); ?>">H<?php echo esc_html( $i ); ?></label>
												</div>
												<?php
											}
											?>
										</div>
										<p class="toc-helper-text">Select heading levels to include in TOC.</p>
									</div>
								</div>

								<!-- Position -->
								<div class="toc-settings-row">
									<div class="toc-settings-label">Position</div>
									<div class="toc-settings-control">
										<div class="toc-select-wrapper">
											<select name="<?php echo esc_attr( $this->option_name ); ?>[position]">
												<option value="before" <?php selected( $position, 'before' ); ?>>Before First Heading</option>
												<option value="after" <?php selected( $position, 'after' ); ?>>After First Heading</option>
												<option value="top" <?php selected( $position, 'top' ); ?>>Top of Content</option>
											</select>
										</div>
										<p class="toc-helper-text">Choose where you want to display the Table of Contents.</p>
									</div>
								</div>
							</div>

							<!-- Submit Button -->
							<div class="toc-submit-wrapper">
								<?php submit_button( 'Save Changes', 'primary', 'submit', false ); ?>
							</div>
						</div>

						<!-- Right Column: Live Preview -->
						<div class="toc-general-preview">
							<div class="toc-preview-card">
								<div class="toc-preview-header">
									<h4>👁️ Live Preview</h4>
									<p>Preview in real-time how the Table of Contents will look</p>
								</div>
								<div class="toc-master-preview">
									<!-- Preview will be injected by JavaScript -->
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	public function render_appearance_page() {
		?>
		<div class="toc-master-settings-wrap">
			<div class="toc-premium-header">
				<h1>
					<span class="toc-header-icon">🎨</span>
					Appearance Settings
					<span class="toc-professional-badge">COMING SOON</span>
				</h1>
				<p>Customize the visual appearance of Table of Contents</p>
			</div>

			<div class="toc-premium-content">
				<div class="toc-settings-card">
					<div class="toc-card-header">
						<div class="toc-card-icon">🎨</div>
						<div>
							<h3>Appearance Settings</h3>
						</div>
					</div>
					<p class="toc-card-description">Customize the visual appearance of Table of Contents (coming soon)</p>

					<div class="toc-premium-features">
						<div class="toc-feature-card">
							<div class="toc-feature-icon">🎨</div>
							<h4>Custom Colors</h4>
							<p>Choose custom colors for TOC that match your brand.</p>
							<span class="toc-coming-soon">Coming Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">✨</div>
							<h4>Typography</h4>
							<p>Configure fonts, sizes and styles for text.</p>
							<span class="toc-coming-soon">Coming Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">📐</div>
							<h4>Layout Options</h4>
							<p>Width, spacing, borders and many other layout options.</p>
							<span class="toc-coming-soon">Coming Soon</span>
						</div>
					</div>

					<div class="toc-info-box">
						<p><strong>💡 Pro Tip:</strong> These features will be available soon to give you complete control over TOC design.</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_advanced_page() {
		?>
		<div class="toc-master-settings-wrap">
			<div class="toc-premium-header">
				<h1>
					<span class="toc-header-icon">🚀</span>
					Advanced Settings
					<span class="toc-professional-badge">COMING SOON</span>
				</h1>
				<p>Advanced options for expert users</p>
			</div>

			<div class="toc-premium-content">
				<div class="toc-settings-card">
					<div class="toc-card-header">
						<div class="toc-card-icon">🚀</div>
						<div>
							<h3>Advanced Options</h3>
						</div>
					</div>
					<p class="toc-card-description">Advanced options for expert users (coming soon)</p>

					<div class="toc-premium-features">
						<div class="toc-feature-card">
							<div class="toc-feature-icon">💻</div>
							<h4>Custom CSS</h4>
							<p>Add custom CSS for advanced styling.</p>
							<span class="toc-coming-soon">Coming Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">⚡</div>
							<h4>Performance</h4>
							<p>Performance optimizations and caching for large sites.</p>
							<span class="toc-coming-soon">Coming Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">🎯</div>
							<h4>Selectors</h4>
							<p>Configure custom CSS selectors for headings.</p>
							<span class="toc-coming-soon">Coming Soon</span>
						</div>
					</div>

					<div class="toc-info-box">
						<p><strong>🔧 Developer Note:</strong> These advanced settings will allow complete customization for developers.</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_premium_page() {
		?>
		<div class="toc-master-settings-wrap">
			<div class="toc-premium-header">
				<h1>
					<span class="toc-header-icon">💎</span>
					Premium Features
					<span class="toc-professional-badge">COMING SOON</span>
				</h1>
				<p>Unlock premium features for complete experience</p>
			</div>

			<div class="toc-premium-content">
				<div class="toc-settings-card">
					<div class="toc-card-header">
						<div class="toc-card-icon">💎</div>
						<div>
							<h3>Premium Features</h3>
						</div>
					</div>
					<p class="toc-card-description">Unlock premium features for complete experience</p>

					<div class="toc-premium-features">
						<div class="toc-feature-card">
							<div class="toc-feature-icon">📊</div>
							<h4>Analytics</h4>
							<p>Tracking and statistics for TOC usage by visitors.</p>
							<span class="toc-coming-soon">Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">🎬</div>
							<h4>Animations</h4>
							<p>Advanced animations and premium transition effects.</p>
							<span class="toc-coming-soon">Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">🔍</div>
							<h4>Search in TOC</h4>
							<p>Search function within Table of Contents.</p>
							<span class="toc-coming-soon">Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">📱</div>
							<h4>Mobile Styles</h4>
							<p>Dedicated styles and behavior for mobile.</p>
							<span class="toc-coming-soon">Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">🎨</div>
							<h4>Templates</h4>
							<p>Pre-built templates for instant design.</p>
							<span class="toc-coming-soon">Soon</span>
						</div>
						<div class="toc-feature-card">
							<div class="toc-feature-icon">🌐</div>
							<h4>Multi-language</h4>
							<p>Complete support for multiple languages and translations.</p>
							<span class="toc-coming-soon">Soon</span>
						</div>
					</div>

					<div class="toc-info-box">
						<p><strong>💎 Premium:</strong> These features will be part of the Premium version of the plugin, offering added value for professional users.</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_checkbox_field( $args ) {
		$options = get_option( $this->option_name );
		$id      = $args['id'];
		$checked = isset( $options[ $id ] ) && $options[ $id ] === '1' ? 'checked' : '';
		echo '<label><input type="checkbox" name="' . esc_attr( $this->option_name ) . '[' . esc_attr( $id ) . ']" value="1" ' . checked( $checked, 'checked', false ) . '> ' . esc_html( $args['label'] ) . '</label>';
	}

	public function render_select_field( $args ) {
		$options = get_option( $this->option_name );
		$id      = $args['id'];
		$current = isset( $options[ $id ] ) ? $options[ $id ] : '';
		echo '<select name="' . esc_attr( $this->option_name ) . '[' . esc_attr( $id ) . ']">';
		foreach ( $args['options'] as $value => $label ) {
			echo '<option value="' . esc_attr( $value ) . '"';
			selected( $current, $value );
			echo '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';
	}

	public function render_headings_field( $args ) {
		$options = get_option( $this->option_name );
		$current = isset( $options['headings'] ) ? $options['headings'] : array( 'h2', 'h3' ); // Default
		
		foreach ( range( 1, 6 ) as $i ) {
			$h = 'h' . $i;
			$checked = in_array( $h, $current ) ? 'checked' : '';
			echo '<label style="margin-right: 10px;"><input type="checkbox" name="' . esc_attr( $this->option_name ) . '[headings][]" value="' . esc_attr( $h ) . '" ' . checked( $checked, 'checked', false ) . '> H' . esc_html( $i ) . '</label>';
		}
	}
}

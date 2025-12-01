=== TOC Builder by RobertIvan ===
Contributors: robertivan
Tags: table of contents, toc, navigation, headings, gutenberg
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A Table of Contents generator for WordPress with admin interface.

== Description ==

TOC Builder by RobertIvan is a Table of Contents plugin that automatically generates a navigation menu from your content's headings. It includes an admin interface for configuration and is suitable for long-form content, documentation sites, and blogs.

= Core Features =

* **Auto-Detection** - Automatically scans content for headings (H1-H6)
* **Smooth Scroll** - Animated scrolling to sections
* **SpyScroll** - Highlights the current section in the TOC while scrolling
* **Gutenberg Ready** - Includes a native block with live preview in the editor
* **Configurable** - Customize position, colors, heading levels, and more
* **Performance** - Assets loading and caching optimized
* **Responsive** - Compatible with desktop, tablet, and mobile devices

= Admin Interface =

TOC Builder by RobertIvan features an admin settings page for configuration:

**Design System**

* Gradient color scheme
* Animations and transitions
* Card-based layout
* System typography

**WordPress Sidebar Menu Structure**

* Dedicated Sidebar Menu - Menu in WordPress sidebar with a custom icon
* General Settings - Core TOC functionality with live preview
* Appearance Settings - Visual customization options (coming soon)
* Advanced Settings - Options for developers (coming soon)
* Premium Features - Planned functionality
* Bookmarkable URLs - Direct URLs for each section

**User Experience**

* Live Preview - Preview updates based on selected heading levels
* Heading Selection - Click on heading boxes (H1-H6) to toggle selection
* Visual Hierarchy - Preview shows TOC structure based on heading selections
* Interactive Demo - Collapsible TOC preview with hide/show functionality
* Visual Feedback - Checkboxes with gradient backgrounds when selected
* 2-Column Layout - Settings on left, sticky live preview on right (desktop)
* Responsive Design - Stacked layout on smaller screens

= Future Architecture =

The admin interface is designed to accommodate upcoming features:

* Custom color schemes and typography options
* Advanced CSS customization
* Performance optimization controls
* Analytics and tracking capabilities
* Pre-built design templates
* Multi-language support infrastructure

= Suitable For =

* Long-form blog posts and articles
* Documentation and knowledge bases
* Educational content and tutorials
* Product guides and manuals
* Any content with multiple headings

== Installation ==

= Automatic Installation =

1. Log in to your WordPress admin panel
2. Navigate to Plugins → Add New
3. Search for "TOC Builder by RobertIvan"
4. Click "Install Now" and then "Activate"
5. Navigate to TOC Builder in the WordPress sidebar to configure

= Manual Installation =

1. Download the plugin ZIP file
2. Upload the `toc-master` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to TOC Builder in the WordPress sidebar to configure the plugin

= After Activation =

1. Go to TOC Builder in the WordPress sidebar
2. Enable the Table of Contents
3. Select which heading levels to include (H1-H6)
4. Choose where to display the TOC (before/after first heading, or top of content)
5. Configure additional options like smooth scroll and collapsible TOC
6. Save your settings

The TOC will appear automatically on posts and pages (if enabled) or you can use the `[toc]` shortcode to place it manually.

== Frequently Asked Questions ==

= How does the plugin work? =

TOC Builder by RobertIvan automatically scans your post or page content for HTML heading tags (H1 through H6) and generates a navigational Table of Contents. It adds unique IDs to each heading so users can jump directly to sections.

= Can I exclude specific headings? =

Currently, you can select which heading levels (H1-H6) to include in the settings. For example, you can choose to only include H2 and H3 headings.

= Does it work with page builders? =

Yes. It works with any content that uses standard HTML heading tags (`<h1>` to `<h6>`) and the standard WordPress `the_content` filter. This includes most major page builders.

= How do I manually insert the TOC? =

You have three options:
1. Enable automatic insertion in TOC Builder settings
2. Use the `[toc]` shortcode anywhere in your content
3. Use the "TOC Builder" block in the Gutenberg editor

= Can I customize the appearance? =

The current version includes basic styling. Advanced appearance customization options (colors, typography, layout) are planned for future releases and will be accessible through the Appearance tab.

= Is it compatible with my theme? =

Yes. TOC Builder by RobertIvan is designed to work with any WordPress theme that follows standard WordPress coding practices.

= Does it affect my site's performance? =

No. The plugin is optimized for performance with minimal CSS and JavaScript that only loads when needed.

= Can I use it on multiple sites? =

Yes. This plugin is licensed under GPLv2, which allows you to use it on as many sites as you want.

== Screenshots ==

1. General Settings page with WordPress sidebar menu, 2-column layout, and live preview
2. Live preview showing dynamic TOC structure based on selected heading levels
3. Heading selectors (H1-H6) with clickable areas
4. Collapsible TOC preview with interactive hide/show functionality
5. Mobile-responsive layout with stacked settings and preview
6. WordPress sidebar menu with TOC Builder icon

== Changelog ==

= 1.2.1 =
* Changed: All functions, classes, and hooks prefixed with tbrv_ to prevent conflicts
* Changed: All CSS classes renamed to .tbrv-* for consistency
* Improved: WordPress coding standards compliance with unique prefixes throughout

= 1.2.0 =
* Added: WordPress sidebar menu with icon and structure
* Added: Separate admin pages for General, Appearance, Advanced, and Premium
* Added: Live preview that regenerates when selecting/deselecting heading levels
* Added: Heading selectors with clickable areas
* Added: Visual selection feedback with gradient backgrounds
* Added: Preview generation showing TOC structure based on heading combinations
* Added: 2-column layout with settings and sticky preview side-by-side on desktop
* Improved: Asset loading - JavaScript only loaded on pages that need it
* Improved: UX with click-anywhere-on-box heading selection
* Fixed: Tab navigation replaced with WordPress standard submenu pages

= 1.1.0 =
* Added: Admin interface with design;
* Added: Tab-based navigation (General, Appearance, Advanced, Premium)
* Added: Live preview with interactive demo
* Added: Card-based settings layout
* Added: Animations and transitions in the admin interface
* Added: Responsive admin design
* Added: Architecture for upcoming premium features
* Improved: Visual feedback on user interactions
* Improved: Color scheme

= 1.0.1 =
* Fixed: Bug where heading ID injection failed when headings had no existing attributes
* Fixed: JavaScript and CSS assets not loading properly on frontend
* Fixed: Variable scope issue causing incorrect heading IDs to be reused
* Improved: Offset-based string replacement for reliability

= 1.0.0 =
* Initial release
* Added auto-detection of headings
* Added smooth scroll and SpyScroll
* Added Gutenberg block support

== Upgrade Notice ==

= 1.2.0 =
Update with WordPress sidebar menu restructuring and live preview. Dynamic preview, clickable heading selectors, and navigation updates. Backward compatible.

= 1.1.0 =
Update with the admin interface. Design with tabs, live preview, and user experience updates. Backward compatible.

= 1.0.1 =
Bug fixes for heading ID injection. Update recommended for all users.

== Technical Details ==

= File Structure =

toc-master/
├── assets/
│   ├── css/
│   │   ├── style.css              (Frontend TOC styles)
│   │   └── admin-premium.css      (Admin interface styles)
│   └── js/
│       ├── script.js              (Frontend functionality)
│       ├── admin-settings.js      (Admin interface interactions)
│       └── block.js               (Gutenberg block)
├── includes/
│   ├── class-toc-generator.php    (Core TOC generation logic)
│   ├── class-toc-settings.php     (Settings page with UI)
│   └── class-toc-block.php        (Gutenberg block registration)
└── toc-builder.php                (Main plugin file)

= Admin Interface Architecture =

The settings pages use a component-based architecture:

* CSS Design System - CSS custom properties, modular components, responsive breakpoints
* WordPress Sidebar Navigation - Individual pages for each settings section
* Live Preview System - TOC rendering with dynamic heading selection

= Color Palette =

Primary Colors:
* Primary Gradient: #4F46E5 → #7C3AED
* Accent Cyan: #06B6D4
* Accent Purple: #A855F7

Neutral Colors:
* Gray Scale: #F1F5F9 → #0F172A
* Success: #10B981
* Warning: #F59E0B

== Support ==

For support, feature requests, and bug reports, please visit the plugin's support forum on WordPress.org.

== Credits ==

Developed with ❤️ for the WordPress community.

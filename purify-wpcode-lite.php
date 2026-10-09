<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Purify WPCode Lite
 * Plugin URI: https://github.com/deckerweb/purify-wpcode-lite
 * Description: Remove promotional elements from WPCode Lite while preserving useful free features.
 * Version: 1.1.0
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * Author: David Decker – DECKERWEB
 * Author URI: https://github.com/deckerweb
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: purify-wpcode-lite
 * Domain Path: /languages/
 * Update URI: https://github.com/deckerweb/purify-wpcode-lite
 * GitHub Plugin URI: https://github.com/deckerweb/purify-wpcode-lite
 *
 * Copyright © 2025–2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

/** Prevent direct access */
if ( ! defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly.

if ( ! class_exists( 'DDW_Purify_WPCode_Lite' ) ) :

/**
 * Clean audited WPCode Lite admin interfaces and preserve snippet operations.
 */
class DDW_Purify_WPCode_Lite {

	/** Class constants & variables */
	private const VERSION = '1.1.0';

	/**
	 * Constructor
	 * @return void No return value.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_translations' ), 0 );
		add_action( 'admin_menu',                 array( $this, 'remove_submenus' ), 999 );
		add_action( 'plugins_loaded', array( $this, 'setup_cleanup' ), 100 );
		add_action( 'admin_init', array( $this, 'setup_cleanup' ), -100 );
		add_action( 'admin_bar_menu', array( $this, 'remove_admin_bar_nodes' ), 1300 );
		add_action( 'admin_bar_menu',             array( $this, 'add_admin_bar_nodes' ), 1301 );  // After WPCode's 999 and 1200 callbacks
		add_action( 'admin_enqueue_scripts',      array( $this, 'enqueue_admin_styles' ), 20 );  // for Admin
		add_action( 'wp_enqueue_scripts',         array( $this, 'enqueue_front_styles' ), 20 );  // for front-end
	}
	
    /**
     * Load the named cleanup rules after WPCode initializes.
     * @return void Registers audited cleanup.
     */
    public function setup_cleanup() {
        if ( ! $this->is_supported_wpcode_lite() ) return;
        require_once __DIR__ . '/includes/cleanup.php';
        DDW_PWL_Cleanup::apply();
    }

	/**
     * Load the current request's host translations, with custom catalog precedence.
     *
     * @return void Loads an available MO catalog and registers the bundled path.
     */
	public function load_translations() {
        $domain = 'purify-wpcode-lite';
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $custom = trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo';
        $bundled = __DIR__ . '/languages/' . $domain . '-' . $locale . '.mo';
        // A custom user-maintained catalog has precedence over the bundled one.
        if ( is_readable( $custom ) ) {
            load_textdomain( $domain, $custom, $locale );
        } elseif ( is_readable( $bundled ) ) {
            load_textdomain( $domain, $bundled, $locale );
        }
        load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

	/**
	 * Check if WPCode Lite is activated or not.
	 *
	 * @return bool TRUE when WPCode Lite is active, FALSE otherwise.
	 */
	private function is_wpcode_lite() {
		return class_exists( 'WPCode_Admin_Bar_Info_Lite' ) && ! class_exists( 'WPCode_Premium' );
	}
	
    /**
     * Allow structural cleanup only for source-audited Lite releases.
     *
     * @return bool Whether the loaded Lite version is supported.
     */
    private function is_supported_wpcode_lite() {
        return $this->is_wpcode_lite() && defined( 'WPCODE_VERSION' ) && in_array( WPCODE_VERSION, array( '2.3.9', '2.4.0' ), true );
    }

	/**
	 * Remove promotional submenus which have no value at all.
	 * @return void No return value.
	 */
	public function remove_submenus() {
		if ( ! $this->is_supported_wpcode_lite() ) return;
		
		remove_submenu_page( 'wpcode', 'wpcode-duplicator' );
		remove_submenu_page( 'wpcode', 'wpcode-search-replace' );
		remove_submenu_page( 'wpcode', 'wpcode-file-editor' );
		remove_submenu_page( 'wpcode', 'wpcode-pixel' );
		global $submenu;
		foreach ( isset( $submenu['wpcode'] ) ? $submenu['wpcode'] : array() as $item ) {
			$url = isset( $item[2] ) ? wp_parse_url( html_entity_decode( $item[2] ) ) : false;
			if ( is_array( $url ) && isset( $url['host'], $url['path'] ) && 'wpcode.com' === strtolower( $url['host'] ) && '/lite/' === trailingslashit( $url['path'] ) ) {
				remove_submenu_page( 'wpcode', $item[2] );
			}
		}
	}
	
	/**
	 * Remove promotional Admin Bar nodes which have no value at all.
	 *   ALSO: Remove some nodes here, only to re-add them later on but with
	 *         tweaked properties.
	 * @return void No return value.
	 */
	public function remove_admin_bar_nodes() {
		
		if ( ! $this->is_supported_wpcode_lite() ) return;
		
		global $wp_admin_bar;
		
		$wp_admin_bar->remove_node( 'wpcode-upgrade' );
		$wp_admin_bar->remove_node( 'wpcode-page-scripts-upgrade' );
		$wp_admin_bar->remove_node( 'wpcode-page-scripts' );
		$wp_admin_bar->remove_node( 'wpcode-admin-bar-info-add-new' );
		$wp_admin_bar->remove_node( 'wpcode-admin-bar-info-settings' );
		$wp_admin_bar->remove_node( 'wpcode-admin-bar-info-help' );
	}
	
	/**
	 * Add and tweak Admin Bar nodes within the existing WPCode Lite main item.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Active toolbar instance.
	 * @return void Updates authorized toolbar nodes only.
	 */
	public function add_admin_bar_nodes( $wp_admin_bar ) {
		
		if ( ! $this->is_supported_wpcode_lite() || ! current_user_can( 'wpcode_edit_snippets' ) || ! $wp_admin_bar->get_node( 'wpcode-admin-bar-info' ) ) return;
		
		$remix_icon = '<span class="icon-svg xab-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12L18.3431 17.6569L16.9289 16.2426L21.1716 12L16.9289 7.75736L18.3431 6.34315L24 12ZM2.82843 12L7.07107 16.2426L5.65685 17.6569L0 12L5.65685 6.34315L7.07107 7.75736L2.82843 12ZM9.78845 21H7.66009L14.2116 3H16.3399L9.78845 21Z"></path></svg></span> ';
		
		/** Main item */
		$main_item = $wp_admin_bar->get_node( 'wpcode-admin-bar-info' );
		if ( ! is_null( $main_item ) ) {
			$main_item->title = $remix_icon . $main_item->title;
			$main_item->meta = (array) $main_item->meta;
			$main_item->meta['class'] = trim( ( isset( $main_item->meta['class'] ) ? $main_item->meta['class'] : '' ) . ' pwl-toolbar has-icon' );
			$wp_admin_bar->add_node( $main_item );
		}
		
		/** WP Core: New Content */
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-nc-add-snippet',
			'title'  => esc_html__( 'Code Snippet', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-snippet-manager&custom=1' ) ),
			'parent' => 'new-content',
		) );
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-nc-import-snippet',
			'title'  => esc_html__( 'Import Snippet', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-tools&view=import' ) ),
			'parent' => 'new-content',
		) );
		
		/** WPCode: Main node */
		$wp_admin_bar->add_group( array(
			'id'     => 'pwl-group-addnew',
			'parent' => 'wpcode-admin-bar-info',
		) );
		
		$icon_add = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13.0001 10.9999L22.0002 10.9997L22.0002 12.9997L13.0001 12.9999L13.0001 21.9998L11.0001 21.9998L11.0001 12.9999L2.00004 13.0001L2 11.0001L11.0001 10.9999L11 2.00025L13 2.00024L13.0001 10.9999Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-addnew',
			'title'  => $icon_add . esc_html__( 'Add Snippet', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-snippet-manager&custom=1' ) ),
			'parent' => 'pwl-group-addnew',
			'meta'   => array( 'class' => 'wpcode-admin-bar-info-submenu has-icon has-separator', ),
		) );
		
		$icon_import = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10H18L12 16L6 10H11V3H13V10ZM4 19H20V12H22V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V12H4V19Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-addnew-import-snippets',
			'title'  => $icon_import . esc_html__( 'Import Snippets', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-tools&view=import' ) ),
			'parent' => 'pwl-group-addnew',
			'meta'   => array( 'class' => 'has-icon' ),
		) );
		
		$icon_export = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 19H20V12H22V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V12H4V19ZM13 9V16H11V9H6L12 3L18 9H13Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-addnew-export-snippets',
			'title'  => $icon_export . esc_html__( 'Export Snippets', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-tools&view=export' ) ),
			'parent' => 'pwl-group-addnew',
			'meta'   => array( 'class' => 'has-icon' ),
		) );
		
		$wp_admin_bar->add_group( array(
			'id'     => 'pwl-group-settings',
			'parent' => 'wpcode-admin-bar-info',
		) );
		
		$icon_settings = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5 7C5 6.17157 5.67157 5.5 6.5 5.5C7.32843 5.5 8 6.17157 8 7C8 7.82843 7.32843 8.5 6.5 8.5C5.67157 8.5 5 7.82843 5 7ZM6.5 3.5C4.567 3.5 3 5.067 3 7C3 8.933 4.567 10.5 6.5 10.5C8.433 10.5 10 8.933 10 7C10 5.067 8.433 3.5 6.5 3.5ZM12 8H20V6H12V8ZM16 17C16 16.1716 16.6716 15.5 17.5 15.5C18.3284 15.5 19 16.1716 19 17C19 17.8284 18.3284 18.5 17.5 18.5C16.6716 18.5 16 17.8284 16 17ZM17.5 13.5C15.567 13.5 14 15.067 14 17C14 18.933 15.567 20.5 17.5 20.5C19.433 20.5 21 18.933 21 17C21 15.067 19.433 13.5 17.5 13.5ZM4 16V18H12V16H4Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-settings',
			'title'  => $icon_settings . esc_html__( 'Settings', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-settings' ) ),
			'parent' => 'pwl-group-settings',
			'meta'   => array( 'class' => 'has-icon' ),
		) );
		
		$icon_tools = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.32943 3.27158C6.56252 2.8332 7.9923 3.10749 8.97927 4.09446C10.1002 5.21537 10.3019 6.90741 9.5843 8.23385L20.293 18.9437L18.8788 20.3579L8.16982 9.64875C6.84325 10.3669 5.15069 10.1654 4.02952 9.04421C3.04227 8.05696 2.7681 6.62665 3.20701 5.39332L5.44373 7.63C6.02952 8.21578 6.97927 8.21578 7.56505 7.63C8.15084 7.04421 8.15084 6.09446 7.56505 5.50868L5.32943 3.27158ZM15.6968 5.15512L18.8788 3.38736L20.293 4.80157L18.5252 7.98355L16.7574 8.3371L14.6361 10.4584L13.2219 9.04421L15.3432 6.92289L15.6968 5.15512ZM8.97927 13.2868L10.3935 14.7011L5.09018 20.0044C4.69966 20.3949 4.06649 20.3949 3.67597 20.0044C3.31334 19.6417 3.28744 19.0699 3.59826 18.6774L3.67597 18.5902L8.97927 13.2868Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-tools',
			'title'  => $icon_tools . esc_html__( 'Tools', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-tools' ) ),
			'parent' => 'pwl-group-settings',
			'meta'   => array( 'class' => 'has-icon' ),
		) );
		
		$wp_admin_bar->add_group( array(
			'id'     => 'pwl-group-links',
			'parent' => 'wpcode-admin-bar-info',
			'meta'   => array( 'class' => 'ab-sub-secondary' ),
		) );
		
		$icon_library = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM4 5V19H20V5H4ZM20 12L16.4645 15.5355L15.0503 14.1213L17.1716 12L15.0503 9.87868L16.4645 8.46447L20 12ZM6.82843 12L8.94975 14.1213L7.53553 15.5355L4 12L7.53553 8.46447L8.94975 9.87868L6.82843 12ZM11.2443 17H9.11597L12.7557 7H14.884L11.2443 17Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-library',
			'title'  => $icon_library. esc_html__( 'Snippets Library', 'purify-wpcode-lite' ),
			'href'   => 'https://library.wpcode.com/',
			'parent' => 'pwl-group-links',
			'meta'   => array( 'class' => 'has-icon', 'target' => '_blank' ),
		) );
		
		$icon_help = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.76282 17H20V5H4V18.3851L5.76282 17ZM6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM11 14H13V16H11V14ZM8.56731 8.81346C8.88637 7.20919 10.302 6 12 6C13.933 6 15.5 7.567 15.5 9.5C15.5 11.433 13.933 13 12 13H11V11H12C12.8284 11 13.5 10.3284 13.5 9.5C13.5 8.67157 12.8284 8 12 8C11.2723 8 10.6656 8.51823 10.5288 9.20577L8.56731 8.81346Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-help',
			'title'  => $icon_help . esc_html__( 'Help & Docs', 'purify-wpcode-lite' ),
			'href'   => 'https://wpcode.com/docs/',
			'parent' => 'pwl-group-links',
			'meta'   => array( 'class' => 'has-icon', 'target' => '_blank' ),
		) );
	}
	
	/**
	 * Prepare the Admin Bar inline styles. (helper function)
	 * @return string Generated toolbar stylesheet.
	 */
	private function get_adminbar_inline_styles() {
		
		$inline_css_adminbar = sprintf(
			'
				#wp-admin-bar-wpcode-upgrade,
				#wp-admin-bar-wpcode-page-scripts {
					display: none !important;
				}
				
				/* for icons */
				#wpadminbar #wp-admin-bar-wpcode-admin-bar-info .icon-svg svg {
					display: inline-block;
					margin-bottom: 3px;
					vertical-align: middle;
					width: 16px;
					height: 16px;
				}
				
				/* for separator */
				#wpadminbar #wp-admin-bar-wpcode-admin-bar-info .has-separator {
					border-top: 1px dashed rgba(255, 255, 255, 0.33);
					padding-top: 5px;
				}
			'
		);
		
		return $inline_css_adminbar;
	}
	
	/**
	 * Add CSS styling for the Admin.
	 * @return void No return value.
	 */
	public function enqueue_admin_styles() {
		
		if ( ! $this->is_supported_wpcode_lite() ) return;
		if ( is_admin_bar_showing() ) {
			wp_add_inline_style( 'admin-bar', $this->get_adminbar_inline_styles() );
		}
		$screen = get_current_screen();
		if ( ! $screen || false === strpos( $screen->id, 'wpcode' ) ) return;
		wp_enqueue_script( 'pwl-cleanup', plugins_url( 'assets/js/cleanup.js', __FILE__ ), array(), self::VERSION, true );
		wp_localize_script( 'pwl-cleanup', 'pwlCleanup', array(
			'unavailable' => __( 'This feature is unavailable in WPCode Lite.', 'purify-wpcode-lite' ),
			'libraryEmpty' => __( 'No additional free snippets are currently available for the installed plugins.', 'purify-wpcode-lite' ),
			'close' => __( 'Close', 'purify-wpcode-lite' ),
		) );
		/** Inline styles for the Admin Area */
		$inline_css_wpadmin = sprintf(
			'
				/* Known promotion fallback selectors, scoped to audited WPCode pages. */
                body.wpcode-admin-page #wpcode-notice-global-wpconsent_pixel,
                body.wpcode-admin-page .wpcode-upsell-box,
                body.wpcode-admin-page .wpcode-library-suggest-plugins,
                body.wpcode-admin-page .wpcode-toggle-testing-mode-wrap,
                body.wpcode-admin-page #wpcode_save_to_library,
                body.wpcode-admin-page .wpcode-library-item-ai-not-available,
                body.wpcode-admin-page .wpcode-button-ai-not-available,
                body.wpcode-admin-page .wpcode-library-tab-button[data-tab="my-library"],
                body.wpcode-admin-page .wpcode-admin-tabs a[href*="my_library"],
                body.wpcode-admin-page .wpcode-admin-tabs a[href*="my_favorites"],
                body.wpcode-admin-page .wpcode-admin-tabs a[href*="view=access"],
                body.wpcode-admin-page .wpcode-metabox-form:has(> .wpcode-schedule-form-fields),
                body.wpcode-admin-page #wpcode_snippet_as_file_option,
                body.wpcode-admin-page #wpcode_compress_output_option {
                    display: none !important;
                }
                body.wpcode-admin-page .wpcode-code-type[data-code-type="blocks"],
                body.wpcode-admin-page .wpcode-code-type[data-code-type="scss"] {
                    display: none !important;
                }

				/** Colors & Tweaks */
				.wpcode-admin-page .wp-list-table.wpcode-snippets .column-name a {
					color: #0073aa
				}
			
				.wp-list-table.wpcode-snippets tbody > tr:hover,
				.wp-list-table.wpcode-snippets.striped tbody > tr:hover{
					background-color: #f0f3d8;
				}
				
				.wpcode-admin-page .wpcode-list-item.wpcode-custom-snippet {
					width: %s;
				}
				
				.wpcode-admin-page:not(.wpcode-dark-mode) .wpcode-list-item.wpcode-custom-snippet {
					background-color: #ffc;
				}
				
				.wpcode-admin-page.wpcode-dark-mode .wpcode-list-item.wpcode-custom-snippet {
					color: #ffc;
				}
				
				.wpcode-code-types-list .wpcode-code-type {
					border: 3px solid transparent;
				}
				
				.wpcode-code-types-list .wpcode-code-type:hover {
					border: 3px solid #ddd;
				}
				
				.wpcode-code-type[data-code-type="php"] {
					background-color: #e6e6fa;
				}
				
				.wpcode-code-type[data-code-type="js"] {
					background-color: #ffc;
				}
				
				.wpcode-code-type[data-code-type="css"] {
					background-color: #ffe4c4;
				}
				
				.wpcode-code-type[data-code-type="html"] {
					background-color: #c1ffc1;
				}
				
				.wpcode-code-type[data-code-type="text"] {
					background-color: #f5f5f5;
				}
				
				.wpcode-admin-page .wpcode-input-title input.wpcode-input-text {
					background-color: #ffc;
					font-size: 2.25rem;
					padding: 1rem 2rem;
					height: 4rem;
				}
				
				.wpcode-admin-page .wpcode-input-title input.wpcode-input-text::placeholder {
					color: #666;
					opacity: 0.5;
				}
				
				.wpcode-metabox-title,
				.wpcode-admin-page .wpcode-metabox-button-toggle {
					background-color: #fafafa;
				}
			',
			'100%'
		);
		
		wp_add_inline_style( 'wp-admin', $inline_css_wpadmin );
		
		/** Additional inline styles for the Admin Bar */
		if ( is_admin_bar_showing() ) {
			wp_add_inline_style( 'admin-bar', $this->get_adminbar_inline_styles() );
		}
	}
	
	/**
	 * Enqueue Admin Bar styles on the front-end.
	 * @return void No return value.
	 */
	public function enqueue_front_styles() {
		
		if ( $this->is_supported_wpcode_lite() && is_admin_bar_showing() ) {
			wp_add_inline_style( 'admin-bar', $this->get_adminbar_inline_styles() );
		}
	}
	
}  // end of class

require_once __DIR__ . '/includes/integration.php';
DDW_PWL_Integration::register( __FILE__ );
new DDW_Purify_WPCode_Lite();
	
endif;


if ( ! function_exists( 'ddw_pwl_pluginrow_meta' ) ) :
	
add_filter( 'plugin_row_meta', 'ddw_pwl_pluginrow_meta', 10, 2 );
/**
* Add plugin related links to plugin page.
*
* @param array  $ddwp_meta (Default) Array of plugin meta links.
* @param string $ddwp_file File location of plugin.
* @return array $ddwp_meta (Modified) Array of plugin links/ meta.
*/
function ddw_pwl_pluginrow_meta( $ddwp_meta, $ddwp_file ) {

	if ( ! current_user_can( 'install_plugins' ) ) return $ddwp_meta;
	
	$url_nl = 'https://eepurl.com/gbAUUn';

	/** List additional links only for this plugin */
	if ( $ddwp_file === trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . basename( __FILE__ ) ) {
		$ddwp_meta[] = '<button type="button" class="button-link" data-pwl-history>' . esc_html__( 'Changelog', 'purify-wpcode-lite' ) . '</button>';
		$ddwp_meta[] = DDW_PWL_Integration::status_label();
		$ddwp_meta[] = sprintf(
			'<a class="button button-inline" href="https://ko-fi.com/deckerweb" target="_blank" rel="nofollow noopener noreferrer" title="%1$s">❤ <b>%1$s</b></a>',
			esc_html_x( 'Donate', 'Plugins page listing', 'purify-wpcode-lite' )
		);
		
		$ddwp_meta[] = sprintf(
			'<a class="button-primary" href="%1$s" target="_blank" rel="nofollow noopener noreferrer" title="%2$s">⚡ <b>%2$s</b></a>',
			esc_url( $url_nl ),
			esc_html_x( 'Join the newsletter', 'Plugins page listing', 'purify-wpcode-lite' )
		);
	}  // end if
	
	/**
     * Filter plugin-row metadata for deckerweb admin integrations.
     *
     * @since 1.0.0
     * @param array $ddwp_meta Escaped metadata links to return.
     */
    return apply_filters( 'ddw/admin_extras/pluginrow_meta', $ddwp_meta );

}  // end function

endif;
<?php # -*- coding: utf-8 -*-
/*
Plugin Name:       Purify WPCode Lite
Plugin URI:        https://github.com/deckerweb/purify-wpcode-lite
Description:       Cleanup the (free) Lite version of WPCode to make it usable. Purify the admin screens to speed up your daily coding, ahem, work :-)
Project:           Code Snippet: DDW Purify WPCode Lite
Version:           1.0.0
Author:            David Decker – DECKERWEB
Author URI:        https://deckerweb.de/
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:       purify-wpcode-lite
Domain Path:       /languages/
Requires WP:       6.7
Requires PHP:      7.4
GitHub Plugin URI: https://github.com/deckerweb/purify-wpcode-lite
GitHub Branch:     master
Copyright:         © 2025, David Decker – DECKERWEB

TESTED WITH:
Product			Versions
--------------------------------------------------------------------------------------------------------------
PHP 			8.0, 8.3
WordPress		6.7.2 ... 6.8 Beta
WPCode Lite		2.2.7
--------------------------------------------------------------------------------------------------------------

VERSION HISTORY:
Date        Version     Description
--------------------------------------------------------------------------------------------------------------
2015-04-??	1.0.0       Initial release
2025-04-02	0.0.0	    Development start
--------------------------------------------------------------------------------------------------------------
*/

/** Prevent direct access */
if ( ! defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly.

if ( ! class_exists( 'DDW_Purify_WPCode_Lite' ) ) :

class DDW_Purify_WPCode_Lite {

	/** Class constants & variables */
	private const VERSION = '1.0.0';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_head',                 array( $this, 'remove_submenus' ), 1 );
		add_action( 'wp_before_admin_bar_render', array( $this, 'remove_admin_bar_nodes' ) );
		add_action( 'admin_bar_menu',             array( $this, 'add_admin_bar_nodes' ), 999 );
		add_action( 'admin_enqueue_scripts',      array( $this, 'enqueue_admin_styles' ), 20 );  // for Admin
		add_action( 'wp_enqueue_scripts',         array( $this, 'enqueue_admin_styles' ), 20 );  // for front-end
	}
	
	/**
	 * Remove promotional submenus which have no value at all.
	 */
	public function remove_submenus() {
		remove_submenu_page( 'wpcode', 'wpcode-duplicator' );
		remove_submenu_page( 'wpcode', 'wpcode-search-replace' );
		remove_submenu_page( 'wpcode', 'wpcode-file-editor' );
		remove_submenu_page( 'wpcode', 'wpcode-pixel' );
		remove_submenu_page( 'wpcode', 'https://wpcode.com/lite/?utm_source=liteplugin&utm_medium=dashboard&utm_campaign=admin-side-menu' );
	}
	
	/**
	 * Remove promotional Admin Bar nodes which have no value at all.
	 *   ALSO: Remove some nodes here, only to re-add them later on but with
	 *         tweaked properties.
	 */
	public function remove_admin_bar_nodes() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_node( 'wpcode-upgrade' );
		$wp_admin_bar->remove_node( 'wpcode-admin-bar-info-add-new' );
		$wp_admin_bar->remove_node( 'wpcode-admin-bar-info-settings' );
		$wp_admin_bar->remove_node( 'wpcode-admin-bar-info-help' );
		$wp_admin_bar->remove_node( 'wpcode-page-scripts' );
	}
	
	/**
	 * Add and tweak Admin Bar nodes within the existing WPCode Lite main item.
	 */
	public function add_admin_bar_nodes( $wp_admin_bar ) {
		
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
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-addnew',
			'title'  => esc_html__( '+ Add Snippet', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-snippet-manager&custom=1' ) ),
			'parent' => 'pwl-group-addnew',
		) );
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-addnew-import-snippets',
			'title'  => esc_html__( 'Import Snippets', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-tools&view=import' ) ),
			'parent' => 'pwl-group-addnew',
		) );
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-addnew-export-snippets',
			'title'  => esc_html__( 'Export Snippets', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-tools&view=export' ) ),
			'parent' => 'pwl-group-addnew',
		) );
		
		$wp_admin_bar->add_group( array(
			'id'     => 'pwl-group-settings',
			'parent' => 'wpcode-admin-bar-info',
		) );
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-settings',
			'title'  => esc_html__( 'Settings', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-settings' ) ),
			'parent' => 'pwl-group-settings',
		) );
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-tools',
			'title'  => esc_html__( 'Tools', 'purify-wpcode-lite' ),
			'href'   => esc_url( admin_url( 'admin.php?page=wpcode-tools' ) ),
			'parent' => 'pwl-group-settings',
		) );
		
		$wp_admin_bar->add_node( array(
			'id'     => 'pwl-help',
			'title'  => esc_html__( 'Help & Docs', 'purify-wpcode-lite' ),
			'href'   => 'https://wpcode.com/docs/',
			'parent' => 'pwl-group-settings',
			'meta'   => array( 'target' => '_blank' ),
		) );
	}
	
	/**
	 * Add CSS styling for the Admin.
	 */
	public function enqueue_admin_styles() {
		
		/**
		 * For WordPress Admin Area – create the styles
		 *   Style handle: 'wp-admin' (WordPress Core)
		 */
		$inline_css = sprintf(
			'
				/** Remove stuff */
				#wpcode-notice-consider-upgrading,
				.wp-submenu li.wpcode-sidebar-upgrade-pro,
				.wpcode-toggle-testing-mode-wrap,
				.wpcode-admin-tabs .wpcode_pro_type_lite,
				.wpcode-items-list-category .wpcode-library-item-ai,
				.wpcode-library-tab-button[data-tab="plugin-snippets"],
				.wpcode-library-tab-button[data-tab="my-library"],
				.wpcode-admin-tabs li a[href*="my_library"],
				.wpcode-admin-tabs li a[href*="my_favorites"],
				.wpcode-admin-tabs li a[href*="view=errors"],
				.wpcode-admin-tabs li a[href*="view=access"],
				.wpcode-admin-page #footer-left,
				#wp-admin-bar-wpcode-upgrade,
				#wpbody-content .wpcode-button-ai-generate,
				#wpcode_save_to_library,
				.wpcode-metabox-form:has(div.wpcode-schedule-form-fields),
				.wpcode-metabox:has(div div.wpcode-device-type-picker),
				.wpcode-metabox:has(div div.wpcode-revisions-list-area),
				.wpcode-code-type[data-code-type="blocks"],
				.wpcode-code-type[data-code-type="scss"],
				.wpcode-smart-tags.wpcode-smart-tags-unavailable,
				.plugins-php tr[data-slug="insert-headers-and-footers"] .wpcodepro {
					display: none !important;
				}
				
				/** Colors & Tweaks */
				.wpcode-admin-page .wp-list-table.wpcode-snippets .column-name a {
					color: #0073aa
				}
			
				.wp-list-table.wpcode-snippets tbody > tr:hover,
				.wp-list-table.wpcode-snippets.striped tbody > tr:hover{
					background-color: #F0F3D8;
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
				
				.wpcode-code-type[data-code-type="php"] {
					background-color: #E6E6FA;
				}
				
				.wpcode-code-type[data-code-type="js"] {
					background-color: #ffc;
				}
				
				.wpcode-code-type[data-code-type="css"] {
					background-color: #FFA07A;
				}
				
				.wpcode-code-type[data-code-type="html"] {
					background-color: #98FB98;
				}
				
				.wpcode-admin-page .wpcode-input-title input.wpcode-input-text {
					background-color: #ffc;
					font-size: 2.25rem;
					padding: 1rem 2rem;
					height: 4rem;
				}
			',
			'100%'
		);
		
		/** Add inline styles to the stylesheet */
		wp_add_inline_style( 'wp-admin', $inline_css );
	}
	
}  // end of class

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
	
	/** Get current user */
	$user = wp_get_current_user();
	
	/** Build Newsletter URL */
	$url_nl = sprintf(
		'https://deckerweb.us2.list-manage.com/subscribe?u=e09bef034abf80704e5ff9809&amp;id=380976af88&amp;MERGE0=%1$s&amp;MERGE1=%2$s',
		esc_attr( $user->user_email ),
		esc_attr( $user->user_firstname )
	);
	
	/** List additional links only for this plugin */
	if ( $ddwp_file === trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . basename( __FILE__ ) ) {
		$ddwp_meta[] = sprintf(
			'<a class="button button-inline" href="https://ko-fi.com/deckerweb" target="_blank" rel="nofollow noopener noreferrer" title="%1$s">❤ <b>%1$s</b></a>',
			esc_html_x( 'Donate', 'Plugins page listing', 'purify-wpcode-lite' )
		);
		
		$ddwp_meta[] = sprintf(
			'<a class="button-primary" href="%1$s" target="_blank" rel="nofollow noopener noreferrer" title="%2$s">⚡ <b>%2$s</b></a>',
			$url_nl,
			esc_html_x( 'Join our Newsletter', 'Plugins page listing', 'purify-wpcode-lite' )
		);
	}  // end if
	
	return apply_filters( 'ddw/admin_extras/pluginrow_meta', $ddwp_meta );

}  // end function

endif;
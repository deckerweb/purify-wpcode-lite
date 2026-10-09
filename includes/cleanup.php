<?php
/** Named, reversible cleanup rules. No WPCode files or saved settings are changed. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Remove named promotional callbacks without altering saved WPCode data. */
final class DDW_PWL_Cleanup {
    /**
     * Return stable identifiers and their exact hook callback contracts.
     *
     * @return array Named cleanup rules; each defines hook, callback and priority or method.
     */
    public static function rules() {
        return array(
            'library-connect-prompt' => array( 'admin_init', 'wpcode_maybe_add_library_connect_notice', 10 ),
            'upgrade-top-bar' => array( 'wpcode_admin_page', 'wpcode_maybe_add_lite_top_bar_notice', 4 ),
            'headers-footers-promo' => array( 'wpcode_admin_page_content_wpcode-headers-footers', 'wpcode_headers_footers_bottom_notice', 250 ),
            'review-prompt' => array( 'admin_init', 'WPCode_Review', 'review_request' ),
            'review-footer' => array( 'admin_footer_text', 'WPCode_Review', 'admin_footer' ),
            'remote-marketing-fetch' => array( 'wpcode_admin_notifications_update', 'WPCode_Notifications', 'update' ),
            'export-upsell' => array( 'admin_init', 'WPCode_Features_Notices', 'maybe_show_notices' ),
            'plugin-suggestions' => array( 'admin_init', 'WPCode_Suggested_Plugins', 'maybe_suggest_plugins' ),
            'post-editor-upsell' => array( 'add_meta_boxes', 'WPCode_Metabox_Snippets_Lite', 'register_metabox' ),
            'upgrade-menu' => array( 'admin_menu', 'WPCode_Admin_Page_Loader_Lite', 'add_upgrade_menu_item' ),
            'upgrade-menu-class' => array( 'admin_head', 'WPCode_Admin_Page_Loader_Lite', 'adjust_pro_menu_item_class' ),
            'upgrade-menu-style' => array( 'admin_head', 'WPCode_Admin_Page_Loader_Lite', 'admin_menu_styles' ),
        );
    }

    /** Apply audited promotional rules without changing data.
     * @return void Removes named callbacks and registers host filters.
     */
    public static function apply() {
        foreach ( self::rules() as $rule ) {
            if ( is_int( $rule[2] ) ) {
                remove_action( $rule[0], $rule[1], $rule[2] );
            } else {
                self::remove_object_callback( $rule[0], $rule[1], $rule[2] );
            }
        }
        add_filter( 'wpcode_admin_notifications_has_access', '__return_false', 100 );
        if ( defined( 'WPCODE_PLUGIN_BASENAME' ) ) {
            add_filter( 'plugin_action_links_' . WPCODE_PLUGIN_BASENAME, array( __CLASS__, 'plugin_links' ), 100 );
        }
        add_action( 'wpcode_before_admin_pages_loaded', array( __CLASS__, 'replace_pages' ), 100 );
    }

    /**
     * Remove only an exact upstream object-class and method pair.
     * @param string $hook WordPress hook name.
     * @param string $class Exact upstream class name.
     * @param string $method Callback method name.
     * @return void Removes matching callbacks at their registered priorities.
     */
    private static function remove_object_callback( $hook, $class, $method ) {
        global $wp_filter;
        if ( empty( $wp_filter[ $hook ] ) || ! isset( $wp_filter[ $hook ]->callbacks ) ) { return; }
        foreach ( $wp_filter[ $hook ]->callbacks as $priority => $callbacks ) {
            foreach ( $callbacks as $entry ) {
                $callback = $entry['function'];
                if ( is_array( $callback ) && is_object( $callback[0] ) && get_class( $callback[0] ) === $class && $callback[1] === $method ) {
                    remove_filter( $hook, $callback, $priority );
                }
            }
        }
    }

    /**
     * Keep functional plugin links and omit the premium sales link.
     * @param array $links Plugin-specific action links.
     * @return array Filtered action links.
     */
    public static function plugin_links( $links ) {
        unset( $links['wpcodepro'] );
        return $links;
    }

    /**
     * Replace audited page classes through WPCode's intended registry hook.
     * @param array $pages Upstream page registry at hook dispatch.
     * @return void Updates only unchanged upstream entries in the live loader.
     */
    public static function replace_pages( $pages ) {
        if ( ! defined( 'WPCODE_VERSION' ) || ! in_array( WPCODE_VERSION, array( '2.3.9', '2.4.0' ), true ) || ! function_exists( 'wpcode' ) ) { return; }
        $loader = wpcode()->admin_page_loader;
        if ( ! $loader instanceof WPCode_Admin_Page_Loader_Lite ) { return; }
        require_once __DIR__ . '/pages-wpcode.php';
        $replacements = array(
            'settings' => array( 'WPCode_Admin_Page_Settings', 'DDW_PWL_Settings_Page' ),
            'snippet_manager' => array( 'WPCode_Admin_Page_Snippet_Manager', 'DDW_PWL_Snippet_Page' ),
            'headers_footers' => array( 'WPCode_Admin_Page_Headers_Footers', 'DDW_PWL_Headers_Page' ),
        );
        foreach ( $replacements as $key => $classes ) {
            // Do not override a different plugin's page replacement.
            if ( isset( $loader->pages[ $key ] ) && $loader->pages[ $key ] === $classes[0] ) {
                $loader->pages[ $key ] = $classes[1];
            }
        }
    }
}

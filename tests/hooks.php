<?php
/** php tests/hooks.php /path/to/wordpress /path/to/insert-headers-and-footers [version] */
define( 'ABSPATH', rtrim( $argv[1], '/' ) . '/' );
define( 'WPINC', 'wp-includes' );
define( 'WPCODE_VERSION', isset( $argv[3] ) ? $argv[3] : '2.3.9' );
define( 'WPCODE_PLUGIN_BASENAME', 'insert-headers-and-footers/ihaf.php' );
require ABSPATH . WPINC . '/plugin.php';
function is_admin() { return false; }
function wp_doing_cron() { return false; }
function __return_false() { return false; }
function __( $text, $domain = '' ) { return $text; }
function esc_html__( $text, $domain = '' ) { return htmlspecialchars( $text ); }
function is_admin_bar_showing() { return true; }
function current_user_can( $cap ) { return $GLOBALS['test_can_edit']; }
function get_current_screen() { return (object) array( 'id' => 'toplevel_page_wpcode' ); }
function plugins_url( $path, $file ) { return $path; }
function wp_enqueue_script() {}
function wp_localize_script() {}
function wp_add_inline_style( $handle, $css ) { $GLOBALS['test_css'][$handle] = $css; }
function get_user_locale() { return 'en_US'; }
function apply_test( $value, $label ) {
    if ( ! $value ) { throw new RuntimeException( $label ); }
    echo "PASS: $label\n";
}
function wp_parse_url( $url ) { return parse_url( $url ); }
function trailingslashit( $path ) { return rtrim( $path, '/' ) . '/'; }
function remove_submenu_page( $parent, $slug ) {
    foreach ( $GLOBALS['submenu'][$parent] as $key => $item ) {
        if ( $item[2] === $slug ) { unset( $GLOBALS['submenu'][$parent][$key] ); }
    }
}
class WPCode_Admin_Bar_Info_Lite {}
function wpcode() { return $GLOBALS['test_wpcode']; }
$base = rtrim( $argv[2], '/' ) . '/includes/admin/';
require $base . 'pages/trait-wpcode-library-refresh-button.php';
require $base . 'pages/trait-wpcode-revisions-display.php';
require $base . 'pages/trait-wpcode-wpconsent-notice.php';
require $base . 'pages/trait-wpcode-my-library-markup.php';
require $base . 'pages/class-wpcode-admin-page.php';
require $base . 'pages/class-wpcode-admin-page-settings.php';
require $base . 'pages/class-wpcode-admin-page-snippet-manager.php';
require $base . 'pages/class-wpcode-admin-page-headers-footers.php';
require $base . 'class-wpcode-admin-page-loader.php';
require rtrim( $argv[2], '/' ) . '/includes/lite/admin/class-wpcode-admin-page-loader-lite.php';
require $base . 'class-wpcode-metabox-snippets.php';
require rtrim( $argv[2], '/' ) . '/includes/lite/admin/class-wpcode-metabox-snippets-lite.php';
require $base . 'class-wpcode-notifications.php';
$metabox = ( new ReflectionClass( 'WPCode_Metabox_Snippets_Lite' ) )->newInstanceWithoutConstructor();
$metabox->hooks();
$notifications = new WPCode_Notifications();
require $base . 'class-wpcode-review.php';
require $base . 'class-wpcode-features-notices.php';
require $base . 'class-wpcode-suggested-plugins.php';
$loader = ( new ReflectionClass( 'WPCode_Admin_Page_Loader_Lite' ) )->newInstanceWithoutConstructor();
$loader->pages = array( 'settings' => 'WPCode_Admin_Page_Settings', 'snippet_manager' => 'WPCode_Admin_Page_Snippet_Manager', 'headers_footers' => 'WPCode_Admin_Page_Headers_Footers' );
$GLOBALS['test_wpcode'] = (object) array( 'admin_page_loader' => $loader );
$loader->hooks();
$loader->hidden_pages = array();
function wpcode_maybe_add_library_connect_notice() {}
function wpcode_maybe_add_lite_top_bar_notice() {}
function wpcode_headers_footers_bottom_notice() {}
add_action( 'admin_init', 'wpcode_maybe_add_library_connect_notice' );
add_action( 'wpcode_admin_page', 'wpcode_maybe_add_lite_top_bar_notice', 4 );
add_action( 'wpcode_admin_page_content_wpcode-headers-footers', 'wpcode_headers_footers_bottom_notice', 250 );
$review = new WPCode_Review();
$suggestions = new WPCode_Suggested_Plugins();
$features = new WPCode_Features_Notices();
$important = static function () {};
add_action( 'wpcode_admin_notices', $important );
require dirname( __DIR__ ) . '/purify-wpcode-lite.php';
do_action( 'plugins_loaded' );
if ( ! in_array( WPCODE_VERSION, array( '2.3.9', '2.4.0' ), true ) ) {
    apply_test( has_action( 'admin_init', 'wpcode_maybe_add_library_connect_notice' ) !== false, 'Unknown version keeps upstream callbacks' );
    apply_test( $loader->pages['settings'] === 'WPCode_Admin_Page_Settings', 'Unknown version keeps upstream pages' );
    ( new DDW_Purify_WPCode_Lite() )->enqueue_admin_styles();
    apply_test( empty( $GLOBALS['test_css'] ), 'Unknown version adds no cleanup CSS' );
    exit;
}

apply_test( ! has_action( 'add_meta_boxes', array( $metabox, 'register_metabox' ) ), 'Premium post metabox not registered' );
apply_test( ! has_action( 'wpcode_admin_notifications_update', array( $notifications, 'update' ) ), 'Remote feed refresh callback removed' );
apply_test( ! has_action( 'admin_init', 'wpcode_maybe_add_library_connect_notice' ), 'Library prompt removed before admin_init' );
apply_test( ! has_action( 'admin_init', array( $review, 'review_request' ) ), 'Review request removed' );
apply_test( ! has_filter( 'admin_footer_text', array( $review, 'admin_footer' ) ), 'Review footer removed' );
apply_test( ! has_action( 'admin_init', array( $suggestions, 'maybe_suggest_plugins' ) ), 'Suggestions removed' );
apply_test( has_action( 'wp_ajax_wpcode_install_plugin', array( $suggestions, 'install_plugin' ) ) !== false, 'Explicit install API preserved' );
apply_test( ! has_action( 'admin_init', array( $features, 'maybe_show_notices' ) ), 'Export upsell removed' );
apply_test( has_action( 'wpcode_admin_notices', $important ) !== false, 'Functional notices preserved' );
apply_test( ! has_action( 'admin_menu', array( $loader, 'add_upgrade_menu_item' ) ), 'Upgrade registration removed' );
apply_test( apply_filters( 'wpcode_admin_notifications_has_access', true ) === false, 'Remote inbox disabled' );
apply_test( DDW_PWL_Cleanup::plugin_links( array( 'wpcodepro' => 'promo', 'settings' => 'settings', 'deactivate' => 'deactivate' ) ) === array( 'settings' => 'settings', 'deactivate' => 'deactivate' ), 'Only plugin upgrade link removed' );
do_action( 'wpcode_before_admin_pages_loaded', $loader->pages );
if ( in_array( WPCODE_VERSION, array( '2.3.9', '2.4.0' ), true ) ) {
    apply_test( $loader->pages['settings'] === 'DDW_PWL_Settings_Page', 'Audited page replacement applied' );
    $page = ( new ReflectionClass( 'DDW_PWL_Snippet_Page' ) )->newInstanceWithoutConstructor();
    apply_test( ( new ReflectionMethod( $page, 'get_input_row_shortcode_attributes' ) )->getDeclaringClass()->getName() === 'WPCode_Admin_Page_Snippet_Manager', 'Free shortcode attributes preserved' );
    apply_test( ( new ReflectionMethod( $page, 'submit_listener' ) )->getDeclaringClass()->getName() === 'WPCode_Admin_Page_Snippet_Manager', 'Saving logic inherited unchanged' );
    ob_start(); $page->get_input_row_schedule(); $page->field_device_type(); $page->field_code_revisions();
    apply_test( ob_get_clean() === '', 'Premium teaser output suppressed' );
    $settings = ( new ReflectionClass( 'DDW_PWL_Settings_Page' ) )->newInstanceWithoutConstructor();
    apply_test( strpos( $settings->get_license_key_input(), '<input' ) === false, 'License install form removed' );
    $loader->pages['settings'] = 'Third_Party_Settings';
    DDW_PWL_Cleanup::replace_pages( $loader->pages );
    apply_test( $loader->pages['settings'] === 'Third_Party_Settings', 'Third-party page replacement respected' );
} else {
    apply_test( $loader->pages['settings'] === 'WPCode_Admin_Page_Settings', 'Unaudited version keeps upstream pages' );
}
$GLOBALS['submenu']['wpcode'] = array(
    array( 'promo', '', 'https://wpcode.com/lite/?utm_source=changed' ),
    array( 'safe', '', 'https://example.com/lite/' ),
    array( 'tools', '', 'wpcode-tools' ),
    array( 'duplicator', '', 'wpcode-duplicator' ),
);
$purify = new DDW_Purify_WPCode_Lite();
$purify->enqueue_admin_styles();
apply_test( strpos( $GLOBALS['test_css']['wp-admin'], 'view=errors' ) === false, 'Free error handling tab stays visible' );
apply_test( strpos( $GLOBALS['test_css']['wp-admin'], '.wpcode-list-item-disabled' ) === false, 'No blanket hiding of disabled locations' );
$purify->remove_submenus();
apply_test( array_values( array_column( $GLOBALS['submenu']['wpcode'], 2 ) ) === array( 'https://example.com/lite/', 'wpcode-tools' ), 'Menu cleanup ignores UTM changes and unrelated URLs' );
$GLOBALS['test_can_edit'] = false;
$bar = new class {
    public function get_node( $id ) { throw new RuntimeException( 'Unauthorized toolbar access' ); }
};
$purify->add_admin_bar_nodes( $bar );
apply_test( true, 'No toolbar additions without snippet capability' );
eval( 'class WPCode_Premium {}' );
$before = $GLOBALS['submenu']['wpcode'];
$purify->remove_submenus();
apply_test( $before === $GLOBALS['submenu']['wpcode'], 'Premium activation leaves menus alone' );

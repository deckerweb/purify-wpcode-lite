<?php
/** Integrate shared deckerweb services without a new branding settings page. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Register the shared catalog, updater and plugin-row history. */
final class DDW_PWL_Integration {
    /** @var string Absolute plugin entry point. */
    private static $main_file;

    /**
     * Register services before shared runtime election.
     * @param string $main_file Absolute host plugin entry point.
     * @return void Registers shared services and admin-only history output.
     */
    public static function register( $main_file ) {
        self::$main_file = $main_file;
        require_once __DIR__ . '/deckerweb-plugin-library/bootstrap.php';
        deckerweb_library_register_v2( $main_file, array(), __DIR__ . '/deckerweb-plugin-library' );
        add_action( 'init', array( __CLASS__, 'updater' ), 5 );
        add_action( 'activate_plugin', array( __CLASS__, 'allow_paused_activation' ), -50, 2 );
        add_action( 'admin_footer', array( __CLASS__, 'history' ) );
    }

    /**
     * Keep this safely paused host activatable without its optional target.
     * Runs after Library election; retain its guard for every other plugin.
     * @param string $plugin Target plugin basename.
     * @param bool $network Whether activation is network-wide.
     * @return void Rebinds only the elected Library activation guard.
     */
    public static function allow_paused_activation( $plugin, $network ) {
        if ( $plugin !== plugin_basename( self::$main_file ) ) { return; }
        $runtime = $GLOBALS['deckerweb_library_runtime_v1'] ?? null;
        if ( ! is_object( $runtime ) || ! is_callable( array( $runtime, 'activation_guard' ) ) ) { return; }
        $callback = array( $runtime, 'activation_guard' );
        $priority = has_action( 'activate_plugin', $callback );
        if ( false === $priority ) { return; }
        remove_action( 'activate_plugin', $callback, $priority );
        $host = plugin_basename( self::$main_file );
        add_action( 'activate_plugin', static function( $target, $network_wide ) use ( $runtime, $host ) {
            if ( $target !== $host ) { $runtime->activation_guard( $target, $network_wide ); }
        }, $priority, 2 );
    }

    /**
     * Bind the pinned updater to this host after its translations initialize.
     * @return void Registers update hooks; configuration failures remain local.
     */
    public static function updater() {
        if ( ! is_admin() && ! wp_doing_cron() && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) { return; }
        if ( ! class_exists( '\Deckerweb\GitHubReleaseUpdater\V2\Updater' ) ) {
            require_once __DIR__ . '/deckerweb-github-release-updater-v2.php';
        }
        if ( ! defined( '\Deckerweb\GitHubReleaseUpdater\V2\Updater::SUPPORTS_HOST_TRANSLATIONS' ) ) {
            add_action( 'admin_notices', array( __CLASS__, 'updater_notice' ) );
            return;
        }
        $language = 0 === strpos( determine_locale(), 'de' ) ? 'de' : 'en';
        try {
            $updater = new \Deckerweb\GitHubReleaseUpdater\V2\Updater(
                self::$main_file, 'https://github.com/deckerweb/purify-wpcode-lite',
                __( 'Purify WPCode Lite', 'purify-wpcode-lite' ),
                __( 'Remove promotional elements from WPCode Lite while preserving useful free features.', 'purify-wpcode-lite' ),
                array(
                    'icons' => array( 'svg' => plugins_url( '../assets/icon.svg', __FILE__ ), '1x' => plugins_url( '../assets/icon-128x128.png', __FILE__ ), '2x' => plugins_url( '../assets/icon-256x256.png', __FILE__ ) ),
                    'banners' => array( 'low' => plugins_url( '../assets/banner-772x250-' . $language . '.png', __FILE__ ), 'high' => plugins_url( '../assets/banner-1544x500-' . $language . '.png', __FILE__ ) ),
                ),
                array( 'translate' => require __DIR__ . '/updater-translations.php' )
            );
            $updater->register();
            add_filter( 'upgrader_source_selection', array( __CLASS__, 'validate_package' ), 30, 4 );
        } catch ( \Throwable $error ) {
            add_action( 'admin_notices', array( __CLASS__, 'updater_notice' ) );
        }
    }

    /**
     * Report an incompatible updater on the host's plugin management screen.
     * @return void Outputs an escaped notice only to plugin administrators.
     */
    public static function updater_notice() {
        $screen = get_current_screen();
        if ( ! current_user_can( 'update_plugins' ) || ! $screen || 'plugins' !== $screen->base ) { return; }
        echo '<div class="notice notice-warning"><p>' . esc_html__( 'Purify WPCode Lite could not initialize its updater. Check the installed deckerweb Updater copies.', 'purify-wpcode-lite' ) . '</p></div>';
    }

    /**
     * Validate host identity and requirements after upstream archive normalization.
     * @param string|WP_Error $source Extracted candidate directory or previous error.
     * @param string $remote_source Original extracted path, unused.
     * @param object $upgrader Native plugin upgrader instance.
     * @param array $context Update operation metadata.
     * @return string|WP_Error Validated directory or localized rejection.
     */
    public static function validate_package( $source, $remote_source, $upgrader, $context ) {
        $basename = plugin_basename( self::$main_file );
        if ( is_wp_error( $source ) || ! is_array( $context ) || ( $context['plugin'] ?? '' ) !== $basename
            || ( isset( $context['type'] ) && 'plugin' !== $context['type'] )
            || ( isset( $context['action'] ) && 'update' !== $context['action'] ) ) { return $source; }
        if ( ! isset( $context['type'], $context['action'] ) && ! ( $upgrader instanceof \Plugin_Upgrader && true === $upgrader->bulk ) ) { return $source; }
        global $wp_filesystem, $wp_version;
        $file = trailingslashit( $source ) . 'purify-wpcode-lite.php';
        $valid = is_object( $wp_filesystem ) && $wp_filesystem->is_file( $file );
        $size = $valid ? $wp_filesystem->size( $file ) : false;
        $text = $valid && is_int( $size ) && $size > 0 && $size <= 262144 ? $wp_filesystem->get_contents( $file ) : false;
        $headers = array();
        if ( is_string( $text ) ) {
            foreach ( array( 'Plugin Name', 'Version', 'Requires at least', 'Requires PHP', 'Text Domain', 'Update URI' ) as $header ) {
                preg_match( '/^[ \t\/*#@]*' . preg_quote( $header, '/' ) . ':\s*([^\r\n]*)/mi', substr( $text, 0, 8192 ), $match );
                $headers[ $header ] = isset( $match[1] ) ? trim( $match[1] ) : '';
            }
        }
        $offers = get_site_transient( 'update_plugins' );
        $offer = is_object( $offers ) && isset( $offers->response[ $basename ] ) ? $offers->response[ $basename ] : null;
        $version = is_object( $offer ) && isset( $offer->new_version ) ? $offer->new_version : '';
        $installed = get_file_data( self::$main_file, array( 'Version' => 'Version' ) );
        $valid = $headers && ( $headers['Plugin Name'] ?? '' ) === 'Purify WPCode Lite'
            && ( $headers['Text Domain'] ?? '' ) === 'purify-wpcode-lite'
            && ( $headers['Update URI'] ?? '' ) === 'https://github.com/deckerweb/purify-wpcode-lite'
            && preg_match( '/^\d+\.\d+\.\d+$/D', $version ) && $headers['Version'] === $version
            && version_compare( $version, $installed['Version'], '>' )
            && preg_match( '/^\d+\.\d+(?:\.\d+)?$/D', $headers['Requires PHP'] )
            && preg_match( '/^\d+\.\d+(?:\.\d+)?$/D', $headers['Requires at least'] )
            && version_compare( PHP_VERSION, $headers['Requires PHP'], '>=' )
            && version_compare( $wp_version, $headers['Requires at least'], '>=' );
        return $valid ? $source : new \WP_Error( 'pwl_invalid_update', __( 'The update package does not match Purify WPCode Lite or its supported requirements.', 'purify-wpcode-lite' ) );
    }

    /**
     * Describe a safely paused cleanup in the plugin's existing metadata row.
     * @return string Escaped status label, or empty string for an audited active host.
     */
    public static function status_label() {
        if ( class_exists( 'WPCode_Premium' ) ) {
            return '<span>' . esc_html__( 'Cleanup is inactive while WPCode Pro is active.', 'purify-wpcode-lite' ) . '</span>';
        }
        if ( ! class_exists( 'WPCode_Admin_Bar_Info_Lite' ) ) {
            return '<span>' . esc_html__( 'Activate WPCode Lite to enable cleanup.', 'purify-wpcode-lite' ) . '</span>';
        }
        if ( ! defined( 'WPCODE_VERSION' ) || ! in_array( WPCODE_VERSION, array( '2.3.9', '2.4.0' ), true ) ) {
            return '<span>' . esc_html__( 'Cleanup is paused for this unverified WPCode Lite version.', 'purify-wpcode-lite' ) . '</span>';
        }
        return '';
    }

    /**
     * Render a bounded, escaped history dialog on the existing plugin list only.
     * @return void Outputs localized accessible history; no branding page is created.
     */
    public static function history() {
        $screen = get_current_screen();
        if ( ! current_user_can( 'activate_plugins' ) || ! $screen || 'plugins' !== $screen->base ) { return; }
        require_once __DIR__ . '/deckerweb-changelog-v1.php';
        $text = require __DIR__ . '/history.php';
        echo '<dialog id="pwl-history" aria-labelledby="pwl-history-title"><h2 id="pwl-history-title">' . esc_html__( 'Purify WPCode Lite changelog', 'purify-wpcode-lite' ) . '</h2>';
        echo Deckerweb_Changelog_Renderer_V1::render( $text ); // Escapes all source text internally.
        echo '<button type="button" data-pwl-history-close>' . esc_html__( 'Close', 'purify-wpcode-lite' ) . '</button></dialog>';
        echo '<style>#pwl-history{max-width:40rem;width:calc(100% - 3rem);max-height:80vh;overflow:auto;border:1px solid #ccd0d4;border-radius:8px;padding:1.5rem}#pwl-history::backdrop{background:rgba(0,0,0,.45)}#pwl-history .ddw-changelog-badge{display:inline-block;border-radius:4px;padding:2px 6px;margin-right:8px;background:#eef0f4;font-weight:600}#pwl-history .ddw-changelog-new{background:#e4f2e8}#pwl-history .ddw-changelog-improved{background:#e6effa}#pwl-history .ddw-changelog-fixed{background:#fff0df}#pwl-history .ddw-changelog-version{display:flex;align-items:baseline;gap:1rem}#pwl-history button:focus-visible{outline:2px solid #2271b1;outline-offset:3px}</style>';
        wp_enqueue_script( 'pwl-history', plugins_url( '../assets/js/history.js', __FILE__ ), array(), '1.1.0', true );
    }
}

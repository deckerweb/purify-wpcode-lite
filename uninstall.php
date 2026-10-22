<?php
/** Remove only shared Library temporary data when this is its final host. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
require_once __DIR__ . '/includes/deckerweb-plugin-library/lifecycle.php';
deckerweb_library_uninstall_v3( __DIR__ . '/purify-wpcode-lite.php' );

/** Clear only this repository's updater metadata without registering update hooks. */
$deckerweb_updater_class = require __DIR__ . '/includes/deckerweb-github-release-updater-v2.php';
if ( is_string( $deckerweb_updater_class ) && method_exists( $deckerweb_updater_class, 'clear_repository_cache' ) ) {
    $deckerweb_updater_class::clear_repository_cache( 'https://github.com/deckerweb/purify-wpcode-lite' );
}

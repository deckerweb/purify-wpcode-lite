<?php
/** Copy into the host adapter and replace the purify-wpcode-lite textdomain with its literal domain. */
defined( 'ABSPATH' ) || exit;
/**
 * Resolve an updater source string in the host textdomain.
 * @param string $message English updater message.
 * @return string Translated host message, or original source text.
 */
return static function ( string $message ): string {
    // Literal calls let the host's normal translation extractor collect every source string.
    switch ( $message ) {
        case 'Private mode must be boolean.':
            return __( 'Private mode must be boolean.', 'purify-wpcode-lite' );
        case 'Invalid authentication provider.':
            return __( 'Invalid authentication provider.', 'purify-wpcode-lite' );
        case 'The plugin must be installed in a stable slug directory.':
            return __( 'The plugin must be installed in a stable slug directory.', 'purify-wpcode-lite' );
        case 'Invalid GitHub repository URL.':
            return __( 'Invalid GitHub repository URL.', 'purify-wpcode-lite' );
        case 'The private update could not be authorized. Check the repository credentials and refresh updates.':
            return __( 'The private update could not be authorized. Check the repository credentials and refresh updates.', 'purify-wpcode-lite' );
        case 'Could not create the update download file.':
            return __( 'Could not create the update download file.', 'purify-wpcode-lite' );
        case 'The private update download failed. Check credentials and try again.':
            return __( 'The private update download failed. Check credentials and try again.', 'purify-wpcode-lite' );
        case 'Could not access the update filesystem.':
            return __( 'Could not access the update filesystem.', 'purify-wpcode-lite' );
        case 'GitHub release does not contain the plugin main file.':
            return __( 'GitHub release does not contain the plugin main file.', 'purify-wpcode-lite' );
        case 'Could not prepare the GitHub release package.':
            return __( 'Could not prepare the GitHub release package.', 'purify-wpcode-lite' );
        case 'See the release on GitHub.':
            return __( 'See the release on GitHub.', 'purify-wpcode-lite' );
        case 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.':
            return __( 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.', 'purify-wpcode-lite' );
        case 'Manage installed plugins':
            return __( 'Manage installed plugins', 'purify-wpcode-lite' );
        case 'Dismiss this notice':
            return __( 'Dismiss this notice', 'purify-wpcode-lite' );
        case 'Permission denied.':
            return __( 'Permission denied.', 'purify-wpcode-lite' );
        case 'Public catalog callbacks require both providers and cannot be used in private mode.':
            return __( 'Public catalog callbacks require both providers and cannot be used in private mode.', 'purify-wpcode-lite' );
        case 'The approved release could not be verified.':
            return __( 'The approved release could not be verified.', 'purify-wpcode-lite' );
        case 'The approved release changed. Refresh updates first.':
            return __( 'The approved release changed. Refresh updates first.', 'purify-wpcode-lite' );
        case 'Catalog diagnostics require a public catalog provider.':
            return __( 'Catalog diagnostics require a public catalog provider.', 'purify-wpcode-lite' );
        case 'Catalog approval':
            return __( 'Catalog approval', 'purify-wpcode-lite' );
        case 'Package download':
            return __( 'Package download', 'purify-wpcode-lite' );
        case 'Checksum verification':
            return __( 'Checksum verification', 'purify-wpcode-lite' );
        case 'Archive verification':
            return __( 'Archive verification', 'purify-wpcode-lite' );
        case 'The update server could not be reached. Try again later.':
            return __( 'The update server could not be reached. Try again later.', 'purify-wpcode-lite' );
        case 'The update request timed out. Try again later.':
            return __( 'The update request timed out. Try again later.', 'purify-wpcode-lite' );
        case 'The update server address could not be resolved.':
            return __( 'The update server address could not be resolved.', 'purify-wpcode-lite' );
        case 'The secure connection to the update server failed.':
            return __( 'The secure connection to the update server failed.', 'purify-wpcode-lite' );
        case 'The update server returned an unsuccessful HTTP response.':
            return __( 'The update server returned an unsuccessful HTTP response.', 'purify-wpcode-lite' );
        case 'The update provider did not return valid update data.':
            return __( 'The update provider did not return valid update data.', 'purify-wpcode-lite' );
        case 'The package does not match the approved checksum. Installation was stopped.':
            return __( 'The package does not match the approved checksum. Installation was stopped.', 'purify-wpcode-lite' );
        case 'The archive or plugin identity could not be verified. Installation was stopped.':
            return __( 'The archive or plugin identity could not be verified. Installation was stopped.', 'purify-wpcode-lite' );
        case 'Update stopped at %1$s: %2$s':
            /* translators: 1: update stage, 2: fixed explanation. */
            return __( 'Update stopped at %1$s: %2$s', 'purify-wpcode-lite' );
        case 'HTTP status: %d.':
            /* translators: %d: HTTP error status. */
            return __( 'HTTP status: %d.', 'purify-wpcode-lite' );
        case 'Invalid private download limits.':
            return __( 'Invalid private download limits.', 'purify-wpcode-lite' );
        case 'Invalid package cleanup provider.':
            return __( 'Invalid package cleanup provider.', 'purify-wpcode-lite' );
        case 'Safe package cleanup is unavailable for this update provider.':
            return __( 'Safe package cleanup is unavailable for this update provider.', 'purify-wpcode-lite' );
        case 'The update package identity, version or requirements could not be verified.':
            return __( 'The update package identity, version or requirements could not be verified.', 'purify-wpcode-lite' );
        default:
            return $message;
    }
};

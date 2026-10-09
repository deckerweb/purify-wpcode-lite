<?php
/**
 * Translate the shared updater through the Purify host domain.
 *
 * @return Closure Callable receiving an English message and returning localized text.
 */
defined( 'ABSPATH' ) || exit;
/**
 * Translate a known updater message at display time.
 *
 * @param string $message English updater message.
 * @return string Localized message or unchanged source for unknown messages.
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
        default:
            return $message;
    }
};

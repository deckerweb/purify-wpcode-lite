<?php
/** Return the localized local plugin history.
 * @return string Categorized source consumed by the escaping HTML renderer.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
return "= 1.1.2 · 2026-11-09 =\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "Updated deckerweb Library to 1.3.0 and Updater to 2.4.0: normal updates are independent of the catalog, with bounded downloads and stricter package checks.", 'purify-wpcode-lite' ) . "\n" .
"= 1.1.1 · 2026-10-22 =\n" .
'* ' . __( "Improved", 'purify-wpcode-lite' ) . ': ' . __( "Updated plugin catalog and GitHub update handling.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "The local changelog stays closed until opened and keeps a documentation fallback.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Misc", 'purify-wpcode-lite' ) . ': ' . __( "Requires PHP 8.1 or newer; WordPress 6.7 remains supported.", 'purify-wpcode-lite' ) . "\n" .
"= 1.1.0 · 2026-10-09 =\n" .
'* ' . __( "New", 'purify-wpcode-lite' ) . ': ' . __( "Shared plugin catalog, GitHub release updates and local changelog.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Improved", 'purify-wpcode-lite' ) . ': ' . __( "Cleanup supports WPCode Lite 2.4.0 and preserves useful free features.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "Unverified WPCode versions pause cleanup safely.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "German dialog translations load through the plugin textdomain.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "Activation stays in the WordPress admin when WPCode Lite is missing or inactive; cleanup remains paused.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Misc", 'purify-wpcode-lite' ) . ': ' . __( "Newsletter links do not include personal account details.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Misc", 'purify-wpcode-lite' ) . ': ' . __( "Updated local artwork.", 'purify-wpcode-lite' ) . "\n" .
"= 1.0.0 · 2025-04-04 =\n" .
'* ' . __( "New", 'purify-wpcode-lite' ) . ': ' . __( "Initial public release.", 'purify-wpcode-lite' ) . "\n";

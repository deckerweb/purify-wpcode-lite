<?php
/** Return the localized local plugin history.
 * @return string Categorized source consumed by the escaping HTML renderer.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
return "= 1.1.0 · 2026-10-09 =\n" .
'* ' . __( "New", 'purify-wpcode-lite' ) . ': ' . __( "Shared plugin catalog, GitHub release updates and local changelog.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Improved", 'purify-wpcode-lite' ) . ': ' . __( "Cleanup supports WPCode Lite 2.4.0 and preserves useful free features.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "Unverified WPCode versions pause cleanup safely.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "German dialog translations load through the plugin textdomain.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Misc", 'purify-wpcode-lite' ) . ': ' . __( "Newsletter links do not include personal account details.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Misc", 'purify-wpcode-lite' ) . ': ' . __( "Updated local artwork.", 'purify-wpcode-lite' ) . "\n" .
'* ' . __( "Fixed", 'purify-wpcode-lite' ) . ': ' . __( "Activation stays in the WordPress admin when WPCode Lite is missing or inactive; cleanup remains paused.", 'purify-wpcode-lite' ) . "\n" .
"= 1.0.0 · 2025-04-04 =\n" .
'* ' . __( "New", 'purify-wpcode-lite' ) . ': ' . __( "Initial public release.", 'purify-wpcode-lite' ) . "\n";

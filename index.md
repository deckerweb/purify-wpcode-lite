---
layout: default
---

# Purify WPCode Lite

![Purify WPCode Lite](assets/github-banner-en.png)

[Deutsch](de.html) · [Dokumentation](docs/FAQ.de.html) · [Documentation](docs/FAQ.en.html)

Purify removes known promotional elements from WPCode Lite while keeping useful free features accessible.

Version **1.1.2** · WordPress **6.7+** · PHP **8.1+** · GPL v2 or later

[Installation](#installation) · [Requirements and limits](#requirements-and-limits) · [FAQ](#frequently-asked-questions) · [Changelog](#changelog)

## At a glance

- Cleanup for WPCode Lite 2.3.9 and 2.4.0.
- Preserves free library access, error logging and shortcode attributes.
- Useful snippet toolbar links and small editor styling improvements.
- Shared deckerweb catalog, GitHub release updates and local changelog.

## Installation

1. Install the ZIP under Plugins → Add New → Upload Plugin.
2. Activate WPCode Lite and Purify. Do not run a duplicate Purify snippet.
3. Check its status under Plugins; cleanup runs automatically with supported WPCode.

## Targeted cleanup

Named PHP hooks and page adapters suppress promotion before output where possible. Targeted CSS and DOM handling cover remaining promotions and dynamic upgrade dialogs. Error notices, free library, generators, import/export, logging and standard shortcodes remain. Shared catalog/updater data follows the documented ownership rules.

## Requirements and limits

Requires WordPress 6.7 and PHP 8.1 or newer. Includes Library 1.3.0 and Updater 2.4.0. Cleanup still targets WPCode Lite 2.3.9 and 2.4.0 and pauses with unverified versions. Hidden premium menu destinations may remain directly accessible. ClassicPress and full production update delivery are not verified.

## Frequently asked questions

### Does it unlock Pro features?

No. Unavailable Pro options keep honest labels and neutral dialogs.

### Which WPCode versions are supported?

The cleanup targets Lite 2.3.9 and 2.4.0. With another version, missing WPCode or active WPCode Pro, cleanup pauses and the Plugins page explains its status.

### Where do I configure it?

Cleanup works automatically. The shared catalog has its own existing integration under Plugins; Purify adds no separate branding settings page.

### Does it disable error messages or tracking?

No. Error, security and save messages remain. Your tracking selection is unchanged. The remote news/marketing inbox is disabled; that inbox can also contain product news.

### Does it support Multisite?

Cleanup uses the current site and user context and can be network activated. Shared Library settings belong to the network. No Multisite-specific snippet features are added; consult the documented testing limits.

### What happens to data when I remove it?

Purify stores no snippet content or cleanup settings. Deactivation preserves data. Uninstall preserves other installed Library hosts and their shared data. Only the final host removes temporary Library caches/tasks; settings are retained unless their separate deletion option was enabled. Installed plugins and WPCode data remain.

### What external connections are used?

The updater checks this public GitHub repository through WordPress update checks. The Library ships a local catalog; optional online refresh is off initially and, when enabled, contacts the approved GitHub catalog. User-triggered installation downloads approved packages. No Purify telemetry is added.

[Full FAQ by topic](docs/FAQ.en.html)

## Changelog

### 1.1.2 · 2026-11-09

- **Fixed:** Updated deckerweb Library to 1.3.0 and Updater to 2.4.0: normal updates are independent of the catalog, with bounded downloads and stricter package checks.

### 1.1.1 · 2026-10-22

- **Improved:** Updated plugin catalog and GitHub update handling.
- **Fixed:** The local changelog stays closed until opened and keeps a documentation fallback.
- **Misc:** Requires PHP 8.1 or newer; WordPress 6.7 remains supported.

### 1.1.0 · 2026-10-09

- **New:** Shared plugin catalog, GitHub release updates and local changelog.
- **Improved:** Cleanup supports WPCode Lite 2.4.0 and preserves useful free features.
- **Fixed:** Unverified WPCode versions pause cleanup safely.
- **Fixed:** German dialog translations load through the plugin textdomain.
- **Fixed:** Activation stays in the WordPress admin when WPCode Lite is missing or inactive; cleanup remains paused.
- **Misc:** Newsletter links do not include personal account details.
- **Misc:** Updated local artwork.

### 1.0.0 · 2025-04-04

- **New:** Initial public release.

## Author and project

David Decker – DECKERWEB. Purify focuses on a usable free interface. Distribution is through GitHub; this plugin is not distributed on WordPress.org.

## Issues and security

[Issues](https://github.com/deckerweb/purify-wpcode-lite/issues) · [Security](https://github.com/deckerweb/purify-wpcode-lite/blob/main/SECURITY.md)

Do not post security details publicly. SECURITY.md describes the confidential reporting route and its current availability.

## Support

[Ko-fi](https://ko-fi.com/deckerweb) · [Buy Me a Coffee](https://buymeacoffee.com/daveshine) · [PayPal](https://paypal.me/deckerweb) · [Newsletter](https://eepurl.com/gbAUUn)

Copyright © 2025–2026 David Decker – DECKERWEB. GPL-2.0-or-later.

Origins and licenses: [third parties](https://github.com/deckerweb/purify-wpcode-lite/blob/main/THIRD-PARTY.md).

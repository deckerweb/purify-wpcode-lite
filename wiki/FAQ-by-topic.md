# FAQ by topic

[Deutsch](FAQ.de.md)

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

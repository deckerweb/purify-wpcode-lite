# Purify WPCode Lite

![Purify WPCode Lite](https://raw.githubusercontent.com/deckerweb/purify-wpcode-lite/main/assets/github-banner-de.png)

[English](README.md) · [Dokumentation](docs/FAQ.de.md) · [Documentation](docs/FAQ.en.md)

Purify entfernt bekannte Werbung aus WPCode Lite und erhält den Zugriff auf nützliche Free-Funktionen.

Version **1.1.0** · WordPress **6.7+** · PHP **7.4+** · GPL v2 or later

[Installation](#installation) · [Voraussetzungen und Grenzen](#voraussetzungen-und-grenzen) · [FAQ](#häufige-fragen) · [Änderungsverlauf](#änderungsverlauf)

## Auf einen Blick

- Bereinigung für WPCode Lite 2.3.9 und 2.4.0.
- Erhält Free-Library, Fehler-Logging und Shortcode-Attribute.
- Nützliche Snippet-Toolbar-Links und kleine Verbesserungen am Editor.
- Gemeinsamer deckerweb-Katalog, GitHub-Release-Updates und lokaler Änderungsverlauf.

## Installation

1. ZIP unter Plugins → Installieren → Plugin hochladen installieren.
2. WPCode Lite und Purify aktivieren. Keine zweite Purify-Snippet-Version parallel verwenden.
3. Status unter Plugins prüfen; bei unterstütztem WPCode arbeitet die Bereinigung automatisch.

## Gezielte Bereinigung

Benannte PHP-Hooks und Seitenadapter unterbinden Werbung bevorzugt vor der Ausgabe. Gezieltes CSS und DOM-Behandlung decken verbleibende Promo-Bereiche und dynamische Upgrade-Dialoge ab. Fehlerhinweise, Free-Library, Generatoren, Import/Export, Logging und Standard-Shortcodes bleiben erhalten. Library- und Updater-Daten werden nach ihren eigenen dokumentierten Regeln verwaltet.

## Voraussetzungen und Grenzen

Die Mindestangaben bleiben WordPress 6.7 und PHP 7.4. Die eingebettete Library 0.8.1 benötigt PHP 8.0 und WordPress 6.4; ihr Bootstrap prüft Anforderungen vor dem Laden und zeigt bei Inkompatibilität einen Hinweis. Der Updater ist Version 2.1.0. Admin-Seiten, PHP-Snippet-Speicherung und Dialoge wurden isoliert mit WordPress 7.1.3/PHP 8.4.5 geprüft. Netzwerkaktivierung ist geprüft; vollständige Installations-/Updatezustellung, ClassicPress und Mindestversionen sind nicht bestätigt. Versteckte Premium-Menüs können weiterhin direkt erreichbar sein.

## Häufige Fragen

### Werden Pro-Funktionen freigeschaltet?

Nein. Nicht verfügbare Pro-Optionen behalten sachliche Kennzeichnungen und neutrale Dialoge.

### Welche WPCode-Versionen werden unterstützt?

Die Bereinigung richtet sich an Lite 2.3.9 und 2.4.0. Bei einer anderen Version, fehlendem WPCode oder aktivem WPCode Pro pausiert sie; die Plugins-Seite erklärt den Status.

### Wo kann ich es konfigurieren?

Die Bereinigung arbeitet automatisch. Der gemeinsame Katalog hat seine vorhandene Einbindung unter Plugins; Purify erzeugt keine eigene Einstellungsseite nur für Branding.

### Werden Fehlerhinweise oder Tracking deaktiviert?

Nein. Fehler-, Sicherheits- und Speicherhinweise bleiben erhalten. Deine Tracking-Auswahl bleibt unverändert. Die Remote-News-/Marketing-Inbox wird deaktiviert; sie kann auch Produktneuigkeiten enthalten.

### Wird Multisite unterstützt?

Die Bereinigung nutzt den jeweiligen Website- und Nutzerkontext und lässt sich netzwerkweit aktivieren. Gemeinsame Library-Einstellungen gelten für das Netzwerk. Zusätzliche Multisite-Snippet-Funktionen werden nicht ergänzt; beachte die dokumentierten Testgrenzen.

### Was passiert bei der Entfernung mit meinen Daten?

Purify speichert keine Snippet-Inhalte oder Cleanup-Einstellungen. Die Deaktivierung erhält Daten. Bei Deinstallation bleiben andere installierte Library-Hosts und ihre gemeinsamen Daten erhalten. Nur der letzte Host bereinigt temporäre Library-Caches und Aufgaben; Einstellungen bleiben erhalten, sofern ihre gesonderte Löschoption nicht eingeschaltet wurde. Installierte Plugins und WPCode-Daten bleiben erhalten.

### Welche externen Verbindungen gibt es?

Der Updater prüft dieses öffentliche GitHub-Repository über WordPress-Updateprüfungen. Die Library enthält einen lokalen Katalog; optionaler Onlineabgleich ist anfangs aus und kontaktiert nach Aktivierung den freigegebenen GitHub-Katalog. Bewusst ausgelöste Installationen laden freigegebene Pakete. Purify ergänzt keine Telemetrie.

[Vollständige Fragen nach Themen](docs/FAQ.de.md)

## Änderungsverlauf

### 1.1.0 · 2026-10-09

- **Neu:** Gemeinsamer Plugin-Katalog, GitHub-Release-Updates und lokaler Änderungsverlauf.
- **Verbessert:** Die Bereinigung unterstützt WPCode Lite 2.4.0 und erhält nützliche Free-Funktionen.
- **Behoben:** Bei ungeprüften WPCode-Versionen pausiert die Bereinigung sicher.
- **Behoben:** Deutsche Dialogübersetzungen werden über die Plugin-Textdomain geladen.
- **Sonstiges:** Newsletter-Links enthalten keine persönlichen Kontodaten.
- **Sonstiges:** Lokale Grafiken aktualisiert.
- **Behoben:** Bei fehlendem oder inaktivem WPCode Lite bleibt die Aktivierung im WordPress-Admin; die Bereinigung pausiert.

### 1.0.0 · 2025-04-04

- **Neu:** Erste öffentliche Veröffentlichung.

## Autor und Projekt

David Decker – DECKERWEB. Purify konzentriert sich auf eine nutzbare Free-Oberfläche. Veröffentlichungsweg ist GitHub; dieses Plugin wird nicht über WordPress.org angeboten.

## Issues and security

[Issues](https://github.com/deckerweb/purify-wpcode-lite/issues) · [Security](SECURITY.md)

Sicherheitsdetails nicht öffentlich posten. Der vertrauliche Meldeweg und sein aktueller Status stehen in SECURITY.md.

## Support

[Ko-fi](https://ko-fi.com/deckerweb) · [Buy Me a Coffee](https://buymeacoffee.com/daveshine) · [PayPal](https://paypal.me/deckerweb) · [Newsletter](https://eepurl.com/gbAUUn)

Copyright © 2025–2026 David Decker – DECKERWEB. GPL-2.0-or-later.

Herkunft und Lizenzen: [Drittanbieter](THIRD-PARTY.md).

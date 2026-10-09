from pathlib import Path
import json,re,subprocess,gettext
r=Path(__file__).resolve().parents[1]
content=json.loads((r/'resources/content.json').read_text())
(r/'resources').mkdir(exist_ok=True)
(r/'resources/content.json').write_text(json.dumps(content,ensure_ascii=False,indent=2)+'\n')
# Strings used by the accessible changelog remain extractable through the host domain.
history="<?php\n/** Return the localized local plugin history.\n * @return string Categorized source consumed by the escaping HTML renderer.\n */\nif ( ! defined( 'ABSPATH' ) ) { exit; }\nreturn "
parts=[]
for version,date,items in content['en']['history']:
 parts.append(json.dumps('= '+version+' · '+date+' =\n',ensure_ascii=False))
 for prefix,text in items:
  parts += ["'* ' . __( "+json.dumps(prefix,ensure_ascii=False)+", 'purify-wpcode-lite' ) . ': ' . __( "+json.dumps(text,ensure_ascii=False)+", 'purify-wpcode-lite' ) . \"\\n\""]
(r/'includes/history.php').write_text(history+' .\n'.join(parts)+';\n')
for lang,d in content.items():
 de=lang=='de';faqhead='Fragen nach Themen' if de else 'FAQ by topic';faq='\n\n'.join('### '+q+'\n\n'+a for q,a in d['faq'])
 changes='\n\n'.join('### '+v+' · '+date+'\n\n'+'\n'.join('- **'+p+':** '+t for p,t in items) for v,date,items in d['history'])
 banner='![Purify WPCode Lite](assets/github-banner-'+lang+'.png)'
 other='[English](README.md)' if de else '[Deutsch](README.de.md)'
 install='''1. ZIP unter Plugins → Installieren → Plugin hochladen installieren.
2. WPCode Lite und Purify aktivieren. Keine zweite Purify-Snippet-Version parallel verwenden.
3. Status unter Plugins prüfen; bei unterstütztem WPCode arbeitet die Bereinigung automatisch.''' if de else '''1. Install the ZIP under Plugins → Add New → Upload Plugin.
2. Activate WPCode Lite and Purify. Do not run a duplicate Purify snippet.
3. Check its status under Plugins; cleanup runs automatically with supported WPCode.'''
 limits='''Die Mindestangaben bleiben WordPress 6.7 und PHP 7.4. Die eingebettete Library 0.8.1 benötigt PHP 8.0 und WordPress 6.4; ihr Bootstrap prüft Anforderungen vor dem Laden und zeigt bei Inkompatibilität einen Hinweis. Der Updater ist Version 2.1.0. Admin-Seiten, PHP-Snippet-Speicherung und Dialoge wurden isoliert mit WordPress 7.1.3/PHP 8.4.5 geprüft. Netzwerkaktivierung ist geprüft; vollständige Installations-/Updatezustellung, ClassicPress und Mindestversionen sind nicht bestätigt. Versteckte Premium-Menüs können weiterhin direkt erreichbar sein.''' if de else '''Minimum headers remain WordPress 6.7 and PHP 7.4. Embedded Library 0.8.1 requires PHP 8.0 and WordPress 6.4; its bootstrap checks requirements before loading and reports incompatibility. The updater is version 2.1.0. Admin routes, PHP snippet saving and dialogs were tested in isolation with WordPress 7.1.3/PHP 8.4.5. Network activation was tested; complete installation/update delivery, ClassicPress and minimum-version environments are not verified. Hidden premium menu destinations may remain directly accessible.'''
 titleinstall='Installation' if de else 'Installation';titlelimits='Voraussetzungen und Grenzen' if de else 'Requirements and limits';titlefaq='Häufige Fragen' if de else 'Frequently asked questions';titlechanges='Änderungsverlauf' if de else 'Changelog'
 text=f'''# Purify WPCode Lite

{banner}

{other} · [Dokumentation](docs/FAQ.de.md) · [Documentation](docs/FAQ.en.md)

{d['intro']}

Version **1.1.0** · WordPress **6.7+** · PHP **7.4+** · GPL v2 or later

[Installation](#installation) · [{titlelimits}](#{'voraussetzungen-und-grenzen' if de else 'requirements-and-limits'}) · [FAQ](#{'häufige-fragen' if de else 'frequently-asked-questions'}) · [{titlechanges}](#{'änderungsverlauf' if de else 'changelog'})

## {'Auf einen Blick' if de else 'At a glance'}

'''+ '\n'.join('- '+x for x in d['glance'])+f'''

## {titleinstall}

{install}

## {'Gezielte Bereinigung' if de else 'Targeted cleanup'}

'''+('Benannte PHP-Hooks und Seitenadapter unterbinden Werbung bevorzugt vor der Ausgabe. Gezieltes CSS und DOM-Behandlung decken verbleibende Promo-Bereiche und dynamische Upgrade-Dialoge ab. Fehlerhinweise, Free-Library, Generatoren, Import/Export, Logging und Standard-Shortcodes bleiben erhalten. Library- und Updater-Daten werden nach ihren eigenen dokumentierten Regeln verwaltet.' if de else 'Named PHP hooks and page adapters suppress promotion before output where possible. Targeted CSS and DOM handling cover remaining promotions and dynamic upgrade dialogs. Error notices, free library, generators, import/export, logging and standard shortcodes remain. Shared catalog/updater data follows the documented ownership rules.')+f'''

## {titlelimits}

{limits}

## {titlefaq}

{faq}

'''+('[Vollständige Fragen nach Themen](docs/FAQ.de.md)' if de else '[Full FAQ by topic](docs/FAQ.en.md)')+f'''

## {titlechanges}

{changes}

## {'Autor und Projekt' if de else 'Author and project'}

David Decker – DECKERWEB. '''+('Purify konzentriert sich auf eine nutzbare Free-Oberfläche. Veröffentlichungsweg ist GitHub; dieses Plugin wird nicht über WordPress.org angeboten.' if de else 'Purify focuses on a usable free interface. Distribution is through GitHub; this plugin is not distributed on WordPress.org.')+'''

## Issues and security

[Issues](https://github.com/deckerweb/purify-wpcode-lite/issues) · [Security](SECURITY.md)

'''+('Sicherheitsdetails nicht öffentlich posten. Der vertrauliche Meldeweg und sein aktueller Status stehen in SECURITY.md.' if de else 'Do not post security details publicly. SECURITY.md describes the confidential reporting route and its current availability.')+'''

## Support

[Ko-fi](https://ko-fi.com/deckerweb) · [Buy Me a Coffee](https://buymeacoffee.com/daveshine) · [PayPal](https://paypal.me/deckerweb) · [Newsletter](https://eepurl.com/gbAUUn)

Copyright © 2025–2026 David Decker – DECKERWEB. GPL-2.0-or-later.

'''+('Herkunft und Lizenzen: [Drittanbieter](THIRD-PARTY.md).' if de else 'Origins and licenses: [third parties](THIRD-PARTY.md).')+'\n'
 (r/('README.de.md' if de else 'README.md')).write_text(text)
 (r/'docs').mkdir(exist_ok=True)
 (r/'docs'/('FAQ.de.md' if de else 'FAQ.en.md')).write_text('# '+faqhead+'\n\n'+('[English](FAQ.en.md)' if de else '[Deutsch](FAQ.de.md)')+'\n\n'+faq+'\n')
# Native readme is a generated companion, not a WordPress.org distribution claim.
d=content['en'];(r/'readme.txt').write_text('=== Purify WPCode Lite ===\nContributors: deckerweb\nRequires at least: 6.7\nRequires PHP: 7.4\nStable tag: 1.1.0\nLicense: GPLv2 or later\nLicense URI: https://www.gnu.org/licenses/gpl-2.0.html\n\n'+d['intro']+'\n\n== Description ==\n\n'+ '\n'.join('* '+x for x in d['glance'])+'\n\n== Installation ==\n\nUpload the plugin ZIP, activate WPCode Lite and Purify, and check the Plugins-page status.\n\n== Frequently Asked Questions ==\n\n'+'\n\n'.join('= '+q+' =\n\n'+a for q,a in d['faq'])+'\n\n== Changelog ==\n\n'+'\n\n'.join('= '+v+' · '+date+' =\n\n'+'\n'.join('* '+p+': '+t for p,t in items) for v,date,items in d['history'])+'\n')
# Populate new host strings in informal/formal German, retaining existing translations.
translations={
'This feature is unavailable in WPCode Lite.':'Diese Funktion ist in WPCode Lite nicht verfügbar.',
'No additional free snippets are currently available for the installed plugins.':'Für die installierten Plugins sind derzeit keine zusätzlichen Free-Snippets verfügbar.',
'Close':'Schließen','Changelog':'Änderungsverlauf','Purify WPCode Lite changelog':'Purify WPCode Lite Änderungsverlauf',
'WPCode Lite does not require a license.':'WPCode Lite benötigt keine Lizenz.',
'Remove promotional elements from WPCode Lite while preserving useful free features.':'Entfernt Werbung aus WPCode Lite und erhält nützliche Free-Funktionen.',
'Purify WPCode Lite could not initialize its updater. Check the installed deckerweb Updater copies.':'Purify WPCode Lite konnte seinen Updater nicht starten. Prüfe die installierten deckerweb-Updater-Kopien.',
'The update package does not match Purify WPCode Lite or its supported requirements.':'Das Updatepaket passt nicht zu Purify WPCode Lite oder seinen unterstützten Voraussetzungen.',
'Cleanup is inactive while WPCode Pro is active.':'Die Bereinigung ist bei aktivem WPCode Pro inaktiv.',
'Activate WPCode Lite to enable cleanup.':'Aktiviere WPCode Lite, um die Bereinigung zu nutzen.',
'Cleanup is paused for this unverified WPCode Lite version.':'Die Bereinigung pausiert bei dieser ungeprüften WPCode-Lite-Version.',
'Purify WPCode Lite':'Purify WPCode Lite','Join the newsletter':'Newsletter abonnieren','New':'Neu','Improved':'Verbessert','Fixed':'Behoben','Misc':'Sonstiges'}
for en,de in zip(content['en']['history'],content['de']['history']):
 for (_,msg),(_,translated) in zip(en[2],de[2]):translations[msg]=translated
scratch=r/'work/language-build';scratch.mkdir(parents=True,exist_ok=True)
files=[str(p.relative_to(r)) for p in [r/'purify-wpcode-lite.php',r/'includes/integration.php',r/'includes/history.php',r/'includes/updater-translations.php',r/'includes/pages-wpcode.php']]
subprocess.run(['xgettext','--language=PHP','--from-code=UTF-8','--keyword=__:1','--keyword=esc_html__:1','--keyword=esc_html_x:1,2c','--keyword=esc_attr__:1','--keyword=esc_attr_x:1,2c','--package-name=Purify WPCode Lite','-o',str(scratch/'host.pot')]+files,check=True,cwd=str(r))
lib=r/'includes/deckerweb-plugin-library/languages'
(scratch/'library.pot').write_text('msgid \"\"\nmsgstr \"Content-Type: text/plain; charset=UTF-8\\n\"\n\n'+(lib/'messages.pot').read_text())
subprocess.run(['msgcat','--use-first',str(scratch/'host.pot'),str(scratch/'library.pot'),'-o',str(r/'languages/purify-wpcode-lite.pot')],check=True)
for locale in ['de_DE','de_DE_formal']:
 table=dict(translations)
 if locale.endswith('formal'):
  table['Purify WPCode Lite could not initialize its updater. Check the installed deckerweb Updater copies.']='Purify WPCode Lite konnte seinen Updater nicht starten. Prüfen Sie die installierten deckerweb-Updater-Kopien.'
  table['Activate WPCode Lite to enable cleanup.']='Aktivieren Sie WPCode Lite, um die Bereinigung zu nutzen.'
 header='Project-Id-Version: Purify WPCode Lite 1.1.0\nPO-Revision-Date: 2026-10-09 12:00+0200\nLast-Translator: David Decker – DECKERWEB\nLanguage-Team: German\nLanguage: '+locale+'\nContent-Type: text/plain; charset=UTF-8\nMIME-Version: 1.0\nContent-Transfer-Encoding: 8bit\nPlural-Forms: nplurals=2; plural=(n != 1);\n'
 new='msgid ""\nmsgstr '+json.dumps(header,ensure_ascii=False)+'\n\n'+'\n\n'.join('msgid '+json.dumps(a,ensure_ascii=False)+'\nmsgstr '+json.dumps(b,ensure_ascii=False) for a,b in table.items())+'\n'
 new += '\nmsgctxt \"Plugins page listing\"\nmsgid \"Join the newsletter\"\nmsgstr \"Newsletter abonnieren\"\n'
 (scratch/(locale+'.po')).write_text(new)
 dest=r/'languages'/('purify-wpcode-lite-'+locale+'.po')
 subprocess.run(['msgcat','--use-first',str(scratch/(locale+'.po')),str(dest),str(lib/(locale+'.po')),'-o',str(scratch/('merged-'+locale+'.po'))],check=True)
 subprocess.run(['msgmerge','--no-fuzzy-matching',str(scratch/('merged-'+locale+'.po')),str(r/'languages/purify-wpcode-lite.pot'),'-o',str(dest)],check=True)
 cleaned=scratch/('cleaned-'+locale+'.po');subprocess.run(['msgattrib','--no-obsolete',str(dest),'-o',str(cleaned)],check=True);dest.write_bytes(cleaned.read_bytes())
 mo=dest.with_suffix('.mo');subprocess.run(['msgfmt','--check',str(dest),'-o',str(mo)],check=True)
 # Remove stale optimized catalogs: WordPress can otherwise prefer obsolete messages over updated MO.
 dest.with_suffix('.l10n.php').unlink(missing_ok=True)
 cat=gettext.GNUTranslations(mo.open('rb')); missing=[a for a in translations if cat.gettext(a)==a and a!=cat.gettext(a)]
 print(locale,'compiled messages:',len(cat._catalog))
print('Documentation, history and all host language resources generated.')

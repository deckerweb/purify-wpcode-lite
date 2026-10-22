from pathlib import Path
import json,re
r=Path(__file__).resolve().parents[1] if 'r' not in globals() else r
c=json.loads((r/'resources/content.json').read_text())
w=r/'wiki';w.mkdir(exist_ok=True)
repo='https://github.com/deckerweb/purify-wpcode-lite'
for lang,d in c.items():
 de=lang=='de';suffix='-de' if de else '';other='' if de else '-de'
 intro=d['intro'];faqname='Fragen-nach-Themen' if de else 'FAQ-by-topic'
 nav=('[English](Home)' if de else '[Deutsch](Home-de)')
 guide="""# Nutzung

[English](User-guide)

Purify arbeitet automatisch auf unterstützten WPCode-Lite-Seiten. Kostenlose Snippet-Bearbeitung, Bibliothekszugang, Generatoren, Import/Export, Fehlerlogging und Shortcodes bleiben erhalten.

Bei fehlendem oder inaktivem WPCode Lite sowie bei WPCode Pro oder ungeprüften Versionen pausiert die Bereinigung. Aktiviere eine unterstützte WPCode-Lite-Version, um sie ohne erneute Purify-Aktivierung zu starten.

Den lokalen Änderungsverlauf findest du unter Plugins in der Purify-Zeile. Ohne Dialog-Unterstützung führt der Link zum formatierten Wiki.

[Fragen nach Themen](Fragen-nach-Themen)
""" if de else """# User guide

[Deutsch](Anleitung)

Purify works automatically on supported WPCode Lite screens. Free snippet editing, library access, generators, import/export, error logging and shortcodes remain available.

Cleanup pauses when WPCode Lite is missing or inactive, when WPCode Pro is active, or with unverified versions. Activate a supported WPCode Lite version to resume automatically without reactivating Purify.

Open the local changelog from the Purify row under Plugins. Without dialog support, its link opens the formatted Wiki.

[FAQ by topic](FAQ-by-topic)
"""
 (w/('Anleitung.md' if de else 'User-guide.md')).write_text(guide)
 getting="""# Erste Schritte

[English](Getting-started)

1. WordPress 6.7 oder neuer und PHP 8.1 oder neuer verwenden.
2. Das Plugin-ZIP über Plugins → Installieren → Plugin hochladen installieren.
3. Purify und WPCode Lite aktivieren; keine zweite Purify-Snippet-Version parallel ausführen.
4. Unter Plugins den Status prüfen. Die Bereinigung unterstützt WPCode Lite 2.3.9 und 2.4.0; andere Versionen bleiben unbeeinflusst.

Purify darf vor WPCode aktiviert werden. Du bleibst im Admin; die Bereinigung pausiert bis zur Verfügbarkeit einer unterstützten Version. Einstellungen und Snippetdaten von WPCode werden nicht gelöscht.

[Anleitung](Anleitung) · [Fragen nach Themen](Fragen-nach-Themen)
""" if de else """# Getting started

[Deutsch](Erste-Schritte)

1. Use WordPress 6.7 or newer and PHP 8.1 or newer.
2. Upload the plugin ZIP through Plugins → Add New → Upload Plugin.
3. Activate Purify and WPCode Lite; do not run a duplicate Purify snippet.
4. Check the status under Plugins. Cleanup supports WPCode Lite 2.3.9 and 2.4.0; other versions are left untouched.

Purify can be activated before WPCode. You stay in the admin; cleanup pauses until a supported version is available. WPCode settings and snippet data are not deleted.

[User guide](User-guide) · [FAQ by topic](FAQ-by-topic)
"""
 (w/('Erste-Schritte.md' if de else 'Getting-started.md')).write_text(getting)
 changes='\n\n'.join('## '+v+' · '+date+'\n\n'+'\n'.join('- **'+p+':** '+t for p,t in items) for v,date,items in d['history'])
 (w/('Changelog'+suffix+'.md')).write_text('# '+('Änderungsverlauf' if de else 'Changelog')+'\n\n'+('[English](Changelog)' if de else '[Deutsch](Changelog-de)')+'\n\n'+changes+'\n')
 faq='# '+('Fragen nach Themen' if de else 'FAQ by topic')+'\n\n'+('[English](FAQ-by-topic)' if de else '[Deutsch](Fragen-nach-Themen)')+'\n'
 for idx,(q,a) in enumerate(d['faq']):
  if idx in [0,2,4]:faq+='\n## '+(['Einstieg','Alltag und Funktionen','Daten und technische Fragen'][[0,2,4].index(idx)] if de else ['Getting started','Everyday use and features','Data and technical questions'][[0,2,4].index(idx)])+'\n'
  faq+='\n### '+q+'\n\n'+a+'\n'
 (w/(faqname+'.md')).write_text(faq)
 home='# Purify WPCode Lite\n\n![Purify WPCode Lite](https://raw.githubusercontent.com/deckerweb/purify-wpcode-lite/main/assets/github-banner-'+lang+'.png)\n\n'
 home+=('**Mehr Fokus. Weniger Ablenkung.**' if de else '**More focus. Less distraction.**')+'\n\n'+intro+'\n\n'+nav+'\n\n'
 home+=('## Einstieg\n\n- [Erste Schritte](Erste-Schritte)\n- [Anleitung](Anleitung)\n- [Fragen nach Themen](Fragen-nach-Themen)\n- [Änderungsverlauf](Changelog-de)\n- [Hilfe und Sicherheit](Hilfe-und-Sicherheit)\n' if de else '## Documentation\n\n- [Getting started](Getting-started)\n- [User guide](User-guide)\n- [FAQ by topic](FAQ-by-topic)\n- [Changelog](Changelog)\n- [Help and security](Help-and-security)\n')
 (w/('Home'+suffix+'.md')).write_text(home)
 helptext=('# Hilfe und Sicherheit\n\n[English](Help-and-security)\n\nNormale Fragen und Fehler: [Issues]('+repo+'/issues) oder [Discussions]('+repo+'/discussions). Sicherheitslücken bitte vertraulich über [GitHubs Sicherheitsformular]('+repo+'/security/advisories/new) melden. Keine Zugangsdaten oder Snippet-Inhalte veröffentlichen.\n\n[Sicherheitsrichtlinie]('+repo+'/blob/main/SECURITY.md)\n' if de else '# Help and security\n\n[Deutsch](Hilfe-und-Sicherheit)\n\nUse [Issues]('+repo+'/issues) or [Discussions]('+repo+'/discussions) for ordinary questions and bugs. Report vulnerabilities privately through [GitHub’s security form]('+repo+'/security/advisories/new). Do not publish credentials or snippet contents.\n\n[Security policy]('+repo+'/blob/main/SECURITY.md)\n')
 (w/('Hilfe-und-Sicherheit.md' if de else 'Help-and-security.md')).write_text(helptext)
sidebar='## English\n\n- [Home](Home)\n- [Getting started](Getting-started)\n- [User guide](User-guide)\n- [FAQ by topic](FAQ-by-topic)\n- [Changelog](Changelog)\n- [Help and security](Help-and-security)\n\n## Deutsch\n\n- [Startseite](Home-de)\n- [Erste Schritte](Erste-Schritte)\n- [Anleitung](Anleitung)\n- [Fragen nach Themen](Fragen-nach-Themen)\n- [Änderungsverlauf](Changelog-de)\n- [Hilfe und Sicherheit](Hilfe-und-Sicherheit)\n'
(w/'_Sidebar.md').write_text(sidebar)
(w/'_Footer.md').write_text('**Purify WPCode Lite · Documentation / Dokumentation**\n\n[Wiki home / Wiki-Startseite]('+repo+'/wiki) · [Repository]('+repo+') · [Downloads & Releases]('+repo+'/releases)\n\n[Help & bugs / Hilfe & Fehler]('+repo+'/issues) · [Discussions / Austausch]('+repo+'/discussions) · [Security / Sicherheit]('+repo+'/blob/main/SECURITY.md)\n\n© 2025–2026 David Decker – DECKERWEB.  \nPlugin-Lizenz / Plugin license: [GPL v2 or later / GPL v2 oder höher](https://www.gnu.org/licenses/gpl-2.0.html).\n')

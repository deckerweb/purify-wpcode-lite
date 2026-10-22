from pathlib import Path
r=Path(__file__).resolve().parents[1] if 'r' not in globals() else r
for filename,readme in [('index.md','README.md'),('de.md','README.de.md')]:
    text=(r/readme).read_text()
    for source,target in [('README.de.md','de.html'),('README.md','index.html'),('docs/FAQ.de.md','docs/FAQ.de.html'),('docs/FAQ.en.md','docs/FAQ.en.html')]:
        text=text.replace('('+source+')','('+target+')')
    for name in ['SECURITY.md','THIRD-PARTY.md']:
        text=text.replace('('+name+')','(https://github.com/deckerweb/purify-wpcode-lite/blob/main/'+name+')')
    (r/filename).write_text('---\nlayout: default\n---\n\n'+text)

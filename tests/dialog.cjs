/* Set NODE_PATH to your package directory containing playwright. */
const { chromium } = require('playwright');
const fs = require('fs');
(async () => {
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();
    await page.setContent('<body class="wpcode-admin-page"><button id="origin">Origin</button><button class="wpcode-start-auth">Connect to Library</button><div class="wpcode-library-tab" data-tab="plugin-snippets"><div class="wpcode-library-suggest-plugins">Install recommended products</div></div><div class="jconfirm" id="unrelated"><p class="jconfirm-content">Unrelated confirmation</p><button>Keep</button></div></body>');
    const unrelatedBefore = await page.locator('#unrelated').innerHTML();
    await page.evaluate(() => { window.pwlCleanup = { unavailable: 'Unavailable in Lite.', close: 'Close', libraryEmpty: 'No free snippets available.' }; });
    await page.addScriptTag({ content: fs.readFileSync(require('path').join(__dirname, '../assets/js/cleanup.js'), 'utf8') });
    await page.waitForFunction(() => !document.querySelector('.wpcode-library-suggest-plugins'));
    if (await page.locator('.wpcode-start-auth').count() !== 1) throw Error('Free library connect removed');
    await page.evaluate(() => {
        document.body.insertAdjacentHTML('beforeend', '<div class="jconfirm" id="upgrade"><div class="jconfirm-box"><div class="jconfirm-closeIcon" role="button">X</div><div class="jconfirm-content wpcode-lite-upgrade">Buy now</div><div class="wpcode-discount-note">Discount</div><div class="wpcode-already-purchased">Purchase</div><div class="jconfirm-buttons"><button id="purchase">Buy</button></div></div></div>');
        document.querySelector('#purchase').addEventListener('click', () => { window.bought = true; });
        document.querySelector('#upgrade .jconfirm-closeIcon').addEventListener('click', () => { document.querySelector('#upgrade').remove(); document.querySelector('#origin').focus(); });
    });
    await page.waitForFunction(() => document.querySelector('#upgrade .jconfirm-content').textContent === 'Unavailable in Lite.');
    if (await page.locator('#purchase, .wpcode-discount-note, .wpcode-already-purchased').count()) throw Error('Marketing remains');
    if (await page.locator('#unrelated').innerHTML() !== unrelatedBefore) throw Error('Unrelated dialog changed');
    await page.getByRole('button', { name: 'Close', exact: true }).click();
    if (await page.locator('#upgrade').count()) throw Error('Close failed');
    if (await page.evaluate(() => Boolean(window.bought))) throw Error('Purchase triggered');
    if (await page.evaluate(() => document.activeElement.id) !== 'origin') throw Error('Focus return failed');
    console.log('PASS: Dynamic upgrade content, purchase removal, unrelated dialog preservation, upstream close and focus return');
    await browser.close();
})().catch(error => { console.error(error); process.exit(1); });

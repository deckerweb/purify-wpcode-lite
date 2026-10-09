/* WPCode 2.3.9 uses jQuery/SelectWoo dialogs, not a React admin app.
 * DOM fallback: preserve the upstream dialog and its close/focus lifecycle,
 * replacing only a positively identified WPCode Lite upgrade dialog.
 */
(function () {
    'use strict';
    if (!document.body.classList.contains('wpcode-admin-page') || !window.pwlCleanup) return;
    var handled = new WeakSet();
    function cleanDialogs() {
        document.querySelectorAll('.wpcode-library-tab[data-tab="plugin-snippets"] .wpcode-library-suggest-plugins').forEach(function (promotion) {
            var empty = document.createElement('p');
            empty.textContent = window.pwlCleanup.libraryEmpty;
            promotion.replaceWith(empty);
        });
        document.querySelectorAll('.jconfirm .wpcode-lite-upgrade').forEach(function (content) {
            var box = content.closest('.jconfirm-box');
            if (!box || handled.has(box)) return;
            var close = box.querySelector('.jconfirm-closeIcon');
            var buttons = box.querySelector('.jconfirm-buttons');
            if (!close || !buttons) return;
            handled.add(box);
            content.textContent = window.pwlCleanup.unavailable;
            box.querySelectorAll('.wpcode-already-purchased, .wpcode-discount-note, .wpcode_check').forEach(function (node) { node.remove(); });
            // Remove the original purchase/addon-install action, retaining close.
            buttons.replaceChildren();
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-default';
            button.textContent = window.pwlCleanup.close;
            button.addEventListener('click', function () { close.click(); });
            buttons.appendChild(button);
            button.focus();
        });
    }
    // Dialogs are appended outside the WPCode content container.
    var pending = false;
    new MutationObserver(function (records) {
        if (pending || !records.some(function (record) {
            if (!record.addedNodes.length) return false;
            if (record.target instanceof Element && record.target.closest('.jconfirm, .wpcode-library-tab[data-tab="plugin-snippets"]')) return true;
            return Array.from(record.addedNodes).some(function (node) {
                return node instanceof Element && (node.matches('.jconfirm, .wpcode-library-tab[data-tab="plugin-snippets"]') || node.querySelector('.jconfirm, .wpcode-lite-upgrade, .wpcode-library-suggest-plugins'));
            });
        })) return;
        pending = true;
        queueMicrotask(function () { pending = false; cleanDialogs(); });
    }).observe(document.body, { childList: true, subtree: true });
    cleanDialogs();
}());

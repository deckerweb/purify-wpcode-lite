/* Local history with native dialog methods and a real documentation fallback. */
(function () {
    'use strict';
    if (document.documentElement.dataset.pwlHistoryReady) return;
    document.documentElement.dataset.pwlHistoryReady = '1';
    var dialog = document.getElementById('pwl-history');
    var proto = window.HTMLDialogElement && window.HTMLDialogElement.prototype;
    if (!dialog || !proto || typeof proto.showModal !== 'function' || typeof proto.close !== 'function') return;
    var opener;
    function close() {
        try { proto.close.call(dialog); } catch (error) { dialog.removeAttribute('open'); }
        dialog.hidden = true;
        if (opener && opener.isConnected) opener.focus();
    }
    document.addEventListener('click', function (event) {
        var link = event.target.closest && event.target.closest('[data-pwl-history]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        opener = link;
        dialog.hidden = false;
        try { proto.showModal.call(dialog); } catch (error) { dialog.hidden = true; return; }
        if (!dialog.open) { dialog.hidden = true; return; }
        event.preventDefault();
        dialog.querySelector('[data-pwl-history-close]').focus();
    }, true);
    dialog.addEventListener('submit', function (event) { event.preventDefault(); close(); }, true);
    dialog.addEventListener('cancel', function (event) { event.preventDefault(); close(); });
    dialog.addEventListener('click', function (event) {
        if (event.target !== dialog) return;
        var box = dialog.getBoundingClientRect();
        if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) close();
    });
    dialog.addEventListener('close', function () { dialog.hidden = true; if (opener && opener.isConnected) opener.focus(); });
}());

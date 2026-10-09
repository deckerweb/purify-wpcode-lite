/* Native dialog keeps keyboard focus inside the local plugin history. */
(function () {
    'use strict';
    var dialog = document.getElementById('pwl-history');
    if (!dialog) return;
    var opener;
    document.querySelectorAll('[data-pwl-history]').forEach(function (button) {
        button.addEventListener('click', function () {
            opener = button;
            dialog.showModal();
            dialog.querySelector('[data-pwl-history-close]').focus();
        });
    });
    dialog.querySelector('[data-pwl-history-close]').addEventListener('click', function () { dialog.close(); });
    dialog.addEventListener('click', function (event) { if (event.target === dialog) { var box = dialog.getBoundingClientRect(); if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) dialog.close(); } });
    dialog.addEventListener('close', function () { if (opener && opener.isConnected) opener.focus(); });
}());

// Kunci tema terang: buang kelas gelap yang mungkin terpasang oleh pengaturan AdminLTE.
(function () {
    function terang() {
        var b = document.body;
        if (!b) { return; }
        b.classList.add('arya-body');
        b.classList.remove('dark-mode');
        document.querySelectorAll('.navbar-dark').forEach(function (e) { e.classList.remove('navbar-dark'); e.classList.add('navbar-white', 'navbar-light'); });
        document.querySelectorAll('[class*="sidebar-dark-"]').forEach(function (e) {
            e.className = e.className.replace(/sidebar-dark-(\w+)/g, 'sidebar-light-$1');
        });
    }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', terang); } else { terang(); }
})();

/* Interaksi dan transisi tema Arya Rangga (tanpa dependensi tambahan). */
(function () {
    'use strict';

    // 1. Angka widget menghitung naik dari 0 (count-up).
    document.querySelectorAll('[data-count]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        var durasi = 1100;
        var mulai = null;

        function langkah(ts) {
            if (mulai === null) { mulai = ts; }
            var p = Math.min((ts - mulai) / durasi, 1);
            var eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
            el.textContent = Math.round(target * eased).toLocaleString('id-ID');
            if (p < 1) { requestAnimationFrame(langkah); }
        }
        requestAnimationFrame(langkah);
    });

    // 2. Transisi keluar halaman (fade) saat klik link internal.
    document.addEventListener('click', function (e) {
        var a = e.target.closest('a[href]');
        if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey) { return; }

        var raw = a.getAttribute('href');
        if (!raw || raw.charAt(0) === '#' || raw.indexOf('javascript:') === 0) { return; }
        if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-widget')) { return; }

        var url;
        try { url = new URL(a.href, window.location.href); } catch (err) { return; }
        if (url.origin !== window.location.origin) { return; }

        e.preventDefault();
        document.body.classList.add('page-leaving');
        setTimeout(function () { window.location.href = a.href; }, 220);
    });

    // Kembali dari cache browser (tombol back): hapus kelas fade-out.
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) { document.body.classList.remove('page-leaving'); }
    });

    // 3. Efek ripple pada tombol.
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn');
        if (!btn) { return; }

        var rect = btn.getBoundingClientRect();
        var ukuran = Math.max(rect.width, rect.height);
        var r = document.createElement('span');
        r.className = 'ripple';
        r.style.width = r.style.height = ukuran + 'px';
        r.style.left = (e.clientX - rect.left - ukuran / 2) + 'px';
        r.style.top = (e.clientY - rect.top - ukuran / 2) + 'px';
        btn.appendChild(r);
        setTimeout(function () { r.remove(); }, 600);
    });

    // 4. Konfirmasi hapus + animasi baris meluncur keluar sebelum form dikirim.
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.matches || !form.matches('form[data-confirm]')) { return; }

        e.preventDefault();
        if (!window.confirm(form.getAttribute('data-confirm'))) { return; }

        var baris = form.closest('tr');
        if (baris) { baris.classList.add('row-removing'); }
        setTimeout(function () { form.submit(); }, 380);
    });

    // 5. Alert sukses menghilang otomatis dengan fade.
    document.querySelectorAll('.arya-alert').forEach(function (alert) {
        setTimeout(function () {
            alert.classList.add('hilang');
            setTimeout(function () { alert.remove(); }, 520);
        }, 4500);
    });
})();
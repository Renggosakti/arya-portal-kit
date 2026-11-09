<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="color-scheme" content="only light">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $brandName }} | Portal Akademik Teknik Informatika ITS</title>
    <meta name="description" content="Portal pengelolaan data akademik Teknik Informatika ITS dengan dashboard dan hak akses berbasis role.">
    <link rel="icon" href="{{ asset($brandLogo) }}">
    <script>document.documentElement.classList.add('js');</script>
    <style>
        :root {
            --navy: #0f2a5c; --navy-2: #163a7a; --gold: #f5a623; --gold-2: #ffb93f;
            --blue: #3b82f6; --cyan: #22d3ee; --ink: #1b2a4a; --muted: #5d6b8a;
            --line: #e6edf8; --cream: #fbf8f3; --ease: cubic-bezier(0.22, 1, 0.36, 1);
            --serif: Georgia, "Times New Roman", serif;
            --sans: "Segoe UI", system-ui, -apple-system, Roboto, Arial, sans-serif;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; font-family: var(--sans); color: var(--ink); background: #fff; line-height: 1.6; animation: fade 0.6s ease both; transition: opacity 0.25s ease; }
        body.leaving { opacity: 0; }
        a { color: inherit; text-decoration: none; }
        .wrap { width: min(1180px, 92vw); margin: 0 auto; }
        svg.i { width: 1em; height: 1em; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; vertical-align: -0.12em; }

        @keyframes fade { from { opacity: 0; } to { opacity: 1; } }
        @keyframes up { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: none; } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
        @keyframes drift { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(24px, -18px); } }
        @keyframes pulse { 0%, 100% { opacity: 0.75; } 50% { opacity: 1; } }

        /* Scroll reveal */
        .js .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.8s var(--ease), transform 0.8s var(--ease); transition-delay: var(--d, 0ms); }
        .js .reveal.in { opacity: 1; transform: none; }

        /* Top bar */
        .topbar { background: var(--navy); color: #cfe0ff; font-size: 0.8rem; }
        .topbar .wrap { display: flex; justify-content: space-between; gap: 16px; padding: 9px 0; flex-wrap: wrap; }
        .topbar span, .topbar a { display: inline-flex; align-items: center; gap: 7px; margin-right: 22px; }
        .topbar a:hover { color: #fff; }

        /* Navbar */
        header.nav { position: sticky; top: 0; z-index: 50; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(10px); border-bottom: 1px solid var(--line); transition: box-shadow 0.3s; }
        header.nav.scrolled { box-shadow: 0 10px 30px rgba(15, 42, 92, 0.10); }
        .nav .wrap { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 12px 0; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand img { width: 46px; height: 46px; border-radius: 12px; object-fit: cover; animation: float 3.4s ease-in-out infinite; }
        .brand b { display: block; font-family: var(--serif); font-size: 1.35rem; color: var(--navy); line-height: 1.1; letter-spacing: 0.4px; }
        .brand small { display: block; font-size: 0.68rem; letter-spacing: 2.2px; text-transform: uppercase; color: var(--muted); }
        .menu { display: flex; gap: 26px; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px; text-transform: uppercase; }
        .menu a { position: relative; padding: 6px 0; color: var(--navy); transition: color 0.25s; }
        .menu a::after { content: ""; position: absolute; left: 0; bottom: 0; height: 2px; width: 100%; background: var(--gold); transform: scaleX(0); transform-origin: left; transition: transform 0.35s var(--ease); }
        .menu a:hover::after, .menu a.on::after { transform: scaleX(1); }
        .btn { position: relative; overflow: hidden; display: inline-flex; align-items: center; gap: 10px; padding: 12px 22px; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.9px; text-transform: uppercase; border: 1.5px solid transparent; border-radius: 6px; cursor: pointer; transition: transform 0.3s var(--ease), box-shadow 0.3s, background 0.3s; }
        .btn:hover { transform: translateY(-3px); }
        .btn:active { transform: scale(0.97); }
        .btn-gold { background: var(--gold); color: #fff; box-shadow: 0 10px 22px rgba(245, 166, 35, 0.35); }
        .btn-gold:hover { background: var(--gold-2); box-shadow: 0 16px 30px rgba(245, 166, 35, 0.5); }
        .btn-navy { background: var(--navy); color: #fff; box-shadow: 0 10px 22px rgba(15, 42, 92, 0.3); }
        .btn-navy:hover { background: var(--navy-2); }
        .btn-line { background: #fff; color: var(--navy); border-color: #c9d6ee; }
        .btn-line:hover { border-color: var(--navy); }
        .btn svg { transition: transform 0.3s var(--ease); }
        .btn:hover svg { transform: translateX(4px); }
        .ripple { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.5); transform: scale(0); animation: rip 0.6s ease-out forwards; pointer-events: none; }
        @keyframes rip { to { transform: scale(2.6); opacity: 0; } }

        /* Slot foto */
        .slot-img { display: block; width: 100%; height: 100%; object-fit: cover; }
        .slot-ph {
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; width: 100%; height: 100%;
            padding: 18px; text-align: center; color: #5a78b0;
            background: repeating-linear-gradient(135deg, #f1f6ff 0 14px, #e8f0fd 14px 28px);
            border: 2px dashed #a9bde3; border-radius: inherit; animation: pulse 2.8s ease-in-out infinite;
        }
        .slot-ph strong { font-size: 0.95rem; color: var(--navy); }
        .slot-ph small { font-size: 0.72rem; color: #7c8fb5; }

        /* Hero */
        .hero { position: relative; min-height: 600px; overflow: hidden; background: #fff; }
        .hero-media { position: absolute; top: 0; right: 0; bottom: 0; width: 64%; }
        .hero::before { content: ""; position: absolute; inset: 0; z-index: 1; background: linear-gradient(90deg, #fff 34%, rgba(255, 255, 255, 0.88) 48%, rgba(255, 255, 255, 0) 70%); }
        .orb { position: absolute; border-radius: 50%; z-index: 1; animation: drift 9s ease-in-out infinite; }
        .orb.a { width: 130px; height: 130px; background: rgba(34, 211, 238, 0.18); left: 36%; top: 60px; }
        .orb.b { width: 90px; height: 90px; background: rgba(245, 166, 35, 0.2); left: 30%; bottom: 70px; animation-delay: -3s; }
        .hero-grid { position: relative; z-index: 2; display: flex; align-items: center; min-height: 600px; padding-bottom: 40px; }
        .hero-text { max-width: 520px; padding: 40px 0; }
        .hero h1 { margin: 0 0 16px; font-family: var(--serif); font-size: clamp(2.2rem, 5vw, 3.4rem); line-height: 1.1; color: var(--navy); animation: up 0.9s var(--ease) both; }
        .hero h1 em { display: block; font-style: normal; color: var(--gold); }
        .hero p { margin: 0 0 26px; color: var(--muted); font-size: 1.02rem; animation: up 0.9s var(--ease) 0.15s both; }
        .hero .cta { display: flex; gap: 14px; flex-wrap: wrap; animation: up 0.9s var(--ease) 0.3s both; }
        .legacy { position: absolute; z-index: 3; right: 4%; bottom: 96px; display: flex; align-items: center; gap: 14px; padding: 16px 22px; background: #fff; border-radius: 16px; box-shadow: 0 20px 44px rgba(15, 42, 92, 0.18); animation: float 4s ease-in-out infinite; }
        .legacy .medal { width: 46px; height: 46px; display: grid; place-items: center; border-radius: 50%; background: linear-gradient(135deg, var(--gold), var(--gold-2)); color: #fff; font-size: 1.3rem; }
        .legacy small { display: block; font-size: 0.7rem; color: var(--muted); }
        .legacy b { font-family: var(--serif); font-size: 1.25rem; color: var(--navy); }

        /* Strip fitur */
        .strip { position: relative; z-index: 4; margin-top: -50px; }
        .strip .box { display: grid; grid-template-columns: repeat(5, 1fr); background: var(--navy); border-radius: 18px; padding: 26px 14px; box-shadow: 0 24px 50px rgba(15, 42, 92, 0.3); }
        .strip .it { display: flex; gap: 14px; padding: 4px 18px; color: #fff; border-right: 1px solid rgba(255, 255, 255, 0.14); transition: transform 0.35s var(--ease); }
        .strip .it:last-child { border-right: 0; }
        .strip .it:hover { transform: translateY(-5px); }
        .strip .ic { flex: none; width: 46px; height: 46px; display: grid; place-items: center; border: 1.5px solid var(--gold); border-radius: 50%; color: var(--gold); font-size: 1.3rem; transition: background 0.3s, color 0.3s; }
        .strip .it:hover .ic { background: var(--gold); color: var(--navy); }
        .strip b { display: block; font-size: 0.86rem; }
        .strip small { display: block; font-size: 0.74rem; color: #b9cdf0; line-height: 1.4; }

        /* Section umum */
        section.s { padding: 78px 0; }
        .eyebrow { font-size: 0.74rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); margin: 0 0 8px; }
        h2.t { margin: 0 0 14px; font-family: var(--serif); font-size: clamp(1.7rem, 3.4vw, 2.3rem); line-height: 1.2; color: var(--navy); }
        h2.t em { display: block; font-style: normal; color: var(--gold); }
        .lead { color: var(--muted); max-width: 520px; margin: 0 0 22px; }

        /* Tentang */
        .about { display: grid; grid-template-columns: 1fr 1.1fr 0.5fr; gap: 34px; align-items: center; }
        .mini { display: flex; gap: 26px; flex-wrap: wrap; margin-top: 26px; }
        .mini div { display: flex; align-items: center; gap: 10px; font-size: 0.8rem; color: var(--muted); }
        .mini .i { color: var(--navy); font-size: 1.6rem; }
        .mini b { display: block; font-family: var(--serif); color: var(--navy); font-size: 1.15rem; line-height: 1.1; }
        .about-img { height: 340px; border-radius: 18px; overflow: hidden; box-shadow: 0 24px 50px rgba(15, 42, 92, 0.16); transition: transform 0.5s var(--ease); }
        .about-img:hover { transform: scale(1.02); }
        .cards { display: grid; gap: 16px; }
        .card { display: flex; align-items: center; gap: 14px; padding: 18px 20px; background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 12px 30px rgba(15, 42, 92, 0.08); transition: transform 0.35s var(--ease), box-shadow 0.35s; }
        .card:hover { transform: translateY(-6px) translateX(-4px); box-shadow: 0 20px 40px rgba(15, 42, 92, 0.16); }
        .card .i { font-size: 1.7rem; color: var(--navy); }
        .card b { display: block; font-family: var(--serif); font-size: 1.3rem; color: var(--navy); line-height: 1.1; }
        .card small { color: var(--muted); font-size: 0.74rem; }

        /* Fitur */
        .feat { background: var(--cream); text-align: center; }
        .feat .lead { margin-inline: auto; }
        .grid5 { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 20px; margin: 34px 0 30px; text-align: left; }
        .fcard { background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 12px 30px rgba(15, 42, 92, 0.08); transition: transform 0.4s var(--ease), box-shadow 0.4s; }
        .fcard:hover { transform: translateY(-10px); box-shadow: 0 26px 46px rgba(15, 42, 92, 0.18); }
        .fcard .ph { height: 130px; overflow: hidden; }
        .fcard .ph > * { transition: transform 0.6s var(--ease); }
        .fcard:hover .ph > * { transform: scale(1.06); }
        .fcard .bd { padding: 16px 18px 20px; }
        .fcard b { display: block; color: var(--navy); font-size: 0.98rem; margin-bottom: 4px; }
        .fcard p { margin: 0 0 10px; font-size: 0.82rem; color: var(--muted); line-height: 1.45; }
        .fcard span { font-size: 0.78rem; font-weight: 700; color: var(--gold); }

        /* Statistik */
        .stats { padding: 0; }
        .stats .box { display: grid; grid-template-columns: repeat(5, 1fr); background: var(--navy); border-radius: 16px; padding: 22px 10px; color: #fff; }
        .stats .it { display: flex; align-items: center; justify-content: center; gap: 14px; border-right: 1px solid rgba(255, 255, 255, 0.14); padding: 4px 10px; }
        .stats .it:last-child { border-right: 0; }
        .stats .i { font-size: 1.9rem; color: var(--gold); transition: transform 0.4s var(--ease); }
        .stats .it:hover .i { transform: scale(1.2) rotate(-6deg); }
        .stats b { display: block; font-family: var(--serif); font-size: 1.7rem; color: var(--gold); line-height: 1.1; }
        .stats small { font-size: 0.74rem; color: #b9cdf0; }

        /* CTA */
        .join { display: grid; grid-template-columns: 1fr 1.15fr; background: var(--cream); border-radius: 18px; overflow: hidden; margin-top: 70px; }
        .join-img { min-height: 300px; border-radius: 0; }
        .join-body { padding: 44px 54px; display: flex; flex-direction: column; justify-content: center; }
        .join-body .cta { display: flex; gap: 14px; flex-wrap: wrap; }

        /* Footer */
        footer { margin-top: 20px; background: var(--navy); color: #cfe0ff; }
        footer .cols { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1.2fr; gap: 34px; padding: 50px 0 34px; }
        footer h4 { margin: 0 0 14px; font-size: 0.78rem; letter-spacing: 1.6px; text-transform: uppercase; color: #fff; }
        footer a, footer p, footer li { font-size: 0.86rem; color: #b9cdf0; }
        footer ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
        footer a:hover { color: var(--gold); }
        footer .brand b { color: #fff; }
        footer .brand small { color: #9fb8e6; }
        footer .copy { border-top: 1px solid rgba(255, 255, 255, 0.12); padding: 16px 0; text-align: center; font-size: 0.8rem; color: #9fb8e6; }

        @media (max-width: 980px) {
            .menu { display: none; }
            .about, .join { grid-template-columns: 1fr; }
            .strip .box, .stats .box { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .strip .it, .stats .it { border-right: 0; }
            .hero-media { width: 100%; opacity: 0.5; }
            .legacy { display: none; }
            footer .cols { grid-template-columns: 1fr 1fr; }
            .join-body { padding: 30px; }
        }
        @media (prefers-reduced-motion: reduce) { * { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; } .js .reveal { opacity: 1; transform: none; } }
    </style>
</head>
<body>
    {{-- Kumpulan ikon (SVG inline, tanpa library) --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <symbol id="i-pin" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></symbol>
        <symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
        <symbol id="i-users" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
        <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
        <symbol id="i-book" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></symbol>
        <symbol id="i-chart" viewBox="0 0 24 24"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></symbol>
        <symbol id="i-check" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></symbol>
        <symbol id="i-arrow" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></symbol>
        <symbol id="i-lock" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
        <symbol id="i-layers" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></symbol>
        <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></symbol>
        <symbol id="i-award" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></symbol>
        <symbol id="i-db" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></symbol>
    </svg>

    <div class="topbar">
        <div class="wrap">
            <div>
                <span><svg class="i"><use href="#i-pin"/></svg> Kampus ITS Sukolilo, Surabaya</span>
                <a href="mailto:info@akadema.test"><svg class="i"><use href="#i-mail"/></svg> info@akadema.test</a>
            </div>
            <div>
                <a href="{{ route('login') }}"><svg class="i"><use href="#i-user"/></svg> Login Admin / Staf</a>
            </div>
        </div>
    </div>

    <header class="nav" id="nav">
        <div class="wrap">
            <a href="#beranda" class="brand">
                <img src="{{ asset($brandLogo) }}" alt="Logo {{ $brandName }}">
                <span><b>{{ $brandName }}</b><small>Teknik Informatika ITS</small></span>
            </a>
            <nav class="menu">
                <a href="#beranda" class="on">Beranda</a>
                <a href="#tentang">Tentang</a>
                <a href="#fitur">Fitur</a>
                <a href="#statistik">Statistik</a>
                <a href="#kontak">Kontak</a>
            </nav>
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-gold">{{ auth()->check() ? 'Buka Dashboard' : 'Masuk Portal' }}</a>
        </div>
    </header>

    <section class="hero" id="beranda">
        <div class="hero-media">
            <x-foto-slot name="hero" label="Tambah background atau gambar hero di sini" />
        </div>
        <span class="orb a"></span><span class="orb b"></span>
        <div class="wrap hero-grid">
            <div class="hero-text">
                <h1>Kelola Data Akademik.<em>Lebih Rapi, Lebih Aman.</em></h1>
                <p>Portal {{ $brandName }} membantu Departemen Teknik Informatika ITS mengelola data mahasiswa, dosen, dan tugas dalam satu dashboard, dengan hak akses yang jelas untuk setiap peran.</p>
                <div class="cta">
                    <a href="{{ route('login') }}" class="btn btn-navy">Masuk Portal <svg class="i"><use href="#i-arrow"/></svg></a>
                    <a href="#fitur" class="btn btn-line">Lihat Fitur</a>
                </div>
            </div>
        </div>
        <div class="legacy">
            <span class="medal"><svg class="i"><use href="#i-award"/></svg></span>
            <span><small>Berbasis Role</small><b>Akses Terkontrol</b></span>
        </div>
    </section>

    <div class="strip">
        <div class="wrap">
            <div class="box reveal">
                <div class="it"><span class="ic"><svg class="i"><use href="#i-chart"/></svg></span><span><b>Dashboard Informatif</b><small>Ringkasan angka penting dalam satu layar.</small></span></div>
                <div class="it"><span class="ic"><svg class="i"><use href="#i-shield"/></svg></span><span><b>Hak Akses Role</b><small>Admin dan Staf punya wewenang berbeda.</small></span></div>
                <div class="it"><span class="ic"><svg class="i"><use href="#i-search"/></svg></span><span><b>Pencarian Cepat</b><small>Cari berdasarkan nama, NRP, atau departemen.</small></span></div>
                <div class="it"><span class="ic"><svg class="i"><use href="#i-db"/></svg></span><span><b>Data Terpusat</b><small>Mahasiswa, dosen, dan tugas di satu tempat.</small></span></div>
                <div class="it"><span class="ic"><svg class="i"><use href="#i-lock"/></svg></span><span><b>Login Terlindungi</b><small>Halaman admin hanya bisa dibuka setelah masuk.</small></span></div>
            </div>
        </div>
    </div>

    <section class="s" id="tentang">
        <div class="wrap about">
            <div class="reveal">
                <p class="eyebrow">Tentang Portal</p>
                <h2 class="t">Satu Portal untuk Seluruh Data Akademik<em>Tertata dan Terkendali</em></h2>
                <p class="lead">{{ $brandName }} dirancang agar pengelolaan data mahasiswa, dosen, dan tugas kuliah di lingkungan Teknik Informatika ITS menjadi lebih cepat, rapi, dan mudah diaudit.</p>
                <a href="#fitur" class="btn btn-navy">Pelajari Fitur</a>
                <div class="mini">
                    <div><svg class="i"><use href="#i-users"/></svg><span><b data-count="{{ $totalMahasiswa }}">{{ $totalMahasiswa }}</b>Mahasiswa</span></div>
                    <div><svg class="i"><use href="#i-book"/></svg><span><b data-count="{{ $totalDosen }}">{{ $totalDosen }}</b>Dosen</span></div>
                    <div><svg class="i"><use href="#i-check"/></svg><span><b data-count="{{ $totalTugas }}">{{ $totalTugas }}</b>Tugas</span></div>
                </div>
            </div>
            <div class="about-img reveal" style="--d: 120ms">
                <x-foto-slot name="tentang" label="Tambah foto gedung Teknik Informatika ITS di sini" />
            </div>
            <div class="cards">
                <div class="card reveal" style="--d: 180ms"><svg class="i"><use href="#i-layers"/></svg><span><b data-count="{{ $totalDepartemen }}">{{ $totalDepartemen }}</b><small>Departemen terdata</small></span></div>
                <div class="card reveal" style="--d: 280ms"><svg class="i"><use href="#i-user"/></svg><span><b data-count="{{ $totalRole }}">{{ $totalRole }}</b><small>Role pengguna</small></span></div>
                <div class="card reveal" style="--d: 380ms"><svg class="i"><use href="#i-shield"/></svg><span><b data-count="{{ $totalPermission }}">{{ $totalPermission }}</b><small>Jenis hak akses</small></span></div>
            </div>
        </div>
    </section>

    <section class="s feat" id="fitur">
        <div class="wrap">
            <div class="reveal">
                <p class="eyebrow">Fitur</p>
                <h2 class="t">Jelajahi Fitur Portal</h2>
                <p class="lead">Fitur lengkap untuk memantau dan mengelola data akademik sehari-hari.</p>
            </div>
            <div class="grid5">
                <div class="fcard reveal" style="--d: 0ms"><div class="ph"><x-foto-slot name="fitur-1" label="Tambah gambar fitur" /></div><div class="bd"><b>Dashboard Statistik</b><p>Widget ringkasan jumlah mahasiswa, dosen, dan tugas.</p><span>Admin dan Staf</span></div></div>
                <div class="fcard reveal" style="--d: 90ms"><div class="ph"><x-foto-slot name="fitur-2" label="Tambah gambar fitur" /></div><div class="bd"><b>Data Mahasiswa</b><p>Cari dan saring berdasarkan nama, NRP, departemen, atau status.</p><span>Admin dan Staf</span></div></div>
                <div class="fcard reveal" style="--d: 180ms"><div class="ph"><x-foto-slot name="fitur-3" label="Tambah gambar fitur" /></div><div class="bd"><b>Data Dosen</b><p>Daftar dosen lengkap dengan NIP dan departemen.</p><span>Admin dan Staf</span></div></div>
                <div class="fcard reveal" style="--d: 270ms"><div class="ph"><x-foto-slot name="fitur-4" label="Tambah gambar fitur" /></div><div class="bd"><b>Tugas Kuliah</b><p>Pantau tenggat dan status tugas yang masih aktif.</p><span>Admin dan Staf</span></div></div>
                <div class="fcard reveal" style="--d: 360ms"><div class="ph"><x-foto-slot name="fitur-5" label="Tambah gambar fitur" /></div><div class="bd"><b>Manajemen Role</b><p>Atur siapa yang boleh melihat dan menghapus data.</p><span>Khusus Admin</span></div></div>
            </div>
            <a href="{{ route('login') }}" class="btn btn-navy reveal">Masuk untuk Melihat Semua <svg class="i"><use href="#i-arrow"/></svg></a>
        </div>
    </section>

    <section class="s stats" id="statistik" style="padding-top: 34px;">
        <div class="wrap">
            <div class="box reveal">
                <div class="it"><svg class="i"><use href="#i-users"/></svg><span><b data-count="{{ $totalMahasiswa }}">{{ $totalMahasiswa }}</b><small>Mahasiswa terdata</small></span></div>
                <div class="it"><svg class="i"><use href="#i-book"/></svg><span><b data-count="{{ $totalDosen }}">{{ $totalDosen }}</b><small>Dosen terdata</small></span></div>
                <div class="it"><svg class="i"><use href="#i-check"/></svg><span><b data-count="{{ $totalTugas }}">{{ $totalTugas }}</b><small>Tugas kuliah</small></span></div>
                <div class="it"><svg class="i"><use href="#i-layers"/></svg><span><b data-count="{{ $totalDepartemen }}">{{ $totalDepartemen }}</b><small>Departemen</small></span></div>
                <div class="it"><svg class="i"><use href="#i-shield"/></svg><span><b data-count="{{ $totalPermission }}">{{ $totalPermission }}</b><small>Jenis hak akses</small></span></div>
            </div>

            <div class="join reveal">
                <div class="join-img"><x-foto-slot name="cta" label="Tambah foto kegiatan mahasiswa Teknik Informatika ITS" /></div>
                <div class="join-body">
                    <p class="eyebrow">Mulai Sekarang</p>
                    <h2 class="t">Mulai Kelola Data Akademik Bersama {{ $brandName }}</h2>
                    <p class="lead">Masuk dengan akun Admin atau Staf untuk membuka dashboard. Belum punya akses? Hubungi administrator portal.</p>
                    <div class="cta">
                        <a href="{{ route('login') }}" class="btn btn-gold">Masuk Portal <svg class="i"><use href="#i-arrow"/></svg></a>
                        <a href="mailto:info@akadema.test" class="btn btn-line">Hubungi Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak">
        <div class="wrap">
            <div class="cols">
                <div>
                    <a href="#beranda" class="brand">
                        <img src="{{ asset($brandLogo) }}" alt="Logo {{ $brandName }}">
                        <span><b>{{ $brandName }}</b><small>Teknik Informatika ITS</small></span>
                    </a>
                    <p style="margin-top: 14px;">Portal data akademik dengan dashboard dan hak akses berbasis role.</p>
                </div>
                <div>
                    <h4>Tautan</h4>
                    <ul>
                        <li><a href="#tentang">Tentang</a></li>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#statistik">Statistik</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Akses</h4>
                    <ul>
                        <li><a href="{{ route('login') }}">Login Admin / Staf</a></li>
                        <li><a href="{{ route('login') }}">Masuk Portal</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Kontak</h4>
                    <ul>
                        <li><svg class="i"><use href="#i-pin"/></svg> Departemen Teknik Informatika, Kampus ITS Sukolilo, Surabaya</li>
                        <li><svg class="i"><use href="#i-mail"/></svg> <a href="mailto:info@akadema.test">info@akadema.test</a></li>
                    </ul>
                </div>
            </div>
            <div class="copy">&copy; {{ date('Y') }} {{ $brandName }}. Dibuat oleh Arya Rangga Putra Pratama &middot; 5025241072.</div>
        </div>
    </footer>

    <script>
        (function () {
            // Bayangan navbar saat halaman digulir.
            var nav = document.getElementById('nav');
            window.addEventListener('scroll', function () { nav.classList.toggle('scrolled', window.scrollY > 10); }, { passive: true });

            // Elemen muncul perlahan saat masuk layar.
            var reveals = document.querySelectorAll('.reveal');
            if ('IntersectionObserver' in window) {
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
                }, { threshold: 0.15 });
                reveals.forEach(function (el) { io.observe(el); });
            } else {
                reveals.forEach(function (el) { el.classList.add('in'); });
            }

            // Angka menghitung naik saat terlihat.
            var counters = document.querySelectorAll('[data-count]');
            var cio = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) { return; }
                    cio.unobserve(e.target);
                    var el = e.target, target = parseInt(el.getAttribute('data-count'), 10) || 0, start = null;
                    function step(ts) {
                        if (start === null) { start = ts; }
                        var p = Math.min((ts - start) / 1200, 1);
                        el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString('id-ID');
                        if (p < 1) { requestAnimationFrame(step); }
                    }
                    requestAnimationFrame(step);
                });
            }, { threshold: 0.4 });
            counters.forEach(function (el) { cio.observe(el); });

            // Menu aktif mengikuti bagian yang sedang dilihat.
            var links = document.querySelectorAll('.menu a');
            var sections = Array.prototype.map.call(links, function (a) { return document.querySelector(a.getAttribute('href')); });
            window.addEventListener('scroll', function () {
                var pos = window.scrollY + 140, aktif = 0;
                sections.forEach(function (s, i) { if (s && s.offsetTop <= pos) { aktif = i; } });
                links.forEach(function (a, i) { a.classList.toggle('on', i === aktif); });
            }, { passive: true });

            // Ripple pada tombol.
            document.addEventListener('click', function (e) {
                var b = e.target.closest('.btn');
                if (!b) { return; }
                var r = b.getBoundingClientRect(), d = Math.max(r.width, r.height), s = document.createElement('span');
                s.className = 'ripple';
                s.style.cssText = 'width:' + d + 'px;height:' + d + 'px;left:' + (e.clientX - r.left - d / 2) + 'px;top:' + (e.clientY - r.top - d / 2) + 'px';
                b.appendChild(s);
                setTimeout(function () { s.remove(); }, 600);
            });

            // Fade-out saat pindah ke halaman lain (bukan anchor di halaman ini).
            document.addEventListener('click', function (e) {
                var a = e.target.closest('a[href]');
                if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey) { return; }
                var raw = a.getAttribute('href');
                if (!raw || raw.charAt(0) === '#' || raw.indexOf('mailto:') === 0 || a.target === '_blank') { return; }
                e.preventDefault();
                document.body.classList.add('leaving');
                setTimeout(function () { window.location.href = a.href; }, 230);
            });
            window.addEventListener('pageshow', function (e) { if (e.persisted) { document.body.classList.remove('leaving'); } });
        })();
    </script>
</body>
</html>

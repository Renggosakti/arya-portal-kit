<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="color-scheme" content="only light">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | {{ $brandName }}</title>
    <style>
        :root {
            --blue: #3b82f6; --cyan: #22d3ee; --pink: #ff5c93; --orange: #ffa63d;
            --green: #22c88f; --purple: #8b5cf6; --ink: #1f2a44; --muted: #6b7a99; --line: #e3ebfa;
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: "Segoe UI", system-ui, -apple-system, Roboto, Arial, sans-serif;
            color: var(--ink); background: #ffffff; overflow: hidden; position: relative;
            animation: fade 0.6s ease both;
        }
        @keyframes fade { from { opacity: 0; } to { opacity: 1; } }
        @keyframes rise { from { opacity: 0; transform: translateY(26px) scale(0.98); } to { opacity: 1; transform: none; } }
        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -30px) scale(1.12); }
        }
        @keyframes bob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        @keyframes pop { 0% { opacity: 0; transform: scale(0.9); } 70% { transform: scale(1.04); } 100% { opacity: 1; transform: none; } }

        .blob { position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.55; animation: drift 12s ease-in-out infinite; }
        .b1 { width: 380px; height: 380px; background: #bfe3ff; top: -90px; left: -80px; }
        .b2 { width: 320px; height: 320px; background: #ffd1e1; bottom: -80px; right: -60px; animation-delay: -4s; }
        .b3 { width: 260px; height: 260px; background: #d4f7e8; top: 40%; right: 18%; animation-delay: -8s; }
        .b4 { width: 220px; height: 220px; background: #ffe8c2; bottom: 12%; left: 14%; animation-delay: -2s; }

        .card {
            position: relative; width: min(430px, 92vw); padding: 34px 32px 28px;
            background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(14px);
            border: 1px solid var(--line); border-radius: 26px;
            box-shadow: 0 30px 70px rgba(59, 130, 246, 0.18);
            animation: rise 0.8s var(--ease) both;
        }
        .logo { display: flex; justify-content: center; margin-bottom: 12px; }
        .logo img { width: 62px; height: 62px; border-radius: 18px; animation: bob 3s ease-in-out infinite; box-shadow: 0 12px 26px rgba(59, 130, 246, 0.35); }
        h1 { margin: 6px 0 2px; text-align: center; font-size: 1.6rem; }
        .by { margin: 0 0 22px; text-align: center; color: var(--muted); font-size: 0.88rem; }
        .by b { color: var(--blue); }

        label { display: block; margin: 14px 0 6px; font-size: 0.82rem; font-weight: 600; color: var(--muted); }
        input[type=email], input[type=password] {
            width: 100%; padding: 12px 14px; font-size: 0.95rem; color: var(--ink);
            border: 1px solid var(--line); border-radius: 13px; background: #fbfdff; outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
        }
        input:focus { border-color: var(--blue); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.16); transform: translateY(-1px); }
        .row { display: flex; align-items: center; justify-content: space-between; margin-top: 14px; font-size: 0.85rem; color: var(--muted); }
        .row a { color: var(--blue); text-decoration: none; }
        .row a:hover { text-decoration: underline; }

        .btn {
            position: relative; overflow: hidden; width: 100%; margin-top: 20px; padding: 13px;
            font-size: 1rem; font-weight: 700; color: #fff; border: 0; border-radius: 14px; cursor: pointer;
            background: linear-gradient(135deg, var(--blue), var(--cyan));
            box-shadow: 0 12px 24px rgba(59, 130, 246, 0.35);
            transition: transform 0.25s var(--ease), box-shadow 0.25s;
        }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 18px 32px rgba(59, 130, 246, 0.45); }
        .btn:active { transform: scale(0.98); }
        .btn::after {
            content: ""; position: absolute; top: 0; bottom: 0; left: 0; width: 60px; background: rgba(255, 255, 255, 0.4);
            transform: translateX(-140%) skewX(-20deg); transition: transform 0.7s ease;
        }
        .btn:hover::after { transform: translateX(520%) skewX(-20deg); }

        .err { margin: 6px 0 0; font-size: 0.82rem; color: #be123c; animation: pop 0.4s var(--ease) both; }
        .status { margin-bottom: 8px; padding: 10px 14px; border-radius: 12px; background: #dcfce7; color: #15803d; font-size: 0.88rem; }

        .demo { margin-top: 24px; padding-top: 18px; border-top: 1px dashed var(--line); }
        .demo p { margin: 0 0 10px; text-align: center; font-size: 0.78rem; letter-spacing: 1.2px; text-transform: uppercase; color: var(--muted); }
        .demo .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .demo button {
            width: 100%; padding: 10px 6px; font-size: 0.85rem; font-weight: 700; color: #fff; border: 0; border-radius: 12px; cursor: pointer;
            transition: transform 0.25s var(--ease), box-shadow 0.25s; animation: pop 0.6s var(--ease) both;
        }
        .demo button:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 12px 22px rgba(31, 42, 68, 0.2); }
        .demo form:nth-child(1) button { background: linear-gradient(135deg, var(--pink), var(--orange)); animation-delay: 0.3s; }
        .demo form:nth-child(2) button { background: linear-gradient(135deg, var(--green), #6ee7b7); animation-delay: 0.4s; }
        .demo form:nth-child(3) button { background: linear-gradient(135deg, var(--purple), #ec7bff); animation-delay: 0.5s; }
        .foot { margin-top: 18px; text-align: center; font-size: 0.78rem; color: var(--muted); }
        .page-leaving { opacity: 0; transition: opacity 0.25s ease; }
        @media (prefers-reduced-motion: reduce) { * { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; } }
    </style>
</head>
<body>
    <span class="blob b1"></span>
    <span class="blob b2"></span>
    <span class="blob b3"></span>
    <span class="blob b4"></span>

    <main class="card">
        <div class="logo"><img src="{{ asset($brandLogo) }}" alt="Logo"></div>
        <h1>{{ $brandName }}</h1>
        <p class="by">dibuat oleh <b>Arya Rangga Putra Pratama</b> &middot; 5025241072</p>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <p class="err">{{ $message }}</p> @enderror

            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password') <p class="err">{{ $message }}</p> @enderror

            <div class="row">
                <label style="margin:0;font-weight:400;display:flex;gap:6px;align-items:center;">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>

            <button type="submit" class="btn">Masuk</button>
        </form>

        {{-- Tombol Auto-Login: hanya tampil saat APP_ENV=local, untuk mempermudah demo dosen/asdos --}}
        @if (app()->environment('local'))
            <div class="demo">
                <p>Login instan (demo)</p>
                <div class="grid">
                    <form method="POST" action="{{ route('login.demo', 'admin') }}">@csrf<button type="submit">Admin</button></form>
                    <form method="POST" action="{{ route('login.demo', 'staf') }}">@csrf<button type="submit">Staf</button></form>
                    <form method="POST" action="{{ route('login.demo', 'pengguna') }}">@csrf<button type="submit">Pengguna</button></form>
                </div>
            </div>
        @endif

        <p class="foot">
            @if (Route::has('register'))
                Belum punya akun? <a href="{{ route('register') }}" style="color:var(--blue);text-decoration:none;">Daftar</a>
            @endif
        </p>
    </main>

    <script>
        // Transisi fade saat pindah halaman dari login.
        document.addEventListener('click', function (e) {
            var a = e.target.closest('a[href]');
            if (!a || a.getAttribute('href').charAt(0) === '#' || a.target === '_blank') { return; }
            e.preventDefault();
            document.body.classList.add('page-leaving');
            setTimeout(function () { window.location.href = a.href; }, 240);
        });
    </script>
</body>
</html>

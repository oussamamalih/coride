<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CoRide – Covoiturage intelligent pour salariés d'entreprises partenaires">
    <title>@yield('title', 'CoRide') — Covoiturage Entreprise</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Design System CoRide ─────────────────────────── */
        :root {
            --cr-bg:        #0d1117;
            --cr-surface:   #161b22;
            --cr-border:    rgba(99,102,241,.25);
            --cr-indigo:    #6366f1;
            --cr-emerald:   #10b981;
            --cr-amber:     #f59e0b;
            --cr-red:       #ef4444;
            --cr-text:      #e6edf3;
            --cr-muted:     #8b949e;
            --cr-glass:     rgba(22,27,34,.7);
        }
        * { box-sizing: border-box; }
        body {
            background: var(--cr-bg);
            color: var(--cr-text);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -20%, rgba(99,102,241,.18) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 80% 100%, rgba(16,185,129,.10) 0%, transparent 70%);
        }
        /* Glassmorphism card */
        .glass {
            background: var(--cr-glass);
            border: 1px solid var(--cr-border);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 1rem;
        }
        /* Navbar */
        .navbar {
            background: rgba(13,17,23,.85);
            border-bottom: 1px solid var(--cr-border);
            backdrop-filter: blur(16px);
            position: sticky; top: 0; z-index: 100;
        }
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--cr-indigo), #818cf8);
            color: #fff; border: none; cursor: pointer;
            padding: .6rem 1.4rem; border-radius: .6rem;
            font-weight: 600; font-size: .9rem;
            transition: all .2s; display: inline-flex; align-items: center; gap: .5rem;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(99,102,241,.4); }
        .btn-secondary {
            background: transparent; color: var(--cr-text);
            border: 1px solid var(--cr-border); cursor: pointer;
            padding: .6rem 1.4rem; border-radius: .6rem;
            font-weight: 500; font-size: .9rem; transition: all .2s;
            display: inline-flex; align-items: center; gap: .5rem;
        }
        .btn-secondary:hover { border-color: var(--cr-indigo); color: var(--cr-indigo); }
        .btn-danger {
            background: linear-gradient(135deg,#dc2626,#ef4444);
            color:#fff; border:none; cursor:pointer;
            padding:.5rem 1.2rem; border-radius:.6rem; font-weight:600;
            font-size:.85rem; transition:all .2s;
        }
        .btn-danger:hover { transform:translateY(-1px); box-shadow:0 4px 15px rgba(239,68,68,.35); }
        .btn-success {
            background: linear-gradient(135deg,#059669,#10b981);
            color:#fff; border:none; cursor:pointer;
            padding:.5rem 1.2rem; border-radius:.6rem; font-weight:600;
            font-size:.85rem; transition:all .2s;
        }
        .btn-success:hover { transform:translateY(-1px); box-shadow:0 4px 15px rgba(16,185,129,.35); }
        /* Form inputs */
        .input {
            background: rgba(255,255,255,.05);
            border: 1px solid var(--cr-border);
            color: var(--cr-text); border-radius: .6rem;
            padding: .65rem 1rem; width: 100%;
            font-size: .9rem; transition: border-color .2s;
        }
        .input:focus { outline: none; border-color: var(--cr-indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .input::placeholder { color: var(--cr-muted); }
        label { font-size: .85rem; font-weight: 500; color: var(--cr-muted); display: block; margin-bottom: .4rem; }
        /* Badges statut */
        .badge { padding: .3rem .8rem; border-radius: 2rem; font-size: .78rem; font-weight: 600; }
        .badge-confirmee  { background: rgba(16,185,129,.15); color: #10b981; border: 1px solid rgba(16,185,129,.3); }
        .badge-refusee    { background: rgba(239,68,68,.15); color: #ef4444; border: 1px solid rgba(239,68,68,.3); }
        .badge-annulee    { background: rgba(107,114,128,.15); color: #9ca3af; border: 1px solid rgba(107,114,128,.3); }
        .badge-attente    { background: rgba(245,158,11,.15); color: #f59e0b; border: 1px solid rgba(245,158,11,.3); }
        /* Score ring */
        .score-ring { position: relative; display: inline-flex; align-items: center; justify-content: center; }
        .score-ring svg { transform: rotate(-90deg); }
        .score-ring .score-value { position: absolute; font-weight: 800; font-size: 1.1rem; }
        /* Alert banners */
        .alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3); border-radius: .7rem; padding: 1rem 1.2rem; color: #10b981; }
        .alert-error   { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: .7rem; padding: 1rem 1.2rem; color: #ef4444; }
        /* Section titles */
        .page-title { font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #fff, var(--cr-indigo)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        /* Avatar initiales */
        .avatar { width: 2.2rem; height: 2.2rem; border-radius: 50%; background: linear-gradient(135deg, var(--cr-indigo), #818cf8); display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 700; color: #fff; flex-shrink: 0; }
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeInUp .4s ease both; }
        @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 0 0 rgba(99,102,241,.3); } 50% { box-shadow: 0 0 0 8px rgba(99,102,241,0); } }
        .pulse { animation: pulse-glow 2s infinite; }
        /* Stat cards */
        .stat-card { text-align: center; padding: 1.5rem; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; color: var(--cr-indigo); }
        .stat-card .stat-label { font-size: .8rem; color: var(--cr-muted); margin-top: .25rem; text-transform: uppercase; letter-spacing: .05em; }
        /* Grid helpers */
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; }
        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
    </style>
</head>
<body>

{{-- ═══ NAVBAR ════════════════════════════════════════════════════════════════ --}}
<nav class="navbar">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;padding-top:.85rem;padding-bottom:.85rem;">
        {{-- Logo --}}
        <a href="{{ route('trajets.index') }}" style="display:flex;align-items:center;gap:.7rem;text-decoration:none;">
            <div style="width:2.2rem;height:2.2rem;background:linear-gradient(135deg,var(--cr-indigo),#818cf8);border-radius:.6rem;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <span style="font-size:1.2rem;font-weight:800;color:#fff;letter-spacing:-.02em;">CoRide</span>
        </a>

        {{-- Nav links --}}
        <div style="display:flex;align-items:center;gap:1.5rem;">
            <a href="{{ route('trajets.index') }}" style="color:var(--cr-muted);text-decoration:none;font-size:.9rem;font-weight:500;transition:color .2s;"
               onmouseover="this.style.color='var(--cr-text)'" onmouseout="this.style.color='var(--cr-muted)'">
                🗺️ Trajets
            </a>
            @auth
                <a href="{{ route('reservations.index') }}" style="color:var(--cr-muted);text-decoration:none;font-size:.9rem;font-weight:500;transition:color .2s;"
                   onmouseover="this.style.color='var(--cr-text)'" onmouseout="this.style.color='var(--cr-muted)'">
                    🎫 Mes réservations
                </a>
                @if(auth()->user()->estConducteur())
                <a href="{{ route('conducteur.dashboard') }}" style="color:var(--cr-muted);text-decoration:none;font-size:.9rem;font-weight:500;transition:color .2s;"
                   onmouseover="this.style.color='var(--cr-text)'" onmouseout="this.style.color='var(--cr-muted)'">
                    🚗 Dashboard
                </a>
                @endif
            @endauth
        </div>

        {{-- User menu --}}
        <div style="display:flex;align-items:center;gap:1rem;">
            @auth
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div class="avatar">{{ auth()->user()->initiales }}</div>
                    <div style="line-height:1.2;">
                        <div style="font-size:.85rem;font-weight:600;">{{ auth()->user()->name }}</div>
                        <div style="font-size:.72rem;color:var(--cr-muted);">{{ auth()->user()->role_libelle }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-secondary" style="padding:.45rem 1rem;font-size:.82rem;">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-secondary" style="font-size:.88rem;">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary" style="font-size:.88rem;">S'inscrire</a>
            @endauth
        </div>
    </div>
</nav>

{{-- ═══ Flash Messages ════════════════════════════════════════════════════════ --}}
@if(session('success'))
<div class="container" style="padding-top:1rem;">
    <div class="alert-success fade-in">✅ {{ session('success') }}</div>
</div>
@endif
@if(session('error'))
<div class="container" style="padding-top:1rem;">
    <div class="alert-error fade-in">❌ {{ session('error') }}</div>
</div>
@endif

{{-- ═══ MAIN CONTENT ══════════════════════════════════════════════════════════ --}}
<main style="padding:2rem 0 4rem;">
    @yield('content')
</main>

{{-- ═══ FOOTER ════════════════════════════════════════════════════════════════ --}}
<footer style="border-top:1px solid var(--cr-border);padding:2rem 0;margin-top:2rem;">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;">
        <div style="color:var(--cr-muted);font-size:.82rem;">© 2026 CoRide · MobiliTech — Mobilité durable en entreprise</div>
        <div style="display:flex;gap:1rem;align-items:center;">
            <span style="width:.5rem;height:.5rem;background:var(--cr-emerald);border-radius:50%;display:inline-block;animation:pulse-glow 2s infinite;"></span>
            <span style="color:var(--cr-muted);font-size:.8rem;">IA by Laravel AI SDK</span>
        </div>
    </div>
</footer>

</body>
</html>

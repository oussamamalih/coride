<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — CoRide</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --cr-bg:#0d1117; --cr-surface:#161b22; --cr-border:rgba(99,102,241,.25); --cr-indigo:#6366f1; --cr-emerald:#10b981; --cr-text:#e6edf3; --cr-muted:#8b949e; --cr-glass:rgba(22,27,34,.7); }
        * { box-sizing:border-box; }
        body { background:var(--cr-bg); color:var(--cr-text); font-family:'Inter',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; background-image:radial-gradient(ellipse 80% 60% at 50% -20%,rgba(99,102,241,.2) 0%,transparent 70%); }
        .glass { background:var(--cr-glass); border:1px solid var(--cr-border); backdrop-filter:blur(12px); border-radius:1rem; }
        .input { background:rgba(255,255,255,.05); border:1px solid var(--cr-border); color:var(--cr-text); border-radius:.6rem; padding:.65rem 1rem; width:100%; font-size:.9rem; transition:border-color .2s; font-family:inherit; }
        .input:focus { outline:none; border-color:var(--cr-indigo); box-shadow:0 0 0 3px rgba(99,102,241,.15); }
        .input::placeholder { color:var(--cr-muted); }
        label { font-size:.82rem; font-weight:500; color:var(--cr-muted); display:block; margin-bottom:.4rem; }
        .btn-primary { background:linear-gradient(135deg,var(--cr-indigo),#818cf8); color:#fff; border:none; cursor:pointer; padding:.75rem 1.5rem; border-radius:.6rem; font-weight:700; font-size:.95rem; width:100%; transition:all .2s; font-family:inherit; }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 20px rgba(99,102,241,.4); }
        .error { color:#ef4444; font-size:.78rem; margin-top:.3rem; }
        .demo-badge { background:rgba(99,102,241,.1); border:1px solid rgba(99,102,241,.3); border-radius:.5rem; padding:.5rem .9rem; font-size:.78rem; color:var(--cr-muted); font-family:monospace; }
    </style>
</head>
<body>
<div style="width:100%;max-width:420px;">
    {{-- Logo --}}
    <div style="text-align:center;margin-bottom:2rem;">
        <a href="{{ route('trajets.index') }}" style="display:inline-flex;align-items:center;gap:.75rem;text-decoration:none;">
            <div style="width:3rem;height:3rem;background:linear-gradient(135deg,var(--cr-indigo),#818cf8);border-radius:.75rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <span style="font-size:1.5rem;font-weight:800;color:#fff;">CoRide</span>
        </a>
        <p style="color:var(--cr-muted);font-size:.88rem;margin-top:.75rem;">Covoiturage intelligent entre collègues</p>
    </div>

    <div class="glass" style="padding:2rem;">
        <h1 style="font-size:1.2rem;font-weight:700;margin:0 0 1.5rem;text-align:center;">Connexion</h1>

        @if(session('status'))
            <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:.6rem;padding:.75rem 1rem;margin-bottom:1.2rem;color:#10b981;font-size:.85rem;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label for="email">Email professionnel</label>
                <input id="email" name="email" type="email" class="input"
                       placeholder="nom@entreprise.com" value="{{ old('email') }}" required autofocus>
                @error('email')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div style="margin-bottom:1.5rem;">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" class="input"
                       placeholder="••••••••" required>
                @error('password')<p class="error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary" style="margin-bottom:1rem;">Se connecter</button>
        </form>

        <p style="text-align:center;color:var(--cr-muted);font-size:.85rem;margin-top:1rem;">
            Pas encore de compte ? <a href="{{ route('register') }}" style="color:var(--cr-indigo);text-decoration:none;font-weight:600;">S'inscrire</a>
        </p>

        {{-- Demo accounts --}}
        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--cr-border);">
            <p style="color:var(--cr-muted);font-size:.75rem;text-align:center;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em;">Comptes de démonstration</p>
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                <div class="demo-badge">🚗 Conducteur : rachid.alaoui@greenlogix.com / password</div>
                <div class="demo-badge">🎫 Passager : othmane.benjelloun@mobilitech.com / password</div>
                <div class="demo-badge">🔄 Les deux : nouhaila.cherkaoui@atlasdigital.com / password</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

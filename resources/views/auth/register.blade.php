<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — CoRide</title>
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
        select.input { cursor:pointer; }
    </style>
</head>
<body>
<div style="width:100%;max-width:520px;">
    {{-- Logo --}}
    <div style="text-align:center;margin-bottom:2rem;">
        <a href="{{ route('trajets.index') }}" style="display:inline-flex;align-items:center;gap:.75rem;text-decoration:none;">
            <div style="width:3rem;height:3rem;background:linear-gradient(135deg,var(--cr-indigo),#818cf8);border-radius:.75rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <span style="font-size:1.5rem;font-weight:800;color:#fff;">CoRide</span>
        </a>
        <p style="color:var(--cr-muted);font-size:.88rem;margin-top:.75rem;">Rejoignez le covoiturage intelligent de votre entreprise</p>
    </div>

    <div class="glass" style="padding:2rem;">
        <h1 style="font-size:1.2rem;font-weight:700;margin:0 0 1.5rem;text-align:center;">Créer votre compte</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div style="grid-column:1/-1;">
                    <label for="name">Nom complet *</label>
                    <input id="name" name="name" type="text" class="input" placeholder="Prénom Nom"
                           value="{{ old('name') }}" required autofocus>
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div style="grid-column:1/-1;">
                    <label for="email">Email professionnel *</label>
                    <input id="email" name="email" type="email" class="input" placeholder="nom@entreprise.com"
                           value="{{ old('email') }}" required>
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label for="entreprise_id">Entreprise *</label>
                    <select id="entreprise_id" name="entreprise_id" class="input" required>
                        <option value="">Sélectionner...</option>
                        @foreach(\App\Models\Entreprise::orderBy('nom')->get() as $e)
                            <option value="{{ $e->id }}" {{ old('entreprise_id') == $e->id ? 'selected' : '' }}>{{ $e->nom }}</option>
                        @endforeach
                    </select>
                    @error('entreprise_id')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ville_residence">Ville de résidence *</label>
                    <input id="ville_residence" name="ville_residence" type="text" class="input"
                           placeholder="Ex: Rabat" value="{{ old('ville_residence') }}" required list="villes-list">
                    <datalist id="villes-list">
                        @foreach(['Rabat','Casablanca','Salé','Témara','Skhirat','Mohammedia','Bouznika'] as $v)
                            <option value="{{ $v }}">
                        @endforeach
                    </datalist>
                    @error('ville_residence')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="margin-bottom:1rem;">
                <label>Rôle *</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem;margin-top:.4rem;">
                    @foreach(['passager' => '🎫 Passager', 'conducteur' => '🚗 Conducteur', 'les_deux' => '🔄 Les deux'] as $val => $label)
                    <label style="cursor:pointer;margin:0;">
                        <input type="radio" name="role" value="{{ $val }}" {{ old('role','passager') === $val ? 'checked' : '' }}
                               style="display:none;" class="role-radio" id="role_{{ $val }}">
                        <div class="role-opt" id="role-opt-{{ $val }}"
                             style="border:1px solid {{ old('role','passager') === $val ? 'var(--cr-indigo)' : 'var(--cr-border)' }};border-radius:.6rem;padding:.6rem;text-align:center;font-size:.82rem;font-weight:600;transition:all .2s;cursor:pointer;background:{{ old('role','passager') === $val ? 'rgba(99,102,241,.15)' : 'rgba(255,255,255,.03)' }};color:{{ old('role','passager') === $val ? 'var(--cr-indigo)' : 'var(--cr-text)' }};">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('role')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <div>
                    <label for="password">Mot de passe *</label>
                    <input id="password" name="password" type="password" class="input"
                           placeholder="••••••••" required autocomplete="new-password">
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation">Confirmation *</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           class="input" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">Créer mon compte</button>
        </form>

        <p style="text-align:center;color:var(--cr-muted);font-size:.85rem;margin-top:1.2rem;">
            Déjà inscrit ? <a href="{{ route('login') }}" style="color:var(--cr-indigo);text-decoration:none;font-weight:600;">Se connecter</a>
        </p>
    </div>
</div>
<script>
document.querySelectorAll('.role-radio').forEach(radio => {
    const id = radio.value;
    document.getElementById('role-opt-' + id).addEventListener('click', () => {
        radio.checked = true;
        document.querySelectorAll('.role-opt').forEach(opt => {
            opt.style.background = 'rgba(255,255,255,.03)';
            opt.style.borderColor = 'var(--cr-border)';
            opt.style.color = 'var(--cr-text)';
        });
        const opt = document.getElementById('role-opt-' + id);
        opt.style.background = 'rgba(99,102,241,.15)';
        opt.style.borderColor = 'var(--cr-indigo)';
        opt.style.color = 'var(--cr-indigo)';
    });
});
</script>
</body>
</html>

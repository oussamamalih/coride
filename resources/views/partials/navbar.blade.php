@php
    $allUsers = \App\Models\User::orderBy('name')->get();
    $allEntreprises = \App\Models\Entreprise::orderBy('nom')->get();
@endphp

<nav class="bg-gray-800 border-b border-gray-700/80 px-6 py-4 mb-8 shadow-lg">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        
        <!-- Logo et Liens principaux -->
        <div class="flex items-center gap-6">
            <a href="{{ route('trajets.index') }}" class="flex items-center gap-2">
                <span class="text-2xl">🚗</span>
                <span class="text-xl font-extrabold text-emerald-400 tracking-wider">CoRide</span>
            </a>
            @auth
                <div class="flex gap-4 text-sm font-semibold">
                    <a href="{{ route('trajets.index') }}" class="text-gray-300 hover:text-white transition">Liste des Trajets</a>
                    <a href="{{ route('trajets.create') }}" class="text-emerald-400 hover:text-emerald-300 transition">+ Proposer un trajet</a>
                </div>
            @endauth
        </div>

        <!-- Section Droite (Auth & Sélecteurs de Test) -->
        <div class="flex flex-wrap items-center gap-4">
            
            @auth
                <!-- Lien vers son Dashboard Personnel -->
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-emerald-400 hover:underline">
                    👤 Mon Espace ({{ auth()->user()->name }})
                </a>

                <!-- Formulaire Déconnexion -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-900/20 hover:bg-red-900/40 text-red-400 border border-red-900/30 text-xs px-2.5 py-1.5 rounded-lg transition font-semibold">
                        Déconnexion
                    </button>
                </form>

                <!-- Sélecteurs de Démo (Mode Test) -->
                <div class="flex items-center gap-3 border-l border-gray-700 pl-4">
                    <!-- Sélecteur Entreprises -->
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-500 font-bold uppercase">Démo Entreprise :</span>
                        <select onchange="window.location.href='/entreprises/' + this.value" class="bg-gray-900 border border-gray-700 text-[10px] text-white rounded px-2 py-1 focus:outline-none">
                            <option value="">-- Choisir --</option>
                            @foreach($allEntreprises as $ent)
                                <option value="{{ $ent->id }}" {{ isset($entreprise) && $entreprise->id == $ent->id ? 'selected' : '' }}>
                                    {{ $ent->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Simulateur Utilisateur Connecté -->
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-emerald-500/80 font-bold uppercase">Démo Salarié :</span>
                        <select onchange="window.location.href='/dashboard/' + this.value" class="bg-gray-900 border border-emerald-950 text-[10px] text-white rounded px-2 py-1 focus:outline-none">
                            <option value="">-- Choisir --</option>
                            @foreach($allUsers as $u)
                                <option value="{{ $u->id }}" {{ isset($user) && $user->id == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <!-- Liens de Connexion / Inscription -->
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition">Connexion</a>
                <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-md">
                    S'inscrire
                </a>
            @endauth

        </div>
    </div>
</nav>

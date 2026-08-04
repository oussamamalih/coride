<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoRide - Détails du trajet #{{ $trajet->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">

    @include('partials.navbar')

    <div class="max-w-4xl mx-auto px-4 pb-12">
        <!-- Lien Retour -->
        <div class="flex items-center justify-between flex-wrap gap-3">
            <a href="{{ route('trajets.index') }}" class="inline-flex items-center gap-2 bg-white shadow px-5 py-3 rounded-xl hover:bg-gray-50">
                ← Retour à la liste des trajets
            </a>

            @auth
                @if(auth()->id() === $trajet->conducteur_id)
                    <div class="flex items-center gap-2">
                        <a href="{{ route('trajets.edit', $trajet) }}" class="inline-flex items-center gap-2 bg-blue-600 text-white shadow px-5 py-3 rounded-xl hover:bg-blue-700">
                            ✏️ Modifier
                        </a>
                        @if($trajet->reservations->where('statut', 'confirmee')->isEmpty())
                            <form action="{{ route('trajets.destroy', $trajet) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce trajet ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white shadow px-5 py-3 rounded-xl hover:bg-red-700">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 italic px-2">Suppression bloquée : réservation(s) confirmée(s)</span>
                        @endif
                    </div>
                @endif
            @endauth
        </div>

        <!-- Carte Principale -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-emerald-500 p-8 text-white">
                <div>
                    <span class="text-xs uppercase font-bold text-emerald-400">Trajet #{{ $trajet->id }}</span>
                    <h1 class="text-4xl font-bold">
                        {{ $trajet->ville_depart }} <span class="text-emerald-400">➔</span> {{ $trajet->ville_arrivee }}
                    </h1>
                </div>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $trajet->places_disponibles > 0 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30' }}">
                    {{ $trajet->places_disponibles }} place(s) disponible(s)
                </span>
            </div>

            <!-- Infos Trajet & Conducteur -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold text-gray-200 mb-4 flex items-center gap-2">
                        🚗 Détails de l'itinéraire
                    </h3>
                    <div class="space-y-3 bg-gray-50 rounded-xl p-5 border border-gray-700/40 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Horaire de départ :</span>
                            <span class="font-bold text-white">{{ $trajet->horaire }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Récurrence :</span>
                            <span class="font-bold text-white">{{ $trajet->jours_recurrence ?: 'Ponctuel' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Places totales :</span>
                            <span class="font-bold text-white">{{ $trajet->places_disponibles }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-200 mb-4 flex items-center gap-2">
                        👤 Conducteur & Entreprise
                    </h3>
                    <div class="space-y-3 bg-gray-50 rounded-xl p-5 border border-gray-700/40 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Nom du conducteur :</span>
                            <span class="font-bold">
                                @if($trajet->conducteur)
                                    {{ $trajet->conducteur->name }}
                                @else
                                    Non spécifié
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Email :</span>
                            <span class="font-medium text-gray-300">{{ $trajet->conducteur->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Entreprise :</span>
                            <span class="font-bold">
                                @if($trajet->conducteur && $trajet->conducteur->entreprise)
                                    <a href="{{ route('entreprises.show', $trajet->conducteur->entreprise) }}" class="text-emerald-400 hover:underline font-bold">
                                        {{ $trajet->conducteur->entreprise->nom }}
                                    </a>
                                @else
                                    Non rattaché
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertes de succès / d'erreur -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm font-semibold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm font-semibold">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <!-- Formulaire de réservation -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-700/50 mb-8">
                <h3 class="text-lg font-bold text-white mb-3">⚡ Demander une réservation</h3>
                <form method="POST" action="{{ route('reservations.store', $trajet) }}" class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label for="passager_id" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Sélectionner un salarié passager</label>
                        <select name="passager_id" id="passager_id" required class="w-full bg-gray-800 border border-gray-200 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                            <option value="">-- Choisir un employé (passager) --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->entreprise->nom ?? 'Indépendant' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-2 rounded-lg transition shadow-md text-sm">
                        Réserver ce trajet
                    </button>
                </form>
            </div>

            <!-- Liste des réservations actuelles -->
            <div class="mt-8 pt-6 border-t border-gray-700/60">
                <h3 class="text-lg font-bold text-gray-200 mb-4">📋 Passagers inscrits ({{ $trajet->reservations->count() }})</h3>
                
                @if($trajet->reservations->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($trajet->reservations as $reservation)
                            <div class="bg-gray-900/40 p-4 rounded-xl border border-gray-700/40 flex justify-between items-center text-sm">
                                <div>
                                    <span class="font-semibold text-white">
                                        @if($reservation->passager)
                                            {{ $reservation->passager->name }}
                                        @else
                                            Passager
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-400 block">{{ $reservation->passager->email ?? '' }}</span>
                                </div>
                                <span class="px-2.5 py-1 text-xs rounded-full font-bold uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                    {{ $reservation->statut }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm italic">Aucune réservation pour ce trajet actuellement.</p>
                @endif
            </div>

        </div>
    </div>

</body>
</html>

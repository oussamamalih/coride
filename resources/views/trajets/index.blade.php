<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoRide - Trajets disponibles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">

    @include('partials.navbar')

    <div class="max-w-6xl mx-auto px-4 pb-12">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 rounded-3xl p-8 mb-10 shadow-2xl">

    <div class="flex flex-col lg:flex-row justify-between items-center">

        <div>

            <h1 class="text-4xl font-extrabold text-gray-700">
                🚗 CoRide
            </h1>

            <p class="text-emerald-100 mt-2 text-lg">
                Trouvez ou proposez facilement un trajet entre collègues.
            </p>

        </div>

        <div class="flex gap-4 mt-6 lg:mt-0">

            <a href="{{ route('trajets.create') }}"
               class="bg-white text-emerald-700 hover:bg-gray-100 px-6 py-3 rounded-xl font-bold shadow-lg transition">

                + Nouveau trajet

            </a>

            <div class="bg-white/20 backdrop-blur-md px-6 py-3 rounded-xl text-gray-700">

                <span class="block text-sm">
                    Trajets disponibles
                </span>

                <span class="text-2xl font-bold">
                    {{ $trajets->count() }}
                </span>

            </div>

        </div>

    </div>

</div>
                <h1 class="text-3xl font-bold text-emerald-400">🚗 CoRide</h1>
                <p class="text-gray-400 text-sm">Plateforme de Covoiturage d'Entreprise</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('trajets.create') }}" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-gray-800 font-bold py-3 rounded-xl transition">
                    + Proposer un trajet
                </a>
                <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-3 py-1.5 rounded-full font-semibold">
                    {{ $trajets->count() }} trajet(s) trouvé(s)
                </span>
            </div>
        </div>

        <!-- Formulaire de recherche / filtrage -->
        <form method="GET" action="{{ route('trajets.index') }}" class="bg-white rounded-3xl shadow-lg p-6 transition duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
            <div>
                <label for="ville_depart" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Ville de départ</label>
                <input type="text" name="ville_depart" id="ville_depart" value="{{ request('ville_depart') }}" placeholder="Ex: Casablanca, Rabat..." class="w-full bg-gray-50 border border-gray-700 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label for="ville_arrivee" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Ville d'arrivée</label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" value="{{ request('ville_arrivee') }}" placeholder="Ex: Technopolis, Bouskoura..." class="w-full bg-gray-50 border border-gray-700 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-gray-800 font-bold py-3 rounded-xl transition">
                    🔍 Rechercher
                </button>
                @if(request()->hasAny(['ville_depart', 'ville_arrivee']))
                    <a href="{{ route('trajets.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 py-2 px-4 rounded-lg transition">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>

        <h2 class="text-xl font-semibold mb-6 text-gray-200">Trajets disponibles</h2>

        <!-- Liste des Trajets -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($trajets as $trajet)
                <div class="bg-white border border-gray-700/60 rounded-xl p-6 shadow-lg hover:border-emerald-500/50 transition flex flex-col justify-between">
                    
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Départ</span>
                                <h3 class="text-lg font-bold text-gray-700">{{ $trajet->ville_depart }}</h3>
                            </div>
                            <span class="text-gray-500 font-bold">➔</span>
                            <div class="text-right">
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Arrivée</span>
                                <h3 class="text-lg font-bold text-gray-700">{{ $trajet->ville_arrivee }}</h3>
                            </div>
                        </div>

                        <div class="space-y-2 border-t border-gray-700/50 pt-4 text-sm text-gray-600">
                            <div class="flex justify-between"
                                <span class="text-gray-400">🕒 Horaire :</span>
                                <span class="font-semibold text-gray-700">{{ $trajet->horaire }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">👤 Conducteur :</span>
                                <span class="font-semibold text-gray-700">{{ $trajet->conducteur->name ?? 'Inconnu' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">🪑 Places dispo :</span>
                                <span class="font-semibold px-2 py-0.5 text-xs rounded {{ $trajet->places_disponibles > 0 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                                    {{ $trajet->places_disponibles }} place(s)
                                </span>
                            </div>
                            @if($trajet->jours_recurrence)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">🔄 Jours :</span>
                                    <span class="text-xs text-gray-400">{{ $trajet->jours_recurrence }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bouton Voir le trajet -->
                    <div class="mt-4 pt-3 border-t border-gray-700/50 flex justify-end">
                        <a href="{{ route('trajets.show',$trajet) }} " class="w-full text-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl font-semibold transition">

                            Voir le trajet →

                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    Aucun trajet ne correspond à vos critères de recherche.
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>

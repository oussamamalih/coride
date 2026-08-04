<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoRide - Proposer un nouveau trajet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">

    @include('partials.navbar')

    <div class="max-w-2xl mx-auto px-4 pb-12">
        <a href="{{ route('trajets.index') }}" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 mb-6 transition text-sm font-semibold">
            ← Annuler et retourner aux trajets
        </a>

        <div class="bg-gray-800 border border-gray-700/60 rounded-2xl p-8 shadow-xl">
            <h1 class="text-2xl font-extrabold text-white mb-6 border-b border-gray-700/60 pb-4">
                🚗 Publier un nouveau trajet
            </h1>

            <form method="POST" action="{{ route('trajets.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="conducteur_id" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Conducteur</label>
                    <select name="conducteur_id" id="conducteur_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                        <option value="">-- Sélectionner le salarié conducteur --</option>
                        @foreach($conducteurs as $conducteur)
                            <option value="{{ $conducteur->id }}" {{ old('conducteur_id') == $conducteur->id ? 'selected' : '' }}>
                                {{ $conducteur->name }} ({{ $conducteur->entreprise->nom ?? 'Indépendant' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="ville_depart" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Ville de départ</label>
                        <input type="text" name="ville_depart" id="ville_depart" value="{{ old('ville_depart') }}" required placeholder="Ex: Casablanca" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                    </div>

                    <div>
                        <label for="ville_arrivee" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Ville d'arrivée</label>
                        <input type="text" name="ville_arrivee" id="ville_arrivee" value="{{ old('ville_arrivee') }}" required placeholder="Ex: Rabat Technopolis" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="horaire" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Horaire (départ)</label>
                        <input type="text" name="horaire" id="horaire" value="{{ old('horaire', '08:00') }}" required placeholder="Ex: 08:00" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                    </div>

                    <div>
                        <label for="places_disponibles" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Places disponibles</label>
                        <input type="number" name="places_disponibles" id="places_disponibles" value="{{ old('places_disponibles', 3) }}" min="1" max="8" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                </div>

                <div>
                    <label for="jours_recurrence" class="block text-xs font-semibold uppercase text-gray-400 mb-1">Jours de récurrence (optionnel)</label>
                    <input type="text" name="jours_recurrence" id="jours_recurrence" value="{{ old('jours_recurrence') }}" placeholder="Ex: Lundi, Mercredi, Vendredi" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                </div>

                <div class="pt-4 border-t border-gray-700/60 flex justify-end gap-3">
                    <a href="{{ route('trajets.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold px-4 py-2 rounded-lg transition text-sm">
                        Annuler
                    </a>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-2 rounded-lg transition shadow-md text-sm">
                        Publier le trajet 🚀
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

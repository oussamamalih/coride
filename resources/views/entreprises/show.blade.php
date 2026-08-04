<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoRide - Espace Entreprise : {{ $entreprise->nom }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">

    @include('partials.navbar')

    <div class="max-w-6xl mx-auto px-4 pb-12">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
            <div>
                <a href="{{ route('trajets.index') }}" class="text-emerald-400 hover:underline text-sm font-semibold">← Liste des trajets</a>
                <h1 class="text-3xl font-extrabold text-white mt-1">🏢 Espace Entreprise</h1>
            </div>
            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-3 py-1.5 rounded-full font-semibold">
                {{ $entreprise->secteur_activite }}
            </span>
        </div>

        <!-- Profil Entreprise -->
        <div class="bg-gray-800 border border-gray-700/60 rounded-2xl p-6 mb-8 shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2">{{ $entreprise->nom }}</h2>
            <p class="text-gray-400 text-sm">📍 Adresse : <span class="text-gray-200 font-semibold">{{ $entreprise->adresse ?? 'Non spécifiée' }}</span></p>
        </div>

        <!-- Statistiques / KPI -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 border border-gray-700/40 rounded-xl p-5 shadow text-center">
                <span class="text-xs uppercase text-gray-400 font-semibold block mb-1">Salariés inscrits</span>
                <span class="text-3xl font-bold text-emerald-400">{{ $totalEmployes }}</span>
            </div>
            <div class="bg-gray-800 border border-gray-700/40 rounded-xl p-5 shadow text-center">
                <span class="text-xs uppercase text-gray-400 font-semibold block mb-1">Trajets proposés</span>
                <span class="text-3xl font-bold text-emerald-400">{{ $totalTrajets }}</span>
            </div>
            <div class="bg-gray-800 border border-gray-700/40 rounded-xl p-5 shadow text-center">
                <span class="text-xs uppercase text-gray-400 font-semibold block mb-1">Demandes de covoiturage</span>
                <span class="text-3xl font-bold text-emerald-400">{{ $totalReservations }}</span>
            </div>
            <div class="bg-gray-800 border border-emerald-500/30 rounded-xl p-5 shadow text-center bg-emerald-500/5">
                <span class="text-xs uppercase text-emerald-400 font-semibold block mb-1">CO2 économisé</span>
                <span class="text-3xl font-bold text-emerald-300">{{ number_format($co2Economise, 1) }} kg</span>
            </div>
        </div>

        <!-- Annuaire des salariés -->
        <div class="bg-gray-800 border border-gray-700/60 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold text-white mb-4">👥 Annuaire des collaborateurs</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($entreprise->employes as $employe)
                    <div class="bg-gray-900/40 p-4 rounded-xl border border-gray-700/40 flex justify-between items-center">
                        <div>
                            <span class="font-semibold text-white block">{{ $employe->name }}</span>
                            <span class="text-xs text-gray-400">{{ $employe->email }}</span>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

</body>
</html>

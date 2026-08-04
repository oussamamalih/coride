<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoRide - Covoiturage d'Entreprise Éco-responsable</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col justify-between">

    <!-- Inclure la Navbar -->
    @include('partials.navbar')

    <!-- Hero Section -->
    <main class="max-w-6xl mx-auto px-6 py-12 flex-1 flex flex-col items-center justify-center text-center">
        
        <div class="mb-6 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-4 py-1.5 rounded-full font-bold uppercase tracking-wider">
            🌱 Le covoiturage d'entreprise intelligent
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight max-w-4xl">
            Partagez vos trajets quotidiens avec vos <span class="text-emerald-400">collaborateurs</span>.
        </h1>

        <p class="text-gray-400 text-lg md:text-xl max-w-2xl mb-10">
            CoRide met en relation les employés des mêmes zones d'activités pour réduire l'empreinte carbone et optimiser vos déplacements professionnels grâce à notre scoring d'affinité IA.
        </p>

        <!-- CTA -->
        <div class="flex flex-col sm:flex-row gap-4 mb-16">
            <a href="{{ route('trajets.index') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg shadow-emerald-900/30 text-lg">
                🚀 Découvrir les trajets
            </a>
            <a href="{{ route('trajets.create') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-200 border border-gray-700 font-semibold px-8 py-4 rounded-xl transition text-lg">
                + Proposer un trajet
            </a>
        </div>

        <!-- Global Stats KPIs -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full border-t border-gray-800 pt-12">
            <div class="p-4 bg-gray-800/40 rounded-xl border border-gray-800">
                <span class="text-2xl md:text-3xl font-extrabold text-emerald-400 block mb-1">
                    {{ $stats['entreprises'] }}
                </span>
                <span class="text-xs uppercase text-gray-500 font-semibold">Entreprises partenaires</span>
            </div>

            <div class="p-4 bg-gray-800/40 rounded-xl border border-gray-800">
                <span class="text-2xl md:text-3xl font-extrabold text-emerald-400 block mb-1">
                    {{ $stats['salaries'] }}
                </span>
                <span class="text-xs uppercase text-gray-500 font-semibold">Salariés actifs</span>
            </div>

            <div class="p-4 bg-gray-800/40 rounded-xl border border-gray-800">
                <span class="text-2xl md:text-3xl font-extrabold text-emerald-400 block mb-1">
                    {{ $stats['trajets'] }}
                </span>
                <span class="text-xs uppercase text-gray-500 font-semibold">Trajets proposés</span>
            </div>

            <div class="p-4 bg-emerald-500/5 rounded-xl border border-emerald-500/10">
                <span class="text-2xl md:text-3xl font-extrabold text-emerald-300 block mb-1">
                    {{ number_format($stats['co2'], 1) }} kg
                </span>
                <span class="text-xs uppercase text-emerald-500 font-bold">CO2 Économisé</span>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-800 py-6 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} CoRide. Conçu pour le covoiturage éco-responsable entre collaborateurs d'entreprises.
    </footer>

</body>
</html>

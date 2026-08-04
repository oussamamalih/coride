<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoRide - Espace Salarié : {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-8">

    <div class="max-w-6xl mx-auto">
        <!-- Header / Navigation -->
        <div class="flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
            <div>
                <a href="{{ route('trajets.index') }}" class="text-emerald-400 hover:underline text-sm font-semibold">← Liste des trajets</a>
                <h1 class="text-3xl font-extrabold text-white mt-1">💼 Espace Salarié</h1>
            </div>
            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-3 py-1.5 rounded-full font-semibold">
                {{ $user->entreprise->nom ?? 'Hors entreprise' }}
            </span>
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

        <!-- Profil info -->
        <div class="bg-gray-800 border border-gray-700/60 rounded-2xl p-6 mb-8 shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-emerald-400 text-sm font-medium">{{ $user->email }}</p>
            </div>
            <div class="text-sm text-gray-400 space-y-1">
                <div>🏢 <span class="font-semibold text-gray-200">Entreprise :</span> 
                    @if($user->entreprise)
                        <a href="{{ route('entreprises.show', $user->entreprise) }}" class="text-emerald-400 hover:underline font-bold">
                            {{ $user->entreprise->nom }}
                        </a>
                    @else
                        Indépendant
                    @endif
                </div>
                <div>📍 <span class="font-semibold text-gray-200">Résidence :</span> {{ $user->ville_residence ?? 'Non spécifiée' }}</div>
                <div>🎭 <span class="font-semibold text-gray-200">Rôle favori :</span> {{ ucfirst($user->role) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- 1. Trajets Proposés (Conducteur) -->
            <div class="space-y-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    🚗 Mes trajets proposés (Conducteur)
                </h3>

                @forelse($user->trajets as $trajet)
                    <div class="bg-gray-800 border border-gray-700/40 rounded-xl p-5 shadow">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-emerald-400 uppercase">Trajet #{{ $trajet->id }}</span>
                            <span class="text-xs text-gray-400 font-medium">🕒 {{ $trajet->horaire }} ({{ $trajet->places_disponibles }} places libres)</span>
                        </div>
                        <h4 class="text-base font-bold text-white mb-4">
                            {{ $trajet->ville_depart }} ➔ {{ $trajet->ville_arrivee }}
                        </h4>

                        <!-- Demandes reçues pour ce trajet -->
                        <div class="border-t border-gray-700/60 pt-3">
                            <h5 class="text-xs font-bold uppercase text-gray-400 mb-2">Demandes de passagers ({{ $trajet->reservations->count() }})</h5>
                            @forelse($trajet->reservations as $reservation)
                                <div class="bg-gray-900/40 p-3 rounded-lg border border-gray-700/30 flex justify-between items-center text-xs mb-2">
                                    <div>
                                        <p class="font-semibold text-white">{{ $reservation->passager->name }}</p>
                                        <p class="text-gray-400">{{ $reservation->passager->entreprise->nom ?? '' }}</p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        @if($reservation->statut === 'en_attente')
                                            <!-- Bouton Accepter -->
                                            <form method="POST" action="{{ route('reservations.accepter', $reservation) }}">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-2.5 py-1 rounded text-[10px] uppercase transition">
                                                    Accepter
                                                </button>
                                            </form>
                                            <!-- Bouton Refuser -->
                                            <form method="POST" action="{{ route('reservations.refuser', $reservation) }}">
                                                @csrf
                                                <button type="submit" class="bg-red-600/80 hover:bg-red-500 text-white font-bold px-2.5 py-1 rounded text-[10px] uppercase transition">
                                                    Refuser
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-2 py-0.5 rounded font-bold uppercase {{ $reservation->statut === 'confirmee' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($reservation->statut === 'annulee' ? 'bg-gray-600/20 text-gray-400 border border-gray-600/30' : 'bg-red-500/20 text-red-300 border border-red-500/30') }}">
                                                {{ $reservation->statut }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 italic">Aucune demande reçue.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm italic">Vous n'avez proposé aucun trajet pour le moment.</p>
                @endforelse
            </div>

            <!-- 2. Mes Réservations (Passager) -->
            <div class="space-y-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    📅 Mes réservations (Passager)
                </h3>

                @forelse($user->reservations as $reservation)
                    <div class="bg-gray-800 border border-gray-700/40 rounded-xl p-5 shadow">
                        <div class="flex justify-between items-center mb-3">
                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $reservation->statut === 'confirmee' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($reservation->statut === 'refusee' ? 'bg-red-500/20 text-red-300 border border-red-500/30' : ($reservation->statut === 'annulee' ? 'bg-gray-600/20 text-gray-400 border border-gray-600/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30')) }}">
                                {{ $reservation->statut }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">🕒 {{ $reservation->trajet->horaire }}</span>
                        </div>
                        <h4 class="text-base font-bold text-white mb-2">
                            {{ $reservation->trajet->ville_depart }} ➔ {{ $reservation->trajet->ville_arrivee }}
                        </h4>
                        
                        <div class="flex justify-between items-center mb-3 text-xs">
                            <p class="text-gray-400">Conducteur : <span class="text-gray-200 font-semibold">{{ $reservation->trajet->conducteur->name }}</span></p>
                            @if($reservation->statut !== 'annulee' && $reservation->statut !== 'refusee')
                                <form method="POST" action="{{ route('reservations.annuler', $reservation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                    @csrf
                                    <button type="submit" class="text-red-400 hover:text-red-300 hover:underline font-semibold">
                                        Annuler ma demande
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Analyse Compatibilité IA -->
                        @if($reservation->resultat_ia)
                            @php $detailsIA = $reservation->resultat_ia; @endphp
                            <div class="bg-gray-900/60 p-3 rounded-lg border border-emerald-500/10 text-xs">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-emerald-400 font-semibold">🤖 Score de compatibilité IA :</span>
                                    <span class="font-bold text-emerald-300 bg-emerald-500/10 px-1.5 py-0.5 rounded">{{ $detailsIA->score }}%</span>
                                </div>
                                <p class="text-gray-400 italic">"{{ $detailsIA->justification }}"</p>
                                @if($detailsIA->horaire_suggere)
                                    <p class="text-gray-500 mt-1">🕒 Horaire suggéré : {{ $detailsIA->horaire_suggere }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-sm italic">Vous n'avez fait aucune demande de réservation pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>

</body>
</html>

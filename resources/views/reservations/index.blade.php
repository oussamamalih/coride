@extends('layouts.app')

@section('content')

<div style="margin-bottom: 32px;">
    <h2 class="page-title">📅 Mes réservations</h2>
    <p class="page-subtitle">Retrouvez toutes vos réservations de covoiturage.</p>
</div>

@if($reservations->count())

<div class="grid">
    @foreach($reservations as $reservation)
    <div class="glass-panel" style="display: flex; flex-direction: column; justify-content: space-between;">
        
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border-light);">
                <h3 style="font-size: 1.2rem; display: flex; align-items: center; gap: 8px; margin: 0;">
                    🚗 {{ $reservation->trajet->ville_depart }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    {{ $reservation->trajet->ville_arrivee }}
                </h3>

                @switch($reservation->statut)
                    @case('confirmee')
                        <span class="badge badge-success">Confirmée</span>
                        @break
                    @case('refusee')
                        <span class="badge badge-danger">Refusée</span>
                        @break
                    @case('annulee')
                        <span class="badge" style="background: rgba(0,0,0,0.1);">Annulée</span>
                        @break
                    @default
                        <span class="badge badge-warning">En attente</span>
                @endswitch
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                <div><strong>👤 Conducteur:</strong> {{ $reservation->trajet->conducteur->nom }}</div>
                <div><strong>📅 Réservé le:</strong> {{ $reservation->date_reservation->format('d/m/Y') }}</div>
                <div><strong>🕒 Horaire du trajet:</strong> {{ $reservation->trajet->horaire->format('d/m/Y H:i') }}</div>
            </div>

            @if($reservation->resultat_ia)
            <div class="ai-score-widget">
                <div class="score-header">
                    🤖 Compatibilité IA (Score: {{ $reservation->resultat_ia['score'] ?? 'N/A' }}/100)
                </div>
                <div class="score-bar-container">
                    <div class="score-bar" style="width: {{ $reservation->resultat_ia['score'] ?? 0 }}%;"></div>
                </div>
                <div class="ai-reasoning">
                    {{ $reservation->resultat_ia['justification'] ?? '' }}
                </div>
                @if(isset($reservation->resultat_ia['horaires_suggeres']))
                <div class="score-text">
                    Horaire suggéré : {{ $reservation->resultat_ia['horaires_suggeres'] }}
                </div>
                @endif
            </div>
            @endif
        </div>

        <div style="margin-top: 24px;">
            @if(in_array($reservation->statut, ['en_attente','confirmee']))
            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Annuler cette réservation ?')" class="btn btn-danger" style="width: 100%;">❌ Annuler la réservation</button>
            </form>
            @else
            <button class="btn" style="width: 100%; background: rgba(0,0,0,0.05); cursor: not-allowed;" disabled>Aucune action disponible</button>
            @endif
        </div>
        
    </div>
    @endforeach
</div>

<div style="margin-top: 32px;">
    {{ $reservations->links() }}
</div>

@else
<div class="alert alert-info">
    Vous n'avez encore effectué aucune réservation.
</div>
@endif

@endsection
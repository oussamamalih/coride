@extends('layouts.app')

@section('content')

<div class="grid" style="grid-template-columns: 2fr 1fr; align-items: start;">
    
    <div class="glass-panel" style="padding: 0; overflow: hidden;">
        <div style="background: var(--primary); color: white; padding: 24px;">
            <h3 style="margin: 0;">🚗 Détail du trajet</h3>
        </div>
        
        <div style="padding: 24px;">
            <div class="grid" style="grid-template-columns: 1fr 1fr;">
                <div style="margin-bottom: 24px;">
                    <strong style="color: var(--text-muted-light);">👤 Conducteur</strong>
                    <div style="font-size: 1.1rem; margin-top: 4px;">{{ $trajet->conducteur->nom }}</div>
                </div>
                <div style="margin-bottom: 24px;">
                    <strong style="color: var(--text-muted-light);">🕒 Horaire</strong>
                    <div style="font-size: 1.1rem; margin-top: 4px;">{{ $trajet->horaire->format('d/m/Y H:i') }}</div>
                </div>
                <div style="margin-bottom: 24px;">
                    <strong style="color: var(--text-muted-light);">📍 Départ</strong>
                    <div style="font-size: 1.1rem; margin-top: 4px;">{{ $trajet->ville_depart }}</div>
                </div>
                <div style="margin-bottom: 24px;">
                    <strong style="color: var(--text-muted-light);">🏁 Arrivée</strong>
                    <div style="font-size: 1.1rem; margin-top: 4px;">{{ $trajet->ville_arrivee }}</div>
                </div>
                <div style="margin-bottom: 24px;">
                    <strong style="color: var(--text-muted-light);">💺 Places disponibles</strong>
                    <div style="font-size: 1.1rem; margin-top: 4px;"><span class="badge badge-success">{{ $trajet->places_disponibles }}</span></div>
                </div>
                <div style="margin-bottom: 24px;">
                    <strong style="color: var(--text-muted-light);">📅 Récurrence</strong>
                    <div style="font-size: 1.1rem; margin-top: 4px;">{{ $trajet->jours_recurrence ?? 'Aucune' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="glass-panel">
            <h4 style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">Actions</h4>
            
            @auth
                @if($trajet->conducteur_id !== auth()->id())
                    @php
                        $dejaReserve = $trajet->reservations->where('passager_id', auth()->id())->count();
                    @endphp
                    @if($dejaReserve)
                        <div class="alert alert-info" style="margin-bottom: 16px;">
                            ✅ Vous avez déjà réservé ce trajet.
                        </div>
                    @else
                        <form action="{{ route('reservations.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="trajet_id" value="{{ $trajet->id }}">
                            <button class="btn btn-success" style="width: 100%;">Réserver une place</button>
                        </form>
                    @endif
                @else
                    <div class="alert alert-primary" style="margin-bottom: 16px;">
                        Vous êtes le conducteur.
                    </div>
                @endif
            @endauth
            
            <a href="{{ route('trajets.index') }}" class="btn btn-outline" style="width: 100%; margin-top: 12px;">Retour</a>
        </div>

        <div class="glass-panel">
            <h4 style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">🤖 Compatibilité IA</h4>
            @if(isset($trajet->ai_score))
                <div class="score-header" style="font-weight: bold; margin-bottom: 8px;">
                    Score: {{ $trajet->ai_score['score'] }}/100
                </div>
                <div class="score-bar-container" style="margin-bottom: 12px;">
                    <div class="score-bar" style="width: {{ $trajet->ai_score['score'] }}%;"></div>
                </div>
                <p style="color: var(--text-muted-light); margin: 0 0 8px 0; font-size: 0.95rem;">
                    {{ $trajet->ai_score['justification'] }}
                </p>
                @if(isset($trajet->ai_score['horaires_suggeres']))
                <p style="color: var(--text-muted-light); margin: 0; font-size: 0.85rem; font-style: italic;">
                    Horaire suggéré : {{ $trajet->ai_score['horaires_suggeres'] }}
                </p>
                @endif
            @else
                <p style="color: var(--text-muted-light); margin: 0; font-size: 0.95rem;">
                    Score de compatibilité non disponible.
                </p>
            @endif
        </div>
    </div>

</div>

@if(auth()->check() && auth()->id() === $trajet->conducteur_id)
<div class="glass-panel" style="margin-top: 32px; padding: 0; overflow: hidden;">
    <div style="background: rgba(0,0,0,0.8); color: white; padding: 20px;">
        <h4 style="margin: 0;">📋 Réservations pour ce trajet</h4>
    </div>
    
    <div style="padding: 24px;">
        @if($trajet->reservations->count())
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-light);">
                        <th style="padding: 12px;">Passager</th>
                        <th style="padding: 12px;">Statut</th>
                        <th style="padding: 12px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($trajet->reservations as $reservation)
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 16px 12px;">{{ $reservation->passager->nom }}</td>
                        <td style="padding: 16px 12px;">
                            @switch($reservation->statut)
                                @case('confirmee') <span class="badge badge-success">Confirmée</span> @break
                                @case('refusee') <span class="badge badge-danger">Refusée</span> @break
                                @case('annulee') <span class="badge" style="background: rgba(0,0,0,0.1);">Annulée</span> @break
                                @default <span class="badge badge-warning">En attente</span>
                            @endswitch
                        </td>
                        <td style="padding: 16px 12px; text-align: right; display: flex; gap: 8px; justify-content: flex-end;">
                            @if($reservation->statut == 'en_attente')
                            <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="statut" value="confirmee">
                                <button class="btn btn-success" style="padding: 6px 12px; font-size: 0.85rem;">Confirmer</button>
                            </form>
                            <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="statut" value="refusee">
                                <button class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;">Refuser</button>
                            </form>
                            @else
                            <span style="color: var(--text-muted-light); font-size: 0.85rem;">Traitée</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info" style="margin: 0;">Aucune réservation pour ce trajet.</div>
        @endif
    </div>
</div>
@endif

@endsection
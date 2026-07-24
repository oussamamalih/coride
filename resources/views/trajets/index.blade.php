@extends('layouts.app')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <div>
        <h2 class="page-title">🚗 Trajets disponibles</h2>
        <p class="page-subtitle" style="margin-bottom: 0;">Consultez les trajets proposés par les employés.</p>
    </div>
    <a href="{{ route('trajets.create') }}" class="btn btn-primary">
        + Nouveau trajet
    </a>
</div>

@if($trajets->count())

<div class="grid">
    @foreach($trajets as $trajet)
    <div class="glass-panel trajet-card">
        
        <div class="trajet-header">
            <div class="trajet-route">
                {{ $trajet->ville_depart }}
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                {{ $trajet->ville_arrivee }}
            </div>
            <span class="badge badge-primary">#{{ $trajet->id }}</span>
        </div>

        <div class="trajet-info">
            <div class="info-row">
                <span class="info-icon">👤</span>
                <strong>Conducteur :</strong> {{ $trajet->conducteur->nom }}
            </div>
            <div class="info-row">
                <span class="info-icon">🕒</span>
                <strong>Horaire :</strong> {{ $trajet->horaire->format('d/m/Y H:i') }}
            </div>
            <div class="info-row">
                <span class="info-icon">💺</span>
                <strong>Places :</strong> <span class="badge badge-success">{{ $trajet->places_disponibles }}</span>
            </div>
            @if($trajet->jours_recurrence)
            <div class="info-row">
                <span class="info-icon">📅</span>
                <strong>Récurrence :</strong> {{ $trajet->jours_recurrence }}
            </div>
            @endif
        </div>

        @if(isset($trajet->ai_score))
        <div class="ai-score-widget">
            <div class="score-header">
                🤖 Compatibilité IA (Score: {{ $trajet->ai_score['score'] ?? 'N/A' }}/100)
            </div>
            <div class="score-bar-container">
                <div class="score-bar" style="width: {{ $trajet->ai_score['score'] ?? 0 }}%;"></div>
            </div>
            <div class="ai-reasoning">
                {{ $trajet->ai_score['justification'] ?? '' }}
            </div>
            @if(isset($trajet->ai_score['horaires_suggeres']))
            <div class="score-text">
                Horaire suggéré : {{ $trajet->ai_score['horaires_suggeres'] }}
            </div>
            @endif
        </div>
        @endif

        <div class="trajet-actions">
            <a href="{{ route('trajets.show', $trajet) }}" class="btn btn-outline">Voir</a>
            
            @if(auth()->id() == $trajet->conducteur_id)
                <a href="{{ route('trajets.edit', $trajet) }}" class="btn btn-secondary">Modifier</a>
                <form action="{{ route('trajets.destroy', $trajet) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Supprimer ce trajet ?')" class="btn btn-danger">Supprimer</button>
                </form>
            @else
                <form action="{{ route('reservations.store') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="trajet_id" value="{{ $trajet->id }}">
                    <button class="btn btn-primary">Réserver</button>
                </form>
            @endif
        </div>
        
    </div>
    @endforeach
</div>

<div style="margin-top: 32px;">
    {{ $trajets->links() }}
</div>

@else
<div class="alert alert-info">
    Aucun trajet disponible.
</div>
@endif

@endsection
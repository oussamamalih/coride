@extends('layouts.app')

@section('content')

<div>
    <div class="glass-panel" style="margin-bottom: 32px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; color: white;">
        <h2 style="font-size: 2rem; margin-bottom: 12px;">👋 Bonjour {{ auth()->user()->nom }}</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; margin: 0;">
            Bienvenue sur <strong>CoRide</strong>, votre plateforme de covoiturage d'entreprise.
            Gérez vos trajets, consultez vos réservations et trouvez les trajets les plus compatibles grâce à l'IA.
        </p>
    </div>

    <div class="grid" style="margin-bottom: 40px;">
        <div class="glass-panel" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px;">
            <div style="font-size: 3rem;">🚗</div>
            <h3>Trajets</h3>
            <p style="color: var(--text-muted-light);">Publiez un trajet ou consultez les trajets disponibles.</p>
            <a href="{{ route('trajets.index') }}" class="btn btn-primary" style="width: 100%;">Voir les trajets</a>
        </div>

        <div class="glass-panel" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px;">
            <div style="font-size: 3rem;">📅</div>
            <h3>Réservations</h3>
            <p style="color: var(--text-muted-light);">Consultez et gérez toutes vos réservations.</p>
            <a href="{{ route('reservations.index') }}" class="btn btn-success" style="width: 100%;">Mes réservations</a>
        </div>

        <div class="glass-panel" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px;">
            <div style="font-size: 3rem;">🤖</div>
            <h3>Compatibilité IA</h3>
            <p style="color: var(--text-muted-light);">Les meilleurs trajets classés selon leur score IA.</p>
            <button class="btn btn-secondary" style="width: 100%;" disabled>Active</button>
        </div>
    </div>

    <div class="grid">
        <div class="glass-panel">
            <h3 style="margin-bottom: 24px; padding-bottom: 12px; border-bottom: 1px solid var(--border-dark);">Informations du compte</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div><strong>Nom :</strong> {{ auth()->user()->nom }}</div>
                <div><strong>Email :</strong> {{ auth()->user()->email }}</div>
                <div><strong>Ville :</strong> {{ auth()->user()->ville_residence }}</div>
                <div><strong>Rôle :</strong> <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span></div>
            </div>
        </div>

        <div class="glass-panel">
            <h3 style="margin-bottom: 24px; padding-bottom: 12px; border-bottom: 1px solid var(--border-dark);">Fonctionnalités</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 8px;">✅ Gestion des trajets</div>
                <div style="display: flex; align-items: center; gap: 8px;">✅ Gestion des réservations</div>
                <div style="display: flex; align-items: center; gap: 8px;">✅ Authentification Laravel Breeze</div>
                <div style="display: flex; align-items: center; gap: 8px;">✅ Score de compatibilité IA</div>
            </div>
        </div>
    </div>
</div>

@endsection
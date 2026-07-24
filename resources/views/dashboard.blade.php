@extends('layouts.app')
@section('title', 'Mon tableau de bord')

@section('content')
<div class="container" style="max-width:900px;">
    <div class="fade-in" style="margin-bottom:2rem;">
        <h1 class="page-title">Bienvenue, {{ auth()->user()->name }} 👋</h1>
        <p style="color:var(--cr-muted);margin-top:.3rem;">{{ auth()->user()->entreprise?->nom }} · {{ auth()->user()->ville_residence }} · {{ auth()->user()->role_libelle }}</p>
    </div>

    <div class="grid-2">
        @if(auth()->user()->estPassager())
        <a href="{{ route('trajets.index') }}" style="text-decoration:none;">
            <div class="glass fade-in" style="padding:2rem;text-align:center;transition:all .2s;animation-delay:.05s;cursor:pointer;"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--cr-indigo)'"
                 onmouseout="this.style.transform='none';this.style.borderColor='rgba(99,102,241,.25)'">
                <div style="font-size:3rem;margin-bottom:1rem;">🗺️</div>
                <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 .5rem;">Explorer les trajets</h2>
                <p style="color:var(--cr-muted);font-size:.85rem;margin:0;">Trouvez un trajet compatible et obtenez votre score IA</p>
            </div>
        </a>
        <a href="{{ route('reservations.index') }}" style="text-decoration:none;">
            <div class="glass fade-in" style="padding:2rem;text-align:center;transition:all .2s;animation-delay:.1s;cursor:pointer;"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--cr-emerald)'"
                 onmouseout="this.style.transform='none';this.style.borderColor='rgba(99,102,241,.25)'">
                <div style="font-size:3rem;margin-bottom:1rem;">🎫</div>
                <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 .5rem;">Mes réservations</h2>
                <p style="color:var(--cr-muted);font-size:.85rem;margin:0;">Suivez vos demandes et scores de compatibilité</p>
            </div>
        </a>
        @endif
        @if(auth()->user()->estConducteur())
        <a href="{{ route('conducteur.dashboard') }}" style="text-decoration:none;">
            <div class="glass fade-in" style="padding:2rem;text-align:center;transition:all .2s;animation-delay:.15s;cursor:pointer;"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--cr-indigo)'"
                 onmouseout="this.style.transform='none';this.style.borderColor='rgba(99,102,241,.25)'">
                <div style="font-size:3rem;margin-bottom:1rem;">🚗</div>
                <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 .5rem;">Dashboard conducteur</h2>
                <p style="color:var(--cr-muted);font-size:.85rem;margin:0;">Gérez vos trajets et confirmez les réservations</p>
            </div>
        </a>
        <a href="{{ route('trajets.create') }}" style="text-decoration:none;">
            <div class="glass fade-in" style="padding:2rem;text-align:center;transition:all .2s;animation-delay:.2s;cursor:pointer;border-color:rgba(99,102,241,.5);"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.background='rgba(99,102,241,.15)'"
                 onmouseout="this.style.transform='none';this.style.background='var(--cr-glass)'">
                <div style="font-size:3rem;margin-bottom:1rem;">➕</div>
                <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 .5rem;color:var(--cr-indigo);">Publier un trajet</h2>
                <p style="color:var(--cr-muted);font-size:.85rem;margin:0;">Proposez vos places à vos collègues</p>
            </div>
        </a>
        @endif
    </div>

    {{-- IA info block --}}
    <div class="glass fade-in" style="padding:1.5rem;margin-top:2rem;animation-delay:.3s;border-color:rgba(99,102,241,.4);">
        <div style="display:flex;gap:1rem;align-items:flex-start;">
            <div style="font-size:2rem;flex-shrink:0;">🤖</div>
            <div>
                <h3 style="margin:0 0 .5rem;font-size:.95rem;font-weight:700;">Score IA de compatibilité</h3>
                <p style="color:var(--cr-muted);font-size:.85rem;margin:0;line-height:1.6;">
                    CoRide utilise l'IA (via <strong style="color:var(--cr-indigo);">laravel/ai</strong>) pour analyser la compatibilité réelle entre votre profil et un trajet : proximité géographique, horaires, jours de récurrence, même entreprise. Le résultat est un score 0–100 expliqué en langage naturel — bien au-delà d'un simple filtre par ville.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

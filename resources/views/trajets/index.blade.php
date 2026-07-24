@extends('layouts.app')
@section('title', 'Trajets disponibles')

@section('content')
<div class="container">

    {{-- ═══ Header ══════════════════════════════════════════════════════════════ --}}
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:2rem;" class="fade-in">
        <div>
            <h1 class="page-title">Trajets disponibles</h1>
            <p style="color:var(--cr-muted);margin-top:.3rem;">{{ $trajets->total() }} trajet{{ $trajets->total() > 1 ? 's' : '' }} trouvé{{ $trajets->total() > 1 ? 's' : '' }}</p>
        </div>
        @auth
            @if(auth()->user()->estConducteur())
            <a href="{{ route('trajets.create') }}" class="btn-primary pulse">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Publier un trajet
            </a>
            @endif
        @endauth
    </div>

    {{-- ═══ Filtres de recherche ════════════════════════════════════════════════ --}}
    <div class="glass fade-in" style="padding:1.5rem;margin-bottom:2rem;animation-delay:.1s;">
        <form method="GET" action="{{ route('trajets.index') }}">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:1rem;align-items:flex-end;">
                <div>
                    <label for="ville_depart">🏠 Ville de départ</label>
                    <input id="ville_depart" name="ville_depart" list="villes-list" class="input"
                           placeholder="Ex: Rabat" value="{{ request('ville_depart') }}">
                </div>
                <div>
                    <label for="ville_arrivee">🏢 Ville d'arrivée</label>
                    <input id="ville_arrivee" name="ville_arrivee" list="villes-list" class="input"
                           placeholder="Ex: Casablanca" value="{{ request('ville_arrivee') }}">
                </div>
                <div>
                    <label for="horaire">⏰ Horaire (±1h)</label>
                    <input id="horaire" name="horaire" type="time" class="input" value="{{ request('horaire') }}">
                </div>
                <div style="display:flex;gap:.75rem;">
                    <button type="submit" class="btn-primary" style="white-space:nowrap;">🔍 Rechercher</button>
                    @if(request()->hasAny(['ville_depart','ville_arrivee','horaire']))
                    <a href="{{ route('trajets.index') }}" class="btn-secondary" style="white-space:nowrap;">✕</a>
                    @endif
                </div>
            </div>
            <datalist id="villes-list">
                @foreach($villes as $v)<option value="{{ $v }}">@endforeach
            </datalist>
        </form>
    </div>

    {{-- ═══ Grille des trajets ══════════════════════════════════════════════════ --}}
    @if($trajets->isEmpty())
        <div class="glass" style="padding:4rem;text-align:center;">
            <div style="font-size:3rem;margin-bottom:1rem;">🚗</div>
            <p style="color:var(--cr-muted);font-size:1.1rem;">Aucun trajet trouvé avec ces critères.</p>
            <a href="{{ route('trajets.index') }}" class="btn-secondary" style="margin-top:1.5rem;display:inline-flex;">Voir tous les trajets</a>
        </div>
    @else
    <div class="grid-2" style="margin-bottom:2rem;">
        @foreach($trajets as $i => $trajet)
        @php
            $maRes = $mesReservations->get($trajet->id);
            $score = $maRes?->score_compatibilite;
            $placesRestantes = $trajet->places_disponibles - $trajet->reservations_confirmees_count;
            $estComplet = $placesRestantes <= 0;
        @endphp
        <div class="glass fade-in" style="padding:1.5rem;transition:transform .2s,box-shadow .2s;animation-delay:{{ $i * 0.05 }}s;"
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 40px rgba(99,102,241,.2)'"
             onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

            {{-- Header carte --}}
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.2rem;">
                <div style="display:flex;align-items:center;gap:.8rem;">
                    <div class="avatar">{{ $trajet->conducteur->initiales ?? '?' }}</div>
                    <div>
                        <div style="font-weight:600;font-size:.9rem;">{{ $trajet->conducteur->name }}</div>
                        <div style="font-size:.75rem;color:var(--cr-muted);">{{ $trajet->conducteur->entreprise?->nom ?? '—' }}</div>
                    </div>
                </div>
                {{-- Score badge ou ring --}}
                @if($score)
                    @php $couleur = match(true){ $score->score>=80 => '#10b981', $score->score>=60 => '#f59e0b', $score->score>=40 => '#f97316', default => '#ef4444' }; @endphp
                    <div title="{{ $score->justification }}" style="cursor:help;">
                        <div class="score-ring" style="width:52px;height:52px;">
                            <svg width="52" height="52" viewBox="0 0 52 52">
                                <circle cx="26" cy="26" r="22" fill="none" stroke="rgba(255,255,255,.08)" stroke-width="5"/>
                                <circle cx="26" cy="26" r="22" fill="none" stroke="{{ $couleur }}" stroke-width="5"
                                    stroke-dasharray="{{ round(2*3.14159*22*$score->score/100) }} {{ round(2*3.14159*22) }}"
                                    stroke-linecap="round" style="transition:stroke-dasharray 1s;"/>
                            </svg>
                            <span class="score-value" style="color:{{ $couleur }};font-size:.9rem;">{{ $score->score }}</span>
                        </div>
                    </div>
                @else
                    <div style="width:52px;height:52px;border-radius:50%;border:2px dashed var(--cr-border);display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cr-muted)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    </div>
                @endif
            </div>

            {{-- Itinéraire --}}
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;background:rgba(255,255,255,.04);border-radius:.6rem;padding:.85rem 1rem;">
                <div style="text-align:center;">
                    <div style="font-size:.7rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.06em;">Départ</div>
                    <div style="font-weight:700;font-size:1rem;">{{ $trajet->ville_depart }}</div>
                </div>
                <div style="flex:1;display:flex;align-items:center;gap:.5rem;">
                    <div style="flex:1;height:1px;background:linear-gradient(90deg,var(--cr-indigo),var(--cr-emerald));opacity:.5;"></div>
                    <div style="background:var(--cr-indigo);color:#fff;padding:.2rem .6rem;border-radius:2rem;font-size:.78rem;font-weight:700;white-space:nowrap;">{{ $trajet->horaire_formate }}</div>
                    <div style="flex:1;height:1px;background:linear-gradient(90deg,var(--cr-emerald),var(--cr-indigo));opacity:.5;"></div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:.7rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.06em;">Arrivée</div>
                    <div style="font-weight:700;font-size:1rem;">{{ $trajet->ville_arrivee }}</div>
                </div>
            </div>

            {{-- Infos --}}
            <div style="display:flex;gap:.75rem;margin-bottom:1.2rem;flex-wrap:wrap;">
                <span style="font-size:.78rem;color:var(--cr-muted);display:flex;align-items:center;gap:.3rem;">
                    📅 {{ $trajet->jours_recurrence }}
                </span>
                <span style="font-size:.78rem;{{ $estComplet ? 'color:#ef4444' : 'color:var(--cr-emerald)' }};font-weight:600;display:flex;align-items:center;gap:.3rem;">
                    🪑 {{ $estComplet ? 'Complet' : $placesRestantes . ' place' . ($placesRestantes > 1 ? 's' : '') }}
                </span>
                @if($maRes)
                    <span class="badge badge-{{ $maRes->statut === 'en_attente' ? 'attente' : $maRes->statut }}">{{ $maRes->statut_libelle }}</span>
                @endif
            </div>

            {{-- Actions --}}
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
                <a href="{{ route('trajets.show', $trajet) }}" class="btn-secondary" style="flex:1;justify-content:center;font-size:.85rem;">
                    👁 Voir détail
                </a>
                @auth
                    @if(auth()->user()->estPassager() && !$maRes && !$estComplet && $trajet->conducteur_id !== auth()->id())
                    <form method="POST" action="{{ route('score.store', $trajet) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;font-size:.85rem;">
                            🤖 Score IA
                        </button>
                    </form>
                    @endif
                @endauth
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div style="display:flex;justify-content:center;">
        {{ $trajets->links() }}
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title', 'Dashboard Conducteur')

@section('content')
<div class="container">

    {{-- ═══ Header ══════════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom:2rem;" class="fade-in">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 class="page-title">Dashboard Conducteur</h1>
                <p style="color:var(--cr-muted);margin-top:.3rem;">Gérez vos trajets et validez les réservations</p>
            </div>
            <a href="{{ route('trajets.create') }}" class="btn-primary pulse">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouveau trajet
            </a>
        </div>
    </div>

    {{-- ═══ Statistiques ════════════════════════════════════════════════════════ --}}
    <div class="grid-4 fade-in" style="margin-bottom:2.5rem;animation-delay:.05s;">
        <div class="glass stat-card">
            <div class="stat-value">{{ $stats['total_trajets'] }}</div>
            <div class="stat-label">Trajets actifs</div>
        </div>
        <div class="glass stat-card">
            <div class="stat-value" style="color:var(--cr-emerald);">{{ $stats['confirmees'] }}</div>
            <div class="stat-label">Réservations confirmées</div>
        </div>
        <div class="glass stat-card">
            <div class="stat-value" style="color:#f59e0b;">{{ $stats['en_attente'] }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="glass stat-card">
            <div class="stat-value" style="color:var(--cr-indigo);">{{ $stats['taux_remplissage'] }}%</div>
            <div class="stat-label">Taux de remplissage</div>
        </div>
    </div>

    {{-- ═══ Mes trajets ═════════════════════════════════════════════════════════ --}}
    @if($trajets->isEmpty())
        <div class="glass" style="padding:4rem;text-align:center;">
            <div style="font-size:3rem;margin-bottom:1rem;">🚗</div>
            <p style="color:var(--cr-muted);font-size:1.1rem;">Vous n'avez publié aucun trajet.</p>
            <a href="{{ route('trajets.create') }}" class="btn-primary" style="margin-top:1.5rem;display:inline-flex;">Publier mon premier trajet</a>
        </div>
    @else
        @foreach($trajets as $i => $trajet)
        <div class="glass fade-in" style="margin-bottom:1.5rem;overflow:hidden;animation-delay:{{ ($i+1)*0.07 }}s;">
            {{-- Header trajet --}}
            <div style="padding:1.2rem 1.5rem;border-bottom:1px solid var(--cr-border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div style="background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);border-radius:.5rem;padding:.5rem .9rem;font-weight:700;font-size:.9rem;color:var(--cr-indigo);">
                        {{ $trajet->horaire_formate }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:1rem;">{{ $trajet->ville_depart }} → {{ $trajet->ville_arrivee }}</div>
                        <div style="font-size:.78rem;color:var(--cr-muted);">{{ $trajet->jours_recurrence }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                    {{-- Places --}}
                    @php $placesRestantes = $trajet->places_disponibles - $trajet->reservations_confirmees_count; @endphp
                    <div style="text-align:center;">
                        <div style="font-weight:700;font-size:1.1rem;color:{{ $placesRestantes > 0 ? 'var(--cr-emerald)' : '#ef4444' }}">{{ $placesRestantes }}/{{ $trajet->places_disponibles }}</div>
                        <div style="font-size:.7rem;color:var(--cr-muted);">places libres</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-weight:700;font-size:1.1rem;color:#f59e0b;">{{ $trajet->reservations_attente_count }}</div>
                        <div style="font-size:.7rem;color:var(--cr-muted);">en attente</div>
                    </div>
                    {{-- Actions --}}
                    <div style="display:flex;gap:.6rem;">
                        <a href="{{ route('trajets.show', $trajet) }}" class="btn-secondary" style="font-size:.82rem;padding:.45rem .9rem;">👁 Voir</a>
                        <form method="POST" action="{{ route('trajets.destroy', $trajet) }}"
                              onsubmit="return confirm('Supprimer ce trajet ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="font-size:.82rem;padding:.45rem .9rem;">🗑</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Réservations en attente --}}
            @php $enAttente = $trajet->reservations->where('statut','en_attente'); @endphp
            @if($enAttente->isNotEmpty())
            <div style="padding:0 1.5rem;">
                @foreach($enAttente as $res)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 0;border-bottom:1px solid var(--cr-border);gap:1rem;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div class="avatar" style="width:2rem;height:2rem;font-size:.72rem;">{{ $res->passager->initiales }}</div>
                        <div>
                            <div style="font-size:.88rem;font-weight:600;">{{ $res->passager->name }}</div>
                            <div style="font-size:.72rem;color:var(--cr-muted);">{{ $res->passager->entreprise?->nom }} · {{ $res->passager->ville_residence }} · Demande du {{ $res->date_reservation->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
                        <form method="POST" action="{{ route('reservations.update', $res) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="confirmee">
                            <button type="submit" class="btn-success" style="font-size:.82rem;">✓ Confirmer</button>
                        </form>
                        <form method="POST" action="{{ route('reservations.update', $res) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="refusee">
                            <button type="submit" class="btn-danger" style="font-size:.82rem;">✕ Refuser</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="padding:1rem 1.5rem;color:var(--cr-muted);font-size:.85rem;">
                @if($trajet->reservations_count > 0)
                    ✅ Toutes les réservations sont traitées ({{ $trajet->reservations_confirmees_count }} confirmées)
                @else
                    🕐 Aucune réservation pour ce trajet
                @endif
            </div>
            @endif
        </div>
        @endforeach
    @endif
</div>
@endsection

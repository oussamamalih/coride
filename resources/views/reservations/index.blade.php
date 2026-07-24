@extends('layouts.app')
@section('title', 'Mes réservations')

@section('content')
<div class="container">

    <div style="margin-bottom:2rem;" class="fade-in">
        <h1 class="page-title">Mes réservations</h1>
        <p style="color:var(--cr-muted);margin-top:.3rem;">{{ $reservations->total() }} réservation{{ $reservations->total() > 1 ? 's' : '' }}</p>
    </div>

    @if($reservations->isEmpty())
        <div class="glass" style="padding:4rem;text-align:center;">
            <div style="font-size:3rem;margin-bottom:1rem;">🎫</div>
            <p style="color:var(--cr-muted);font-size:1.1rem;">Vous n'avez encore aucune réservation.</p>
            <a href="{{ route('trajets.index') }}" class="btn-primary" style="margin-top:1.5rem;display:inline-flex;">Explorer les trajets</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:1.2rem;">
            @foreach($reservations as $i => $res)
            @php $score = $res->score_compatibilite; @endphp
            <div class="glass fade-in" style="padding:1.5rem;animation-delay:{{ $i * 0.06 }}s;transition:transform .2s;"
                 onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='none'">
                <div style="display:flex;gap:1.5rem;align-items:center;flex-wrap:wrap;">

                    {{-- Score ring --}}
                    <div style="flex-shrink:0;">
                        @if($score)
                        @php $couleur = match(true){ $score->score>=80 => '#10b981', $score->score>=60 => '#f59e0b', $score->score>=40 => '#f97316', default => '#ef4444' }; @endphp
                        <div class="score-ring" style="width:70px;height:70px;" title="{{ $score->justification }}">
                            <svg width="70" height="70" viewBox="0 0 70 70">
                                <circle cx="35" cy="35" r="28" fill="none" stroke="rgba(255,255,255,.06)" stroke-width="7"/>
                                <circle cx="35" cy="35" r="28" fill="none" stroke="{{ $couleur }}" stroke-width="7"
                                    stroke-dasharray="{{ round(2*3.14159*28*$score->score/100) }} {{ round(2*3.14159*28) }}"
                                    stroke-linecap="round"/>
                            </svg>
                            <span class="score-value" style="color:{{ $couleur }};font-size:1rem;">{{ $score->score }}</span>
                        </div>
                        @else
                        <div style="width:70px;height:70px;border-radius:50%;border:2px dashed var(--cr-border);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:.2rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cr-muted)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            <span style="font-size:.65rem;color:var(--cr-muted);">IA</span>
                        </div>
                        @endif
                    </div>

                    {{-- Trajet info --}}
                    <div style="flex:1;min-width:200px;">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.5rem;flex-wrap:wrap;">
                            <span style="font-weight:700;font-size:1rem;">{{ $res->trajet->ville_depart }} → {{ $res->trajet->ville_arrivee }}</span>
                            <span class="badge badge-{{ $res->statut === 'en_attente' ? 'attente' : $res->statut }}">{{ $res->statut_libelle }}</span>
                        </div>
                        <div style="color:var(--cr-muted);font-size:.82rem;display:flex;gap:1rem;flex-wrap:wrap;">
                            <span>⏰ {{ $res->trajet->horaire_formate }}</span>
                            <span>📅 {{ $res->trajet->jours_recurrence }}</span>
                            <span>🧑 {{ $res->trajet->conducteur->name }}</span>
                            <span>🏢 {{ $res->trajet->conducteur->entreprise?->nom ?? '—' }}</span>
                        </div>
                        @if($score)
                        <div style="margin-top:.6rem;font-size:.8rem;color:var(--cr-muted);font-style:italic;line-clamp:1;-webkit-line-clamp:1;overflow:hidden;display:-webkit-box;-webkit-box-orient:vertical;">
                            {{ $score->justification }}
                        </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;flex-direction:column;gap:.6rem;flex-shrink:0;min-width:140px;">
                        <a href="{{ route('trajets.show', $res->trajet) }}" class="btn-secondary" style="font-size:.82rem;justify-content:center;">
                            👁 Voir trajet
                        </a>
                        @if(!$score)
                        <form method="POST" action="{{ route('score.store', $res->trajet) }}">
                            @csrf
                            <button type="submit" class="btn-primary" style="width:100%;font-size:.82rem;justify-content:center;">🤖 Score IA</button>
                        </form>
                        @endif
                        @if(in_array($res->statut, ['en_attente','confirmee']))
                        <form method="POST" action="{{ route('reservations.destroy', $res) }}"
                              onsubmit="return confirm('Annuler cette réservation ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="width:100%;font-size:.78rem;padding:.4rem .8rem;">Annuler</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top:2rem;display:flex;justify-content:center;">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection

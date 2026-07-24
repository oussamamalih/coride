@extends('layouts.app')
@section('title', $trajet->ville_depart . ' → ' . $trajet->ville_arrivee)

@section('content')
<div class="container">

    {{-- Breadcrumb --}}
    <nav style="margin-bottom:1.5rem;font-size:.85rem;color:var(--cr-muted);">
        <a href="{{ route('trajets.index') }}" style="color:var(--cr-indigo);text-decoration:none;">← Tous les trajets</a>
    </nav>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:2rem;align-items:start;">

        {{-- ═══ Colonne principale ═══════════════════════════════════════════════ --}}
        <div>
            {{-- Carte trajet --}}
            <div class="glass fade-in" style="padding:2rem;margin-bottom:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;">
                    <h1 class="page-title" style="font-size:1.5rem;">{{ $trajet->ville_depart }} → {{ $trajet->ville_arrivee }}</h1>
                    @if(auth()->id() === $trajet->conducteur_id)
                        <form method="POST" action="{{ route('trajets.destroy', $trajet) }}"
                              onsubmit="return confirm('Supprimer ce trajet ? Cette action est irréversible.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="font-size:.82rem;">🗑 Supprimer</button>
                        </form>
                    @endif
                </div>

                {{-- Itinéraire visuel --}}
                <div style="background:rgba(255,255,255,.04);border-radius:.8rem;padding:1.5rem;margin-bottom:1.5rem;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <div style="text-align:center;flex:1;">
                            <div style="font-size:.72rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.4rem;">Départ</div>
                            <div style="font-size:1.6rem;font-weight:800;">{{ $trajet->ville_depart }}</div>
                        </div>
                        <div style="flex:2;display:flex;flex-direction:column;align-items:center;gap:.5rem;">
                            <div style="background:linear-gradient(135deg,var(--cr-indigo),#818cf8);color:#fff;padding:.4rem 1.2rem;border-radius:2rem;font-size:1rem;font-weight:800;">
                                {{ $trajet->horaire_formate }}
                            </div>
                            <div style="width:100%;height:2px;background:linear-gradient(90deg,var(--cr-indigo),var(--cr-emerald));border-radius:1px;position:relative;">
                                <div style="position:absolute;top:-6px;right:0;width:0;height:0;border-left:10px solid var(--cr-emerald);border-top:7px solid transparent;border-bottom:7px solid transparent;"></div>
                            </div>
                        </div>
                        <div style="text-align:center;flex:1;">
                            <div style="font-size:.72rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.4rem;">Arrivée</div>
                            <div style="font-size:1.6rem;font-weight:800;">{{ $trajet->ville_arrivee }}</div>
                        </div>
                    </div>
                </div>

                {{-- Détails --}}
                <div class="grid-4">
                    <div style="padding:1rem;background:rgba(255,255,255,.04);border-radius:.7rem;text-align:center;">
                        <div style="font-size:1.4rem;margin-bottom:.3rem;">📅</div>
                        <div style="font-size:.72rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.06em;">Jours</div>
                        <div style="font-weight:600;font-size:.85rem;margin-top:.3rem;">{{ $trajet->jours_recurrence }}</div>
                    </div>
                    <div style="padding:1rem;background:rgba(255,255,255,.04);border-radius:.7rem;text-align:center;">
                        <div style="font-size:1.4rem;margin-bottom:.3rem;">🪑</div>
                        <div style="font-size:.72rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.06em;">Places dispo</div>
                        <div style="font-weight:700;font-size:1.2rem;margin-top:.3rem;color:{{ $placesRestantes > 0 ? 'var(--cr-emerald)' : '#ef4444' }}">{{ $placesRestantes }}</div>
                    </div>
                    <div style="padding:1rem;background:rgba(255,255,255,.04);border-radius:.7rem;text-align:center;">
                        <div style="font-size:1.4rem;margin-bottom:.3rem;">🏢</div>
                        <div style="font-size:.72rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.06em;">Entreprise</div>
                        <div style="font-weight:600;font-size:.82rem;margin-top:.3rem;">{{ $trajet->conducteur->entreprise?->nom ?? '—' }}</div>
                    </div>
                    <div style="padding:1rem;background:rgba(255,255,255,.04);border-radius:.7rem;text-align:center;">
                        <div style="font-size:1.4rem;margin-bottom:.3rem;">👤</div>
                        <div style="font-size:.72rem;color:var(--cr-muted);text-transform:uppercase;letter-spacing:.06em;">Conducteur</div>
                        <div style="font-weight:600;font-size:.82rem;margin-top:.3rem;">{{ $trajet->conducteur->name }}</div>
                    </div>
                </div>
            </div>

            {{-- ═══ Score IA ════════════════════════════════════════════════════ --}}
            @if($maReservation && $maReservation->aUnScore())
            @php $score = $maReservation->score_compatibilite; @endphp
            <div class="glass fade-in" style="padding:2rem;margin-bottom:1.5rem;animation-delay:.15s;border-color:rgba(99,102,241,.4);">
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;">
                    <div style="width:2rem;height:2rem;background:linear-gradient(135deg,var(--cr-indigo),#818cf8);border-radius:.5rem;display:flex;align-items:center;justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <h2 style="font-size:1.1rem;font-weight:700;margin:0;">Score de compatibilité IA</h2>
                </div>

                <div style="display:flex;gap:2rem;align-items:center;flex-wrap:wrap;">
                    {{-- Grand score --}}
                    @php
                        $couleur = match(true){ $score->score>=80 => '#10b981', $score->score>=60 => '#f59e0b', $score->score>=40 => '#f97316', default => '#ef4444' };
                        $circumference = round(2 * 3.14159 * 48);
                        $dash = round($circumference * $score->score / 100);
                    @endphp
                    <div style="text-align:center;flex-shrink:0;">
                        <div class="score-ring" style="width:120px;height:120px;">
                            <svg width="120" height="120" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="48" fill="none" stroke="rgba(255,255,255,.06)" stroke-width="10"/>
                                <circle cx="60" cy="60" r="48" fill="none" stroke="{{ $couleur }}" stroke-width="10"
                                    stroke-dasharray="{{ $dash }} {{ $circumference }}" stroke-linecap="round"
                                    style="transition:stroke-dasharray 1.2s ease;filter:drop-shadow(0 0 8px {{ $couleur }}66);"/>
                            </svg>
                            <div class="score-value" style="font-size:1.8rem;color:{{ $couleur }};">{{ $score->score }}</div>
                        </div>
                        <div style="font-size:.8rem;font-weight:700;color:{{ $couleur }};margin-top:.4rem;">{{ $score->libelleCouleur() }}</div>
                    </div>

                    {{-- Justification et détails --}}
                    <div style="flex:1;min-width:200px;">
                        <p style="color:var(--cr-text);line-height:1.6;margin:0 0 1.2rem;">{{ $score->justification }}</p>

                        @if($score->points_forts)
                        <div style="margin-bottom:.8rem;">
                            @foreach($score->points_forts as $point)
                            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.4rem;">
                                <span style="color:var(--cr-emerald);font-size:.9rem;">✓</span>
                                <span style="font-size:.85rem;">{{ $point }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if($score->points_faibles)
                        <div style="margin-bottom:.8rem;">
                            @foreach($score->points_faibles as $point)
                            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.4rem;">
                                <span style="color:#f59e0b;font-size:.9rem;">⚠</span>
                                <span style="font-size:.85rem;color:var(--cr-muted);">{{ $point }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);border-radius:.5rem;padding:.5rem .9rem;">
                            <span style="font-size:.78rem;color:var(--cr-muted);">Horaire suggéré :</span>
                            <span style="font-weight:700;color:var(--cr-indigo);">{{ $score->horaire_suggere }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══ Passagers inscrits ═════════════════════════════════════════ --}}
            @if(auth()->id() === $trajet->conducteur_id && $trajet->reservations->isNotEmpty())
            <div class="glass fade-in" style="padding:1.5rem;animation-delay:.2s;">
                <h2 style="font-size:1rem;font-weight:700;margin:0 0 1rem;">🎫 Réservations</h2>
                @foreach($trajet->reservations as $res)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:.85rem 0;border-bottom:1px solid var(--cr-border);gap:1rem;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div class="avatar" style="width:2rem;height:2rem;font-size:.72rem;">{{ $res->passager->initiales }}</div>
                        <div>
                            <div style="font-size:.88rem;font-weight:600;">{{ $res->passager->name }}</div>
                            <div style="font-size:.75rem;color:var(--cr-muted);">{{ $res->passager->entreprise?->nom }} · {{ $res->passager->ville_residence }}</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <span class="badge badge-{{ $res->badge_class === 'badge-attente' ? 'attente' : str_replace('badge-','',$res->badge_class) }}">{{ $res->statut_libelle }}</span>
                        @if($res->statut === 'en_attente')
                        <form method="POST" action="{{ route('reservations.update', $res) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="confirmee">
                            <button type="submit" class="btn-success" style="font-size:.78rem;padding:.35rem .8rem;">✓ Confirmer</button>
                        </form>
                        <form method="POST" action="{{ route('reservations.update', $res) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="refusee">
                            <button type="submit" class="btn-danger" style="font-size:.78rem;padding:.35rem .8rem;">✕ Refuser</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ═══ Sidebar réservation ════════════════════════════════════════════ --}}
        <div>
            <div class="glass fade-in" style="padding:1.5rem;position:sticky;top:5rem;animation-delay:.1s;">
                <h2 style="font-size:1rem;font-weight:700;margin:0 0 1.2rem;">
                    @if($maReservation) 🎫 Ma réservation @else 🚗 Réserver ce trajet @endif
                </h2>

                @guest
                    <p style="color:var(--cr-muted);font-size:.88rem;margin-bottom:1rem;">Connectez-vous pour réserver.</p>
                    <a href="{{ route('login') }}" class="btn-primary" style="width:100%;justify-content:center;">Se connecter</a>

                @elseif(auth()->id() === $trajet->conducteur_id)
                    <div style="text-align:center;padding:1rem;color:var(--cr-muted);font-size:.88rem;">
                        <div style="font-size:2rem;margin-bottom:.5rem;">🚗</div>
                        Vous êtes le conducteur de ce trajet.
                    </div>

                @elseif($maReservation)
                    {{-- Statut de la réservation --}}
                    <div style="text-align:center;margin-bottom:1.5rem;">
                        <div style="font-size:3rem;margin-bottom:.5rem;">
                            @if($maReservation->statut === 'confirmee') ✅
                            @elseif($maReservation->statut === 'refusee') ❌
                            @elseif($maReservation->statut === 'annulee') 🚫
                            @else ⏳ @endif
                        </div>
                        <span class="badge badge-{{ $maReservation->badge_class === 'badge-attente' ? 'attente' : str_replace('badge-','',$maReservation->badge_class) }}" style="font-size:.9rem;">
                            {{ $maReservation->statut_libelle }}
                        </span>
                        <p style="color:var(--cr-muted);font-size:.82rem;margin-top:.75rem;">Réservé le {{ $maReservation->date_reservation->format('d/m/Y') }}</p>
                    </div>

                    {{-- Calcul score IA --}}
                    @if(!$maReservation->aUnScore())
                    <form method="POST" action="{{ route('score.store', $trajet) }}" style="margin-bottom:1rem;">
                        @csrf
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;" id="score-btn">
                            🤖 Calculer mon score IA
                        </button>
                    </form>
                    @endif

                    {{-- Annulation --}}
                    @if(in_array($maReservation->statut, ['en_attente', 'confirmee']))
                    <form method="POST" action="{{ route('reservations.destroy', $maReservation) }}"
                          onsubmit="return confirm('Annuler cette réservation ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-secondary" style="width:100%;justify-content:center;font-size:.85rem;">
                            Annuler ma réservation
                        </button>
                    </form>
                    @endif

                @elseif(!auth()->user()->estPassager())
                    <p style="color:var(--cr-muted);font-size:.88rem;text-align:center;">
                        Seuls les passagers peuvent réserver.
                    </p>

                @elseif($placesRestantes <= 0)
                    <div style="text-align:center;padding:1rem;color:#ef4444;font-size:.9rem;">
                        <div style="font-size:2rem;margin-bottom:.5rem;">🚫</div>
                        Ce trajet est complet.
                    </div>

                @else
                    {{-- Formulaire de réservation --}}
                    <form method="POST" action="{{ route('reservations.store') }}">
                        @csrf
                        <input type="hidden" name="trajet_id" value="{{ $trajet->id }}">
                        <div style="background:rgba(255,255,255,.04);border-radius:.7rem;padding:1rem;margin-bottom:1.2rem;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                                <span style="color:var(--cr-muted);font-size:.82rem;">Places restantes</span>
                                <span style="color:var(--cr-emerald);font-weight:700;">{{ $placesRestantes }} / {{ $trajet->places_disponibles }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:var(--cr-muted);font-size:.82rem;">Horaire</span>
                                <span style="font-weight:600;">{{ $trajet->horaire_formate }}</span>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary pulse" style="width:100%;justify-content:center;">
                            🎫 Réserver ma place
                        </button>
                    </form>
                    <p style="color:var(--cr-muted);font-size:.75rem;margin-top:.75rem;text-align:center;">
                        En attente de confirmation du conducteur
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

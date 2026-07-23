@extends('layouts.app')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>Détail du trajet</h3>
    </div>

    <div class="card-body">

        <p><strong>Conducteur :</strong> {{ $trajet->conducteur->nom }}</p>

        <p><strong>Ville de départ :</strong> {{ $trajet->ville_depart }}</p>

        <p><strong>Ville d'arrivée :</strong> {{ $trajet->ville_arrivee }}</p>

        <p><strong>Horaire :</strong> {{ $trajet->horaire->format('d/m/Y H:i') }}</p>

        <p><strong>Places disponibles :</strong> {{ $trajet->places_disponibles }}</p>

        <p><strong>Jours de récurrence :</strong>
            {{ $trajet->jours_recurrence ?? 'Aucun' }}
        </p>

        @auth
            @if($trajet->conducteur_id !== auth()->id())
                @php
                    $dejaReserve = $trajet->reservations()
                        ->where('passager_id', auth()->id())
                        ->exists();
                @endphp

                @if($dejaReserve)
                    <div class="alert alert-info">Vous avez déjà réservé ce trajet.</div>
                @else
                    <form action="{{ route('reservations.store') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="trajet_id" value="{{ $trajet->id }}">
                        <button class="btn btn-success">Réserver</button>
                    </form>
                @endif
            @endif
        @endauth

        <a href="{{ route('trajets.index') }}" class="btn btn-secondary">
            Retour
        </a>

    </div>

</div>

@auth
    @if($trajet->conducteur_id === auth()->id())

        <div class="card shadow mt-4">
            <div class="card-header">
                <h4>Réservations pour ce trajet</h4>
            </div>

            <div class="card-body">

                @if($trajet->reservations->count())

                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Passager</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trajet->reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->passager->nom }}</td>
                            <td>{{ $reservation->statut }}</td>
                            <td>
                                @if($reservation->statut === 'en_attente')
                                <form action="{{ route('reservations.update',$reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="confirmee">
                                    <button class="btn btn-success btn-sm">Confirmer</button>
                                </form>
                                <form action="{{ route('reservations.update',$reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="refusee">
                                    <button class="btn btn-danger btn-sm">Refuser</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @else
                <p class="text-muted mb-0">Aucune réservation pour ce trajet.</p>
                @endif

            </div>
        </div>

    @endif
@endauth

@endsection
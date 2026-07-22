@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <h2>Mes réservations</h2>

</div>

@if($reservations->count())

<table class="table table-bordered">

    <thead class="table-dark">

    <tr>

        <th>Trajet</th>
        <th>Passager</th>
        <th>Statut</th>
        <th>Date</th>

    </tr>

    </thead>

    <tbody>

    @foreach($reservations as $reservation)

    <tr>

        <td>
            {{ $reservation->trajet->ville_depart }}
            →
            {{ $reservation->trajet->ville_arrivee }}
        </td>

        <td>
            {{ $reservation->passager->nom }}
        </td>

        <td>

            @switch($reservation->statut)

                @case('confirmee')
                    <span class="badge bg-success">Confirmée</span>
                    @break

                @case('refusee')
                    <span class="badge bg-danger">Refusée</span>
                    @break

                @case('annulee')
                    <span class="badge bg-secondary">Annulée</span>
                    @break

                @default
                    <span class="badge bg-warning text-dark">
                        En attente
                    </span>

            @endswitch

        </td>

        <td>

            {{ $reservation->date_reservation->format('d/m/Y') }}

        </td>

    </tr>

    @endforeach

    </tbody>

</table>

{{ $reservations->links() }}

@else

<div class="alert alert-info">

Aucune réservation.

</div>

@endif

@endsection
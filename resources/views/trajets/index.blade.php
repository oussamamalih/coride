@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Liste des trajets</h2>

    <a href="{{ route('trajets.create') }}" class="btn btn-primary">
        + Nouveau trajet
    </a>
</div>

@if($trajets->count())

<table class="table table-bordered table-hover align-middle">

    <thead class="table-dark">

        <tr>
            <th>#</th>
            <th>Conducteur</th>
            <th>Départ</th>
            <th>Arrivée</th>
            <th>Horaire</th>
            <th>Places</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

    @foreach($trajets as $trajet)

        <tr>

            <td>{{ $trajet->id }}</td>

            <td>{{ $trajet->conducteur->nom }}</td>

            <td>{{ $trajet->ville_depart }}</td>

            <td>{{ $trajet->ville_arrivee }}</td>

            <td>{{ $trajet->horaire->format('d/m/Y H:i') }}</td>

            <td>{{ $trajet->places_disponibles }}</td>

            <td>

                <a href="{{ route('trajets.show',$trajet) }}"
                    class="btn btn-info btn-sm">
                    Voir
                </a>

                <a href="{{ route('trajets.edit',$trajet) }}"
                    class="btn btn-warning btn-sm">
                    Modifier
                </a>

                <form action="{{ route('trajets.destroy',$trajet) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button
                        onclick="return confirm('Supprimer ce trajet ?')"
                        class="btn btn-danger btn-sm">

                        Supprimer

                    </button>

                </form>

            </td>

        </tr>

    @endforeach

    </tbody>

</table>

{{ $trajets->links() }}

@else

<div class="alert alert-info">
    Aucun trajet disponible.
</div>

@endif

@endsection
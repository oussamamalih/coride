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

        <a href="{{ route('trajets.index') }}" class="btn btn-secondary">
            Retour
        </a>

    </div>

</div>

@endsection
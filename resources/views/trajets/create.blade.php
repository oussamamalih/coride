@extends('layouts.app')

@section('content')

<h2>Créer un trajet</h2>

<form action="{{ route('trajets.store') }}" method="POST">

    @csrf

    <div class="mb-3">
        <label>Ville de départ</label>
        <input
            type="text"
            name="ville_depart"
            class="form-control"
            required>
    </div>

    <div class="mb-3">
        <label>Ville d'arrivée</label>
        <input
            type="text"
            name="ville_arrivee"
            class="form-control"
            required>
    </div>

    <div class="mb-3">
        <label>Horaire</label>
        <input
            type="datetime-local"
            name="horaire"
            class="form-control"
            required>
    </div>

    <div class="mb-3">
        <label>Places disponibles</label>
        <input
            type="number"
            name="places_disponibles"
            class="form-control"
            min="1"
            required>
    </div>

    <div class="mb-3">
        <label>Jours de récurrence</label>
        <input
            type="text"
            name="jours_recurrence"
            class="form-control"
            placeholder="Ex : Lundi, Mardi, Vendredi">
    </div>

    <button class="btn btn-success">
        Enregistrer
    </button>

    <a href="{{ route('trajets.index') }}" class="btn btn-secondary">
        Retour
    </a>

</form>

@endsection
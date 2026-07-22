@extends('layouts.app')

@section('content')

<h2>Modifier un trajet</h2>

<form action="{{ route('trajets.update',$trajet) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Ville de départ</label>
        <input type="text"
               class="form-control"
               name="ville_depart"
               value="{{ old('ville_depart',$trajet->ville_depart) }}">
    </div>

    <div class="mb-3">
        <label>Ville d'arrivée</label>
        <input type="text"
               class="form-control"
               name="ville_arrivee"
               value="{{ old('ville_arrivee',$trajet->ville_arrivee) }}">
    </div>

    <div class="mb-3">
        <label>Horaire</label>
        <input type="datetime-local"
               class="form-control"
               name="horaire"
               value="{{ $trajet->horaire->format('Y-m-d\TH:i') }}">
    </div>

    <div class="mb-3">
        <label>Places disponibles</label>
        <input type="number"
               class="form-control"
               name="places_disponibles"
               value="{{ $trajet->places_disponibles }}">
    </div>

    <div class="mb-3">
        <label>Jours de récurrence</label>
        <input type="text"
               class="form-control"
               name="jours_recurrence"
               value="{{ $trajet->jours_recurrence }}">
    </div>

    <button class="btn btn-success">
        Modifier
    </button>

    <a href="{{ route('trajets.index') }}" class="btn btn-secondary">
        Retour
    </a>

</form>

@endsection
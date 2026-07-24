@extends('layouts.app')

@section('content')

<div style="max-width: 800px; margin: 0 auto;">

    <div class="glass-panel" style="padding: 0; overflow: hidden;">
        <div style="background: var(--primary); color: white; padding: 24px;">
            <h3 style="margin: 0;">🚗 Publier un nouveau trajet</h3>
        </div>

        <div style="padding: 32px;">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('trajets.store') }}" method="POST">
                @csrf
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="form-group">
                        <label class="form-label">📍 Ville de départ</label>
                        <input type="text" name="ville_depart" class="form-control" value="{{ old('ville_depart') }}" placeholder="Ex : Casablanca" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">🏁 Ville d'arrivée</label>
                        <input type="text" name="ville_arrivee" class="form-control" value="{{ old('ville_arrivee') }}" placeholder="Ex : Rabat" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">🕒 Horaire</label>
                        <input type="datetime-local" name="horaire" class="form-control" value="{{ old('horaire') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">💺 Places disponibles</label>
                        <input type="number" name="places_disponibles" class="form-control" value="{{ old('places_disponibles') }}" min="1" max="8" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 24px;">
                    <label class="form-label">📅 Jours de récurrence</label>
                    <input type="text" name="jours_recurrence" class="form-control" value="{{ old('jours_recurrence') }}" placeholder="Ex : Lundi, Mardi, Jeudi">
                    <small style="color: var(--text-muted-light); margin-top: 8px; display: block;">Facultatif</small>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 32px;">
                    <a href="{{ route('trajets.index') }}" class="btn btn-outline">← Retour</a>
                    <button type="submit" class="btn btn-primary">🚗 Publier le trajet</button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
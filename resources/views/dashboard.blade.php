@extends('layouts.app')

@section('content')

<div class="card shadow">

    <div class="card-body">

        <h2 class="mb-3">
            Bienvenue {{ auth()->user()->nom }}
        </h2>

        <p>
            Gérez vos trajets et vos réservations depuis votre espace personnel.
        </p>

        <div class="row mt-4">

            <div class="col-md-4">

                <div class="card text-center">

                    <div class="card-body">

                        <h3>🚗</h3>

                        <h5>Trajets</h5>

                        <a href="{{ route('trajets.index') }}"
                            class="btn btn-primary">

                            Voir les trajets

                        </a>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card text-center">

                    <div class="card-body">

                        <h3>📅</h3>

                        <h5>Réservations</h5>

                        <a href="{{ route('reservations.index') }}"
                            class="btn btn-success">

                            Mes réservations

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
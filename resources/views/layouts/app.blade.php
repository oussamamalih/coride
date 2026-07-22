<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoRide</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            🚗 CoRide
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            @auth

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('trajets.index') }}">
                        Trajets
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reservations.index') }}">
                        Réservations
                    </a>
                </li>

            </ul>

            <span class="text-white me-3">
                {{ auth()->user()->nom }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-light btn-sm">
                    Déconnexion
                </button>
            </form>

            @endauth

        </div>

    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
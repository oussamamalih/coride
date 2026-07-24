<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            🚗 CoRide
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">

                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                       href="{{ route('dashboard') }}">

                        🏠 Dashboard

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->routeIs('trajets.*') ? 'active fw-bold' : '' }}"
                       href="{{ route('trajets.index') }}">

                        🚗 Trajets

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->routeIs('reservations.*') ? 'active fw-bold' : '' }}"
                       href="{{ route('reservations.index') }}">

                        📅 Réservations

                    </a>

                </li>

            </ul>

            <span class="badge bg-light text-dark me-3">

                👤 {{ Auth::user()->nom }}

            </span>

            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button type="submit" class="btn btn-light">

                    🚪 Déconnexion

                </button>

            </form>

        </div>

    </div>

</nav>
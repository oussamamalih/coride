<nav x-data="{ open: false }" class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center gap-10">

                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">

                    <i class="bi bi-car-front-fill text-blue-600 text-2xl"></i>

                    <span class="font-bold text-2xl text-gray-800">
                        CoRide
                    </span>

                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">

                    <a href="{{ route('dashboard') }}"
                       class="text-gray-700 hover:text-blue-600 font-medium transition">
                        Dashboard
                    </a>

                    {{-- Décommente quand les routes existent --}}
                    {{--
                    <a href="{{ route('trajets.index') }}"
                       class="text-gray-700 hover:text-blue-600 font-medium">
                        Trajets
                    </a>

                    <a href="{{ route('trajets.recherche') }}"
                       class="text-gray-700 hover:text-blue-600 font-medium">
                        Recherche
                    </a>

                    <a href="{{ route('reservations.index') }}"
                       class="text-gray-700 hover:text-blue-600 font-medium">
                        Réservations
                    </a>
                    --}}

                </div>

            </div>


            <!-- Right -->
            <div class="hidden md:flex items-center gap-5">

                <div class="text-end">

                    <p class="text-sm text-gray-500">
                        Bonjour
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ Auth::user()->name }}
                    </p>

                </div>

                <div class="relative">

                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">

                            <button
                                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                                <i class="bi bi-person-circle"></i>

                                Mon compte

                                <i class="bi bi-chevron-down"></i>

                            </button>

                        </x-slot>

                        <x-slot name="content">

                            <x-dropdown-link :href="route('profile.edit')">

                                👤 Profil

                            </x-dropdown-link>

                            <form method="POST"
                                  action="{{ route('logout') }}">

                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">

                                    🚪 Déconnexion

                                </x-dropdown-link>

                            </form>

                        </x-slot>

                    </x-dropdown>

                </div>

            </div>


            <!-- Mobile Button -->

            <button
                @click="open=!open"
                class="md:hidden text-2xl text-gray-700">

                <i class="bi bi-list"></i>

            </button>

        </div>
    </div>


    <!-- Mobile Menu -->

    <div x-show="open"
         class="md:hidden bg-white border-t">

        <a href="{{ route('dashboard') }}"
           class="block px-5 py-3 hover:bg-gray-100">

            Dashboard

        </a>

        {{-- Décommente plus tard --}}
        {{--
        <a href="{{ route('trajets.index') }}" class="block px-5 py-3">
            Trajets
        </a>

        <a href="{{ route('trajets.recherche') }}" class="block px-5 py-3">
            Recherche
        </a>

        <a href="{{ route('reservations.index') }}" class="block px-5 py-3">
            Réservations
        </a>
        --}}

        <a href="{{ route('profile.edit') }}"
           class="block px-5 py-3 hover:bg-gray-100">

            Profil

        </a>

        <form method="POST"
              action="{{ route('logout') }}">

            @csrf

            <button
                class="w-full text-left px-5 py-3 text-red-600 hover:bg-gray-100">

                Déconnexion

            </button>

        </form>

    </div>

</nav>
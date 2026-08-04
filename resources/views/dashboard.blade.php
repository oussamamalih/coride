<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">

            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    🚗 Dashboard CoRide
                </h2>

                <p class="text-gray-500 mt-1">
                    Bienvenue {{ Auth::user()->name }}
                </p>
            </div>

            <a href="#"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">
                + Nouveau trajet
            </a>

        </div>
    </x-slot>


    <div class="py-8">

        <!-- Statistics -->

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-blue-600 text-4xl mb-3">
                    🚗
                </div>

                <h3 class="text-gray-500">
                    Trajets
                </h3>

                <p class="text-3xl font-bold">
                    12
                </p>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-green-600 text-4xl mb-3">
                    👥
                </div>

                <h3 class="text-gray-500">
                    Réservations
                </h3>

                <p class="text-3xl font-bold">
                    8
                </p>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-orange-500 text-4xl mb-3">
                    🪑
                </div>

                <h3 class="text-gray-500">
                    Places disponibles
                </h3>

                <p class="text-3xl font-bold">
                    24
                </p>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-red-500 text-4xl mb-3">
                    ⭐
                </div>

                <h3 class="text-gray-500">
                    Avis
                </h3>

                <p class="text-3xl font-bold">
                    4.9
                </p>

            </div>

        </div>


        <!-- Main Content -->

        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Derniers trajets -->

            <div class="lg:col-span-2 bg-white rounded-xl shadow">

                <div class="border-b px-6 py-4">

                    <h3 class="font-bold text-lg">
                        Derniers trajets
                    </h3>

                </div>

                <table class="w-full">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="p-4 text-left">Départ</th>
                            <th class="p-4 text-left">Destination</th>
                            <th class="p-4 text-left">Date</th>
                            <th class="p-4 text-left">Places</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr class="border-b">

                            <td class="p-4">Casablanca</td>
                            <td class="p-4">Rabat</td>
                            <td class="p-4">04/08/2026</td>
                            <td class="p-4">3</td>

                        </tr>

                        <tr class="border-b">

                            <td class="p-4">Béni Mellal</td>
                            <td class="p-4">Khouribga</td>
                            <td class="p-4">05/08/2026</td>
                            <td class="p-4">2</td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- Profil -->

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-center">

                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563eb&color=fff"
                         class="mx-auto rounded-full mb-4">

                    <h2 class="text-xl font-bold">

                        {{ Auth::user()->name }}

                    </h2>

                    <p class="text-gray-500">

                        {{ Auth::user()->email }}

                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="mt-5 inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                        Modifier le profil

                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
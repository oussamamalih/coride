<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nom -->
        <div>
            <x-input-label for="nom" :value="'Nom'" />
            <x-text-input id="nom"
                          class="block mt-1 w-full"
                          type="text"
                          name="nom"
                          :value="old('nom')"
                          required
                          autofocus />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email"
                          class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Entreprise -->
        <div class="mt-4">
    <x-input-label for="entreprise_id" :value="'Entreprise'" />

    <select name="entreprise_id">
    @foreach($entreprises as $entreprise)
        <option value="{{ $entreprise->id }}">
            {{ $entreprise->nom }}
        </option>
    @endforeach
    </select>

    <x-input-error :messages="$errors->get('entreprise_id')" class="mt-2" />
    </div>

        <!-- Ville -->
        <div class="mt-4">
            <x-input-label for="ville_residence" :value="'Ville de résidence'" />
            <x-text-input id="ville_residence"
                          class="block mt-1 w-full"
                          type="text"
                          name="ville_residence"
                          :value="old('ville_residence')"
                          required />
            <x-input-error :messages="$errors->get('ville_residence')" class="mt-2" />
        </div>

        <!-- Rôle -->
        <div class="mt-4">
            <x-input-label for="role" :value="'Rôle'" />

            <select name="role" class="block mt-1 w-full rounded-md border-gray-300">
                <option value="conducteur">Conducteur</option>
                <option value="passager">Passager</option>
                <option value="les_deux">Les deux</option>
            </select>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="'Mot de passe'" />
            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmation -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Confirmer le mot de passe'" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600" href="{{ route('login') }}">
                Déjà inscrit ?
            </a>

            <x-primary-button class="ms-4">
                S'inscrire
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
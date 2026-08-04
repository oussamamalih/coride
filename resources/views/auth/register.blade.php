<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" />
            <x-text-input id="name" class="block mt-1 w-full bg-gray-900 text-white border-gray-700 focus:border-emerald-500 focus:ring-emerald-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Adresse Email professionnelle')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-900 text-white border-gray-700 focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Entreprise -->
        <div class="mt-4">
            <x-input-label for="entreprise_id" :value="__('Entreprise')" />
            <select id="entreprise_id" name="entreprise_id" required class="block mt-1 w-full bg-gray-900 text-white border-gray-700 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                <option value="" disabled {{ old('entreprise_id') ? '' : 'selected' }}>-- Sélectionnez votre entreprise --</option>
                @foreach($entreprises as $entreprise)
                    <option value="{{ $entreprise->id }}" {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                        {{ $entreprise->nom }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('entreprise_id')" class="mt-2" />
        </div>

        <!-- Ville de Résidence -->
        <div class="mt-4">
            <x-input-label for="ville_residence" :value="__('Ville de résidence')" />
            <x-text-input id="ville_residence" class="block mt-1 w-full bg-gray-900 text-white border-gray-700 focus:border-emerald-500 focus:ring-emerald-500" type="text" name="ville_residence" :value="old('ville_residence')" required placeholder="Ex: Casablanca" />
            <x-input-error :messages="$errors->get('ville_residence')" class="mt-2" />
        </div>

        <!-- Rôle favori -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Rôle principal')" />
            <select id="role" name="role" required class="block mt-1 w-full bg-gray-900 text-white border-gray-700 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                <option value="passager" {{ old('role') == 'passager' ? 'selected' : '' }}>Passager</option>
                <option value="conducteur" {{ old('role') == 'conducteur' ? 'selected' : '' }}>Conducteur</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full bg-gray-900 text-white border-gray-700 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 text-white border-gray-700 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-400 hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                {{ __('Déjà inscrit ? Connexion') }}
            </a>

            <x-primary-button class="ms-4 bg-emerald-600 hover:bg-emerald-500 text-white focus:bg-emerald-700 active:bg-emerald-800">
                {{ __("S'inscrire") }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

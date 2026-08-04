<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $entreprises = Entreprise::orderBy('nom')->get();
        return view('auth.register', compact('entreprises'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class,
                function ($attribute, $value, $fail) use ($request) {
                    $entreprise = Entreprise::find($request->entreprise_id);

                    if (! $entreprise || ! $entreprise->domaine_email) {
                        return; // Pas de domaine configuré pour cette entreprise : pas de restriction.
                    }

                    $domaineEmail = strtolower(substr(strrchr($value, '@'), 1));

                    if ($domaineEmail !== strtolower($entreprise->domaine_email)) {
                        $fail("L'adresse email doit être une adresse professionnelle @{$entreprise->domaine_email} pour l'entreprise {$entreprise->nom}.");
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'entreprise_id' => ['required', 'exists:entreprises,id'],
            'ville_residence' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:conducteur,passager'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'entreprise_id' => $request->entreprise_id,
            'ville_residence' => $request->ville_residence,
            'role' => $request->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

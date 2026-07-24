@extends('layouts.app')
@section('title', 'Publier un trajet')

@section('content')
<div class="container" style="max-width:640px;">
    <div style="margin-bottom:1.5rem;" class="fade-in">
        <a href="{{ route('trajets.index') }}" style="color:var(--cr-indigo);text-decoration:none;font-size:.85rem;">← Retour aux trajets</a>
    </div>

    <div class="glass fade-in" style="padding:2rem;animation-delay:.05s;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:2rem;">
            <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,var(--cr-indigo),#818cf8);border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <h1 class="page-title" style="font-size:1.4rem;">Publier un trajet</h1>
        </div>

        @if($errors->any())
        <div class="alert-error" style="margin-bottom:1.5rem;">
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($errors->all() as $e)<li style="font-size:.88rem;">{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('trajets.store') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;margin-bottom:1.2rem;">
                <div>
                    <label for="ville_depart">🏠 Ville de départ *</label>
                    <input id="ville_depart" name="ville_depart" type="text" class="input"
                           placeholder="Ex: Rabat" value="{{ old('ville_depart', auth()->user()->ville_residence) }}"
                           required list="villes-suggest">
                </div>
                <div>
                    <label for="ville_arrivee">🏢 Ville d'arrivée *</label>
                    <input id="ville_arrivee" name="ville_arrivee" type="text" class="input"
                           placeholder="Ex: Casablanca" value="{{ old('ville_arrivee') }}"
                           required list="villes-suggest">
                </div>
            </div>

            <datalist id="villes-suggest">
                @foreach(['Rabat','Casablanca','Salé','Témara','Skhirat','Mohammedia','Bouznika','Kénitra','Meknès','Fès'] as $v)
                    <option value="{{ $v }}">
                @endforeach
            </datalist>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;margin-bottom:1.2rem;">
                <div>
                    <label for="horaire">⏰ Horaire de départ *</label>
                    <input id="horaire" name="horaire" type="time" class="input"
                           value="{{ old('horaire', '08:00') }}" required>
                </div>
                <div>
                    <label for="places_disponibles">🪑 Places disponibles *</label>
                    <input id="places_disponibles" name="places_disponibles" type="number"
                           min="1" max="8" class="input" value="{{ old('places_disponibles', 2) }}" required>
                </div>
            </div>

            <div style="margin-bottom:1.8rem;">
                <label>📅 Jours de récurrence *</label>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.6rem;margin-top:.5rem;">
                    @php
                        $joursOptions = [
                            'Tous les jours'        => '🔁 Tous les jours',
                            'Lun,Mar,Mer,Jeu,Ven'  => '💼 Semaine (Lun–Ven)',
                            'Lun,Mer,Ven'           => 'Lun Mer Ven',
                            'Mar,Jeu'               => 'Mar Jeu',
                        ];
                        $selected = old('jours_recurrence', 'Tous les jours');
                    @endphp
                    @foreach($joursOptions as $val => $label)
                    <label style="cursor:pointer;margin:0;">
                        <input type="radio" name="jours_recurrence" value="{{ $val }}"
                               {{ $selected === $val ? 'checked' : '' }}
                               style="display:none;" class="jour-radio" id="jour_{{ $loop->index }}">
                        <div class="jour-option" id="jour-opt-{{ $loop->index }}"
                             style="border:1px solid var(--cr-border);border-radius:.6rem;padding:.65rem .5rem;text-align:center;font-size:.78rem;font-weight:500;transition:all .2s;cursor:pointer;background:{{ $selected === $val ? 'rgba(99,102,241,.15)' : 'rgba(255,255,255,.03)' }};border-color:{{ $selected === $val ? 'var(--cr-indigo)' : 'var(--cr-border)' }};color:{{ $selected === $val ? 'var(--cr-indigo)' : 'var(--cr-text)' }};">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;gap:1rem;">
                <button type="submit" class="btn-primary pulse" style="flex:1;justify-content:center;padding:.85rem;">
                    🚗 Publier le trajet
                </button>
                <a href="{{ route('trajets.index') }}" class="btn-secondary" style="padding:.85rem 1.5rem;">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
// Sélection visuelle des jours
document.querySelectorAll('.jour-radio').forEach((radio, i) => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.jour-option').forEach(opt => {
            opt.style.background = 'rgba(255,255,255,.03)';
            opt.style.borderColor = 'var(--cr-border)';
            opt.style.color = 'var(--cr-text)';
        });
        const opt = document.getElementById('jour-opt-' + i);
        opt.style.background = 'rgba(99,102,241,.15)';
        opt.style.borderColor = 'var(--cr-indigo)';
        opt.style.color = 'var(--cr-indigo)';
    });
    document.querySelectorAll('.jour-option')[i].addEventListener('click', () => {
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
    });
});
</script>
@endsection

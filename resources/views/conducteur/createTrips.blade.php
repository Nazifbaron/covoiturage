@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"/>
<style>
    #trip-map { height: 280px; border-radius: 16px; overflow: hidden; z-index: 0; }

    .autocomplete-list {
        position: absolute; top: 100%; left: 0; right: 0; z-index: 1000;
        background: white; border-radius: 12px; margin-top: 4px;
        box-shadow: 0 8px 32px rgba(0,0,0,.12); overflow: hidden;
        max-height: 220px; overflow-y: auto;
    }
    .dark .autocomplete-list {
        background: #152b1a; border: 1px solid rgba(19,236,73,.1);
        box-shadow: 0 8px 32px rgba(0,0,0,.4);
    }
    .autocomplete-item {
        padding: 10px 14px; font-size: 13px; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; gap: 8px;
        color: #1e293b; border-bottom: 1px solid #f1f5f9; transition: background .15s;
    }
    .dark .autocomplete-item { color: #e2e8f0; border-color: rgba(255,255,255,.06); }
    .autocomplete-item:hover { background: #f8fafc; }
    .dark .autocomplete-item:hover { background: rgba(255,255,255,.07); }
    .autocomplete-item:last-child { border-bottom: none; }

    .field-spinner {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; border-radius: 50%;
        border: 2px solid #e2e8f0; border-top-color: #13ec49;
        animation: spin .7s linear infinite; display: none;
    }
    @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }
    .leaflet-routing-container { display: none !important; }

    .map-marker-depart, .map-marker-arrivee {
        width: 28px; height: 28px; border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg); border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0,0,0,.25);
    }
    .map-marker-depart  { background: #13ec49; }
    .map-marker-arrivee { background: #f87171; }

    /* Compteur de places */
    .seat-btn {
        width: 36px; height: 36px; border-radius: 10px; border: 2px solid;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; font-weight: 900; cursor: pointer; transition: all .15s;
        flex-shrink: 0; user-select: none;
    }
    .seat-btn:hover { transform: scale(1.05); }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard') }}"
           class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                  flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Publier un trajet</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Les passagers aux alentours verront votre offre</p>
        </div>
    </div>

    {{-- ── Sélection du véhicule ── --}}
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Véhicule</h2>
            <a href="{{ route('profile.edit') }}"
               class="text-xs font-bold text-slate-400 hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-base">manage_accounts</span>
                Gérer
            </a>
        </div>

        @error('vehicle_id')
            <p class="text-xs text-red-500 font-semibold">{{ $message }}</p>
        @enderror

        @if($vehicles->count() === 1)
            {{-- Un seul véhicule : affichage info + champ caché --}}
            @php $v = $vehicles->first(); @endphp
            <input type="hidden" name="vehicle_id" value="{{ $v->id }}"/>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-primary/5 border border-primary/20">
                <div class="w-10 h-10 rounded-xl bg-primary/15 border border-primary/20
                            flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary text-xl">{{ $v->type_icon }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-slate-900 dark:text-white text-sm">{{ $v->full_name }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $v->color }}</p>
                </div>
                <span class="text-xs font-black text-slate-600 dark:text-slate-300
                             bg-slate-200 dark:bg-white/10 px-2.5 py-1 rounded-lg tracking-widest flex-shrink-0">
                    {{ strtoupper($v->plate) }}
                </span>
            </div>
        @else
            {{-- Plusieurs véhicules : sélecteur en cartes radio --}}
            <div class="grid grid-cols-1 gap-2">
                @foreach($vehicles as $v)
                <label class="relative cursor-pointer">
                    <input type="radio" name="vehicle_id" value="{{ $v->id }}"
                           {{ old('vehicle_id', $vehicles->first()->id) == $v->id ? 'checked' : '' }}
                           class="peer sr-only"/>
                    <div class="flex items-center gap-3 p-3 rounded-xl border-2
                                border-slate-200 dark:border-white/10
                                bg-slate-50 dark:bg-white/5
                                peer-checked:border-primary peer-checked:bg-primary/5
                                dark:peer-checked:bg-primary/10
                                transition-all cursor-pointer hover:border-primary/40">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 border border-slate-200 dark:border-white/10
                                    flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-slate-400 peer-checked:text-primary text-xl
                                         [.peer:checked~div_&]:text-primary">{{ $v->type_icon }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-slate-900 dark:text-white text-sm">{{ $v->full_name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $v->color }} · {{ $v->type_label }}</p>
                        </div>
                        <span class="text-xs font-black text-slate-600 dark:text-slate-300
                                     bg-slate-200 dark:bg-white/10 px-2.5 py-1 rounded-lg tracking-widest flex-shrink-0">
                            {{ strtoupper($v->plate) }}
                        </span>
                        {{-- Check badge --}}
                        <span class="hidden peer-checked:flex w-5 h-5 rounded-full bg-primary
                                     items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-background-dark" style="font-size:12px">check</span>
                        </span>
                    </div>
                </label>
                @endforeach
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('driver.trips.store') }}" class="space-y-4" id="trip-form">
        @csrf

        {{-- ── ITINÉRAIRE + CARTE ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-4">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Itinéraire</h2>

            <div class="flex gap-3">
                {{-- Connecteur visuel --}}
                <div class="flex flex-col items-center pt-3.5 gap-1 flex-shrink-0">
                    <span class="w-3 h-3 rounded-full bg-primary border-2 border-primary/30"></span>
                    <span class="w-0.5 flex-1 bg-slate-200 dark:bg-white/10 min-h-8"></span>
                    <span class="w-3 h-3 rounded-full bg-red-400 border-2 border-red-200"></span>
                </div>

                <div class="flex-1 space-y-2">
                    {{-- Départ --}}
                    <div class="relative" id="departure-wrapper">
                        <input type="text" id="departure_input"
                               placeholder="Point de départ *" autocomplete="off"
                               value="{{ old('departure_city') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                      text-sm font-semibold placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                        <div class="field-spinner" id="departure-spinner"></div>
                        <div class="autocomplete-list" id="departure-list" style="display:none"></div>
                        <input type="hidden" name="departure_city" id="departure_city" value="{{ old('departure_city') }}" required/>
                        <input type="hidden" name="departure_lat"  id="departure_lat"  value="{{ old('departure_lat') }}"/>
                        <input type="hidden" name="departure_lng"  id="departure_lng"  value="{{ old('departure_lng') }}"/>
                    </div>
                    @error('departure_city') <p class="text-xs text-red-500 font-semibold -mt-1">{{ $message }}</p> @enderror

                    {{-- Arrivée --}}
                    <div class="relative" id="arrival-wrapper">
                        <input type="text" id="arrival_input"
                               placeholder="Point d'arrivée *" autocomplete="off"
                               value="{{ old('arrival_city') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                      text-sm font-semibold placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                        <div class="field-spinner" id="arrival-spinner"></div>
                        <div class="autocomplete-list" id="arrival-list" style="display:none"></div>
                        <input type="hidden" name="arrival_city" id="arrival_city" value="{{ old('arrival_city') }}" required/>
                        <input type="hidden" name="arrival_lat"  id="arrival_lat"  value="{{ old('arrival_lat') }}"/>
                        <input type="hidden" name="arrival_lng"  id="arrival_lng"  value="{{ old('arrival_lng') }}"/>
                    </div>
                    @error('arrival_city') <p class="text-xs text-red-500 font-semibold -mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Carte Leaflet --}}
            <div class="relative">
                <div id="trip-map"></div>
                <div id="route-info"
                     class="absolute bottom-3 left-3 right-3 flex items-center justify-center gap-4
                            bg-white/90 dark:bg-card-dark/90 backdrop-blur-sm
                            rounded-xl px-4 py-2 shadow-lg border border-slate-100 dark:border-white/10
                            transition-all duration-300 opacity-0 pointer-events-none">
                    <span class="flex items-center gap-1.5 text-xs font-black text-slate-700 dark:text-slate-300">
                        <span class="material-symbols-outlined text-primary" style="font-size:16px">route</span>
                        <span id="route-distance">—</span>
                    </span>
                    <span class="w-px h-4 bg-slate-200 dark:bg-white/10"></span>
                    <span class="flex items-center gap-1.5 text-xs font-black text-slate-700 dark:text-slate-300">
                        <span class="material-symbols-outlined text-orange-400" style="font-size:16px">schedule</span>
                        <span id="route-duration">—</span>
                    </span>
                </div>
            </div>

            {{-- Adresses précises --}}
            <div class="flex-1 space-y-2">
                <input type="text" name="departure_address" value="{{ old('departure_address') }}"
                       placeholder="Point de rendez-vous (opt.)"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-medium placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>

                {{-- <input type="text" name="arrival_address" value="{{ old('arrival_address') }}"
                       placeholder="Point d'arrivée précis (opt.)"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-medium placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/> --}}
            </div>
        </div>

        {{-- ── DATE & HEURE ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-4">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Départ</h2>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Date *</label>
                    <input type="date" name="departure_date" value="{{ old('departure_date') }}"
                           min="{{ now()->format('Y-m-d') }}" required
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                    @error('departure_date') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Heure *</label>
                    <input type="time" name="departure_time" value="{{ old('departure_time') }}" required
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                    @error('departure_time') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ── PLACES & PRIX ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-4">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Places & Tarif</h2>
            <div class="grid grid-cols-2 gap-4">

                {{-- Compteur de places --}}
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 block">Places disponibles *</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="changeSeat(-1)"
                                class="seat-btn border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400
                                       hover:border-red-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10">−</button>
                        <div class="flex-1 text-center">
                            <span id="seats-display" class="text-2xl font-black text-slate-900 dark:text-white">{{ old('seats_total', 3) }}</span>
                            <p class="text-xs text-slate-400 font-medium">place{{ old('seats_total', 3) > 1 ? 's' : '' }}</p>
                        </div>
                        <button type="button" onclick="changeSeat(1)"
                                class="seat-btn border-primary/40 text-primary
                                       hover:bg-primary/10 hover:border-primary">+</button>
                        <input type="hidden" name="seats_total" id="seats_total" value="{{ old('seats_total', 3) }}"/>
                    </div>
                    @error('seats_total') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Prix --}}
                <div>
    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Prix / siège *</label>
    <div class="relative">
        <input type="number" name="price_per_seat"
               placeholder="Calculé automatiquement"
               readonly
               class="w-full pl-3 pr-16 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                      bg-slate-100 dark:bg-white/10 text-slate-900 dark:text-white
                      text-sm font-semibold placeholder-slate-400
                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all cursor-not-allowed"/>
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-black
                     text-slate-500 dark:text-slate-400 bg-slate-200 dark:bg-white/10 px-1.5 py-0.5 rounded-lg">FCFA</span>
    </div>
    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
        <span class="material-symbols-outlined text-primary text-sm">auto_awesome</span>
        Prix calculé automatiquement selon la distance et le nombre de places
    </p>
</div>
            </div>
        </div>

        {{-- ── OPTIONS ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-3">
            {{-- <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Options du trajet</h2>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    ['name' => 'luggage_allowed', 'icon' => 'luggage',          'label' => 'Bagages acceptés',    'default' => true],
                    ['name' => 'pets_allowed',    'icon' => 'pets',             'label' => 'Animaux acceptés',    'default' => false],
                    ['name' => 'silent_ride',     'icon' => 'hearing_disabled', 'label' => 'Trajet silencieux',   'default' => false],
                    ['name' => 'female_only',     'icon' => 'woman',            'label' => 'Femmes uniquement',   'default' => false],
                ] as $opt)
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 cursor-pointer
                              hover:bg-slate-100 dark:hover:bg-white/10 transition-colors select-none">
                    <input type="checkbox" name="{{ $opt['name'] }}" value="1"
                           {{ old($opt['name'], $opt['default'] ? '1' : '') ? 'checked' : '' }}
                           class="w-4 h-4 rounded accent-primary cursor-pointer flex-shrink-0"/>
                    <span class="material-symbols-outlined text-slate-500 dark:text-slate-400" style="font-size:18px">{{ $opt['icon'] }}</span>
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 leading-tight">{{ $opt['label'] }}</span>
                </label>
                @endforeach
            </div> --}}

            {{-- Point Important --}}
            <div>
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 block">
                    Point Important <span class="font-normal">(optionnel)</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach([
                        ['value' => 'Pas de bagages',       'icon' => 'no_luggage'],
                        ['value' => 'Non-fumeur',            'icon' => 'smoke_free'],
                        ['value' => 'Pas d\'animaux',        'icon' => 'pets'],
                        ['value' => 'Ponctualité requise',   'icon' => 'schedule'],
                        ['value' => 'Femmes uniquement',     'icon' => 'woman'],
                        ['value' => 'Silence apprécié',      'icon' => 'hearing_disabled'],
                    ] as $opt)
                    @php $checked = collect(old('notes', []))->contains($opt['value']); @endphp
                    <label class="note-tag flex items-center gap-1.5 px-3 py-1.5 rounded-full border-2 cursor-pointer select-none transition-all
                                  {{ $checked
                                      ? 'border-primary bg-primary/10 text-primary'
                                      : 'border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:border-primary/50' }}">
                        <input type="checkbox" name="notes[]" value="{{ $opt['value'] }}"
                               {{ $checked ? 'checked' : '' }} class="hidden">
                        <span class="material-symbols-outlined" style="font-size:15px">{{ $opt['icon'] }}</span>
                        <span class="text-xs font-semibold">{{ $opt['value'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── BOUTONS ── --}}
        <div class="flex gap-3 pb-6">
            <a href="{{ route('dashboard') }}"
               class="flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl
                      border-2 border-slate-200 dark:border-white/10
                      text-slate-600 dark:text-slate-400 font-bold text-sm
                      hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                Annuler
            </a>
            <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2
                           bg-primary hover:bg-primary/90 text-background-dark
                           font-black px-8 py-3.5 rounded-xl transition-all
                           shadow-lg shadow-primary/25 text-sm">
                <span class="material-symbols-outlined text-xl">publish</span>
                Publier le trajet
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
<script>
// ── Tags "Point Important" ─────────────────────────────────────────────
document.querySelectorAll('.note-tag').forEach(label => {
    label.addEventListener('click', () => {
        const cb = label.querySelector('input[type="checkbox"]');
        const active = cb.checked;
        label.classList.toggle('border-primary',    !active);
        label.classList.toggle('bg-primary/10',     !active);
        label.classList.toggle('text-primary',      !active);
        label.classList.toggle('border-slate-200',   active);
        label.classList.toggle('dark:border-white/10', active);
        label.classList.toggle('text-slate-500',     active);
        label.classList.toggle('dark:text-slate-400', active);
    });
});

(function () {
    //  Carte
    const map = L.map('trip-map', { zoomControl: true, attributionControl: false })
        .setView([9.3077, 2.3158], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    function makeIcon(type) {
        return L.divIcon({
            html: `<div class="map-marker-${type}"></div>`,
            className: '', iconSize: [28, 28], iconAnchor: [14, 28]
        });
    }

    let markerDepart = null, markerArrivee = null, routeControl = null;
    let coords = { depart: null, arrivee: null };

    // Autocomplétion
    let debounceTimer = null;

    function setupAutocomplete(inputId, listId, spinnerId, hiddenCityId, latId, lngId, type) {
        const input   = document.getElementById(inputId);
        const list    = document.getElementById(listId);
        const spinner = document.getElementById(spinnerId);

        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const q = input.value.trim();
            if (q.length < 3) { list.style.display = 'none'; return; }

            spinner.style.display = 'block';
            debounceTimer = setTimeout(async () => {
                try {
                    const res  = await fetch(
                        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=6&countrycodes=bj,tg,ng,gh,ne,bf&accept-language=fr`,
                        { headers: { 'Accept-Language': 'fr' } }
                    );
                    const data = await res.json();
                    spinner.style.display = 'none';
                    renderList(data, list, input, hiddenCityId, latId, lngId, type);
                } catch {
                    spinner.style.display = 'none';
                }
            }, 350);
        });

        document.addEventListener('click', e => {
            if (!input.contains(e.target)) list.style.display = 'none';
        });
    }

    function renderList(results, list, input, hiddenCityId, latId, lngId, type) {
        list.innerHTML = '';
        if (!results.length) { list.style.display = 'none'; return; }

        results.forEach(r => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            const shortName = r.display_name.split(',').slice(0, 3).join(', ');
            item.innerHTML = `
                <span class="material-symbols-outlined text-slate-400" style="font-size:16px">location_on</span>
                <span>${shortName}</span>`;
            item.addEventListener('click', () => {
                input.value = shortName;
                document.getElementById(hiddenCityId).value = shortName;
                document.getElementById(latId).value = r.lat;
                document.getElementById(lngId).value = r.lon;
                list.style.display = 'none';
                coords[type] = [parseFloat(r.lat), parseFloat(r.lon)];
                updateMap(type, coords[type]);
            });
            list.appendChild(item);
        });
        list.style.display = 'block';
    }

    function updateMap(type, latlng) {
        if (type === 'depart') {
            if (markerDepart) map.removeLayer(markerDepart);
            markerDepart = L.marker(latlng, { icon: makeIcon('depart') }).addTo(map);
        } else {
            if (markerArrivee) map.removeLayer(markerArrivee);
            markerArrivee = L.marker(latlng, { icon: makeIcon('arrivee') }).addTo(map);
        }
        if (coords.depart && coords.arrivee) drawRoute(coords.depart, coords.arrivee);
        else map.setView(latlng, 11);
    }

    function drawRoute(from, to) {
        if (routeControl) map.removeControl(routeControl);
        routeControl = L.Routing.control({
            waypoints: [L.latLng(from), L.latLng(to)],
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'driving',
            }),
            lineOptions: {
                styles: [{ color: '#13ec49', weight: 5, opacity: 0.85 }],
                extendToWaypoints: false, missingRouteTolerance: 0,
            },
            createMarker: () => null,
            show: false, addWaypoints: false, fitSelectedRoutes: true,
        }).addTo(map);

        routeControl.on('routesfound', e => {
            const r    = e.routes[0].summary;
            const dist = (r.totalDistance / 1000).toFixed(1) + ' km';
            const mins = Math.round(r.totalTime / 60);
            const dur  = mins >= 60
                ? Math.floor(mins/60) + 'h' + (mins%60 ? (mins%60)+'min' : '')
                : mins + ' min';
            document.getElementById('route-distance').textContent = dist;
            document.getElementById('route-duration').textContent = dur;
            const info = document.getElementById('route-info');
            info.classList.remove('opacity-0', 'pointer-events-none');
            info.classList.add('opacity-100');
        });
    }

    //  Compteur de places
    window.changeSeat = function(delta) {
        const input   = document.getElementById('seats_total');
        const display = document.getElementById('seats-display');
        let val = parseInt(input.value) + delta;
        val = Math.min(7, Math.max(1, val));
        input.value = val;
        display.textContent = val;
        display.nextElementSibling.textContent = 'place' + (val > 1 ? 's' : '');
    };

    // Ajoutez ce script après votre code existant
(function() {
    // Configuration des prix par kilomètre selon le type de véhicule
    const pricingConfig = {
        'tricycle': { pricePerKm: 40 },      // 40 FCFA par km pour tricycle
        'voiture': { pricePerKm: 50 },       // 50 FCFA par km pour voiture

    };

    // Fonction pour obtenir le type de véhicule
    function getVehicleType() {
        // Rechercher le nom du véhicule dans le texte affiché
        const vehicleNameElement = document.querySelector('.font-black.text-slate-900.dark\\:text-white');
        if (vehicleNameElement) {
            const vehicleText = vehicleNameElement.textContent.toLowerCase();
            if (vehicleText.includes('tricycle') || vehicleText.includes('tricycle')) {
                return 'tricycle';
            }
            if (vehicleText.includes('voiture') || vehicleText.includes('car')) {
                return 'voiture';
            }
        }

        // Alternative: vérifier l'icône
        const vehicleIcon = document.querySelector('.material-symbols-outlined.text-primary');
        if (vehicleIcon) {
            const iconType = vehicleIcon.textContent.toLowerCase();
            if (iconType.includes('tricycle') || iconType.includes('pedal')) {
                return 'tricycle';
            }
            if (iconType.includes('directions_car') || iconType.includes('electric_car') || iconType.includes('car')) {
                return 'voiture';
            }
        }

        return 'voiture'; // Par défaut
    }

    // Fonction pour calculer le prix
    function calculatePrice(distanceKm, seats, vehicleType = 'voiture') {
        if (!distanceKm || distanceKm <= 0) return 0;

        const pricePerKm = pricingConfig[vehicleType]?.pricePerKm || pricingConfig.default.pricePerKm;
        let basePrice = distanceKm * pricePerKm;

        // Ajustement selon le nombre de sièges
        if (vehicleType === 'tricycle') {
            // Tricycle: généralement 3 places max
            if (seats === 3) basePrice = basePrice * 0.9; // -10% pour 3 places
            if (seats === 2) basePrice = basePrice * 0.95; // -5% pour 2 places
            if (seats === 1) basePrice = basePrice * 1; // Prix normal pour 1 place
        } else {
            // Voiture: ajustements standards
            if (seats >= 4) basePrice = basePrice * 0.95;    // -5% pour 4+ sièges
            if (seats <= 2) basePrice = basePrice * 1.05;     // +5% pour 1-2 sièges
        }

        const finalPrice = Math.ceil(basePrice / 50) * 50; // Arrondi à 50 FCFA supérieur
        return finalPrice;
    }

    // Fonction pour obtenir le nom du véhicule affiché
    function getVehicleDisplayName() {
        const vehicleNameElement = document.querySelector('.font-black.text-slate-900.dark\\:text-white');
        if (vehicleNameElement) {
            return vehicleNameElement.textContent.trim();
        }
        return 'Véhicule';
    }

    // Fonction pour mettre à jour l'affichage du prix
    function updatePriceDisplay() {
        const distanceElement = document.getElementById('route-distance');
        const seatsElement = document.getElementById('seats_total');
        const priceInput = document.querySelector('input[name="price_per_seat"]');

        if (!distanceElement || !seatsElement || !priceInput) return;

        const distanceText = distanceElement.textContent;
        const distanceMatch = distanceText.match(/[\d\.]+/);

        if (distanceMatch && distanceMatch[0] !== '—') {
            const distanceKm = parseFloat(distanceMatch[0]);
            const seats = parseInt(seatsElement.value) || 1;
            const vehicleType = getVehicleType();

            const calculatedPrice = calculatePrice(distanceKm, seats, vehicleType);

            if (calculatedPrice > 0) {
                priceInput.value = calculatedPrice;

                // Mettre à jour le prix total
                updateTotalPrice(calculatedPrice, seats);

                // Afficher les informations de calcul
                showPriceInfo(distanceKm, calculatedPrice, seats, vehicleType);
            }
        } else {
            priceInput.value = '';
            hidePriceInfo();
        }
    }

    // Fonction pour afficher les informations de calcul
    function showPriceInfo(distance, price, seats, vehicleType) {
        let infoDiv = document.getElementById('price-info');
        if (!infoDiv) {
            infoDiv = document.createElement('div');
            infoDiv.id = 'price-info';
            infoDiv.className = 'text-xs text-slate-500 dark:text-slate-400 mt-2 p-2 rounded-lg bg-slate-50 dark:bg-white/5';
            const priceField = document.querySelector('input[name="price_per_seat"]').parentElement;
            priceField.appendChild(infoDiv);
        }

        const pricePerKm = pricingConfig[vehicleType]?.pricePerKm || pricingConfig.default.pricePerKm;
        const vehicleDisplay = vehicleType === 'tricycle' ? 'Tricycle' : 'Voiture';

        let multiplierText = '';
        if (vehicleType === 'tricycle') {
            if (seats === 3) multiplierText = ' (-10% plein)';
            if (seats === 2) multiplierText = ' (-5% duo)';
            if (seats === 1) multiplierText = ' (tarif normal)';
        } else {
            if (seats >= 4) multiplierText = ' (-5% groupe)';
            if (seats <= 2) multiplierText = ' (+5% individuel)';
        }

        infoDiv.innerHTML = `
            <div class="flex items-start gap-1.5">
                <span class="material-symbols-outlined text-primary text-sm flex-shrink-0">calculate</span>
                <div class="flex-1">
                    <span class="font-medium block mb-0.5">${vehicleDisplay} · ${pricePerKm} FCFA/km</span>
                    <span>${distance} km × ${pricePerKm} FCFA/km${multiplierText} = ${price} FCFA/place</span>
                </div>
            </div>
        `;
    }

    function hidePriceInfo() {
        const infoDiv = document.getElementById('price-info');
        if (infoDiv) infoDiv.remove();
    }

    // Fonction pour mettre à jour le prix total
    function updateTotalPrice(pricePerSeat, seats) {
        let totalElement = document.getElementById('total-price-amount');
        if (totalElement) {
            const total = pricePerSeat * seats;
            totalElement.textContent = total.toLocaleString() + ' FCFA';
        }
    }

    // Ajouter l'affichage du prix total
    function addTotalPriceDisplay() {
        const priceSection = document.querySelector('.grid-cols-2.gap-4');
        if (priceSection && !document.getElementById('total-price-display')) {
            const totalDiv = document.createElement('div');
            totalDiv.id = 'total-price-display';
            totalDiv.className = 'col-span-2 mt-3 pt-3 border-t border-slate-200 dark:border-white/10';
            totalDiv.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-sm">payments</span>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Total du trajet</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black text-primary" id="total-price-amount">0 FCFA</span>
                        <p class="text-[11px] text-slate-400">pour ${document.getElementById('seats_total')?.value || 0} place(s)</p>
                    </div>
                </div>
            `;
            priceSection.appendChild(totalDiv);
        }
    }

    // Observer les changements pour recalculer automatiquement
    function setupObservers() {
        // Observer la distance
        const distanceElement = document.getElementById('route-distance');
        if (distanceElement) {
            const observer = new MutationObserver(() => {
                updatePriceDisplay();
            });
            observer.observe(distanceElement, {
                childList: true,
                characterData: true,
                subtree: true
            });
        }

        // Observer les changements de sièges
        const seatsElement = document.getElementById('seats_total');
        if (seatsElement) {
            seatsElement.addEventListener('change', () => {
                setTimeout(updatePriceDisplay, 100);
                // Mettre à jour le texte du total
                setTimeout(() => {
                    const totalText = document.querySelector('#total-price-display p');
                    if (totalText) {
                        totalText.textContent = `pour ${seatsElement.value} place(s)`;
                    }
                }, 150);
            });
        }

        // Observer les boutons +/- des sièges
        const seatButtons = document.querySelectorAll('.seat-btn');
        seatButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                setTimeout(updatePriceDisplay, 150);
                setTimeout(() => {
                    const seatsElement = document.getElementById('seats_total');
                    const totalText = document.querySelector('#total-price-display p');
                    if (totalText && seatsElement) {
                        totalText.textContent = `pour ${seatsElement.value} place(s)`;
                    }
                }, 200);
            });
        });
    }

    // Rendre le champ prix en lecture seule
    function makePriceFieldReadonly() {
        const priceInput = document.querySelector('input[name="price_per_seat"]');
        if (priceInput) {
            priceInput.readOnly = true;
            priceInput.classList.add('bg-slate-100', 'dark:bg-white/10', 'cursor-not-allowed');
            priceInput.style.opacity = '0.9';
            priceInput.placeholder = 'Calcul automatique';

            // Ajouter une icône de cadenas
            const container = priceInput.parentElement;
            if (!container.querySelector('.lock-icon')) {
                const lockIcon = document.createElement('span');
                lockIcon.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 lock-icon';
                lockIcon.innerHTML = '<span class="material-symbols-outlined text-sm">lock</span>';
                lockIcon.title = 'Prix calculé automatiquement selon la distance';
                container.style.position = 'relative';
                container.appendChild(lockIcon);
                priceInput.style.paddingRight = '35px';
            }
        }
    }

    // Initialiser tout
    function initDynamicPricing() {
        makePriceFieldReadonly();
        addTotalPriceDisplay();

        setTimeout(() => {
            updatePriceDisplay();
            setupObservers();
        }, 500);

        // Vérifier périodiquement jusqu'à ce que la distance soit chargée
        const checkInterval = setInterval(() => {
            const distanceElement = document.getElementById('route-distance');
            if (distanceElement && distanceElement.textContent !== '—') {
                updatePriceDisplay();
                clearInterval(checkInterval);
            }
        }, 500);
    }

    // Démarrer quand la page est chargée
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDynamicPricing);
    } else {
        initDynamicPricing();
    }
})();

    // ── Validation submit ────────────────────────────────────────
    document.getElementById('trip-form').addEventListener('submit', e => {
        const depCity = document.getElementById('departure_city').value;
        const arrCity = document.getElementById('arrival_city').value;
        if (!depCity || !arrCity) {
            e.preventDefault();
            alert('Veuillez sélectionner une ville de départ et d\'arrivée dans la liste.');
        }
    });

    // ── Setup autocompletions ────────────────────────────────────
    setupAutocomplete('departure_input', 'departure-list', 'departure-spinner', 'departure_city', 'departure_lat', 'departure_lng', 'depart');
    setupAutocomplete('arrival_input',   'arrival-list',   'arrival-spinner',   'arrival_city',   'arrival_lat',   'arrival_lng',   'arrivee');

    // ── Restaurer old() si validation échouée ────────────────────
    @if(old('departure_city') && old('departure_lat'))
        document.getElementById('departure_input').value = '{{ old('departure_city') }}';
        coords.depart = [{{ old('departure_lat') }}, {{ old('departure_lng') }}];
        markerDepart = L.marker(coords.depart, { icon: makeIcon('depart') }).addTo(map);
    @endif
    @if(old('arrival_city') && old('arrival_lat'))
        document.getElementById('arrival_input').value = '{{ old('arrival_city') }}';
        coords.arrivee = [{{ old('arrival_lat') }}, {{ old('arrival_lng') }}];
        markerArrivee = L.marker(coords.arrivee, { icon: makeIcon('arrivee') }).addTo(map);
    @endif
    @if(old('departure_lat') && old('arrival_lat'))
        drawRoute([{{ old('departure_lat') }}, {{ old('departure_lng') }}], [{{ old('arrival_lat') }}, {{ old('arrival_lng') }}]);
    @endif

})();
</script>
@endpush

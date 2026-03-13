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

    {{-- ── Véhicule associé ── --}}
    <div class="flex items-center gap-4 p-4 rounded-2xl
                bg-primary/5 border border-primary/20
                dark:bg-primary/10 dark:border-primary/20">
        <div class="w-11 h-11 rounded-xl bg-primary/15 border border-primary/20
                    flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary text-2xl">{{ $vehicle->type_icon }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-black text-primary uppercase tracking-wider">Véhicule enregistré</p>
            <p class="font-black text-slate-900 dark:text-white text-sm mt-0.5">
                {{ $vehicle->full_name }}
                <span class="font-semibold text-slate-400 ml-1">· {{ $vehicle->color }}</span>
            </p>
        </div>
        <span class="flex-shrink-0 text-xs font-black text-slate-600 dark:text-slate-300
                     bg-slate-200 dark:bg-white/10 px-2.5 py-1 rounded-lg tracking-widest">
            {{ strtoupper($vehicle->plate) }}
        </span>
        <a href="{{ route('profile.edit') }}"
           class="flex-shrink-0 text-xs font-bold text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-base">edit</span>
        </a>
    </div>

    <form method="POST" action="" class="space-y-4" id="trip-form">
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
                               placeholder="Ville de départ *" autocomplete="off"
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
                               placeholder="Ville d'arrivée *" autocomplete="off"
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
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="departure_address" value="{{ old('departure_address') }}"
                       placeholder="Point de départ précis (opt.)"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-medium placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                <input type="text" name="arrival_address" value="{{ old('arrival_address') }}"
                       placeholder="Point d'arrivée précis (opt.)"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-medium placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
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
                        <input type="number" name="price_per_seat" value="{{ old('price_per_seat') }}"
                               placeholder="Ex: 1500" min="0" step="50" required
                               class="w-full pl-3 pr-16 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                      text-sm font-semibold placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-black
                                     text-slate-500 dark:text-slate-400 bg-slate-200 dark:bg-white/10 px-1.5 py-0.5 rounded-lg">FCFA</span>
                    </div>
                    @error('price_per_seat') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ── OPTIONS ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-3">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Options du trajet</h2>
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
            </div>

            {{-- Description --}}
            <div>
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                    Description <span class="font-normal">(optionnel)</span>
                </label>
                <textarea name="description" rows="2"
                          placeholder="Ex: Je passe par la route Nationale 1. Ponctuel et non-fumeur."
                          class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                 bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                 text-sm font-medium placeholder-slate-400
                                 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                 transition-all resize-none">{{ old('description') }}</textarea>
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
(function () {
    // ── Carte ────────────────────────────────────────────────────
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

    // ── Autocomplétion ───────────────────────────────────────────
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

    // ── Compteur de places ───────────────────────────────────────
    window.changeSeat = function(delta) {
        const input   = document.getElementById('seats_total');
        const display = document.getElementById('seats-display');
        let val = parseInt(input.value) + delta;
        val = Math.min(7, Math.max(1, val));
        input.value = val;
        display.textContent = val;
        display.nextElementSibling.textContent = 'place' + (val > 1 ? 's' : '');
    };

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

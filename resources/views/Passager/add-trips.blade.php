@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"/>
<style>
    #trip-map { height: 280px; border-radius: 16px; overflow: hidden; z-index: 0; }

    /* Autocomplétion dropdown */
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
        color: #1e293b; border-bottom: 1px solid #f1f5f9;
        transition: background .15s;
    }
    .dark .autocomplete-item { color: #e2e8f0; border-color: rgba(255,255,255,.06); }
    .autocomplete-item:hover { background: #f8fafc; }
    .dark .autocomplete-item:hover { background: rgba(255,255,255,.07); }
    .autocomplete-item:last-child { border-bottom: none; }

    /* Loader spinner */
    .field-spinner {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; border-radius: 50%;
        border: 2px solid #e2e8f0; border-top-color: #13ec49;
        animation: spin .7s linear infinite; display: none;
    }
    @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

    /* Leaflet routing panel — masqué */
    .leaflet-routing-container { display: none !important; }

    /* Marqueurs custom */
    .map-marker-depart, .map-marker-arrivee {
        width: 28px; height: 28px; border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg); border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0,0,0,.25);
    }
    .map-marker-depart  { background: #13ec49; }
    .map-marker-arrivee { background: #f87171; }
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
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Demander un trajet</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Les conducteurs aux alentours verront votre demande</p>
        </div>
    </div>

    <form method="POST" action="{{ route('passenger.storetrips') }}" class="space-y-4">
        @csrf

        {{-- ── ITINÉRAIRE + CARTE ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-4">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Itinéraire</h2>

            {{-- Champs départ / arrivée --}}
            <div class="flex gap-3">
                <div class="flex flex-col items-center pt-3.5 gap-1 flex-shrink-0">
                    <span class="w-3 h-3 rounded-full bg-primary border-2 border-primary/30"></span>
                    <span class="w-0.5 flex-1 bg-slate-200 dark:bg-white/10 min-h-8"></span>
                    <span class="w-3 h-3 rounded-full bg-red-400 border-2 border-red-200"></span>
                </div>
                <div class="flex-1 space-y-2">

                    {{-- Départ --}}
                    <div class="relative" id="departure-wrapper">
                        <input type="text" id="departure_input"
                               placeholder="Point de départ *"
                               autocomplete="off"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                      text-sm font-semibold placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                        <div class="field-spinner" id="departure-spinner"></div>
                        <div class="autocomplete-list" id="departure-list" style="display:none"></div>
                        {{-- Champs cachés pour le formulaire --}}
                        <input type="hidden" name="departure_city" id="departure_city" value="{{ old('departure_city') }}" required/>
                        <input type="hidden" name="departure_lat"  id="departure_lat"/>
                        <input type="hidden" name="departure_lng"  id="departure_lng"/>
                    </div>
                    @error('departure_city') <p class="text-xs text-red-500 font-semibold -mt-1">{{ $message }}</p> @enderror

                    {{-- Arrivée --}}
                    <div class="relative" id="arrival-wrapper">
                        <input type="text" id="arrival_input"
                               placeholder="Point d'arrivée *"
                               autocomplete="off"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                      text-sm font-semibold placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                        <div class="field-spinner" id="arrival-spinner"></div>
                        <div class="autocomplete-list" id="arrival-list" style="display:none"></div>
                        <input type="hidden" name="arrival_city" id="arrival_city" value="{{ old('arrival_city') }}" required/>
                        <input type="hidden" name="arrival_lat"  id="arrival_lat"/>
                        <input type="hidden" name="arrival_lng"  id="arrival_lng"/>
                    </div>
                    @error('arrival_city') <p class="text-xs text-red-500 font-semibold -mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Carte Leaflet --}}
            <div class="relative">
                <div id="trip-map"></div>
                {{-- Infos de distance/durée estimée --}}
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
            <div class="relative">
                <input type="text" name="departure_address" value="{{ old('departure_address') }}"
                       placeholder="Point de rendez-vous (opt.)"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-medium placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                {{-- <input type="text" name="arrival_address" value="{{ old('arrival_address') }}"
                       placeholder="Point arrivée (opt.)"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-medium placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/> --}}
            </div>
        </div>

        {{-- ── DATE, HEURE & FLEXIBILITÉ ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-4">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Quand ?</h2>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Date *</label>
                    <input type="date" name="requested_date" value="{{ old('requested_date') }}"
                           min="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" required/>
                    @error('requested_date') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Heure *</label>
                    <input type="time" name="requested_time" value="{{ old('requested_time') }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" required/>
                    @error('requested_time') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Flexibilité horaire</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">schedule</span>
                    <select name="flexibility"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                   bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                   text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                   transition-all appearance-none cursor-pointer">
                        <option value="0"   {{ old('flexibility','30') == '0'   ? 'selected' : '' }}>Heure exacte</option>
                        <option value="30"  {{ old('flexibility','30') == '30'  ? 'selected' : '' }}>± 30 minutes</option>
                        <option value="60"  {{ old('flexibility','30') == '60'  ? 'selected' : '' }}>± 1 heure</option>
                        <option value="120" {{ old('flexibility','30') == '120' ? 'selected' : '' }}>± 2 heures</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ── PASSAGERS ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-4">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Passagers</h2>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Nombre de passagers *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">group</span>
                        <select name="passengers" id="passengers_select"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                       bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                       text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                       transition-all appearance-none cursor-pointer" required>
                            @foreach([1,2,3,4] as $n)
                                <option value="{{ $n }}" {{ old('passengers',1) == $n ? 'selected' : '' }}>
                                    {{ $n }} passager{{ $n > 1 ? 's' : '' }}
                                </option>
                            @endforeach

                        </select>
                        {{-- <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl pointer-events-none">expand_more</span> --}}
                    </div>
                </div>

                {{-- Prix estimé automatiquement --}}
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Prix estimé / pers.</label>
                    <div id="price-display"
                         class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400
                                text-sm font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-400" style="font-size:18px">payments</span>
                        <span id="price-value">— FCFA</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Calculé selon la distance</p>
                    <input type="hidden" name="budget_max" id="budget_max_hidden" value="{{ old('budget_max') }}"/>
                </div>
            </div>
        </div>

        {{-- ── MESSAGE ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-3">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Message <span class="font-normal normal-case tracking-normal text-slate-400 text-xs">(optionnel)</span></h2>
            <textarea name="message" rows="2"
                      placeholder="Ex: Je serai devant la pharmacie au carrefour..."
                      class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                             bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                             text-sm font-medium placeholder-slate-400
                             focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                             transition-all resize-none">{{ old('message') }}</textarea>
        </div>

        {{-- ── DURÉE DE VALIDITÉ ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-3">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">Visibilité de la demande</h2>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-orange-400 text-xl">timer</span>
                <select name="expires_in_hours"
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                               bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                               text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                               transition-all appearance-none cursor-pointer">
                    <option value="1"  {{ old('expires_in_hours','3') == '1'  ? 'selected' : '' }}>Visible pendant 1 heure</option>
                    <option value="3"  {{ old('expires_in_hours','3') == '3'  ? 'selected' : '' }}>Visible pendant 3 heures</option>
                    <option value="6"  {{ old('expires_in_hours','3') == '6'  ? 'selected' : '' }}>Visible pendant 6 heures</option>
                    <option value="24" {{ old('expires_in_hours','3') == '24' ? 'selected' : '' }}>Visible pendant 24 heures</option>
                </select>
                {{-- <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl pointer-events-none">expand_more</span> --}}
            </div>
            <p class="text-xs text-slate-400">Votre demande disparaîtra automatiquement après ce délai.</p>
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
                <span class="material-symbols-outlined text-xl">send</span>
                Envoyer ma demande
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

    // ── Init carte centrée sur le Bénin ──────────────────────────────────
    const map = L.map('trip-map', { zoomControl: true, attributionControl: false })
        .setView([9.3077, 2.3158], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // ── Marqueurs custom ──────────────────────────────────────────────────
    function makeIcon(color) {
        return L.divIcon({
            html: `<div class="map-marker-${color}"></div>`,
            className: '', iconSize: [28, 28], iconAnchor: [14, 28]
        });
    }

    let markerDepart  = null;
    let markerArrivee = null;
    let routeControl  = null;
    let coords        = { depart: null, arrivee: null };

    // ── Autocomplétion Nominatim ──────────────────────────────────────────
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

        // Fermer en cliquant ailleurs
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
                <span>${shortName}</span>
            `;
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

    // ── Mise à jour carte ─────────────────────────────────────────────────
    function updateMap(type, latlng) {
        if (type === 'depart') {
            if (markerDepart) map.removeLayer(markerDepart);
            markerDepart = L.marker(latlng, { icon: makeIcon('depart') }).addTo(map);
        } else {
            if (markerArrivee) map.removeLayer(markerArrivee);
            markerArrivee = L.marker(latlng, { icon: makeIcon('arrivee') }).addTo(map);
        }

        // Tracer la route si les deux points sont définis
        if (coords.depart && coords.arrivee) {
            drawRoute(coords.depart, coords.arrivee);
        } else {
            map.setView(latlng, 11);
        }
    }

    function drawRoute(from, to) {
        // Supprimer l'ancienne route
        if (routeControl) map.removeControl(routeControl);

        routeControl = L.Routing.control({
            waypoints: [L.latLng(from), L.latLng(to)],
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'driving',
            }),
            lineOptions: {
                styles: [{ color: '#13ec49', weight: 5, opacity: 0.85 }],
                extendToWaypoints: false,
                missingRouteTolerance: 0,
            },
            createMarker: () => null,  // pas de marqueurs par défaut
            show: false,
            addWaypoints: false,
            fitSelectedRoutes: true,
        }).addTo(map);

        // Afficher distance + durée + prix estimé
        routeControl.on('routesfound', e => {
            const r    = e.routes[0].summary;
            const km   = r.totalDistance / 1000;
            const dist = km.toFixed(1) + ' km';
            const mins = Math.round(r.totalTime / 60);
            const dur  = mins >= 60
                ? Math.floor(mins / 60) + 'h' + (mins % 60 ? (mins % 60) + 'min' : '')
                : mins + ' min';

            document.getElementById('route-distance').textContent = dist;
            document.getElementById('route-duration').textContent = dur;
            const info = document.getElementById('route-info');
            info.classList.remove('opacity-0', 'pointer-events-none');
            info.classList.add('opacity-100');

            // Prix estimé : 100 FCFA/km, arrondi à la centaine
            const price = Math.max(200, Math.round((km * 100) / 100) * 100);
            document.getElementById('price-value').textContent =
                price.toLocaleString('fr-FR') + ' FCFA';
            document.getElementById('budget_max_hidden').value = price;
            updateTotalPrice(price);
        });
    }

    // ── Prix estimé : recalcul si nb passagers change ────────────────────
    let currentPricePerPerson = 0;

    function updateTotalPrice(pricePerPerson) {
        currentPricePerPerson = pricePerPerson;
        const nb = parseInt(document.getElementById('passengers_select').value) || 1;
        const total = pricePerPerson * nb;
        document.getElementById('price-value').textContent =
            pricePerPerson.toLocaleString('fr-FR') + ' FCFA / pers.'
            + (nb > 1 ? ' · Total ' + total.toLocaleString('fr-FR') + ' FCFA' : '');
    }

    document.getElementById('passengers_select').addEventListener('change', () => {
        if (currentPricePerPerson > 0) updateTotalPrice(currentPricePerPerson);
    });

    // ── Validation avant submit ───────────────────────────────────────────
    document.querySelector('form').addEventListener('submit', e => {
        const depCity = document.getElementById('departure_city').value;
        const arrCity = document.getElementById('arrival_city').value;
        if (!depCity || !arrCity) {
            e.preventDefault();
            alert('Veuillez sélectionner une ville de départ et d\'arrivée dans la liste.');
        }
    });

    // ── Lancer les autocompletions ────────────────────────────────────────
    setupAutocomplete(
        'departure_input', 'departure-list', 'departure-spinner',
        'departure_city', 'departure_lat', 'departure_lng', 'depart'
    );
    setupAutocomplete(
        'arrival_input', 'arrival-list', 'arrival-spinner',
        'arrival_city', 'arrival_lat', 'arrival_lng', 'arrivee'
    );

    // ── Restaurer depuis old() si validation a échoué ─────────────────────
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
        drawRoute(
            [{{ old('departure_lat') }}, {{ old('departure_lng') }}],
            [{{ old('arrival_lat') }}, {{ old('arrival_lng') }}]
        );
    @endif

})();
</script>
@endpush

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard') }}"
           class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                  flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Trajets disponibles</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Trouvez un trajet et réservez votre place</p>
        </div>
    </div>

    {{-- ── Barre de recherche ── --}}
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-primary" style="font-size:18px">trip_origin</span>
                <input id="search-departure" type="text" placeholder="Ville de départ..."
                       autocomplete="off"
                       class="w-full pl-9 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-semibold placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
            </div>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400" style="font-size:18px">location_on</span>
                <input id="search-arrival" type="text" placeholder="Ville d'arrivée..."
                       autocomplete="off"
                       class="w-full pl-9 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                              text-sm font-semibold placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
            </div>
        </div>
        <div class="mt-3 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <div id="search-spinner" class="hidden w-4 h-4 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                <p id="results-count" class="text-xs font-semibold text-slate-400"></p>
            </div>
            <a href="{{ route('passenger.showtrips') }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-xl
                      bg-primary hover:bg-primary/90 text-background-dark
                      font-black text-xs transition-all shadow-md shadow-primary/20 whitespace-nowrap">
                <span class="material-symbols-outlined" style="font-size:14px">add_circle</span>
                Demander un trajet
            </a>
        </div>
    </div>

    {{-- ── Liste des trajets ── --}}
    <div id="trips-container" class="space-y-3">
        <div id="skeleton" class="space-y-3">
            @for($i = 0; $i < 3; $i++)
            <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 animate-pulse">
                <div class="flex gap-3">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 h-8 rounded-xl bg-slate-200 dark:bg-white/10"></div>
                        <div class="w-px h-4 bg-slate-200 dark:bg-white/10"></div>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 dark:bg-white/10"></div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-slate-200 dark:bg-white/10 rounded-lg w-1/2"></div>
                        <div class="h-3 bg-slate-200 dark:bg-white/10 rounded-lg w-1/3"></div>
                        <div class="h-4 bg-slate-200 dark:bg-white/10 rounded-lg w-2/5 mt-3"></div>
                        <div class="h-3 bg-slate-200 dark:bg-white/10 rounded-lg w-1/4"></div>
                    </div>
                    <div class="w-20 h-9 bg-slate-200 dark:bg-white/10 rounded-xl"></div>
                </div>
            </div>
            @endfor
        </div>

        <div id="trips-list" class="space-y-3 hidden"></div>

        <div id="empty-state" class="hidden bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-3xl">search_off</span>
            </div>
            <p class="font-black text-slate-900 dark:text-white text-lg" id="empty-title">Aucun trajet trouvé</p>
            <p class="text-sm text-slate-400 mt-1" id="empty-subtitle">Essayez d'autres villes ou revenez plus tard.</p>
        </div>
    </div>

    {{-- ── Pagination ── --}}
    <div id="pagination" class="hidden flex items-center justify-center gap-2 pb-4"></div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL DE RÉSERVATION
══════════════════════════════════════════════ --}}
<div id="bookModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4
            opacity-0 pointer-events-none transition-opacity duration-200">
    <div id="bookModalOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div id="bookModalBox"
         class="relative w-full max-w-md bg-white dark:bg-card-dark rounded-2xl shadow-2xl
                border border-slate-100 dark:border-white/10
                translate-y-4 transition-transform duration-200
                max-h-[90dvh] overflow-y-auto">
        <div class="p-6 space-y-5">

            {{-- ── Header modal ── --}}
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <h3 class="font-black text-slate-900 dark:text-white text-lg" id="modal-route">—</h3>
                    {{-- Conducteur avec avatar --}}
                    <div class="flex items-center gap-2 mt-1.5">
                        <div id="modal-driver-avatar-wrap"
                             class="w-7 h-7 rounded-full bg-primary/10 border border-primary/20
                                    flex items-center justify-center shrink-0 overflow-hidden">
                            <span class="material-symbols-outlined text-primary" style="font-size:14px">person</span>
                        </div>
                        <p class="text-xs text-slate-400 font-semibold" id="modal-driver">—</p>
                    </div>
                </div>
                <button onclick="closeBookModal()"
                        class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/10 flex items-center justify-center
                               hover:bg-slate-200 dark:hover:bg-white/20 transition-colors shrink-0 ml-3">
                    <span class="material-symbols-outlined text-slate-500 dark:text-slate-400" style="font-size:18px">close</span>
                </button>
            </div>

            {{-- ── Infos trajet ── --}}
            <div class="grid grid-cols-3 gap-2">
                <div class="bg-slate-50 dark:bg-white/5 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-primary text-xl block mb-1">calendar_today</span>
                    <p class="text-xs font-black text-slate-900 dark:text-white" id="modal-date">—</p>
                    <p class="text-xs text-slate-400">Date</p>
                </div>
                <div class="bg-slate-50 dark:bg-white/5 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-primary text-xl block mb-1">schedule</span>
                    <p class="text-xs font-black text-slate-900 dark:text-white" id="modal-time">—</p>
                    <p class="text-xs text-slate-400">Heure</p>
                </div>
                <div class="bg-slate-50 dark:bg-white/5 rounded-xl p-3 text-center">
                    <span class="material-symbols-outlined text-primary text-xl block mb-1">payments</span>
                    <p class="text-xs font-black text-slate-900 dark:text-white" id="modal-price">—</p>
                    <p class="text-xs text-slate-400">/ place</p>
                </div>
            </div>

            {{-- ── Bloc véhicule ── --}}
            <div id="modal-vehicle-block"
                 class="rounded-xl border border-primary/15 bg-primary/5 overflow-hidden">
                <div id="modal-vehicle-photo-wrap" class="hidden relative">
                    <img id="modal-vehicle-photo" src="" alt="Photo du véhicule"
                         class="w-full h-36 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                <div class="flex items-center gap-3 p-3">
                    <span class="material-symbols-outlined text-primary text-2xl shrink-0"
                          id="modal-vehicle-icon">directions_car</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-900 dark:text-white truncate"
                           id="modal-vehicle-name">—</p>
                        <p class="text-xs text-slate-400 font-semibold"
                           id="modal-vehicle-plate">—</p>
                    </div>
                </div>
                <div id="modal-vehicle-expiry" class="hidden border-t border-primary/15">
                    <div class="grid grid-cols-2 divide-x divide-primary/15">
                        <div class="px-3 py-2.5 bg-white/50 dark:bg-white/[0.03]">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Assurance</p>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-primary shrink-0" style="font-size:13px">verified</span>
                                <p class="text-xs font-black text-slate-900 dark:text-white" id="modal-insurance-expiry">—</p>
                            </div>
                        </div>
                        <div class="px-3 py-2.5 bg-white/50 dark:bg-white/[0.03]">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Carte grise</p>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-primary shrink-0" style="font-size:13px">description</span>
                                <p class="text-xs font-black text-slate-900 dark:text-white" id="modal-registration-expiry">—</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Formulaire ── --}}
            <form id="book-form" method="POST" action="{{ route('passenger.storetrips') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="driver_trip_id"   id="modal-trip-id"/>
                <input type="hidden" name="departure_city"   id="modal-dep-city"/>
                <input type="hidden" name="arrival_city"     id="modal-arr-city"/>
                <input type="hidden" name="requested_date"   id="modal-req-date"/>
                <input type="hidden" name="requested_time"   id="modal-req-time"/>
                <input type="hidden" name="flexibility"      value="0"/>
                <input type="hidden" name="expires_in_hours" value="24"/>
                <input type="hidden" name="vehicle_type"     id="modal-vehicle-type" value="voiture"/>

                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 block">
                        Nombre de places *
                        <span class="text-slate-300 dark:text-slate-600 font-normal" id="modal-seats-label"></span>
                    </label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="changeSeat(-1)"
                                class="w-9 h-9 rounded-xl border-2 border-slate-200 dark:border-white/10
                                       flex items-center justify-center text-slate-500 dark:text-slate-400
                                       hover:border-primary hover:text-primary transition-all font-black text-lg">−</button>
                        <span id="seat-count"
                              class="flex-1 text-center font-black text-2xl text-slate-900 dark:text-white">1</span>
                        <input type="hidden" name="passengers" id="passengers-input" value="1"/>
                        <button type="button" onclick="changeSeat(1)"
                                class="w-9 h-9 rounded-xl border-2 border-slate-200 dark:border-white/10
                                       flex items-center justify-center text-slate-500 dark:text-slate-400
                                       hover:border-primary hover:text-primary transition-all font-black text-lg">+</button>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                        Message au conducteur (optionnel)
                    </label>
                    <textarea name="message" rows="2"
                              placeholder="Ex : Je serai devant la pharmacie..."
                              class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                     bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                     text-sm font-semibold placeholder-slate-400 resize-none
                                     focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"></textarea>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-primary/10 border border-primary/20">
                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Total estimé</span>
                    <span id="modal-total" class="font-black text-primary text-lg">— FCFA</span>
                </div>

                <button id="book-submit-btn" type="submit"
                        class="w-full flex items-center justify-center gap-2
                               bg-primary hover:bg-primary/90 text-background-dark
                               font-black py-3 rounded-xl transition-all shadow-lg shadow-primary/20 text-sm">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    Confirmer la réservation
                </button>
            </form>

            {{-- ── Succès ── --}}
            <div id="book-success" class="hidden flex-col items-center gap-5 py-4 text-center">
                <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500 text-4xl">check_circle</span>
                </div>
                <div>
                    <p class="font-black text-slate-900 dark:text-white text-lg">Réservation confirmée !</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Votre place a été réservée avec succès.</p>
                </div>
                <a id="book-chat-link" href="#"
                   class="w-full flex items-center justify-center gap-2
                          bg-primary hover:bg-primary/90 text-background-dark
                          font-black py-3 rounded-xl transition-all shadow-lg shadow-primary/20 text-sm">
                    <span class="material-symbols-outlined text-lg">pending</span>
                    Voir le statut de ma réservation
                </a>
                <button onclick="closeBookModal()"
                        class="w-full text-sm font-semibold text-slate-500 dark:text-slate-400 py-2
                               hover:text-slate-700 dark:hover:text-white transition-colors">
                    Fermer
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
const SEARCH_URL = "{{ route('passenger.trips.search') }}";
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let debounceTimer  = null;
let currentPage    = 1;
let lastDep        = '';
let lastArr        = '';
let modalMaxSeats  = 1;
let pricePerSeat   = 0;

// ══════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════
window.addEventListener('DOMContentLoaded', () => {
    fetchTrips();

    document.getElementById('search-departure').addEventListener('input', onSearchInput);
    document.getElementById('search-arrival').addEventListener('input', onSearchInput);

    document.getElementById('book-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('book-submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin inline-block"></span> Réservation…';

        try {
            const res  = await fetch(this.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: new FormData(this),
            });
            const data = await res.json();

            if (res.ok && data.success) {
                document.getElementById('book-form').classList.add('hidden');
                document.getElementById('book-chat-link').href = data.chat_url;
                const s = document.getElementById('book-success');
                s.classList.remove('hidden');
                s.classList.add('flex');
            } else {
                alert(data.message || 'Une erreur est survenue. Veuillez réessayer.');
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined text-lg">check_circle</span> Confirmer la réservation';
            }
        } catch {
            alert('Erreur réseau. Veuillez réessayer.');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-lg">check_circle</span> Confirmer la réservation';
        }
    });
});

function onSearchInput() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => { currentPage = 1; fetchTrips(); }, 350);
}

// ══════════════════════════════════════════════
//  FETCH TRAJETS
// ══════════════════════════════════════════════
async function fetchTrips(page = 1) {
    currentPage = page;
    const dep = document.getElementById('search-departure').value.trim();
    const arr = document.getElementById('search-arrival').value.trim();
    lastDep = dep; lastArr = arr;

    document.getElementById('search-spinner').classList.remove('hidden');
    document.getElementById('results-count').textContent = 'Recherche...';

    try {
        const params = new URLSearchParams({ page, departure: dep, arrival: arr });
        const res  = await fetch(`${SEARCH_URL}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
        });
        const text = await res.text();
        let data;
        try { data = JSON.parse(text); }
        catch {
            console.error('Réponse non-JSON :', text.substring(0, 500));
            document.getElementById('results-count').textContent = 'Erreur serveur.';
            document.getElementById('skeleton').classList.add('hidden');
            document.getElementById('trips-list').classList.remove('hidden');
            document.getElementById('empty-state').classList.remove('hidden');
            document.getElementById('empty-title').textContent = 'Erreur de chargement';
            document.getElementById('empty-subtitle').textContent = 'Vérifiez la console pour plus de détails.';
            return;
        }
        renderTrips(data.trips || [], data.pagination, data.total || 0);
    } catch(e) {
        console.error('Fetch échoué :', e);
        document.getElementById('results-count').textContent = 'Impossible de joindre le serveur.';
    } finally {
        document.getElementById('search-spinner').classList.add('hidden');
        document.getElementById('skeleton').classList.add('hidden');
        document.getElementById('trips-list').classList.remove('hidden');
    }
}

// ══════════════════════════════════════════════
//  RENDU DES TRAJETS
// ══════════════════════════════════════════════
function renderTrips(trips, pagination, total) {
    const list    = document.getElementById('trips-list');
    const empty   = document.getElementById('empty-state');
    const pagDiv  = document.getElementById('pagination');
    const countEl = document.getElementById('results-count');

    list.innerHTML = '';
    pagDiv.innerHTML = '';
    pagDiv.classList.add('hidden');

    if (!trips.length) {
        empty.classList.remove('hidden');
        countEl.textContent = '0 trajet trouvé';
        document.getElementById('empty-title').textContent =
            lastDep || lastArr ? 'Aucun trajet pour cette recherche' : 'Aucun trajet disponible';
        document.getElementById('empty-subtitle').textContent =
            lastDep || lastArr ? "Essayez d'autres villes." : "Les conducteurs n'ont pas encore publié de trajets.";
        return;
    }

    empty.classList.add('hidden');
    countEl.textContent = `${total} trajet${total > 1 ? 's' : ''} trouvé${total > 1 ? 's' : ''}`;

    trips.forEach(trip => {
        const isFull = trip.seats_available <= 0;
        const date   = new Date(trip.departure_date).toLocaleDateString('fr-FR', { weekday:'short', day:'numeric', month:'short' });
        const time   = trip.departure_time.slice(0, 5);
        const price  = parseInt(trip.price_per_seat).toLocaleString('fr-FR');

        // ── Avatar conducteur ──
        const driverAvatarHtml = trip.driver_avatar
            ? `<img src="${trip.driver_avatar}" alt="${trip.driver_name}"
                    class="w-7 h-7 rounded-full object-cover border-2 border-primary/30 shrink-0">`
            : `<div class="w-7 h-7 rounded-full bg-primary/10 border-2 border-primary/20
                          flex items-center justify-center shrink-0">
                   <span class="material-symbols-outlined text-primary" style="font-size:14px">person</span>
               </div>`;

        // ── Options ──
        const opts = [];
        if (trip.luggage_allowed) opts.push({ icon:'luggage',    label:'Bagages' });
        if (trip.pets_allowed)    opts.push({ icon:'pets',       label:'Animaux' });
        if (trip.silent_ride)     opts.push({ icon:'volume_off', label:'Silence' });
        if (trip.female_only)     opts.push({ icon:'female',     label:'Femmes' });
        const optsHtml = opts.map(o =>
            `<span class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-white/5
                         text-xs font-semibold text-slate-500 dark:text-slate-400">
                <span class="material-symbols-outlined" style="font-size:13px">${o.icon}</span>${o.label}
             </span>`
        ).join('');

        // ── Véhicule ──
        let vehicleHtml = '';
        if (trip.vehicle) {
            const photoHtml = trip.vehicle.vehicle_photo_url
                ? `<img src="${trip.vehicle.vehicle_photo_url}" alt="${trip.vehicle.brand}"
                        class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-white/10 shrink-0">`
                : `<div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/10
                              border border-slate-200 dark:border-white/10
                              flex items-center justify-center shrink-0">
                       <span class="material-symbols-outlined text-slate-400" style="font-size:16px">${trip.vehicle.type_icon}</span>
                   </div>`;

            vehicleHtml = `
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100 dark:border-white/5">
                ${photoHtml}
                <div class="flex-1 min-w-0">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block truncate">
                        ${trip.vehicle.brand} ${trip.vehicle.model}
                    </span>
                    <span class="text-[11px] text-slate-400">${trip.vehicle.color}</span>
                </div>
                <span class="text-xs font-black text-slate-600 dark:text-slate-300
                             bg-slate-100 dark:bg-white/10 px-2 py-0.5 rounded-lg tracking-wider shrink-0">
                    ${trip.vehicle.plate.toUpperCase()}
                </span>
            </div>`;
        }

        const card = `
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10
                    shadow-sm overflow-hidden ${isFull ? 'opacity-60' : ''}
                    hover:shadow-md hover:border-primary/20 transition-all duration-200">
            <div class="h-1 w-full bg-primary/40"></div>
            <div class="p-5">
                <div class="flex items-start gap-3">
                    <div class="flex flex-col items-center gap-1 flex-shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary" style="font-size:16px">trip_origin</span>
                        </div>
                        <div class="w-px h-4 bg-slate-200 dark:bg-white/10"></div>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-400" style="font-size:16px">location_on</span>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-black text-slate-900 dark:text-white text-base truncate">${trip.departure_city}</p>
                        <p class="text-xs text-slate-400 font-medium my-0.5">→</p>
                        <p class="font-black text-slate-700 dark:text-slate-300 text-base truncate">${trip.arrival_city}</p>

                        <div class="flex flex-wrap gap-3 mt-3">
                            <span class="flex items-center gap-1 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                <span class="material-symbols-outlined" style="font-size:14px">calendar_today</span>${date}
                            </span>
                            <span class="flex items-center gap-1 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                <span class="material-symbols-outlined" style="font-size:14px">schedule</span>${time}
                            </span>
                            <span class="flex items-center gap-1 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                <span class="material-symbols-outlined" style="font-size:14px">airline_seat_recline_normal</span>
                                ${trip.seats_available} place${trip.seats_available > 1 ? 's' : ''}
                            </span>
                        </div>

                        ${opts.length ? `<div class="flex flex-wrap gap-1.5 mt-2">${optsHtml}</div>` : ''}

                        <div class="flex items-center gap-2 mt-3">
                            ${driverAvatarHtml}
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">${trip.driver_name}</span>
                        </div>

                        ${vehicleHtml}
                    </div>

                    <div class="flex flex-col items-end gap-3 flex-shrink-0 ml-2">
                        <div class="text-right">
                            <p class="font-black text-primary text-lg leading-none">${price}</p>
                            <p class="text-xs text-slate-400 font-semibold">FCFA/place</p>
                        </div>
                        ${isFull
                            ? `<span class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-white/5
                                          text-xs font-black text-slate-400 border border-slate-200 dark:border-white/10">
                                Complet
                               </span>`
                            : `<button onclick='openBookModal(${JSON.stringify(trip)})'
                                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl
                                              bg-primary hover:bg-primary/90 text-background-dark
                                              font-black text-xs transition-all shadow-md shadow-primary/20">
                                   <span class="material-symbols-outlined" style="font-size:15px">check_circle</span>
                                   Réserver
                               </button>`
                        }
                    </div>
                </div>
            </div>
        </div>`;

        list.insertAdjacentHTML('beforeend', card);
    });

    renderPagination(pagination);
}

// ══════════════════════════════════════════════
//  PAGINATION
// ══════════════════════════════════════════════
function renderPagination(p) {
    if (!p || p.last_page <= 1) return;
    const pagDiv = document.getElementById('pagination');
    pagDiv.classList.remove('hidden');
    pagDiv.innerHTML = '';

    const btn = (label, page, disabled, active) => {
        if (disabled) return `<span class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark
            border border-slate-100 dark:border-primary/10 flex items-center justify-center
            opacity-30 cursor-not-allowed text-sm">${label}</span>`;
        if (active)   return `<span class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center
            text-background-dark font-black text-sm shadow-md shadow-primary/20">${label}</span>`;
        return `<button onclick="fetchTrips(${page})"
                        class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-100 dark:border-primary/10
                               flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-sm
                               hover:border-primary/30 hover:bg-primary/5 transition-all shadow-sm">${label}</button>`;
    };

    pagDiv.innerHTML += btn('‹', p.current_page - 1, p.current_page === 1, false);
    const start = Math.max(1, p.current_page - 2);
    const end   = Math.min(p.last_page, p.current_page + 2);
    for (let i = start; i <= end; i++) pagDiv.innerHTML += btn(i, i, false, i === p.current_page);
    pagDiv.innerHTML += btn('›', p.current_page + 1, p.current_page === p.last_page, false);
}

// ══════════════════════════════════════════════
//  MODAL RÉSERVATION
// ══════════════════════════════════════════════
function openBookModal(trip) {
    modalMaxSeats = trip.seats_available;
    pricePerSeat  = trip.price_per_seat;

    const date = new Date(trip.departure_date).toLocaleDateString('fr-FR', { weekday:'short', day:'numeric', month:'short' });
    const time = trip.departure_time.slice(0, 5);

    // Définir le type de véhicule
    const vehicleType = trip.vehicle ? (trip.vehicle.type_icon === 'tricycle' ? 'tricycle' : 'voiture') : 'voiture';
    document.getElementById('modal-vehicle-type').value = vehicleType;

    document.getElementById('modal-route').textContent       = `${trip.departure_city} → ${trip.arrival_city}`;
    document.getElementById('modal-driver').textContent      = trip.driver_name;
    document.getElementById('modal-seats-label').textContent = `(${trip.seats_available} dispo.)`;
    document.getElementById('modal-date').textContent        = date;
    document.getElementById('modal-time').textContent        = time;
    document.getElementById('modal-price').textContent       = parseInt(trip.price_per_seat).toLocaleString('fr-FR') + ' FCFA';

    // ── Avatar conducteur dans le modal ──
    const avatarWrap = document.getElementById('modal-driver-avatar-wrap');
    avatarWrap.innerHTML = trip.driver_avatar
        ? `<img src="${trip.driver_avatar}" alt="${trip.driver_name}"
                class="w-full h-full object-cover rounded-full">`
        : `<span class="material-symbols-outlined text-primary" style="font-size:14px">person</span>`;

    // ── Véhicule ──
    const vBlock = document.getElementById('modal-vehicle-block');
    if (trip.vehicle) {
        document.getElementById('modal-vehicle-name').textContent  = `${trip.vehicle.brand} ${trip.vehicle.model} · ${trip.vehicle.color}`;
        document.getElementById('modal-vehicle-plate').textContent = trip.vehicle.plate.toUpperCase();
        document.getElementById('modal-vehicle-icon').textContent  = trip.vehicle.type_icon;
        vBlock.classList.remove('hidden');

        const photoWrap = document.getElementById('modal-vehicle-photo-wrap');
        const photoImg  = document.getElementById('modal-vehicle-photo');
        if (trip.vehicle.vehicle_photo_url) {
            photoImg.src = trip.vehicle.vehicle_photo_url;
            photoWrap.classList.remove('hidden');
        } else {
            photoWrap.classList.add('hidden');
        }

        const expiryBlock = document.getElementById('modal-vehicle-expiry');
        const insExp = trip.vehicle.insurance_expires_at;
        const regExp = trip.vehicle.registration_expires_at;

        if (insExp || regExp) {
            expiryBlock.classList.remove('hidden');
            const fmtDate = (str) => str
                ? new Date(str).toLocaleDateString('fr-FR', { day:'2-digit', month:'short', year:'numeric' })
                : '—';

            const insEl = document.getElementById('modal-insurance-expiry');
            const regEl = document.getElementById('modal-registration-expiry');
            insEl.textContent = fmtDate(insExp);
            regEl.textContent = fmtDate(regExp);

            [[insEl, insExp], [regEl, regExp]].forEach(([el, dateStr]) => {
                if (!dateStr) return;
                el.classList.remove('text-orange-500', 'text-slate-900', 'dark:text-white');
                const daysLeft = Math.ceil((new Date(dateStr) - new Date()) / 86400000);
                if (daysLeft <= 30) {
                    el.classList.add('text-orange-500');
                    el.textContent += ' ⚠';
                } else {
                    el.classList.add('text-slate-900', 'dark:text-white');
                }
            });
        } else {
            expiryBlock.classList.add('hidden');
        }
    } else {
        vBlock.classList.add('hidden');
    }

    // Champs cachés
    document.getElementById('modal-trip-id').value  = trip.id;
    document.getElementById('modal-dep-city').value = trip.departure_city;
    document.getElementById('modal-arr-city').value = trip.arrival_city;
    document.getElementById('modal-req-date').value = trip.departure_date;
    document.getElementById('modal-req-time').value = time;

    // Reset compteur
    document.getElementById('seat-count').textContent = '1';
    document.getElementById('passengers-input').value = '1';
    updateTotal();

    // Ouvrir
    const modal    = document.getElementById('bookModal');
    const modalBox = document.getElementById('bookModalBox');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');
    modalBox.classList.remove('translate-y-4');
}

function closeBookModal() {
    const modal    = document.getElementById('bookModal');
    const modalBox = document.getElementById('bookModalBox');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    modalBox.classList.add('translate-y-4');
    setTimeout(() => modal.classList.add('pointer-events-none'), 200);

    const form = document.getElementById('book-form');
    const succ = document.getElementById('book-success');
    const btn  = document.getElementById('book-submit-btn');
    form.classList.remove('hidden');
    succ.classList.add('hidden');
    succ.classList.remove('flex');
    btn.disabled = false;
    btn.innerHTML = '<span class="material-symbols-outlined text-lg">check_circle</span> Confirmer la réservation';
}

document.getElementById('bookModalOverlay').addEventListener('click', closeBookModal);

// ══════════════════════════════════════════════
//  PLACES + TOTAL
// ══════════════════════════════════════════════
function changeSeat(delta) {
    const el  = document.getElementById('seat-count');
    const inp = document.getElementById('passengers-input');
    let val   = Math.max(1, Math.min(modalMaxSeats, parseInt(el.textContent) + delta));
    el.textContent = val;
    inp.value      = val;
    updateTotal();
}

function updateTotal() {
    const seats = parseInt(document.getElementById('seat-count').textContent);
    document.getElementById('modal-total').textContent =
        (seats * pricePerSeat).toLocaleString('fr-FR') + ' FCFA';
}
</script>
@endpush
@endsection

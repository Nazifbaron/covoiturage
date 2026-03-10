@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard') }}"
           class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                  flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black">Publier un trajet</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Renseignez les informations de votre trajet</p>
        </div>
    </div>

    <form method="POST" action="" class="space-y-5">
        @csrf

        {{-- ── SECTION 1 : ITINÉRAIRE ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-blue-50 to-white dark:from-blue-500/10 dark:to-transparent border-b border-blue-100 dark:border-primary/10">
                <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500 text-lg">route</span>
                </div>
                <h2 class="font-black text-base">Itinéraire</h2>
            </div>
            <div class="p-6 space-y-5">

                {{-- Départ / Arrivée --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Ville de départ <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-primary text-xl">trip_origin</span>
                            <input type="text" name="departure_city"
                                   value="{{ old('departure_city') }}"
                                   placeholder="Ex: Cotonou"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                          bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                          dark:text-white placeholder-slate-400 transition-all"
                                   required/>
                        </div>
                        @error('departure_city') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Ville d'arrivée <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-red-400 text-xl">location_on</span>
                            <input type="text" name="arrival_city"
                                   value="{{ old('arrival_city') }}"
                                   placeholder="Ex: Porto-Novo"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                          bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                          dark:text-white placeholder-slate-400 transition-all"
                                   required/>
                        </div>
                        @error('arrival_city') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Adresse précise (optionnel) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Point de départ précis
                            <span class="normal-case font-medium text-slate-400 ml-1">(optionnel)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">place</span>
                            <input type="text" name="departure_address"
                                   value="{{ old('departure_address') }}"
                                   placeholder="Ex: Carrefour Cadjèhoun"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                          bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                          dark:text-white placeholder-slate-400 transition-all"/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Point d'arrivée précis
                            <span class="normal-case font-medium text-slate-400 ml-1">(optionnel)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">place</span>
                            <input type="text" name="arrival_address"
                                   value="{{ old('arrival_address') }}"
                                   placeholder="Ex: Gare de Porto-Novo"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                          bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                          dark:text-white placeholder-slate-400 transition-all"/>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── SECTION 2 : DATE & HEURE ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-violet-50 to-white dark:from-violet-500/10 dark:to-transparent border-b border-violet-100 dark:border-primary/10">
                <div class="w-8 h-8 rounded-xl bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-violet-500 text-lg">calendar_month</span>
                </div>
                <h2 class="font-black text-base">Date & Heure</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Date du trajet <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-violet-500 text-xl">calendar_today</span>
                            <input type="date" name="departure_date"
                                   value="{{ old('departure_date') }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                          bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                          dark:text-white transition-all"
                                   required/>
                        </div>
                        @error('departure_date') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Heure de départ <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-violet-500 text-xl">schedule</span>
                            <input type="time" name="departure_time"
                                   value="{{ old('departure_time') }}"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                          bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                          dark:text-white transition-all"
                                   required/>
                        </div>
                        @error('departure_time') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SECTION 3 : PLACES & PRIX ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-emerald-50 to-white dark:from-emerald-500/10 dark:to-transparent border-b border-emerald-100 dark:border-primary/10">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-500 text-lg">payments</span>
                </div>
                <h2 class="font-black text-base">Places & Tarif</h2>
            </div>
            <div class="p-6 space-y-5">

                {{-- Nombre de places --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        Nombre de places disponibles <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-emerald-500 text-xl">event_seat</span>
                        <select name="seats"
                                class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                       bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                       focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                       dark:text-white transition-all appearance-none cursor-pointer"
                                required>
                            @foreach([1,2,3,4,5,6] as $n)
                                <option value="{{ $n }}" {{ old('seats', 2) == $n ? 'selected' : '' }}>
                                    {{ $n }} place{{ $n > 1 ? 's' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl pointer-events-none">expand_more</span>
                    </div>
                    @error('seats') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Prix --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Prix par passager <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-emerald-500 text-xl">payments</span>
                            <input type="number" name="price"
                                   value="{{ old('price') }}"
                                   placeholder="Ex: 1500"
                                   min="0" step="50"
                                   class="w-full pl-10 pr-20 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                          bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                          dark:text-white placeholder-slate-400 transition-all"
                                   required/>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400 bg-slate-200 dark:bg-white/10 px-2 py-1 rounded-lg">FCFA</span>
                        </div>
                        @error('price') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Aperçu gains --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Gains estimés
                        </label>
                        <div class="flex items-center gap-3 px-4 py-3 rounded-xl
                                    bg-emerald-50 dark:bg-emerald-500/10
                                    border border-emerald-200 dark:border-emerald-500/20">
                            <span class="material-symbols-outlined text-emerald-500 text-xl">trending_up</span>
                            <div>
                                <p class="font-black text-emerald-700 dark:text-emerald-400 text-lg" id="gainPreview">— FCFA</p>
                                <p class="text-xs text-emerald-600/70 dark:text-emerald-500/70">si toutes les places sont prises</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── SECTION 4 : VÉHICULE ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-orange-50 to-white dark:from-orange-500/10 dark:to-transparent border-b border-orange-100 dark:border-primary/10">
                <div class="w-8 h-8 rounded-xl bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-orange-500 text-lg">directions_car</span>
                </div>
                <h2 class="font-black text-base">Véhicule</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">Marque</label>
                        <input type="text" name="vehicle_brand"
                               value="{{ old('vehicle_brand') }}"
                               placeholder="Ex: Toyota"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                      dark:text-white placeholder-slate-400 transition-all"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">Modèle</label>
                        <input type="text" name="vehicle_model"
                               value="{{ old('vehicle_model') }}"
                               placeholder="Ex: Corolla"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                      dark:text-white placeholder-slate-400 transition-all"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">Couleur</label>
                        <input type="text" name="vehicle_color"
                               value="{{ old('vehicle_color') }}"
                               placeholder="Ex: Blanc"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                      bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                      dark:text-white placeholder-slate-400 transition-all"/>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SECTION 5 : PRÉFÉRENCES ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-slate-50 to-white dark:from-white/5 dark:to-transparent border-b border-slate-100 dark:border-primary/10">
                <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-slate-500 text-lg">tune</span>
                </div>
                <h2 class="font-black text-base">Préférences</h2>
            </div>
            <div class="p-6 space-y-4">

                {{-- Toggles --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach([
                        ['name' => 'smoking_allowed',  'icon' => 'smoking_rooms',    'label' => 'Fumeur autorisé',        'color' => 'text-slate-500'],
                        ['name' => 'pets_allowed',     'icon' => 'pets',              'label' => 'Animaux acceptés',       'color' => 'text-amber-500'],
                        ['name' => 'music_allowed',    'icon' => 'music_note',        'label' => 'Musique dans le véhicule','color' => 'text-blue-500'],
                        ['name' => 'luggage_allowed',  'icon' => 'luggage',           'label' => 'Bagages acceptés',       'color' => 'text-violet-500'],
                    ] as $pref)
                    <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 cursor-pointer
                                  has-[:checked]:border-primary has-[:checked]:bg-primary/5
                                  transition-all hover:border-slate-300 dark:hover:border-white/20">
                        <input type="checkbox" name="{{ $pref['name'] }}" value="1"
                               class="sr-only peer"
                               {{ old($pref['name']) ? 'checked' : '' }}/>
                        <span class="material-symbols-outlined {{ $pref['color'] }} text-xl">{{ $pref['icon'] }}</span>
                        <span class="flex-1 text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $pref['label'] }}</span>
                        {{-- Toggle visuel --}}
                        <div class="relative w-10 h-5 rounded-full bg-slate-300 dark:bg-white/20 peer-checked:bg-primary transition-colors flex-shrink-0">
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- Message --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                        Message pour les passagers
                        <span class="normal-case font-medium text-slate-400 ml-1">(optionnel)</span>
                    </label>
                    <textarea name="message" rows="3"
                              placeholder="Ex: Je fais une pause à Abomey-Calavi. Soyez à l'heure !"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10
                                     bg-slate-50 dark:bg-white/5 text-sm font-semibold
                                     focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                     dark:text-white placeholder-slate-400 transition-all resize-none">{{ old('message') }}</textarea>
                </div>

            </div>
        </div>

        {{-- ── BOUTONS ── --}}
        <div class="flex flex-col sm:flex-row gap-3 pb-6">
            <a href="{{ route('dashboard') }}"
               class="flex-1 sm:flex-none flex items-center justify-center gap-2
                      px-6 py-3.5 rounded-xl border-2 border-slate-200 dark:border-white/10
                      text-slate-600 dark:text-slate-400 font-bold text-sm
                      hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                Annuler
            </a>
            <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2
                           bg-primary hover:bg-primary/90 text-background-dark
                           font-black px-8 py-3.5 rounded-xl transition-all
                           shadow-lg shadow-primary/25 text-sm">
                <span class="material-symbols-outlined text-xl">check_circle</span>
                Publier le trajet
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    const priceInput  = document.querySelector('input[name="price"]');
    const seatsSelect = document.querySelector('select[name="seats"]');
    const gainPreview = document.getElementById('gainPreview');

    function updateGain() {
        const price = parseInt(priceInput.value) || 0;
        const seats = parseInt(seatsSelect?.value) || 0;
        const total = price * seats;
        gainPreview.textContent = total > 0 ? total.toLocaleString('fr-FR') + ' FCFA' : '— FCFA';
    }

    priceInput?.addEventListener('input', updateGain);
    seatsSelect?.addEventListener('change', updateGain);

    // Toggle visuel des checkboxes préférences
    document.querySelectorAll('.sr-only.peer').forEach(checkbox => {
        const toggle = checkbox.closest('label').querySelector('.relative div');
        checkbox.addEventListener('change', () => {
            toggle.style.transform = checkbox.checked ? 'translateX(20px)' : 'translateX(0)';
        });
    });
</script>
@endpush
@endsection

<x-layout>
    <x-header></x-header>
            {{-- HERO FULL SCREEN --}}
<section class="relative min-h-[80vh] md:min-h-[50vh] w-full flex items-center justify-center overflow-hidden">

    {{-- Image FULL --}}
    <img
        src="https://images.unsplash.com/photo-1502877338535-766e1452684a"
        class="absolute inset-0 w-full h-full object-cover"
        alt="Covoiturage"
    >

    {{-- Overlay sombre --}}
    <div class="absolute inset-0 bg-black/60"></div>

    {{-- Glow (option premium) --}}
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-primary/20 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-blue-500/20 blur-3xl rounded-full"></div>

    {{-- CONTENU --}}
    <div class="relative z-10 w-full max-w-6xl px-6 text-center">

        {{-- Titre --}}
        <h1 class="text-white text-4xl md:text-6xl font-extrabold leading-tight">
            Trouvez votre trajet idéal 🚗
        </h1>

        {{-- Sous-titre --}}
        <p class="mt-4 text-slate-200 text-lg md:text-xl max-w-2xl mx-auto">
            Voyagez moins cher, plus confortable et rencontrez des personnes sur votre trajet.
        </p>

    </div>

</section>
    <main class="max-w-7xl mx-auto w-full px-6 lg:px-20 py-8">

        {{-- ── Résumé de la recherche ── --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-primary mb-2">
                    <a href="/">Accueil</a>
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-slate-400">Résultats de recherche</span>
                </nav>
                <div class="px-6 py-10">
                    <form action="{{ route('search.results') }}" method="GET">
                        <div class="bg-white dark:bg-charcoal p-3 rounded-2xl shadow-2xl flex flex-col md:flex-row items-stretch gap-2">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-2">
                            <div class="relative flex items-center group border-2 border-primary/20 rounded-xl focus-within:border-primary transition-all">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[16px]">location_on</span>
                                <input name="departure" class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Départ" type="text"/>
                            </div>
                            <div class="relative flex items-center group border-2 border-primary/20 rounded-xl focus-within:border-primary transition-all">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">near_me</span>
                                <input name="arrival" class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Destination" type="text"/>
                            </div>
                            <div class="relative flex items-center group border-2 border-primary/20 rounded-xl focus-within:border-primary transition-all">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">calendar_month</span>
                                <input name="date" class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" type="date"/>
                            </div>
                            <div class="relative flex items-center group border-2 border-primary/20 rounded-xl focus-within:border-primary transition-all">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">person</span>
                                <select name="passengers" class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium appearance-none">
                                    <option value="1">1 passager</option>
                                    <option value="2">2 passagers</option>
                                    <option value="3">3 passagers</option>
                                    <option value="4">4+ passagers</option>
                                </select>
                            </div>
                        </div>
                        <button class="bg-primary text-background-dark font-bold px-10 py-5 rounded-xl hover:bg-primary/90 transition-all text-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span> Rechercher
                        </button>
                     </div>
                    </form>
                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                    @if($departure || $arrival)
                        {{ $departure ?: '…' }}
                        <span class="text-primary">→</span>
                        {{ $arrival ?: '…' }}
                    @else
                        Tous les trajets
                    @endif
                </h1>
                <p class="mt-2 text-slate-500 font-medium flex items-center gap-2 flex-wrap">
                    @if($date)
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                        <span class="mx-1">•</span>
                    @endif
                    <span class="material-symbols-outlined text-sm">person</span>
                    {{ $passengers }} passager{{ $passengers > 1 ? 's' : '' }}
                    <span class="mx-1">•</span>
                    <span class="font-bold text-primary">{{ $trips->total() }} trajet{{ $trips->total() > 1 ? 's' : '' }} trouvé{{ $trips->total() > 1 ? 's' : '' }}</span>
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            {{-- ── Filtres ── --}}
            <aside class="lg:col-span-3 space-y-8">
                <form method="GET" action="{{ route('search.results') }}" id="filter-form">
                    <input type="hidden" name="departure"  value="{{ $departure }}">
                    <input type="hidden" name="arrival"    value="{{ $arrival }}">
                    <input type="hidden" name="date"       value="{{ $date }}">
                    <input type="hidden" name="passengers" value="{{ $passengers }}">

                    <div class="bg-white dark:bg-background-dark/50 p-6 rounded-2xl border border-primary/5 shadow-sm">
                        <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">tune</span> Filtres
                        </h3>

                        {{-- Prix max --}}
                        <div class="mb-8">
                            <label class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3 block">
                                Prix maximum
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="number" name="price_max" min="0" step="100"
                                       value="{{ request('price_max') }}"
                                       placeholder="Ex: 3000"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                                <span class="text-xs font-bold text-slate-400 whitespace-nowrap">F CFA</span>
                            </div>
                        </div>

                        {{-- Heure de départ --}}
                        <div class="mb-8">
                            <label class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3 block">
                                Heure de départ
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(['matin'=>'Matin','apres-midi'=>'Après-midi','soiree'=>'Soirée','nuit'=>'Nuit'] as $val => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="time_of_day" value="{{ $val }}"
                                           {{ request('time_of_day') === $val ? 'checked' : '' }}
                                           class="hidden peer">
                                    <span class="block p-2 text-xs font-bold text-center border-2 rounded-lg transition-all
                                                 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary
                                                 border-primary/20 hover:border-primary">
                                        {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Options --}}
                        {{-- <div class="mb-8">
                            <label class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3 block">
                                Options
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="luggage" value="1"
                                           {{ request('luggage') ? 'checked' : '' }}
                                           class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5">
                                    <span class="text-sm font-medium group-hover:text-primary transition-colors">Bagages acceptés</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="pets" value="1"
                                           {{ request('pets') ? 'checked' : '' }}
                                           class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5">
                                    <span class="text-sm font-medium group-hover:text-primary transition-colors">Animaux acceptés</span>
                                </label>
                            </div>
                        </div> --}}

                        <div class="pt-4 border-t border-primary/10 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                                <span class="material-symbols-outlined">filter_alt</span>
                                Appliquer
                            </button>
                            @if(request()->hasAny(['price_max','time_of_day','luggage','pets']))
                            <a href="{{ route('search.results', ['departure'=>$departure,'arrival'=>$arrival,'date'=>$date,'passengers'=>$passengers]) }}"
                               class="w-full text-center text-xs font-semibold text-slate-400 hover:text-red-500 transition-colors py-1">
                                Réinitialiser les filtres
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </aside>

            {{-- ── Résultats ── --}}
            <div class="lg:col-span-9 space-y-6">

                <div class="flex items-center justify-between">
                    <p class="text-sm font-bold text-slate-500">
                        {{ $trips->total() }} trajet{{ $trips->total() > 1 ? 's' : '' }} disponible{{ $trips->total() > 1 ? 's' : '' }}
                    </p>
                    <form method="GET" action="{{ route('search.results') }}" id="sort-form">
                        <input type="hidden" name="departure"  value="{{ $departure }}">
                        <input type="hidden" name="arrival"    value="{{ $arrival }}">
                        <input type="hidden" name="date"       value="{{ $date }}">
                        <input type="hidden" name="passengers" value="{{ $passengers }}">
                        @if($priceMax) <input type="hidden" name="price_max"  value="{{ $priceMax }}"> @endif
                        @if($timeOfDay) <input type="hidden" name="time_of_day" value="{{ $timeOfDay }}"> @endif
                        <select name="sort" onchange="document.getElementById('sort-form').submit()"
                                class="text-sm font-semibold border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none bg-white dark:bg-background-dark dark:text-white">
                            <option value="date"       {{ $sortBy === 'date'       ? 'selected' : '' }}>Trier : Date</option>
                            <option value="price_asc"  {{ $sortBy === 'price_asc'  ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ $sortBy === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        </select>
                    </form>
                </div>

                @forelse($trips as $trip)
                @php
                    $driver     = $trip->driver;
                    $driverName = trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) ?: 'Conducteur';
                    $initial    = strtoupper(substr($driver->first_name ?? $driver->name ?? '?', 0, 1));
                    $timeStr    = is_string($trip->departure_time) ? substr($trip->departure_time, 0, 5) : $trip->departure_time->format('H:i');
                    $seatsLeft  = $trip->seats_available;
                    $seatsTotal = $trip->seats_total;
                @endphp
                <div class="bg-white dark:bg-background-dark/50 rounded-2xl border border-primary/10 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all overflow-hidden">
                    <div class="flex flex-col md:flex-row">
                        <div class="flex-1 p-6">

                            {{-- Driver + prix --}}
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="size-12 rounded-xl bg-primary/10 border-2 border-primary/20
                                                flex items-center justify-center font-black text-primary text-lg">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg">{{ $driverName }}</h4>
                                        <p class="text-xs text-slate-400 font-semibold">
                                            {{ $trip->departure_date->translatedFormat('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-primary">{{ number_format($trip->price_per_seat) }} F</div>
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">par siège</div>
                                </div>
                            </div>

                            {{-- Trajet --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                <div class="col-span-2 flex items-center gap-6">
                                    <div class="text-center">
                                        <div class="text-xl font-extrabold">{{ $timeStr }}</div>
                                        <div class="text-xs font-bold text-slate-400">{{ $trip->departure_city }}</div>
                                    </div>
                                    <div class="flex-1 relative flex flex-col items-center">
                                        <div class="w-full h-px border-dashed border-b-2 border-slate-300 relative">
                                            <div class="absolute left-0 top-1/2 -translate-y-1/2 size-2 bg-primary rounded-full"></div>
                                            <div class="absolute right-0 top-1/2 -translate-y-1/2 size-2 bg-primary rounded-full"></div>
                                            <span class="absolute left-1/2 -translate-x-1/2 -top-6 material-symbols-outlined text-primary text-xl">directions_car</span>
                                        </div>
                                        {{-- Options --}}
                                        <div class="mt-2 flex items-center gap-2">
                                            @if($trip->luggage_allowed)
                                            <span class="material-symbols-outlined text-xs text-slate-400" title="Bagages acceptés">luggage</span>
                                            @endif
                                            @if($trip->pets_allowed)
                                            <span class="material-symbols-outlined text-xs text-slate-400" title="Animaux acceptés">pets</span>
                                            @endif
                                            @if($trip->silent_ride)
                                            <span class="material-symbols-outlined text-xs text-slate-400" title="Trajet silencieux">volume_off</span>
                                            @endif
                                            @if($trip->female_only)
                                            <span class="material-symbols-outlined text-xs text-slate-400" title="Femmes uniquement">female</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xl font-extrabold">–</div>
                                        <div class="text-xs font-bold text-slate-400">{{ $trip->arrival_city }}</div>
                                    </div>
                                </div>

                                {{-- Places --}}
                                <div class="bg-primary/5 rounded-xl p-3 border border-primary/10">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-slate-500">Places restantes</span>
                                        <span class="text-sm font-black text-primary">{{ $seatsLeft }}/{{ $seatsTotal }}</span>
                                    </div>
                                    <div class="flex gap-1">
                                        @for($i = 1; $i <= $seatsTotal; $i++)
                                        <div class="h-1.5 flex-1 rounded-full {{ $i <= ($seatsTotal - $seatsLeft) ? 'bg-slate-200' : 'bg-primary' }}"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Bouton Voir le trajet --}}
                        <div class="w-full md:w-48 flex flex-col justify-end p-4 md:p-6 border-t md:border-t-0 md:border-l border-primary/10">
                            <a href="{{ route('trip.detail', $trip->id) }}"
                               class="block text-center bg-primary text-white font-bold py-3 px-4 rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                                Voir le trajet
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20">
                    <span class="material-symbols-outlined text-slate-300 text-6xl block mb-4">search_off</span>
                    <h3 class="text-xl font-bold text-slate-500 mb-2">Aucun trajet trouvé</h3>
                    <p class="text-slate-400 mb-6">
                        Aucun trajet disponible pour
                        @if($departure || $arrival)
                            <strong>{{ $departure ?: '…' }} → {{ $arrival ?: '…' }}</strong>
                        @else
                            ces critères
                        @endif
                    </p>
                    <a href="/search"
                       class="inline-flex items-center gap-2 bg-primary text-white font-bold px-6 py-3 rounded-xl hover:opacity-90 transition">
                        <span class="material-symbols-outlined">search</span>
                        Nouvelle recherche
                    </a>
                </div>
                @endforelse

                {{-- Pagination --}}
                @if($trips->hasPages())
                <div class="pt-4">
                    {{ $trips->links() }}
                </div>
                @endif

            </div>
        </div>
    </main>
</x-layout>

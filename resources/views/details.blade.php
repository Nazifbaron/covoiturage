<x-layout>
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <x-header></x-header>

        <main class="max-w-[1200px] mx-auto w-full px-6 py-8">

            {{-- Fil d'ariane --}}
            <nav class="flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 mb-6">
                <a class="hover:text-primary" href="{{ url()->previous() }}">Résultats de recherche</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-slate-900 dark:text-slate-100">Détails du trajet</span>
            </nav>

            @php
                $driver    = $trip->driver;
                $driverName = trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) ?: 'Conducteur';
                $initial   = strtoupper(substr($driver->first_name ?? $driver->name ?? '?', 0, 1));
                $timeStr   = is_string($trip->departure_time) ? substr($trip->departure_time, 0, 5) : $trip->departure_time->format('H:i');
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ── Colonne principale ── --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Trajet --}}
                    <section class="bg-white dark:bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h1 class="text-3xl font-extrabold tracking-tight mb-2">
                                    {{ $trip->departure_city }} → {{ $trip->arrival_city }}
                                </h1>
                                <p class="text-slate-500 font-medium">
                                    {{ $trip->departure_date->translatedFormat('l, d F Y') }}
                                    &bull; {{ $timeStr }}
                                </p>
                            </div>
                            <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                {{ $trip->seats_available }} place{{ $trip->seats_available > 1 ? 's' : '' }} dispo
                            </span>
                        </div>

                        <div class="relative space-y-0 pl-10">
                            <div class="absolute left-4 top-1 bottom-1 w-0.5 bg-slate-200 dark:bg-slate-800"></div>
                            <div class="relative pb-8">
                                <span class="absolute -left-[30px] top-0 size-5 rounded-full border-4 border-white dark:border-slate-900 bg-primary ring-2 ring-primary/20"></span>
                                <div class="flex flex-col">
                                    <span class="text-lg font-bold">Départ : {{ $trip->departure_city }}</span>
                                    <span class="text-slate-500">
                                        {{ $trip->departure_address ?: 'Lieu de départ à confirmer avec le conducteur' }}
                                        — {{ $timeStr }}
                                    </span>
                                </div>
                            </div>
                            <div class="relative">
                                <span class="absolute -left-[30px] top-0 size-5 rounded-full border-4 border-white dark:border-slate-900 bg-slate-400 ring-2 ring-slate-400/20"></span>
                                <div class="flex flex-col">
                                    <span class="text-lg font-bold">Arrivée : {{ $trip->arrival_city }}</span>
                                    <span class="text-slate-500">
                                        {{ $trip->arrival_address ?: 'Lieu d\'arrivée à confirmer avec le conducteur' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Options du trajet --}}
                        @if($trip->luggage_allowed || $trip->pets_allowed || $trip->silent_ride || $trip->female_only)
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-3">
                            @if($trip->luggage_allowed)
                            <span class="flex items-center gap-1.5 text-xs font-semibold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-full text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-sm">luggage</span> Bagages acceptés
                            </span>
                            @endif
                            @if($trip->pets_allowed)
                            <span class="flex items-center gap-1.5 text-xs font-semibold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-full text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-sm">pets</span> Animaux acceptés
                            </span>
                            @endif
                            @if($trip->silent_ride)
                            <span class="flex items-center gap-1.5 text-xs font-semibold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-full text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-sm">volume_off</span> Trajet silencieux
                            </span>
                            @endif
                            @if($trip->female_only)
                            <span class="flex items-center gap-1.5 text-xs font-semibold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-full text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-sm">female</span> Femmes uniquement
                            </span>
                            @endif
                        </div>
                        @endif
                    </section>

                    {{-- Conducteur --}}
                    <section class="bg-white dark:bg-slate-900 rounded-xl p-8 shadow-sm border border-slate-200 dark:border-slate-800">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">person</span>
                            Votre conducteur
                        </h3>
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="relative">
                                    <div class="size-24 rounded-full bg-primary/10 ring-4 ring-primary/10
                                                flex items-center justify-center font-black text-primary text-4xl">
                                        {{ $initial }}
                                    </div>
                                    <div class="absolute bottom-0 right-0 bg-primary text-white size-8 rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900">
                                        <span class="material-symbols-outlined text-sm">verified</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold">{{ $driverName }}</h4>
                                    <p class="text-xs text-slate-400 mt-1">Conducteur vérifié</p>
                                </div>
                            </div>
                            <div class="flex-1 space-y-4">
                                @if($trip->description)
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed italic">
                                    « {{ $trip->description }} »
                                </p>
                                @endif
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                                        <span class="material-symbols-outlined text-slate-400">event_seat</span>
                                        <div>
                                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Places totales</p>
                                            <p class="font-medium">{{ $trip->seats_total }} sièges</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                                        <span class="material-symbols-outlined text-slate-400">check_circle</span>
                                        <div>
                                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Statut</p>
                                            <p class="font-medium text-green-600">Vérifié</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

                {{-- ── Sidebar réservation ── --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-4">
                        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 p-6">

                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <span class="text-slate-500 text-sm font-medium">Prix par siège</span>
                                    <div class="text-3xl font-extrabold text-primary">
                                        {{ number_format($trip->price_per_seat) }} F CFA
                                    </div>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    {{ $trip->seats_available }} place{{ $trip->seats_available > 1 ? 's' : '' }}
                                </div>
                            </div>

                            {{-- Sélecteur de sièges --}}
                            <div class="space-y-4 mb-6">
                                <label class="block">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">Nombre de sièges</span>
                                    <div class="relative">
                                        <select id="seats-select" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg py-3 pl-4 pr-10 appearance-none focus:ring-primary focus:border-primary transition-all font-medium">
                                            @for($i = 1; $i <= min($trip->seats_available, 4); $i++)
                                            <option value="{{ $i }}">{{ $i }} siège{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">expand_more</span>
                                    </div>
                                </label>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-800 pt-4 mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-slate-600 dark:text-slate-400">Coût du voyage</span>
                                    <span class="font-bold" id="price-display">{{ number_format($trip->price_per_seat) }} F</span>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-slate-600 dark:text-slate-400">Frais de service</span>
                                    <span class="font-bold text-green-600">Gratuit</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-800">
                                    <span class="text-lg font-extrabold">Total</span>
                                    <span class="text-2xl font-extrabold text-slate-900 dark:text-white" id="total-display">{{ number_format($trip->price_per_seat) }} F</span>
                                </div>
                            </div>

                            {{-- Bouton Réserver --}}
                            @auth
                                @if(Auth::user()->role === 'passenger')
                                    <a href="{{ route('passenger.showtrips', [
                                            'driver_trip_id' => $trip->id,
                                            'departure_city' => $trip->departure_city,
                                            'arrival_city'   => $trip->arrival_city,
                                            'date'           => $trip->departure_date->format('Y-m-d'),
                                            'time'           => $timeStr,
                                            'passengers'     => 1,
                                        ]) }}"
                                       id="reserve-btn"
                                       class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group">
                                        Réserver maintenant
                                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                    </a>
                                @else
                                    <button disabled
                                        class="w-full bg-slate-100 text-slate-400 font-bold py-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">block</span>
                                        Réservé aux passagers
                                    </button>
                                @endif
                            @else
                                <button onclick="document.getElementById('auth-modal').classList.remove('hidden')"
                                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group">
                                    Réserver maintenant
                                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </button>
                            @endauth

                            <p class="text-center text-xs text-slate-500 mt-4">
                                Aucun paiement requis. La réservation sera confirmée par le conducteur.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- ── Modal connexion/inscription (visiteurs non connectés) ── --}}
    <div id="auth-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             onclick="document.getElementById('auth-modal').classList.add('hidden')"></div>

        {{-- Card --}}
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md p-8 z-10">

            <button onclick="document.getElementById('auth-modal').classList.add('hidden')"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <span class="material-symbols-outlined text-slate-400">close</span>
            </button>

            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-primary text-3xl">directions_car</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-2">
                    Presque prêt !
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">
                    Connectez-vous ou créez un compte pour réserver ce trajet
                    <strong class="text-slate-700 dark:text-slate-300">{{ $trip->departure_city }} → {{ $trip->arrival_city }}</strong>.
                </p>
            </div>

            <div class="space-y-3">
                <a href="{{ route('login', ['redirect' => url()->current()]) }}"
                   class="w-full flex items-center justify-center gap-3 bg-primary text-white font-bold py-3.5 rounded-xl hover:bg-primary/90 transition-all shadow-md shadow-primary/20">
                    <span class="material-symbols-outlined">login</span>
                    Se connecter
                </a>
                <a href="{{ route('register') }}"
                   class="w-full flex items-center justify-center gap-3 border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold py-3.5 rounded-xl hover:border-primary hover:text-primary transition-all">
                    <span class="material-symbols-outlined">person_add</span>
                    Créer un compte
                </a>
            </div>

            <p class="text-center text-xs text-slate-400 mt-5">
                Inscription gratuite &bull; Aucun paiement requis
            </p>
        </div>
    </div>

    <script>
        const pricePerSeat = {{ $trip->price_per_seat }};
        const select       = document.getElementById('seats-select');
        const priceDisplay = document.getElementById('price-display');
        const totalDisplay = document.getElementById('total-display');
        const reserveBtn   = document.getElementById('reserve-btn');

        if (select) {
            select.addEventListener('change', function () {
                const seats  = parseInt(this.value);
                const total  = seats * pricePerSeat;
                const fmt    = n => n.toLocaleString('fr') + ' F';
                if (priceDisplay) priceDisplay.textContent = fmt(total);
                if (totalDisplay) totalDisplay.textContent = fmt(total);

                @auth
                @if(Auth::check() && Auth::user()->role === 'passenger')
                if (reserveBtn) {
                    const url = new URL(reserveBtn.href);
                    url.searchParams.set('passengers', seats);
                    reserveBtn.href = url.toString();
                }
                @endif
                @endauth
            });
        }
    </script>
</x-layout>

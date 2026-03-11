@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
               class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                      flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Mes demandes</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Trajets proposés et leur statut en temps réel</p>
            </div>
        </div>
        <a href="{{ route('passenger.showtrips') }}"
           class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                  font-black px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-primary/20 text-sm flex-shrink-0">
            <span class="material-symbols-outlined text-lg">add</span>
            Nouvelle demande
        </a>
    </div>

    {{-- ── Statistiques : comptages réels depuis le contrôleur ── --}}
    <div class="grid grid-cols-4 gap-3">
        @foreach([
            ['label' => 'Total',      'value' => $counts['total'],     'color' => 'text-slate-700 dark:text-slate-300',   'bg' => 'bg-slate-100 dark:bg-white/10'],
            ['label' => 'En attente', 'value' => $counts['pending'],   'color' => 'text-orange-600 dark:text-orange-400', 'bg' => 'bg-orange-50 dark:bg-orange-500/10'],
            ['label' => 'Acceptées',  'value' => $counts['accepted'],  'color' => 'text-emerald-600 dark:text-emerald-400','bg' => 'bg-emerald-50 dark:bg-emerald-500/10'],
            ['label' => 'Annulées',   'value' => $counts['cancelled'], 'color' => 'text-slate-400 dark:text-slate-500',   'bg' => 'bg-slate-50 dark:bg-white/5'],
        ] as $stat)
        <div class="rounded-2xl {{ $stat['bg'] }} p-3.5 text-center">
            <p class="text-2xl font-black {{ $stat['color'] }}">{{ $stat['value'] }}</p>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── Filtres ── --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        @foreach([
            ['value' => '',          'label' => 'Toutes'],
            ['value' => 'pending',   'label' => 'En attente'],
            ['value' => 'accepted',  'label' => 'Acceptées'],
            ['value' => 'cancelled', 'label' => 'Annulées'],
            ['value' => 'expired',   'label' => 'Expirées'],
        ] as $filter)
        <a href="{{ request()->fullUrlWithQuery(['status' => $filter['value'], 'page' => 1]) }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-bold transition-all
                  {{ request('status', '') === $filter['value']
                      ? 'bg-primary text-background-dark shadow-md shadow-primary/20'
                      : 'bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:border-primary/50' }}">
            {{ $filter['label'] }}
        </a>
        @endforeach
    </div>

    {{-- ── Liste ── --}}
    @if($requests->isEmpty())
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-slate-400 text-3xl">hail</span>
            </div>
            <p class="font-black text-slate-700 dark:text-slate-300 text-lg">Aucune demande</p>
            <p class="text-sm text-slate-400 mt-1 mb-5">
                {{ request('status') ? 'Aucune demande avec ce statut.' : 'Vous n\'avez pas encore proposé de trajet.' }}
            </p>
            <a href="{{ route('passenger.showtrips') }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                      font-black px-5 py-2.5 rounded-xl transition-all text-sm">
                <span class="material-symbols-outlined text-lg">add</span>
                Proposer un trajet
            </a>
        </div>

    @else
        <div class="space-y-3">
            @foreach($requests as $trip)
            @php
                $statusConfig = [
                    'pending'   => ['label' => 'En attente', 'dot' => 'bg-orange-400 animate-pulse', 'bar' => 'bg-orange-400',  'bg' => 'bg-orange-50 dark:bg-orange-500/10',   'text' => 'text-orange-600 dark:text-orange-400',   'border' => 'border-orange-200 dark:border-orange-500/20'],
                    'accepted'  => ['label' => 'Acceptée',   'dot' => 'bg-emerald-400',              'bar' => 'bg-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-500/20'],
                    'cancelled' => ['label' => 'Annulée',    'dot' => 'bg-slate-300',                'bar' => 'bg-slate-200',   'bg' => 'bg-slate-50 dark:bg-white/5',          'text' => 'text-slate-400 dark:text-slate-500',     'border' => 'border-slate-200 dark:border-white/10'],
                    'expired'   => ['label' => 'Expirée',    'dot' => 'bg-red-300',                  'bar' => 'bg-red-300',     'bg' => 'bg-red-50 dark:bg-red-500/10',         'text' => 'text-red-400',                           'border' => 'border-red-100 dark:border-red-500/20'],
                ];
                $s = $statusConfig[$trip->status] ?? $statusConfig['pending'];
            @endphp

            <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden hover:shadow-md transition-all duration-200">

                {{-- Barre colorée statut --}}
                <div class="h-1 w-full {{ $s['bar'] }}"></div>

                <div class="p-5">
                    <div class="flex items-start gap-4">

                        {{-- Connecteur itinéraire --}}
                        <div class="flex flex-col items-center gap-1 pt-1 flex-shrink-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-primary border-2 border-primary/30"></span>
                            <span class="w-0.5 h-8 bg-slate-200 dark:bg-white/10"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400 border-2 border-red-200"></span>
                        </div>

                        <div class="flex-1 min-w-0">

                            {{-- Villes + badge statut --}}
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <p class="font-black text-slate-900 dark:text-white text-base leading-tight">{{ $trip->departure_city }}</p>
                                    <p class="font-black text-slate-900 dark:text-white text-base leading-tight mt-1.5">{{ $trip->arrival_city }}</p>
                                </div>
                                <div class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ $s['bg'] }} border {{ $s['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                                    <span class="text-xs font-black {{ $s['text'] }}">{{ $s['label'] }}</span>
                                </div>
                            </div>

                            {{-- Métadonnées --}}
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3">
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($trip->requested_date)->locale('fr')->isoFormat('ddd D MMM YYYY') }}
                                </span>
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">schedule</span>
                                    {{ \Carbon\Carbon::parse($trip->requested_time)->format('H\hi') }}
                                    @if($trip->flexibility > 0)
                                        <span class="text-slate-400">(±{{ $trip->flexibility >= 60 ? ($trip->flexibility/60).'h' : $trip->flexibility.'min' }})</span>
                                    @endif
                                </span>
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">group</span>
                                    {{ $trip->passengers }} passager{{ $trip->passengers > 1 ? 's' : '' }}
                                </span>
                                @if($trip->budget_max)
                                <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                    <span class="material-symbols-outlined" style="font-size:14px">payments</span>
                                    max {{ number_format($trip->budget_max, 0, ',', '\u{202F}') }} FCFA
                                </span>
                                @endif
                            </div>

                            {{-- Adresses optionnelles --}}
                            @if($trip->departure_address || $trip->arrival_address)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if($trip->departure_address)
                                <span class="text-xs text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-white/5 px-2 py-1 rounded-lg">
                                    📍 {{ $trip->departure_address }}
                                </span>
                                @endif
                                @if($trip->arrival_address)
                                <span class="text-xs text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-white/5 px-2 py-1 rounded-lg">
                                    🏁 {{ $trip->arrival_address }}
                                </span>
                                @endif
                            </div>
                            @endif

                            {{-- Conducteur assigné (statut accepted) --}}
                            @if($trip->status === 'accepted' && $trip->driver)
                            <div class="mt-3 flex items-center gap-2.5 p-3 rounded-xl
                                        bg-emerald-50 dark:bg-emerald-500/10
                                        border border-emerald-200 dark:border-emerald-500/20">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-600
                                            flex items-center justify-center flex-shrink-0 font-black text-white text-sm">
                                    {{ strtoupper(substr($trip->driver->first_name ?? $trip->driver->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-emerald-700 dark:text-emerald-400">Conducteur assigné</p>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">
                                        {{ $trip->driver->first_name ?? '' }} {{ $trip->driver->last_name ?? $trip->driver->name ?? '' }}
                                    </p>
                                </div>
                                <span class="material-symbols-outlined text-emerald-500 ml-auto">verified</span>
                            </div>
                            @endif

                            {{-- Timer expiration --}}
                            @if($trip->status === 'pending' && $trip->expires_at)
                            <div class="mt-2 flex items-center gap-1 text-xs font-semibold
                                        {{ \Carbon\Carbon::parse($trip->expires_at)->diffInMinutes(now()) < 30 ? 'text-red-500' : 'text-orange-500 dark:text-orange-400' }}">
                                <span class="material-symbols-outlined" style="font-size:14px">timer</span>
                                @if(\Carbon\Carbon::parse($trip->expires_at)->isPast())
                                    Expirée
                                @else
                                    Expire {{ \Carbon\Carbon::parse($trip->expires_at)->diffForHumans() }}
                                @endif
                            </div>
                            @endif

                            {{-- Message --}}
                            @if($trip->message)
                            <p class="mt-2 text-xs text-slate-400 dark:text-slate-500 italic line-clamp-1">
                                "{{ $trip->message }}"
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-400 font-medium">
                            Publiée {{ $trip->created_at->diffForHumans() }}
                        </span>
                        <div class="flex items-center gap-2">
                            @if($trip->status === 'accepted')
                            <a href="{{ route('passenger.chat', $trip->id) }}"
                               class="flex items-center gap-1.5 px-4 py-1.5 rounded-xl
                                      bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black
                                      transition-all shadow-md shadow-emerald-200 dark:shadow-emerald-900/30">
                                <span class="material-symbols-outlined" style="font-size:14px">chat</span>
                                Contacter le conducteur
                            </a>
                            @endif
                            @if($trip->status === 'pending')
                            <form method="POST" action="{{ route('passenger.requests.cancel', $trip) }}"
                                  onsubmit="return confirm('Annuler cette demande ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                                               border border-red-200 dark:border-red-500/30
                                               text-red-500 dark:text-red-400 text-xs font-bold
                                               hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                    <span class="material-symbols-outlined" style="font-size:14px">cancel</span>
                                    Annuler
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Pagination ── --}}
        @if($requests->hasPages())
        <div class="flex items-center justify-center gap-2 pb-6">
            @if($requests->onFirstPage())
                <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-300 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </span>
            @else
                <a href="{{ $requests->previousPageUrl() }}"
                   class="w-9 h-9 rounded-xl flex items-center justify-center bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </a>
            @endif

            @foreach($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                @if($page == $requests->currentPage())
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-primary text-background-dark font-black text-sm">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 font-semibold text-sm hover:border-primary/50 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($requests->hasMorePages())
                <a href="{{ $requests->nextPageUrl() }}"
                   class="w-9 h-9 rounded-xl flex items-center justify-center bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </a>
            @else
                <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-300 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </span>
            @endif
        </div>
        @endif
    @endif

</div>
@endsection

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

    {{-- ── Statistiques rapides ── --}}
    @php
        $total     = $requests->total();
        $pending   = $requests->getCollection()->where('status', 'pending')->count();
        $accepted  = $requests->getCollection()->where('status', 'accepted')->count();
        $cancelled = $requests->getCollection()->where('status', 'cancelled')->count();
    @endphp
    <div class="grid grid-cols-4 gap-3">
        @foreach([
            ['label' => 'Total',    'value' => $total,     'color' => 'text-slate-700 dark:text-slate-300',  'bg' => 'bg-slate-100 dark:bg-white/10'],
            ['label' => 'En attente','value' => $pending,  'color' => 'text-orange-600 dark:text-orange-400','bg' => 'bg-orange-50 dark:bg-orange-500/10'],
            ['label' => 'Acceptées', 'value' => $accepted, 'color' => 'text-emerald-600 dark:text-emerald-400','bg' => 'bg-emerald-50 dark:bg-emerald-500/10'],
            ['label' => 'Annulées',  'value' => $cancelled,'color' => 'text-slate-400 dark:text-slate-500',  'bg' => 'bg-slate-50 dark:bg-white/5'],
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
            ['value' => '',           'label' => 'Toutes'],
            ['value' => 'pending',    'label' => 'En attente'],
            ['value' => 'accepted',   'label' => 'Acceptées'],
            ['value' => 'cancelled',  'label' => 'Annulées'],
            ['value' => 'expired',    'label' => 'Expirées'],
        ] as $filter)
        <a href="{{ request()->fullUrlWithQuery(['status' => $filter['value']]) }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-bold transition-all
                  {{ request('status', '') === $filter['value']
                      ? 'bg-primary text-background-dark shadow-md shadow-primary/20'
                      : 'bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:border-primary/50' }}">
            {{ $filter['label'] }}
        </a>
        @endforeach
    </div>

    {{-- ── Liste des demandes ── --}}
    @if($requests->isEmpty())
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-slate-400 text-3xl">hail</span>
            </div>
            <p class="font-black text-slate-700 dark:text-slate-300 text-lg">Aucune demande</p>
            <p class="text-sm text-slate-400 mt-1 mb-5">Vous n'avez pas encore proposé de trajet.</p>
            <a href="{{ route('passenger.showtrips') }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                      font-black px-5 py-2.5 rounded-xl transition-all text-sm">
                <span class="material-symbols-outlined text-lg">add</span>
                Proposer un trajet
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($requests as $request)
            @php
                $statusConfig = [
                    'pending'   => ['label' => 'En attente',   'icon' => 'schedule',      'bg' => 'bg-orange-50 dark:bg-orange-500/10',   'text' => 'text-orange-600 dark:text-orange-400',  'border' => 'border-orange-200 dark:border-orange-500/20',  'dot' => 'bg-orange-400 animate-pulse'],
                    'accepted'  => ['label' => 'Acceptée',     'icon' => 'check_circle',  'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400','border' => 'border-emerald-200 dark:border-emerald-500/20','dot' => 'bg-emerald-400'],
                    'cancelled' => ['label' => 'Annulée',      'icon' => 'cancel',        'bg' => 'bg-slate-50 dark:bg-white/5',          'text' => 'text-slate-400 dark:text-slate-500',    'border' => 'border-slate-200 dark:border-white/10',        'dot' => 'bg-slate-300'],
                    'expired'   => ['label' => 'Expirée',      'icon' => 'timer_off',     'bg' => 'bg-red-50 dark:bg-red-500/10',         'text' => 'text-red-400 dark:text-red-400',        'border' => 'border-red-100 dark:border-red-500/20',        'dot' => 'bg-red-300'],
                ];
                $s = $statusConfig[$request->status] ?? $statusConfig['pending'];
            @endphp

            <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden
                        hover:shadow-md transition-all duration-200">

                {{-- Barre de statut colorée en haut --}}
                <div class="h-1 w-full {{ str_replace(['bg-', 'dark:bg-'], ['bg-', 'dark:bg-'], explode(' ', $s['dot'])[0]) }}
                            {{ $request->status === 'accepted' ? 'bg-emerald-400' : ($request->status === 'pending' ? 'bg-orange-400' : ($request->status === 'expired' ? 'bg-red-300' : 'bg-slate-200')) }}">
                </div>

                <div class="p-5">
                    <div class="flex items-start gap-4">

                        {{-- Connecteur itinéraire --}}
                        <div class="flex flex-col items-center gap-1 pt-1 flex-shrink-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-primary border-2 border-primary/30"></span>
                            <span class="w-0.5 h-8 bg-slate-200 dark:bg-white/10"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400 border-2 border-red-200"></span>
                        </div>

                        {{-- Infos principales --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <p class="font-black text-slate-900 dark:text-white text-base leading-tight">
                                        {{ $request->departure_city }}
                                    </p>
                                    <p class="font-black text-slate-900 dark:text-white text-base leading-tight mt-1.5">
                                        {{ $request->arrival_city }}
                                    </p>
                                </div>

                                {{-- Badge statut --}}
                                <div class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ $s['bg'] }} border {{ $s['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                                    <span class="text-xs font-black {{ $s['text'] }}">{{ $s['label'] }}</span>
                                </div>
                            </div>

                            {{-- Métadonnées ── --}}
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3">
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($request->requested_date)->locale('fr')->isoFormat('ddd D MMM YYYY') }}
                                </span>
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">schedule</span>
                                    {{ \Carbon\Carbon::parse($request->requested_time)->format('H\hi') }}
                                    @if($request->flexibility > 0)
                                        <span class="text-slate-400">(±{{ $request->flexibility >= 60 ? $request->flexibility/60 .'h' : $request->flexibility.'min' }})</span>
                                    @endif
                                </span>
                                <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="material-symbols-outlined text-slate-400" style="font-size:14px">group</span>
                                    {{ $request->passengers }} passager{{ $request->passengers > 1 ? 's' : '' }}
                                </span>
                                @if($request->budget_max)
                                <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                    <span class="material-symbols-outlined" style="font-size:14px">payments</span>
                                    max {{ number_format($request->budget_max, 0, ',', ' ') }} FCFA
                                </span>
                                @endif
                            </div>

                            {{-- Adresses précises --}}
                            @if($request->departure_address || $request->arrival_address)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if($request->departure_address)
                                <span class="text-xs text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-white/5 px-2 py-1 rounded-lg">
                                    📍 {{ $request->departure_address }}
                                </span>
                                @endif
                                @if($request->arrival_address)
                                <span class="text-xs text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-white/5 px-2 py-1 rounded-lg">
                                    🏁 {{ $request->arrival_address }}
                                </span>
                                @endif
                            </div>
                            @endif

                            {{-- Conducteur acceptant (si accepted) --}}
                            @if($request->status === 'accepted' && $request->acceptedBy)
                            <div class="mt-3 flex items-center gap-2.5 p-3 rounded-xl
                                        bg-emerald-50 dark:bg-emerald-500/10
                                        border border-emerald-200 dark:border-emerald-500/20">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-white" style="font-size:16px">person</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-emerald-700 dark:text-emerald-400">Conducteur assigné</p>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">
                                        {{ $request->acceptedBy->first_name }} {{ $request->acceptedBy->last_name }}
                                    </p>
                                </div>
                                <span class="material-symbols-outlined text-emerald-500 ml-auto">verified</span>
                            </div>
                            @endif

                            {{-- Timer expiration (si pending) --}}
                            @if($request->status === 'pending' && $request->expires_at)
                            <div class="mt-2 flex items-center gap-1 text-xs font-semibold
                                        {{ $request->expires_at->diffInMinutes(now()) < 30 ? 'text-red-500' : 'text-orange-500 dark:text-orange-400' }}">
                                <span class="material-symbols-outlined" style="font-size:14px">timer</span>
                                @if($request->expires_at->isPast())
                                    Expirée
                                @else
                                    Expire {{ $request->expires_at->diffForHumans() }}
                                @endif
                            </div>
                            @endif

                            {{-- Message --}}
                            @if($request->message)
                            <p class="mt-2 text-xs text-slate-400 dark:text-slate-500 italic line-clamp-1">
                                "{{ $request->message }}"
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    @if($request->status === 'pending')
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-400 font-medium">
                            Publiée {{ $request->created_at->diffForHumans() }}
                        </span>
                        <form method="POST" action="{{ route('passenger.requests.cancel', $request) }}"
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
                    </div>
                    @else
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/5">
                        <span class="text-xs text-slate-400 font-medium">
                            Publiée {{ $request->created_at->diffForHumans() }}
                        </span>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
        <div class="flex items-center justify-center gap-2 pb-6">
            {{-- Précédent --}}
            @if($requests->onFirstPage())
                <span class="w-9 h-9 rounded-xl flex items-center justify-center
                             bg-slate-100 dark:bg-white/5 text-slate-300 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </span>
            @else
                <a href="{{ $requests->previousPageUrl() }}"
                   class="w-9 h-9 rounded-xl flex items-center justify-center
                          bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10
                          text-slate-600 dark:text-slate-400 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </a>
            @endif

            {{-- Pages --}}
            @foreach($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                @if($page == $requests->currentPage())
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center
                                 bg-primary text-background-dark font-black text-sm">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="w-9 h-9 rounded-xl flex items-center justify-center
                              bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10
                              text-slate-600 dark:text-slate-400 font-semibold text-sm
                              hover:border-primary/50 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Suivant --}}
            @if($requests->hasMorePages())
                <a href="{{ $requests->nextPageUrl() }}"
                   class="w-9 h-9 rounded-xl flex items-center justify-center
                          bg-white dark:bg-card-dark border border-slate-200 dark:border-white/10
                          text-slate-600 dark:text-slate-400 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </a>
            @else
                <span class="w-9 h-9 rounded-xl flex items-center justify-center
                             bg-slate-100 dark:bg-white/5 text-slate-300 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </span>
            @endif
        </div>
        @endif

    @endif
</div>
@endsection

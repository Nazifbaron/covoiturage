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
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Mes gains</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Suivi de vos revenus de conducteur</p>
        </div>
    </div>

    {{-- ── Carte principale — Total + ce mois ── --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary to-emerald-600 p-6 shadow-xl shadow-primary/20">
        {{-- Cercles décoratifs --}}
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-6 -left-4 w-28 h-28 rounded-full bg-white/5"></div>

        <div class="relative">
            <p class="text-xs font-black text-white/70 uppercase tracking-widest mb-1">Total gagné</p>
            <p class="text-4xl font-black text-white leading-none">
                {{ number_format($totalEarnings, 0, ',', '\u{202F}') }}
                <span class="text-2xl font-bold text-white/80">FCFA</span>
            </p>

            <div class="mt-5 grid grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-white/60 font-semibold">Ce mois</p>
                    <p class="text-lg font-black text-white">{{ number_format($thisMonth, 0, ',', '\u{202F}') }} <span class="text-sm font-bold">FCFA</span></p>
                    @if($evolution !== 0)
                    <p class="flex items-center gap-0.5 text-xs font-bold mt-0.5
                               {{ $evolution > 0 ? 'text-white/80' : 'text-red-200' }}">
                        <span class="material-symbols-outlined" style="font-size:13px">
                            {{ $evolution > 0 ? 'trending_up' : 'trending_down' }}
                        </span>
                        {{ $evolution > 0 ? '+' : '' }}{{ $evolution }}% vs mois dernier
                    </p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-white/60 font-semibold">Trajets</p>
                    <p class="text-lg font-black text-white">{{ $totalTrips }}</p>
                </div>
                <div>
                    <p class="text-xs text-white/60 font-semibold">Passagers</p>
                    <p class="text-lg font-black text-white">{{ $totalPassengers }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Graphique barres — 6 derniers mois ── --}}
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5">
        <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-4">
            Évolution mensuelle
        </h2>

        @php $maxAmount = $monthly->max('amount') ?: 1; @endphp

        <div class="flex items-end gap-2 h-32">
            @foreach($monthly as $m)
            @php $pct = $maxAmount > 0 ? ($m['amount'] / $maxAmount * 100) : 0; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5">
                <span class="text-xs font-black text-slate-500 dark:text-slate-400 whitespace-nowrap"
                      style="{{ $m['amount'] > 0 ? '' : 'opacity:.4' }}">
                    {{ $m['amount'] > 0 ? number_format($m['amount'] / 1000, 0) . 'k' : '—' }}
                </span>
                <div class="w-full rounded-t-lg transition-all duration-500 relative overflow-hidden"
                     style="height: {{ max(4, $pct * 0.9) }}%; min-height: 4px;
                            background: {{ $m['amount'] > 0 ? 'linear-gradient(to top, #13ec49, #22d3ee)' : '#e2e8f0' }};">
                </div>
                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 whitespace-nowrap">
                    {{ $m['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Meilleur trajet ── --}}
    @if($bestTrip && ($bestTrip->seats_total - $bestTrip->seats_available) > 0)
    <div class="flex items-center gap-4 p-4 rounded-2xl
                bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20">
        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20
                    flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-500 text-xl">emoji_events</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider">Meilleur trajet</p>
            <p class="font-black text-slate-900 dark:text-white text-sm truncate">
                {{ $bestTrip->departure_city }} → {{ $bestTrip->arrival_city }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                {{ $bestTrip->departure_date->locale('fr')->isoFormat('D MMM YYYY') }}
                · {{ $bestTrip->seats_total - $bestTrip->seats_available }} passager(s)
            </p>
        </div>
        <div class="text-right flex-shrink-0">
            <p class="font-black text-amber-600 dark:text-amber-400 text-base">
                {{ number_format($bestTrip->price_per_seat * ($bestTrip->seats_total - $bestTrip->seats_available), 0, ',', '\u{202F}') }}
            </p>
            <p class="text-xs text-slate-400 font-semibold">FCFA</p>
        </div>
    </div>
    @endif

    {{-- ── Liste des trajets avec gains ── --}}
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
        <div class="px-5 pt-5 pb-3 border-b border-slate-100 dark:border-white/5">
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">
                Détail par trajet
            </h2>
        </div>

        @if($trips->isEmpty())
        <div class="p-10 text-center">
            <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-4xl">payments</span>
            <p class="text-sm font-bold text-slate-400 mt-2">Aucun gain enregistré pour l'instant.</p>
            <a href="{{ route('driver.create-tips') }}"
               class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl
                      bg-primary hover:bg-primary/90 text-background-dark font-black text-xs
                      transition-all shadow-md shadow-primary/20">
                <span class="material-symbols-outlined text-base">add</span>
                Publier un trajet
            </a>
        </div>
        @else

        <div class="divide-y divide-slate-100 dark:divide-white/5">
            @foreach($trips as $trip)
            @php
                $sold   = $trip->seats_total - $trip->seats_available;
                $gain   = $trip->price_per_seat * $sold;
                $statusColors = [
                    'scheduled' => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',    'text' => 'text-blue-500',   'label' => 'Planifié'],
                    'ongoing'   => ['bg' => 'bg-orange-50 dark:bg-orange-500/10','text' => 'text-orange-500', 'label' => 'En cours'],
                    'completed' => ['bg' => 'bg-primary/10',                     'text' => 'text-primary',    'label' => 'Terminé'],
                ];
                $sc = $statusColors[$trip->status] ?? $statusColors['scheduled'];
            @endphp
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 dark:hover:bg-white/3 transition-colors">

                {{-- Date --}}
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-white/5 flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-base font-black text-slate-900 dark:text-white leading-none">
                        {{ $trip->departure_date->format('d') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400 uppercase">
                        {{ $trip->departure_date->locale('fr')->isoFormat('MMM') }}
                    </span>
                </div>

                {{-- Trajet --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <p class="font-black text-slate-900 dark:text-white text-sm truncate">
                            {{ $trip->departure_city }}
                            <span class="text-slate-400 font-normal">→</span>
                            {{ $trip->arrival_city }}
                        </p>
                        <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $sc['bg'] }} {{ $sc['text'] }}">
                            {{ $sc['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                        <span class="flex items-center gap-1 text-xs text-slate-400 font-medium">
                            <span class="material-symbols-outlined" style="font-size:13px">group</span>
                            {{ $sold }} / {{ $trip->seats_total }} passager{{ $sold > 1 ? 's' : '' }}
                        </span>
                        <span class="flex items-center gap-1 text-xs text-slate-400 font-medium">
                            <span class="material-symbols-outlined" style="font-size:13px">payments</span>
                            {{ number_format($trip->price_per_seat, 0, ',', '\u{202F}') }} FCFA/place
                        </span>
                    </div>
                </div>

                {{-- Gain --}}
                <div class="text-right flex-shrink-0">
                    <p class="font-black text-primary text-base">
                        +{{ number_format($gain, 0, ',', '\u{202F}') }}
                    </p>
                    <p class="text-xs text-slate-400 font-semibold">FCFA</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($trips->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-white/5">
            {{ $trips->links() }}
        </div>
        @endif

        @endif
    </div>

</div>
@endsection

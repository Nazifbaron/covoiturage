@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto flex flex-col items-center gap-6 pt-8 px-4">

    {{-- ── Header retour ── --}}
    <div class="w-full flex items-center gap-3">
        <a href="{{ route('dashboard') }}"
           class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                  flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
        </a>
        <h1 class="text-lg font-black text-slate-900 dark:text-white">Demande envoyée</h1>
    </div>

    {{-- ── Illustration attente ── --}}
    <div class="w-full bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10
                shadow-sm p-8 flex flex-col items-center gap-5 text-center">

        {{-- Icône animée --}}
        <div class="relative">
            <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-5xl">pending</span>
            </div>
            {{-- Pulse --}}
            <span class="absolute inset-0 rounded-full bg-primary/20 animate-ping"></span>
        </div>

        <div class="space-y-2">
            <p class="font-black text-slate-900 dark:text-white text-xl">En attente de confirmation</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Votre demande a bien été envoyée au conducteur.<br>
                La messagerie sera disponible dès qu'il aura accepté votre réservation.
            </p>
        </div>

        {{-- Récapitulatif trajet --}}
        <div class="w-full bg-slate-50 dark:bg-white/5 rounded-xl p-4 space-y-2 text-left">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-base">trip_origin</span>
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $trip->departure_city }}</span>
            </div>
            <div class="w-px h-3 bg-slate-300 dark:bg-slate-600 ml-[9px]"></div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-base">location_on</span>
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $trip->arrival_city }}</span>
            </div>
            <div class="flex items-center gap-2 pt-1">
                <span class="material-symbols-outlined text-slate-400 text-base">calendar_today</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    {{ \Carbon\Carbon::parse($trip->requested_date)->translatedFormat('d F Y') }}
                    à {{ substr($trip->requested_time, 0, 5) }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-base">groups</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    {{ $trip->passengers }} place{{ $trip->passengers > 1 ? 's' : '' }}
                </span>
            </div>
        </div>

        {{-- Statut badge --}}
        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 dark:bg-amber-900/30
                    border border-amber-200 dark:border-amber-700/40">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            <span class="text-xs font-bold text-amber-700 dark:text-amber-400">En attente d'acceptation</span>
        </div>

        {{-- Actions --}}
        <div class="w-full space-y-3 pt-2">
            <a href="{{ route('dashboard') }}"
               class="w-full flex items-center justify-center gap-2
                      bg-primary hover:bg-primary/90 text-background-dark
                      font-black py-3 rounded-xl transition-all shadow-lg shadow-primary/20 text-sm">
                <span class="material-symbols-outlined text-lg">home</span>
                Retour au tableau de bord
            </a>
            <a href="{{ route('passenger.my-requests') }}"
               class="w-full flex items-center justify-center gap-2
                      border border-slate-200 dark:border-white/10
                      bg-white dark:bg-card-dark hover:bg-slate-50 dark:hover:bg-white/5
                      text-slate-700 dark:text-slate-300 font-bold py-3 rounded-xl transition-colors text-sm">
                <span class="material-symbols-outlined text-lg">list_alt</span>
                Voir mes demandes
            </a>
        </div>
    </div>

    {{-- Note info --}}
    <p class="text-xs text-center text-slate-400 dark:text-slate-500">
        Vous recevrez une notification dès que le conducteur aura confirmé votre réservation.
    </p>
</div>
@endsection

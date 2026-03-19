@extends('layouts.admin')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de la plateforme')

@section('content')

{{-- ── Stat cards ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    @foreach([
        ['label' => 'Utilisateurs',   'value' => $stats['users_total'],  'icon' => 'group',          'sub' => $newUsersWeek . ' cette semaine',   'color' => 'bg-blue-50 text-blue-600'],
        ['label' => 'Conducteurs',    'value' => $stats['drivers'],      'icon' => 'directions_car',  'sub' => $stats['vehicles'] . ' véhicules',  'color' => 'bg-indigo-50 text-indigo-600'],
        ['label' => 'Trajets',        'value' => $stats['trips_total'],  'icon' => 'route',           'sub' => $newTripsWeek . ' cette semaine',   'color' => 'bg-emerald-50 text-emerald-600'],
        ['label' => 'Réservations',   'value' => $stats['reservations'], 'icon' => 'bookmark',        'sub' => $stats['res_pending'] . ' en attente', 'color' => 'bg-amber-50 text-amber-600'],
    ] as $card)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</p>
            <div class="w-9 h-9 rounded-lg {{ $card['color'] }} flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size:18px">{{ $card['icon'] }}</span>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($card['value']) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $card['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- ── Deux colonnes : trajets + réservations ── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Statuts trajets --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Statuts des trajets</h3>
        <div class="space-y-3">
            @php
                $tripStatuses = [
                    ['label' => 'Planifiés',  'value' => $stats['trips_scheduled'], 'total' => $stats['trips_total'], 'color' => 'bg-blue-500'],
                    ['label' => 'Terminés',   'value' => $stats['trips_completed'], 'total' => $stats['trips_total'], 'color' => 'bg-emerald-500'],
                    ['label' => 'Annulés',    'value' => $stats['trips_cancelled'], 'total' => $stats['trips_total'], 'color' => 'bg-red-400'],
                ];
            @endphp
            @foreach($tripStatuses as $s)
            @php $pct = $stats['trips_total'] > 0 ? round($s['value'] / $s['total'] * 100) : 0; @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-600">{{ $s['label'] }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ $s['value'] }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                </div>
                <div class="w-full h-2 rounded-full bg-gray-100">
                    <div class="h-2 rounded-full {{ $s['color'] }}" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Statuts réservations --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Statuts des réservations</h3>
        <div class="space-y-3">
            @php
                $resStatuses = [
                    ['label' => 'En attente', 'value' => $stats['res_pending'],  'total' => $stats['reservations'], 'color' => 'bg-amber-400'],
                    ['label' => 'Acceptées',  'value' => $stats['res_accepted'], 'total' => $stats['reservations'], 'color' => 'bg-emerald-500'],
                ];
            @endphp
            @foreach($resStatuses as $s)
            @php $pct = $stats['reservations'] > 0 ? round($s['value'] / $s['total'] * 100) : 0; @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-600">{{ $s['label'] }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ $s['value'] }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                </div>
                <div class="w-full h-2 rounded-full bg-gray-100">
                    <div class="h-2 rounded-full {{ $s['color'] }}" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Répartition conducteurs/passagers --}}
        <div class="mt-5 pt-4 border-t border-gray-100 grid grid-cols-2 gap-3">
            <div class="text-center p-3 rounded-lg bg-gray-50">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['drivers'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Conducteurs</p>
            </div>
            <div class="text-center p-3 rounded-lg bg-gray-50">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['passengers'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Passagers</p>
            </div>
        </div>
    </div>
</div>

{{-- ── Raccourcis ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
    @foreach([
        ['label' => 'Gérer les utilisateurs', 'route' => 'admin.users',        'icon' => 'manage_accounts', 'color' => 'text-blue-600'],
        ['label' => 'Voir les trajets',        'route' => 'admin.trips',        'icon' => 'route',           'color' => 'text-emerald-600'],
        ['label' => 'Réservations',            'route' => 'admin.reservations', 'icon' => 'bookmark',        'color' => 'text-amber-600'],
        ['label' => 'Véhicules',               'route' => 'admin.vehicles',     'icon' => 'two_wheeler',     'color' => 'text-indigo-600'],
    ] as $link)
    <a href="{{ route($link['route']) }}"
       class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3
              hover:border-gray-300 hover:shadow-sm transition-all">
        <span class="material-symbols-outlined {{ $link['color'] }}">{{ $link['icon'] }}</span>
        <span class="text-sm font-semibold text-gray-700">{{ $link['label'] }}</span>
        <span class="material-symbols-outlined text-gray-300 ml-auto" style="font-size:16px">arrow_forward</span>
    </a>
    @endforeach
</div>

@endsection

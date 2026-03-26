@extends('layouts.admin')
@section('page-title', 'Trajets conducteurs')
@section('page-subtitle', $trips->total() . ' trajets au total')

@section('content')

{{-- Filtres --}}
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-48">
        <label class="text-xs font-semibold text-gray-500 block mb-1">Recherche (ville)</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Départ ou arrivée..."
                   class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"/>
        </div>
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Statut</label>
        <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
            <option value="">Tous</option>
            <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Planifiés</option>
            <option value="ongoing"   {{ request('status') === 'ongoing'   ? 'selected' : '' }}>En cours</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminés</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulés</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
        Filtrer
    </button>
    @if(request()->hasAny(['search','status']))
    <a href="{{ route('admin.trips') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
        Réinitialiser
    </a>
    @endif
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Trajet</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden md:table-cell">Conducteur</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden lg:table-cell">Date & Heure</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Places</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Statut</th>
                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($trips as $trip)
            @php
                $statusMap = [
                    'scheduled' => ['label' => 'Planifié',  'class' => 'bg-blue-50 text-blue-700'],
                    'ongoing'   => ['label' => 'En cours',  'class' => 'bg-emerald-50 text-emerald-700'],
                    'completed' => ['label' => 'Terminé',   'class' => 'bg-gray-100 text-gray-600'],
                    'cancelled' => ['label' => 'Annulé',    'class' => 'bg-red-50 text-red-600'],
                ];
                $sc = $statusMap[$trip->status] ?? $statusMap['scheduled'];
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <p class="font-semibold text-gray-900">{{ $trip->departure_city }} → {{ $trip->arrival_city }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ number_format($trip->price_per_seat) }} FCFA / place</p>
                </td>
                <td class="px-4 py-3 hidden md:table-cell">
                    @if($trip->driver)
                    <p class="font-medium text-gray-900 text-xs">{{ $trip->driver->first_name }} {{ $trip->driver->last_name }}</p>
                    <p class="text-xs text-gray-400">{{ $trip->driver->email }}</p>
                    @else
                    <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-600">
                    <p>{{ \Carbon\Carbon::parse($trip->departure_date)->format('d/m/Y') }}</p>
                    <p class="text-gray-400">{{ substr($trip->departure_time, 0, 5) }}</p>
                </td>
                <td class="px-4 py-3 text-xs text-gray-600">
                    {{ $trip->seats_available }}/{{ $trip->seats_total }}
                </td>
                <td class="px-4 py-3">
                    <span class="badge {{ $sc['class'] }}">{{ $sc['label'] }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    @if(in_array($trip->status, ['scheduled', 'ongoing']))
                    <form method="POST" action="{{ route('admin.trips.cancel', $trip->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors"
                                onclick="return confirm('Annuler ce trajet ?')">
                            Annuler
                        </button>
                    </form>
                    @else
                    <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                    <span class="material-symbols-outlined text-3xl block mb-2 text-gray-300">route</span>
                    Aucun trajet trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($trips->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-500">{{ $trips->firstItem() }}–{{ $trips->lastItem() }} sur {{ $trips->total() }}</p>
        <div class="flex gap-1">
            @if($trips->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">←</span>
            @else
                <a href="{{ $trips->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">←</a>
            @endif
            @if($trips->hasMorePages())
                <a href="{{ $trips->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">→</a>
            @else
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">→</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

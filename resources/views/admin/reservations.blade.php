@extends('layouts.admin')
@section('page-title', 'Réservations')
@section('page-subtitle', $reservations->total() . ' réservation' . ($reservations->total() > 1 ? 's' : '') . ' au total')

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl">
    <span class="material-symbols-outlined text-emerald-500" style="font-size:18px">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- Filtres --}}
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-48">
        <label class="text-xs font-semibold text-gray-500 block mb-1">Recherche (passager ou ville)</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, départ ou arrivée..."
                   class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"/>
        </div>
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Statut</label>
        <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
            <option value="">Tous</option>
            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>En attente</option>
            <option value="accepted"  {{ request('status') === 'accepted'  ? 'selected' : '' }}>Acceptées</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées</option>
            <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expirées</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
        Filtrer
    </button>
    @if(request()->hasAny(['search', 'status']))
    <a href="{{ route('admin.reservations') }}"
       class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
        Réinitialiser
    </a>
    @endif
</form>

{{-- Tableau --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Passager</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Trajet</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden md:table-cell">Date & Heure</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden lg:table-cell">Conducteur</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Places</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Statut</th>
                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reservations as $res)
            @php
                $statusMap = [
                    'pending'   => ['label' => 'En attente', 'class' => 'bg-amber-50 text-amber-700'],
                    'accepted'  => ['label' => 'Acceptée',   'class' => 'bg-emerald-50 text-emerald-700'],
                    'cancelled' => ['label' => 'Annulée',    'class' => 'bg-red-50 text-red-600'],
                    'expired'   => ['label' => 'Expirée',    'class' => 'bg-gray-100 text-gray-500'],
                ];
                $sc = $statusMap[$res->status] ?? $statusMap['expired'];
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">

                {{-- Passager --}}
                <td class="px-4 py-3">
                    @if($res->user)
                        <p class="font-semibold text-gray-900 text-xs">{{ $res->user->first_name }} {{ $res->user->last_name }}</p>
                        <p class="text-xs text-gray-400 truncate max-w-[140px]">{{ $res->user->email }}</p>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>

                {{-- Trajet --}}
                <td class="px-4 py-3">
                    <p class="font-semibold text-gray-900">{{ $res->departure_city }} → {{ $res->arrival_city }}</p>
                    @if($res->driverTrip)
                        <p class="text-xs text-gray-400 mt-0.5">{{ number_format($res->driverTrip->price_per_seat) }} FCFA / place</p>
                    @endif
                </td>

                {{-- Date & Heure --}}
                <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-600">
                    <p>{{ $res->requested_date->format('d/m/Y') }}</p>
                    <p class="text-gray-400">{{ substr($res->requested_time, 0, 5) }}</p>
                </td>

                {{-- Conducteur acceptant --}}
                <td class="px-4 py-3 hidden lg:table-cell">
                    @if($res->driver)
                        <p class="font-medium text-gray-900 text-xs">{{ $res->driver->first_name }} {{ $res->driver->last_name }}</p>
                        <p class="text-xs text-gray-400">{{ $res->driver->email }}</p>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>

                {{-- Places --}}
                <td class="px-4 py-3 text-xs font-semibold text-gray-700">
                    {{ $res->passengers }} place{{ $res->passengers > 1 ? 's' : '' }}
                </td>

                {{-- Statut --}}
                <td class="px-4 py-3">
                    <span class="badge {{ $sc['class'] }}">{{ $sc['label'] }}</span>
                    @if($res->expires_at && $res->status === 'pending')
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            Expire {{ $res->expires_at->diffForHumans() }}
                        </p>
                    @endif
                </td>

                {{-- Actions --}}
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Annuler --}}
                        @if(in_array($res->status, ['pending', 'accepted']))
                        <form method="POST" action="{{ route('admin.reservations.cancel', $res->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    onclick="return confirm('Annuler cette réservation ?')"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-amber-200 text-amber-700 hover:bg-amber-50 transition-colors">
                                Annuler
                            </button>
                        </form>
                        @endif

                        {{-- Supprimer --}}
                        <form method="POST" action="{{ route('admin.reservations.delete', $res->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Supprimer définitivement cette réservation ?')"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                    <span class="material-symbols-outlined text-3xl block mb-2 text-gray-300">bookmark_border</span>
                    Aucune réservation trouvée
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($reservations->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-500">
            {{ $reservations->firstItem() }}–{{ $reservations->lastItem() }} sur {{ $reservations->total() }}
        </p>
        <div class="flex gap-1">
            @if($reservations->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">←</span>
            @else
                <a href="{{ $reservations->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">←</a>
            @endif
            @if($reservations->hasMorePages())
                <a href="{{ $reservations->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">→</a>
            @else
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">→</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection

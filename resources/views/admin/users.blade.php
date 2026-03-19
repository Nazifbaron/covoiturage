@extends('layouts.admin')
@section('page-title', 'Utilisateurs')
@section('page-subtitle', $users->total() . ' utilisateurs enregistrés')

@section('content')

{{-- ── Filtres ── --}}
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-48">
        <label class="text-xs font-semibold text-gray-500 block mb-1">Recherche</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, prénom, email..."
                   class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"/>
        </div>
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Rôle</label>
        <select name="role" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
            <option value="">Tous</option>
            <option value="driver"    {{ request('role') === 'driver'    ? 'selected' : '' }}>Conducteurs</option>
            <option value="passenger" {{ request('role') === 'passenger' ? 'selected' : '' }}>Passagers</option>
        </select>
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Statut</label>
        <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
            <option value="">Tous</option>
            <option value="active"  {{ request('status') === 'active'  ? 'selected' : '' }}>Actifs</option>
            <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Bloqués</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
        Filtrer
    </button>
    @if(request()->hasAny(['search','role','status']))
    <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
        Réinitialiser
    </a>
    @endif
</form>

{{-- ── Table ── --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Utilisateur</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Rôle</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden md:table-cell">Activité</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden lg:table-cell">Inscrit le</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Statut</th>
                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors {{ $user->is_blocked ? 'opacity-60' : '' }}">

                {{-- Utilisateur --}}
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0 text-xs font-bold text-gray-600">
                            {{ strtoupper(substr($user->first_name ?? $user->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Rôle --}}
                <td class="px-4 py-3">
                    @if($user->role === 'driver')
                        <span class="badge bg-blue-50 text-blue-700">
                            <span class="material-symbols-outlined mr-1" style="font-size:12px">directions_car</span>Conducteur
                        </span>
                    @else
                        <span class="badge bg-gray-100 text-gray-600">
                            <span class="material-symbols-outlined mr-1" style="font-size:12px">hail</span>Passager
                        </span>
                    @endif
                </td>

                {{-- Activité --}}
                <td class="px-4 py-3 hidden md:table-cell">
                    <div class="text-xs text-gray-500 space-y-0.5">
                        @if($user->role === 'driver')
                            <p>{{ $user->driver_trips_count }} trajet(s) publié(s)</p>
                        @else
                            <p>{{ $user->passenger_trips_count }} réservation(s)</p>
                        @endif
                    </div>
                </td>

                {{-- Date --}}
                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>

                {{-- Statut --}}
                <td class="px-4 py-3">
                    @if($user->is_blocked)
                        <span class="badge bg-red-50 text-red-600">Bloqué</span>
                    @else
                        <span class="badge bg-green-50 text-green-700">Actif</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Bloquer / Débloquer --}}
                       <form method="POST" action="{{ route('admin.users.block', $user->id) }}">
                            @csrf @method('PATCH')
                            @php
                                // Cast explicite en bool pour éviter "1"/"0" string de MySQL
                                $isBlocked = (bool) $user->is_blocked;
                                $label     = $isBlocked ? 'Débloquer' : 'Bloquer';
                                $btnClass  = $isBlocked
                                    ? 'border-green-200 text-green-700 hover:bg-green-50'
                                    : 'border-orange-200 text-orange-600 hover:bg-orange-50';
                            @endphp
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors {{ $btnClass }}"
                                    onclick="return confirm('{{ $label }} {{ $user->first_name }} {{ $user->last_name }} ?')">
                                {{ $label }}
                            </button>
                        </form>

                        {{-- Supprimer --}}
                        <form method="POST" action="{{ route('admin.users.delete', $user->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors"
                                    onclick="return confirm('Supprimer définitivement cet utilisateur ?')">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                    <span class="material-symbols-outlined text-3xl block mb-2 text-gray-300">person_off</span>
                    Aucun utilisateur trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-500">
            {{ $users->firstItem() }}–{{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
        </p>
        <div class="flex gap-1">
            @if($users->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">←</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">←</a>
            @endif
            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">→</a>
            @else
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">→</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection

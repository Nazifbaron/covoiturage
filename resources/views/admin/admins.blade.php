@extends('layouts.admin')
@section('page-title', 'Gestion des administrateurs')
@section('page-subtitle', $admins->count() . ' admin(s) secondaire(s)')

@section('content')

{{-- En-tête avec bouton ajouter --}}
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('admin.admins.create') }}"
       class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
        <span class="material-symbols-outlined" style="font-size:16px">add_circle</span>
        Nouvel administrateur
    </a>
</div>

{{-- Table des admins --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Administrateur</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden md:table-cell">Accès accordés</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden lg:table-cell">Créé le</th>
                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($admins as $admin)
            <tr class="hover:bg-gray-50 transition-colors">

                {{-- Identité --}}
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0 text-xs font-bold text-gray-600">
                            {{ strtoupper(substr($admin->first_name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $admin->first_name }} {{ $admin->last_name }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $admin->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Permissions --}}
                <td class="px-4 py-3 hidden md:table-cell">
                    @php $perms = $admin->getPermissionNames(); @endphp
                    @if($perms->isEmpty())
                        <span class="badge bg-red-50 text-red-500">Aucun accès</span>
                    @else
                        <div class="flex flex-wrap gap-1">
                            @foreach($perms as $perm)
                                @php
                                    $labels = [
                                        'manage_users'        => ['label' => 'Utilisateurs', 'icon' => 'group'],
                                        'manage_trips'        => ['label' => 'Trajets',      'icon' => 'directions_car'],
                                        'manage_vehicles'     => ['label' => 'Véhicules',    'icon' => 'two_wheeler'],
                                        'manage_reservations' => ['label' => 'Réservations', 'icon' => 'bookmark'],
                                    ];
                                    $info = $labels[$perm] ?? ['label' => $perm, 'icon' => 'check'];
                                @endphp
                                <span class="badge bg-green-50 text-green-700">
                                    <span class="material-symbols-outlined mr-0.5" style="font-size:11px">{{ $info['icon'] }}</span>
                                    {{ $info['label'] }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </td>

                {{-- Date --}}
                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500">
                    {{ $admin->created_at->format('d/m/Y') }}
                </td>

                {{-- Actions --}}
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.admins.permissions.edit', $admin->id) }}"
                           class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:13px">key</span>
                            Accès
                        </a>
                        <form method="POST" action="{{ route('admin.admins.delete', $admin->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors"
                                    onclick="return confirm('Supprimer cet administrateur ?')">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-12 text-center text-gray-400 text-sm">
                    <span class="material-symbols-outlined text-3xl block mb-2 text-gray-300">admin_panel_settings</span>
                    Aucun administrateur secondaire.<br>
                    <a href="{{ route('admin.admins.create') }}" class="text-gray-700 font-semibold underline">Créer le premier</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

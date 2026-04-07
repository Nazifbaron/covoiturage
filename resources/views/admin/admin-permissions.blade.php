@extends('layouts.admin')
@section('page-title', 'Gestion des accès')
@section('page-subtitle', $admin->first_name . ' ' . $admin->last_name)

@section('content')

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- En-tête admin --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0 text-sm font-bold text-gray-600">
                {{ strtoupper(substr($admin->first_name ?? '?', 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">{{ $admin->first_name }} {{ $admin->last_name }}</p>
                <p class="text-xs text-gray-400">{{ $admin->email }}</p>
            </div>
            @if($admin->getPermissionNames()->isEmpty())
                <span class="ml-auto badge bg-red-50 text-red-500">Aucun accès</span>
            @else
                <span class="ml-auto badge bg-green-50 text-green-700">
                    {{ $admin->getPermissionNames()->count() }} accès actif(s)
                </span>
            @endif
        </div>

        {{-- Formulaire permissions --}}
        <form method="POST" action="{{ route('admin.admins.permissions.update', $admin->id) }}" class="px-6 py-6">
            @csrf @method('PATCH')

            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Sections accessibles</p>

            <div class="space-y-3">

                @php
                    $permConfig = [
                        'manage_users' => [
                            'label'       => 'Utilisateurs',
                            'description' => 'Voir, bloquer et supprimer les utilisateurs',
                            'icon'        => 'group',
                        ],
                        'manage_trips' => [
                            'label'       => 'Trajets',
                            'description' => 'Voir et annuler les trajets des conducteurs',
                            'icon'        => 'directions_car',
                        ],
                        'manage_vehicles' => [
                            'label'       => 'Véhicules',
                            'description' => 'Approuver, rejeter et supprimer les véhicules',
                            'icon'        => 'two_wheeler',
                        ],
                        'manage_reservations' => [
                            'label'       => 'Réservations',
                            'description' => 'Voir les réservations des passagers',
                            'icon'        => 'bookmark',
                        ],
                    ];
                @endphp

                @foreach($allPermissions as $perm)
                @php $config = $permConfig[$perm]; @endphp
                <label class="flex items-center gap-4 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors
                              {{ $admin->hasPermissionTo($perm) ? 'bg-green-50 border-green-200' : '' }}">
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $perm }}"
                           {{ $admin->hasPermissionTo($perm) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900/10">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-gray-600" style="font-size:16px">{{ $config['icon'] }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $config['label'] }}</p>
                        <p class="text-xs text-gray-400">{{ $config['description'] }}</p>
                    </div>
                </label>
                @endforeach

            </div>

            {{-- Info --}}
            <div class="flex items-start gap-2 mt-5 px-3 py-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
                <span class="material-symbols-outlined text-blue-500 flex-shrink-0" style="font-size:16px">info</span>
                <span>Les sections non cochées seront inaccessibles pour cet administrateur. Les modifications sont effectives immédiatement.</span>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-5">
                <a href="{{ route('admin.admins.list') }}"
                   class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    <span class="material-symbols-outlined" style="font-size:16px">save</span>
                    Enregistrer les accès
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

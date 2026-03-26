@extends('layouts.admin')
@section('page-title', 'Véhicules')
@section('page-subtitle', $vehicles->total() . ' véhicule(s) enregistré(s)')

@section('content')

{{-- ── Flash messages ── --}}
{{-- @if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm font-semibold rounded-xl px-4 py-3 flex items-center gap-2">
    <span class="material-symbols-outlined text-base">check_circle</span>
    {{ session('success') }}
</div>
@endif --}}

{{-- ── Filtres ── --}}
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-48">
        <label class="text-xs font-semibold text-gray-500 block mb-1">Recherche</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Marque, modèle, immatriculation, conducteur..."
                   class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"/>
        </div>
    </div>
    <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Statut</label>
        <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/10">
            <option value="">Tous</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>En attente</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvés</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
        </select>
    </div>
    <button type="submit"
            class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
        Filtrer
    </button>
    @if(request()->hasAny(['search','status']))
    <a href="{{ route('admin.vehicles') }}"
       class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
        Réinitialiser
    </a>
    @endif
</form>

{{-- ── Table ── --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Véhicule</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden md:table-cell">Conducteur</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden lg:table-cell">Immatriculation</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden lg:table-cell">Soumis le</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Statut</th>
                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($vehicles as $vehicle)
            <tr class="hover:bg-gray-50 transition-colors">

                {{-- Véhicule --}}
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-violet-600" style="font-size:18px">
                                {{ $vehicle->type === 'tricycle' ? 'electric_rickshaw' : 'directions_car' }}
                            </span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ $vehicle->type }} · {{ $vehicle->color }}</p>
                        </div>
                    </div>
                </td>

                {{-- Conducteur --}}
                <td class="px-4 py-3 hidden md:table-cell">
                    <p class="font-medium text-gray-900">{{ $vehicle->driver->first_name }} {{ $vehicle->driver->last_name }}</p>
                    <p class="text-xs text-gray-400">{{ $vehicle->driver->email }}</p>
                </td>

                {{-- Immatriculation --}}
                <td class="px-4 py-3 hidden lg:table-cell">
                    <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded">{{ $vehicle->plate }}</span>
                </td>

                {{-- Date --}}
                <td class="px-4 py-3 hidden lg:table-cell text-gray-500 text-xs">
                    {{ $vehicle->created_at->format('d/m/Y') }}
                </td>

                {{-- Statut --}}
                <td class="px-4 py-3">
                    @if($vehicle->status === 'approved')
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined" style="font-size:13px">check_circle</span> Approuvé
                        </span>
                    @elseif($vehicle->status === 'rejected')
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-700 bg-red-100 px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined" style="font-size:13px">cancel</span> Rejeté
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined" style="font-size:13px">schedule</span> En attente
                        </span>
                    @endif
                </td>

                {{-- Actions --}}
                <td class="px-4 py-3 text-right">
                    <button onclick="openModal({{ $vehicle->id }})"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-gray-600 border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="material-symbols-outlined" style="font-size:14px">visibility</span>
                        Détails
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                    <span class="material-symbols-outlined text-4xl block mb-2">directions_car</span>
                    Aucun véhicule trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($vehicles->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $vehicles->links() }}
    </div>
    @endif
</div>


{{-- ══════════════════════════════════════════════════
     Modals détail — un par véhicule
══════════════════════════════════════════════════ --}}
@foreach($vehicles as $vehicle)
<div id="modal-{{ $vehicle->id }}"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this) closeModal({{ $vehicle->id }})">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-violet-600">
                        {{ $vehicle->type === 'tricycle' ? 'electric_rickshaw' : 'directions_car' }}
                    </span>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h2>
                    <p class="text-xs text-gray-400">{{ $vehicle->driver->first_name }} {{ $vehicle->driver->last_name }}</p>
                </div>
            </div>
            <button onclick="closeModal({{ $vehicle->id }})"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-gray-500" style="font-size:20px">close</span>
            </button>
        </div>

        <div class="p-6 space-y-5">

            {{-- Infos véhicule --}}
            <div>
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Informations</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        ['Type',           ucfirst($vehicle->type)],
                        ['Marque',         $vehicle->brand],
                        ['Modèle',         $vehicle->model],
                        ['Couleur',        $vehicle->color],
                        ['Immatriculation',$vehicle->plate],
                        ['Soumis le',      $vehicle->created_at->format('d/m/Y à H:i')],
                    ] as [$label, $value])
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-gray-400 mb-0.5">{{ $label }}</p>
                        <p class="font-semibold text-gray-900 text-sm font-mono">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Documents --}}
            <div>
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Documents</h3>
                <div class="space-y-2">
                    @foreach([
                        ['insurance',         'Assurance',          'verified',    'text-blue-500'],
                        ['registration',      'Carte grise',        'description', 'text-green-500'],
                        ['technical_control', 'Contrôle technique', 'engineering', 'text-orange-500'],
                        ['driver_license',    'Permis de conduire', 'badge',       'text-purple-500'],
                    ] as [$key, $label, $icon, $color])
                    @php $path = $vehicle->{$key.'_path'}; $name = $vehicle->{$key.'_name'}; @endphp
                    <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ $color }}" style="font-size:20px">{{ $icon }}</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $label }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-[220px]">{{ $name ?? '—' }}</p>
                            </div>
                        </div>
                        @if($path)
                        <a href="{{ Storage::url($path) }}" target="_blank"
                           class="flex items-center gap-1 text-xs font-semibold text-violet-600 hover:text-violet-800 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:14px">open_in_new</span>
                            Ouvrir
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Motif de rejet si applicable --}}
            @if($vehicle->status === 'rejected' && $vehicle->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                <p class="text-xs font-bold text-red-600 mb-1">Motif du rejet</p>
                <p class="text-sm text-red-700">{{ $vehicle->rejection_reason }}</p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-1">

                {{-- Approuver --}}
                @if($vehicle->status !== 'approved')
                <form action="{{ route('admin.vehicles.approve', $vehicle) }}" method="POST" class="flex-1">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                                   bg-green-600 hover:bg-green-700 text-white text-sm font-bold
                                   rounded-xl transition-colors">
                        <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                        Approuver
                    </button>
                </form>
                @endif

                {{-- Rejeter --}}
                @if($vehicle->status !== 'rejected')
                <button onclick="toggleRejectForm({{ $vehicle->id }})"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5
                               border-2 border-red-300 text-red-600 hover:bg-red-50
                               text-sm font-bold rounded-xl transition-colors">
                    <span class="material-symbols-outlined" style="font-size:18px">cancel</span>
                    Rejeter
                </button>
                @endif

                {{-- Supprimer --}}
                <form action="{{ route('admin.vehicles.delete', $vehicle) }}" method="POST"
                      onsubmit="return confirm('Supprimer définitivement ce véhicule ?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="flex items-center justify-center gap-1 px-4 py-2.5
                                   border border-gray-200 text-gray-500 hover:bg-gray-50
                                   text-sm font-semibold rounded-xl transition-colors">
                        <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                    </button>
                </form>
            </div>

            {{-- Formulaire de rejet (caché par défaut) --}}
            <div id="reject-form-{{ $vehicle->id }}" class="hidden">
                <form action="{{ route('admin.vehicles.reject', $vehicle) }}" method="POST">
                    @csrf @method('PATCH')
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">
                        Motif du rejet <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" rows="3" required
                              placeholder="Ex : Documents illisibles, assurance expirée…"
                              class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2
                                     focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 resize-none"></textarea>
                    <div class="flex gap-2 mt-2">
                        <button type="submit"
                                class="flex-1 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors">
                            Confirmer le rejet
                        </button>
                        <button type="button" onclick="toggleRejectForm({{ $vehicle->id }})"
                                class="px-4 py-2 border border-gray-200 text-gray-500 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endforeach

<script>
    function openModal(id) {
        const m = document.getElementById('modal-' + id);
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        const m = document.getElementById('modal-' + id);
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = '';
    }
    function toggleRejectForm(id) {
        document.getElementById('reject-form-' + id).classList.toggle('hidden');
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach(m => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            });
            document.body.style.overflow = '';
        }
    });
</script>

@endsection

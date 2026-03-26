@extends('layouts.auth')

@section('title', 'Vérification en cours - Covoiturage Bénin')

@push('styles')
<style>
    .pending-card {
        background: linear-gradient(135deg, rgba(108, 43, 217, 0.05), rgba(139, 92, 246, 0.05));
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .pulse-animation {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="text-center mb-8">
        <div class="w-24 h-24 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mx-auto mb-6 pulse-animation">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-5xl">hourglass_top</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-3">
            Vérification en cours
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-lg">
            Votre véhicule et vos documents sont en cours de vérification
        </p>
    </div>

    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-white/10">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6C2BD9] to-[#8B5CF6] flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">directions_car</span>
                </div>
                <div>
                    <h2 class="font-bold text-slate-900 dark:text-white">Informations du véhicule</h2>
                    <p class="text-xs text-slate-500">Soumis le {{ $vehicle->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Type</p>
                        <p class="font-semibold text-slate-900 dark:text-white">
                            @switch($vehicle->type)
                                @case('moto') Moto @break
                                @case('tricycle') Tricycle @break
                                @case('voiture') Voiture @break
                            @endswitch
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Immatriculation</p>
                        <p class="font-semibold text-slate-900 dark:text-white uppercase">{{ $vehicle->plate }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Marque</p>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $vehicle->brand }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Modèle</p>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $vehicle->model }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Couleur</p>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $vehicle->color }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 dark:border-white/10 p-6">
            <h3 class="font-bold text-sm text-slate-700 dark:text-slate-300 mb-3">Documents soumis</h3>
            <div class="space-y-2">
                @php
                    $documents = [
                        'insurance' => ['label' => 'Assurance', 'icon' => 'verified'],
                        'registration' => ['label' => 'Carte grise', 'icon' => 'description'],
                        'technical_control' => ['label' => 'Contrôle technique', 'icon' => 'engineering'],
                        'driver_license' => ['label' => 'Permis de conduire', 'icon' => 'badge']
                    ];
                @endphp

                @foreach($documents as $key => $doc)
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-white/5">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-500">{{ $doc['icon'] }}</span>
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $doc['label'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($vehicle->{$key . '_path'})
                            <span class="text-xs px-2 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
                                En attente
                            </span>
                            <a href="{{ Storage::url($vehicle->{$key . '_path'}) }}" target="_blank"
                               class="text-[#6C2BD9] hover:text-[#8B5CF6] text-sm">
                                Voir
                            </a>
                        @else
                            <span class="text-xs text-red-500">Manquant</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-amber-50 dark:bg-amber-900/20 p-6 border-t border-amber-100 dark:border-amber-800/30">
            <div class="flex gap-3">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">info</span>
                <div class="text-sm text-amber-700 dark:text-amber-300">
                    <p class="font-semibold mb-1">Délai de vérification</p>
                    <p>Notre équipe vérifie généralement les documents dans un délai de 24 à 48 heures. Vous recevrez une notification dès que votre véhicule sera approuvé.</p>
                </div>
            </div>
        </div>

        <div class="p-6 flex gap-3">
            <a href=""
               class="flex-1 text-center px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                Modifier les informations
            </a>
            <a href="{{ route('dashboard') }}"
               class="flex-1 text-center px-4 py-3 rounded-xl bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-200 dark:hover:bg-white/20 transition-colors">
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection

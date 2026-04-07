@extends('layouts.admin')
@section('page-title', 'Ajouter un administrateur')
@section('page-subtitle', 'Créer un nouveau compte administrateur')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- En-tête --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined" style="font-size:20px;color:#13ec49">admin_panel_settings</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">Nouveau compte admin</p>
                <p class="text-xs text-gray-400">Le compte aura un accès complet au panneau d'administration</p>
            </div>
        </div>

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('admin.admins.store') }}" class="px-6 py-6 space-y-5">
            @csrf

            {{-- Prénom / Nom --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Prénom <span class="text-red-400">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                           placeholder="Ex : Jean"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400
                                  {{ $errors->has('first_name') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                    @error('first_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Nom <span class="text-red-400">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                           placeholder="Ex : Dupont"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400
                                  {{ $errors->has('last_name') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                    @error('last_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="text-xs font-semibold text-gray-500 block mb-1">Adresse email <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">mail</span>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="admin@exemple.com"
                           class="w-full pl-9 pr-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400
                                  {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                </div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Téléphone --}}
            <div>
                <label class="text-xs font-semibold text-gray-500 block mb-1">Téléphone</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">phone</span>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="+229 97 00 00 00"
                           class="w-full pl-9 pr-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400
                                  {{ $errors->has('phone') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                </div>
                @error('phone')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mot de passe --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Mot de passe <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">lock</span>
                        <input type="password" name="password" id="password"
                               placeholder="8 caractères minimum"
                               class="w-full pl-9 pr-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400
                                      {{ $errors->has('password') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 block mb-1">Confirmer le mot de passe <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400" style="font-size:16px">lock_reset</span>
                        <input type="password" name="password_confirmation"
                               placeholder="Retapez le mot de passe"
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="flex items-start gap-2 px-3 py-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
                <span class="material-symbols-outlined text-amber-500 flex-shrink-0" style="font-size:16px">info</span>
                <span>Ce compte aura accès à toutes les fonctionnalités d'administration. Assurez-vous de l'accorder à une personne de confiance.</span>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    <span class="material-symbols-outlined" style="font-size:16px">add_circle</span>
                    Créer l'administrateur
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

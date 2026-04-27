@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6 pb-20 lg:pb-6">

        {{-- ── Header ── --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
                class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                  flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Mon profil</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Gérez vos informations personnelles</p>
            </div>
        </div>

        {{-- ── Avatar + nom ── --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-6">
            <div class="flex items-center gap-5">
                {{-- Photo de profil --}}
                <div class="relative flex-shrink-0 group">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Photo de profil" id="avatarPreview"
                            class="w-20 h-20 rounded-2xl object-cover shadow-lg ring-2 ring-primary/20">
                    @else
                        <div id="avatarFallback"
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-emerald-600
                                flex items-center justify-center shadow-lg shadow-primary/20">
                            <span class="text-3xl font-black text-background-dark">
                                {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                        <img id="avatarPreview"
                            class="w-20 h-20 rounded-2xl object-cover shadow-lg ring-2 ring-primary/20 hidden"
                            alt="Aperçu">
                    @endif
                    {{-- Overlay bouton changer — toujours visible sur mobile --}}
                    <label for="avatarInput"
                        class="absolute inset-0 rounded-2xl bg-black/40
                              opacity-100 sm:opacity-0 sm:group-hover:opacity-100
                              transition-opacity flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-white text-2xl">photo_camera</span>
                    </label>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xl font-black text-slate-900 dark:text-white">{{ $user->first_name }}
                        {{ $user->last_name }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    <span
                        class="inline-flex items-center gap-1 mt-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold
                             bg-primary/10 text-primary">
                        <span class="material-symbols-outlined" style="font-size:12px">
                            {{ auth()->user()->role === 'driver' ? 'directions_car' : 'hail' }}
                        </span>
                        {{ auth()->user()->role === 'driver' ? 'Conducteur' : 'Passager' }}
                    </span>
                    <p class="text-xs text-slate-400 mt-2">
                        <span class="sm:hidden">Appuyez sur la photo pour la modifier</span>
                        <span class="hidden sm:inline">Survolez la photo pour la modifier</span>
                    </p>
                </div>
            </div>

            {{-- Formulaire upload avatar — hors-écran (pas display:none pour iOS) --}}
            <form id="avatarForm" method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data"
                style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;">
                @csrf
                <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/jpg,image/png,image/webp">
            </form>

            @error('avatar')
                <p class="text-xs text-red-500 font-semibold mt-3">{{ $message }}</p>
            @enderror

            @if (session('success') && session('success') === 'Photo de profil mise à jour.')
                <div
                    class="flex items-center gap-2 mt-3 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20">
                    <span class="material-symbols-outlined text-emerald-500 text-lg">check_circle</span>
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Photo de profil mise à jour.</p>
                </div>
            @endif
        </div>

        {{-- ── Informations du profil ── --}}
        <div
            class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-5">
            <div>
                <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">
                    Informations personnelles
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Mettez à jour votre nom et votre adresse email.</p>
            </div>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                {{-- Nom --}}
                <div>
                    <label for="name" class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                        Nom *
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">person</span>
                        <input id="name" name="last_name" type="text"
                            value="{{ old('last_name', $user->last_name) }}" required autocomplete="last_name"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" />
                    </div>
                    @error('last_name')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Prénom --}}
                <div>
                    <label for="first_name" class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                        Prénom *
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">person</span>
                        <input id="first_name" name="first_name" type="text"
                            value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="first_name"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" />
                    </div>
                    @error('first_name')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                        Adresse email *
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">mail</span>
                        <input id="email" name="email" type="email" readonly
                            value="{{ old('email', $user->email) }}" required autocomplete="username"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" />
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Email non vérifié --}}
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div
                            class="flex items-start gap-2.5 p-3 rounded-xl bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 mt-2">
                            <span
                                class="material-symbols-outlined text-orange-500 text-xl flex-shrink-0 mt-0.5">warning</span>
                            <div>
                                <p class="text-xs font-bold text-orange-700 dark:text-orange-400">Email non vérifié</p>
                                <button form="send-verification"
                                    class="text-xs text-orange-600 dark:text-orange-400 underline font-semibold hover:no-underline mt-0.5">
                                    Renvoyer l'email de vérification
                                </button>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-1">
                                        ✓ Lien envoyé à votre adresse email.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-1">
                    @if (session('status') === 'profile-updated')
                        <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            Profil mis à jour
                        </span>
                    @else
                        <span></span>
                    @endif
                    <button type="submit"
                        class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                               font-black px-5 py-2.5 rounded-xl transition-all shadow-md shadow-primary/20 text-sm">
                        <span class="material-symbols-outlined text-lg">save</span>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Mot de passe ── --}}
        <div
            class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-5">
            <div>
                <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">
                    Mot de passe
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Utilisez un mot de passe long et unique pour sécuriser votre
                    compte.</p>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                @foreach ([['id' => 'update_password_current_password', 'name' => 'current_password', 'label' => 'Mot de passe actuel', 'autocomplete' => 'current-password'], ['id' => 'update_password_password', 'name' => 'password', 'label' => 'Nouveau mot de passe', 'autocomplete' => 'new-password'], ['id' => 'update_password_password_confirmation', 'name' => 'password_confirmation', 'label' => 'Confirmer le mot de passe', 'autocomplete' => 'new-password']] as $field)
                    <div>
                        <label for="{{ $field['id'] }}"
                            class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                            {{ $field['label'] }}
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">lock</span>
                            <input id="{{ $field['id'] }}" name="{{ $field['name'] }}" type="password"
                                autocomplete="{{ $field['autocomplete'] }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" />
                        </div>
                        @if ($field['name'] === 'current_password')
                            @error('current_password', 'updatePassword')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        @elseif($field['name'] === 'password')
                            @error('password', 'updatePassword')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        @else
                            @error('password_confirmation', 'updatePassword')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                @endforeach

                <div class="flex items-center justify-between pt-1">
                    @if (session('status') === 'password-updated')
                        <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            Mot de passe mis à jour
                        </span>
                    @else
                        <span></span>
                    @endif
                    <button type="submit"
                        class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                               font-black px-5 py-2.5 rounded-xl transition-all shadow-md shadow-primary/20 text-sm">
                        <span class="material-symbols-outlined text-lg">lock_reset</span>
                        Modifier
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Véhicules (conducteurs uniquement) ── --}}
        @if (auth()->user()->role === 'driver')
            <div
                class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-4">

                {{-- En-tête --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">
                            Mes véhicules
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $vehicles->count() }}/3 véhicule{{ $vehicles->count() > 1 ? 's' : '' }}
                            enregistré{{ $vehicles->count() > 1 ? 's' : '' }}
                        </p>
                    </div>
                    @if ($vehicles->count() < 3)
                        <a href="{{ route('driver.vehicle.setup') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl
                      bg-primary hover:bg-primary/90 text-background-dark
                      font-black text-xs transition-all shadow-md shadow-primary/20">
                            <span class="material-symbols-outlined text-base">add</span>
                            Ajouter un véhicule
                        </a>
                    @endif
                </div>

                {{-- Flash messages --}}
                @if (session('success'))
                    <div
                        class="flex items-center gap-2 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20">
                        <span class="material-symbols-outlined text-emerald-500 text-lg">check_circle</span>
                        <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('warning'))
                    <div
                        class="flex items-center gap-2 p-3 rounded-xl bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20">
                        <span class="material-symbols-outlined text-orange-500 text-lg">warning</span>
                        <p class="text-xs font-bold text-orange-700 dark:text-orange-400">{{ session('warning') }}</p>
                    </div>
                @endif

                {{-- Liste des véhicules --}}
                @if ($vehicles->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <span
                            class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl mb-3">directions_car</span>
                        <p class="font-black text-slate-500 dark:text-slate-400 text-sm">Aucun véhicule enregistré</p>
                        <p class="text-xs text-slate-400 mt-1">Ajoutez un véhicule pour publier des trajets.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($vehicles as $v)
                            @php
                                $statusMap = [
                                    'pending' => [
                                        'label' => 'En attente',
                                        'bg' => 'bg-orange-50 dark:bg-orange-500/10',
                                        'border' => 'border-orange-200 dark:border-orange-500/20',
                                        'text' => 'text-orange-600 dark:text-orange-400',
                                        'dot' => 'bg-orange-400 animate-pulse',
                                        'icon' => 'directions_car',
                                    ],
                                    'approved' => [
                                        'label' => 'Approuvé',
                                        'bg' => 'bg-emerald-50 dark:bg-emerald-500/10',
                                        'border' => 'border-emerald-200 dark:border-emerald-500/20',
                                        'text' => 'text-emerald-600 dark:text-emerald-400',
                                        'dot' => 'bg-emerald-400',
                                        'icon' => 'verified',
                                    ],
                                    'rejected' => [
                                        'label' => 'Rejeté',
                                        'bg' => 'bg-red-50 dark:bg-red-500/10',
                                        'border' => 'border-red-200 dark:border-red-500/20',
                                        'text' => 'text-red-500 dark:text-red-400',
                                        'dot' => 'bg-red-400',
                                        'icon' => 'cancel',
                                    ],
                                ];
                                $st = $statusMap[$v->status] ?? $statusMap['pending'];
                            @endphp
                            <div class="rounded-xl border {{ $st['border'] }} {{ $st['bg'] }} p-4">
                                <div class="flex items-center gap-3">
                                    {{-- Icône véhicule --}}
                                    <div
                                        class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 shadow-sm border border-slate-200 dark:border-white/10 cursor-pointer hover:opacity-80 transition-opacity"
     onclick="openVehiclePhotoModal('{{ asset('storage/' . $v->vehicle_photo_path) }}', '{{ $v->brand . ' ' . $v->model }}', '{{ $v->plate }}')">
                                        @if ($v->vehicle_photo_path)
                                            <img src="{{ asset('storage/' . $v->vehicle_photo_path) }}"
                                                alt="{{ $v->brand . ' ' . $v->model }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full bg-white dark:bg-white/10 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary text-xl">
                                                    {{ $v->type === 'tricycle' ? 'electric_rickshaw' : 'directions_car' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Infos --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="font-black text-slate-900 dark:text-white text-sm">
                                                {{ $v->full_name }}</p>
                                            <span
                                                class="flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-black {{ $st['text'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                                {{ $st['label'] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1 flex-wrap">
                                            <span
                                                class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $v->color }}</span>
                                            <span
                                                class="text-xs font-black text-slate-700 dark:text-slate-300
                                         bg-slate-200 dark:bg-white/10 px-2 py-0.5 rounded-lg tracking-widest">
                                                {{ strtoupper($v->plate) }}
                                            </span>
                                            <span class="text-xs text-slate-400 font-medium">{{ $v->type_label }}</span>
                                        </div>
                                        @if ($v->status === 'rejected' && $v->rejection_reason)
                                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 font-semibold">
                                                Motif : {{ $v->rejection_reason }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        {{-- Bouton upload photo --}}
                                        <label for="photoInput-{{ $v->id }}"
                                            class="w-8 h-8 rounded-xl border border-slate-200 dark:border-white/10
                  flex items-center justify-center cursor-pointer
                  text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10
                  transition-colors"
                                            title="Ajouter une photo">
                                            <span class="material-symbols-outlined text-base">photo_camera</span>
                                        </label>

                                        @if ($v->status !== 'approved')
                                            <form method="POST" action="{{ route('vehicle.destroy', $v->id) }}"
                                                onsubmit="return confirm('Supprimer ce véhicule définitivement ?')"
                                                style="display:contents">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-xl border border-red-200 dark:border-red-500/30
                       flex items-center justify-center
                       text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10
                       transition-colors">
                                                    <span class="material-symbols-outlined text-base">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                {{-- Upload photo véhicule — hors-écran (pas display:none pour iOS) --}}
                @foreach ($vehicles as $v)
                    <form method="POST" action="{{ route('vehicle.photo.update', $v->id) }}"
                        enctype="multipart/form-data" id="photoForm-{{ $v->id }}"
                        style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;">
                        @csrf
                        <input type="file" id="photoInput-{{ $v->id }}" name="vehicle_photo"
                            accept="image/jpeg,image/jpg,image/png,image/webp">
                    </form>
                @endforeach



                {{-- Limite atteinte --}}
                @if ($vehicles->count() >= 3)
                    <p class="text-xs text-slate-400 text-center pt-1">
                        Limite de 3 véhicules atteinte.
                    </p>
                @endif

            </div>
        @endif



        {{-- ── Zone de danger ── --}}
        <div
            class="bg-white dark:bg-card-dark rounded-2xl border border-red-100 dark:border-red-500/20 shadow-sm p-5 space-y-4 pb-8">
            <div>
                <h2 class="font-black text-sm uppercase tracking-widest text-red-400">
                    Zone de danger
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    La suppression est permanente. Toutes vos données seront effacées définitivement.
                </p>
            </div>

            {{-- Bouton d'ouverture modal --}}
            <button id="openDeleteModal"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl
                       border-2 border-red-200 dark:border-red-500/30
                       text-red-500 dark:text-red-400 font-bold text-sm
                       hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                <span class="material-symbols-outlined text-lg">delete_forever</span>
                Supprimer mon compte
            </button>
        </div>

    </div>

    {{-- Modal photo véhicule --}}
    {{-- Modal pour visualiser la photo du véhicule --}}
<div id="vehiclePhotoModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/70 backdrop-blur-sm transition-all duration-300">
    <div class="relative max-w-2xl w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white" id="modalVehicleTitle">Photo du véhicule</h3>
            <button type="button" onclick="closeVehiclePhotoModal()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <span class="material-symbols-outlined text-gray-500 dark:text-gray-400">close</span>
            </button>
        </div>

        {{-- Image --}}
        <div class="p-4 flex items-center justify-center bg-gray-100 dark:bg-gray-900">
            <img id="modalVehicleImage" src="" alt="Photo du véhicule" class="max-w-full max-h-[70vh] object-contain rounded-lg">
        </div>

        {{-- Footer avec infos --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white" id="modalVehicleName"></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" id="modalVehiclePlate"></p>
                </div>
                <button type="button" onclick="closeVehiclePhotoModal()" class="px-4 py-2 rounded-lg bg-primary text-background-dark font-bold text-sm hover:bg-primary/90 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>


    {{-- ── Modal suppression compte ── --}}
    <div id="deleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">

        {{-- Overlay --}}
        <div id="deleteModalOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        {{-- Contenu --}}
        <div class="relative w-full max-w-md bg-white dark:bg-card-dark rounded-2xl shadow-2xl border border-slate-100 dark:border-white/10 p-6 space-y-5
                translate-y-4 transition-transform duration-200"
            id="deleteModalBox">

            <div class="flex items-start gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-red-500 text-2xl">delete_forever</span>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white text-lg leading-tight">Supprimer le compte ?</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Cette action est <strong class="text-red-500">irréversible</strong>. Toutes vos données, trajets et
                        réservations seront supprimés définitivement.
                    </p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="delete_password"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                        Confirmez avec votre mot de passe
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">lock</span>
                        <input id="delete_password" name="password" type="password" placeholder="Votre mot de passe"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all" />
                    </div>
                    @error('password', 'userDeletion')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" id="closeDeleteModal"
                        class="flex-1 py-2.5 rounded-xl border-2 border-slate-200 dark:border-white/10
                               text-slate-600 dark:text-slate-400 font-bold text-sm
                               hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2
                               bg-red-500 hover:bg-red-600 text-white
                               font-black py-2.5 rounded-xl transition-all text-sm shadow-lg shadow-red-500/20">
                        <span class="material-symbols-outlined text-lg">delete_forever</span>
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('[id^="photoInput-"]').forEach(input => {
                input.addEventListener('change', function() {
                    if (!this.files[0]) return;
                    const id = this.id.replace('photoInput-', '');
                    document.getElementById('photoForm-' + id).submit();
                });
            });
            // Avatar — aperçu + soumission automatique
            const avatarInput = document.getElementById('avatarInput');
            const avatarForm = document.getElementById('avatarForm');
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarFallback = document.getElementById('avatarFallback');

            if (avatarInput) {
                avatarInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    // Aperçu immédiat
                    const reader = new FileReader();
                    reader.onload = e => {
                        avatarPreview.src = e.target.result;
                        avatarPreview.classList.remove('hidden');
                        if (avatarFallback) avatarFallback.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                    // Soumission auto
                    avatarForm.submit();
                });
            }

            // Modal photo véhicule
            // Fonctions pour le modal de photo
function openVehiclePhotoModal(imageUrl, vehicleName, vehiclePlate) {
    const modal = document.getElementById('vehiclePhotoModal');
    const modalImage = document.getElementById('modalVehicleImage');
    const modalTitle = document.getElementById('modalVehicleTitle');
    const modalName = document.getElementById('modalVehicleName');
    const modalPlate = document.getElementById('modalVehiclePlate');

    if (!imageUrl || imageUrl.includes('null')) {
        modalImage.src = '';
        modalTitle.textContent = 'Aucune photo disponible';
        modalName.textContent = vehicleName || 'Véhicule';
        modalPlate.textContent = 'Aucune photo uploadée';
    } else {
        modalImage.src = imageUrl;
        modalTitle.textContent = 'Photo du véhicule';
        modalName.textContent = vehicleName || 'Véhicule';
        modalPlate.textContent = vehiclePlate || '';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeVehiclePhotoModal() {
    const modal = document.getElementById('vehiclePhotoModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Fermer le modal avec la touche Echap
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVehiclePhotoModal();
    }
});

// Fermer en cliquant sur l'arrière-plan
document.getElementById('vehiclePhotoModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeVehiclePhotoModal();
    }
});
            // Modal suppression compte
            const modal = document.getElementById('deleteModal');
            const modalBox = document.getElementById('deleteModalBox');
            const overlay = document.getElementById('deleteModalOverlay');

            function openModal() {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                modalBox.classList.remove('translate-y-4');
            }

            function closeModal() {
                modal.classList.add('opacity-0');
                modalBox.classList.add('translate-y-4');
                setTimeout(() => modal.classList.add('pointer-events-none'), 200);
            }

            document.getElementById('openDeleteModal').addEventListener('click', openModal);
            document.getElementById('closeDeleteModal').addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            // Ouvrir automatiquement si erreur de validation
            @if ($errors->userDeletion->isNotEmpty())
                openModal();
            @endif
        </script>
    @endpush
@endsection

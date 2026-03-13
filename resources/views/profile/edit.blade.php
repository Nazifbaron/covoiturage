@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

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
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-emerald-600
                        flex items-center justify-center flex-shrink-0 shadow-lg shadow-primary/20">
                <span class="text-2xl font-black text-background-dark">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>
            <div>
                <p class="text-xl font-black text-slate-900 dark:text-white">{{ $user->name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                <span class="inline-flex items-center gap-1 mt-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold
                             bg-primary/10 text-primary">
                    <span class="material-symbols-outlined" style="font-size:12px">
                        {{ auth()->user()->role === 'driver' ? 'directions_car' : 'hail' }}
                    </span>
                    {{ auth()->user()->role === 'driver' ? 'Conducteur' : 'Passager' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Informations du profil ── --}}
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-5">
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
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">person</span>
                    <input id="name" name="last_name" type="text"
                           value="{{ old('last_name', $user->last_name) }}"
                           required autocomplete="last_name"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
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
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">person</span>
                    <input id="first_name" name="first_name" type="text"
                           value="{{ old('first_name', $user->first_name) }}"
                           required autofocus autocomplete="first_name"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
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
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">mail</span>
                    <input id="email" name="email" type="email" readonly
                           value="{{ old('email', $user->email) }}"
                           required autocomplete="username"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                </div>
                @error('email')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror

                {{-- Email non vérifié --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 mt-2">
                    <span class="material-symbols-outlined text-orange-500 text-xl flex-shrink-0 mt-0.5">warning</span>
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
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-5">
        <div>
            <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">
                Mot de passe
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Utilisez un mot de passe long et unique pour sécuriser votre compte.</p>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            @foreach([
                ['id' => 'update_password_current_password', 'name' => 'current_password', 'label' => 'Mot de passe actuel',    'autocomplete' => 'current-password'],
                ['id' => 'update_password_password',         'name' => 'password',          'label' => 'Nouveau mot de passe',    'autocomplete' => 'new-password'],
                ['id' => 'update_password_password_confirmation', 'name' => 'password_confirmation', 'label' => 'Confirmer le mot de passe', 'autocomplete' => 'new-password'],
            ] as $field)
            <div>
                <label for="{{ $field['id'] }}" class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                    {{ $field['label'] }}
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">lock</span>
                    <input id="{{ $field['id'] }}" name="{{ $field['name'] }}" type="password"
                           autocomplete="{{ $field['autocomplete'] }}"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                </div>
                @if($field['name'] === 'current_password')
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

    {{-- cette section est uniquement visible pour les conducteurs, car les passagers n'ont pas besoin de gérer un véhicule. --}}

        @if(auth()->user()->role === 'driver')
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-5 space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-sm uppercase tracking-widest text-slate-400 dark:text-slate-500">
                    Mon véhicule
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Requis pour publier des trajets.</p>
            </div>
            @if($vehicle)
            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-xs font-black text-primary">
                <span class="material-symbols-outlined" style="font-size:14px">{{ $vehicle->type_icon }}</span>
                {{ $vehicle->type_label }}
            </div>
            @endif
        </div>

        {{-- Affichage véhicule existant --}}
        @if($vehicle)
        <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/10">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/20 to-emerald-500/20
                        border border-primary/20 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary text-2xl">{{ $vehicle->type_icon }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-black text-slate-900 dark:text-white">{{ $vehicle->full_name }}</p>
                <div class="flex items-center gap-3 mt-1 flex-wrap">
                    <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <span class="material-symbols-outlined" style="font-size:13px">palette</span>
                        {{ $vehicle->color }}
                    </span>
                    <span class="flex items-center gap-1 text-xs font-black text-slate-700 dark:text-slate-300
                                 bg-slate-200 dark:bg-white/10 px-2 py-0.5 rounded-lg tracking-widest">
                        {{ strtoupper($vehicle->plate) }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        {{-- Formulaire ajout / modification --}}
        <form method="POST" action="{{ route('vehicle.save') }}" class="space-y-4" id="vehicle-form">
            @csrf

            {{-- Type de véhicule --}}
            <div>
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 block">Type de véhicule *</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(\App\Models\Vehicle::$types as $key => $type)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="{{ $key }}"
                               {{ old('type', $vehicle?->type ?? 'voiture') === $key ? 'checked' : '' }}
                               class="peer sr-only"/>
                        <div class="flex flex-col items-center gap-2 p-3 rounded-xl border-2
                                    border-slate-200 dark:border-white/10
                                    bg-slate-50 dark:bg-white/5
                                    peer-checked:border-primary peer-checked:bg-primary/5
                                    dark:peer-checked:bg-primary/10
                                    transition-all cursor-pointer hover:border-primary/50">
                            <span class="material-symbols-outlined text-slate-400 peer-checked:text-primary text-2xl
                                         dark:text-slate-500" style="font-size:28px">{{ $type['icon'] }}</span>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400
                                         peer-checked:text-primary">{{ $type['label'] }}</span>
                        </div>
                        {{-- Check badge --}}
                        <span class="absolute top-2 right-2 w-4 h-4 rounded-full bg-primary
                                     flex items-center justify-center hidden peer-checked:flex">
                            <span class="material-symbols-outlined text-background-dark" style="font-size:11px">check</span>
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('type') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Marque & Modèle --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Marque *</label>
                    <input type="text" name="brand" value="{{ old('brand', $vehicle?->brand) }}"
                           placeholder="Ex: Toyota"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                    @error('brand') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Modèle *</label>
                    <input type="text" name="model" value="{{ old('model', $vehicle?->model) }}"
                           placeholder="Ex: Corolla"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                    @error('model') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            

            {{-- Couleur & Immatriculation --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Couleur *</label>
                    <input type="text" name="color" value="{{ old('color', $vehicle?->color) }}"
                           placeholder="Ex: Blanc"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                    @error('color') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">Immatriculation *</label>
                    <input type="text" name="plate" value="{{ old('plate', $vehicle?->plate) }}"
                           placeholder="Ex: BJ-1234-AB"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400 uppercase
                                  focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"/>
                    @error('plate') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-1">
                @if (session('status') === 'vehicle-saved')
                    <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        Véhicule enregistré
                    </span>
                @elseif($vehicle)
                    <form method="POST" action="{{ route('vehicle.destroy') }}" onsubmit="return confirm('Supprimer ce véhicule ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="flex items-center gap-1.5 text-xs font-bold text-red-400 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-base">delete</span>
                            Supprimer
                        </button>
                    </form>
                @else
                    <span></span>
                @endif
                <button type="submit"
                        class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-background-dark
                               font-black px-5 py-2.5 rounded-xl transition-all shadow-md shadow-primary/20 text-sm">
                    <span class="material-symbols-outlined text-lg">save</span>
                    {{ $vehicle ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </form>
    </div>
    @endif



    {{-- ── Zone de danger ── --}}
    <div class="bg-white dark:bg-card-dark rounded-2xl border border-red-100 dark:border-red-500/20 shadow-sm p-5 space-y-4 pb-8">
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

{{-- ── Modal suppression compte ── --}}
<div id="deleteModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">

    {{-- Overlay --}}
    <div id="deleteModalOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    {{-- Contenu --}}
    <div class="relative w-full max-w-md bg-white dark:bg-card-dark rounded-2xl shadow-2xl border border-slate-100 dark:border-white/10 p-6 space-y-5
                translate-y-4 transition-transform duration-200" id="deleteModalBox">

        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-red-500 text-2xl">delete_forever</span>
            </div>
            <div>
                <h3 class="font-black text-slate-900 dark:text-white text-lg leading-tight">Supprimer le compte ?</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Cette action est <strong class="text-red-500">irréversible</strong>. Toutes vos données, trajets et réservations seront supprimés définitivement.
                </p>
            </div>
        </div>

        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
            @csrf
            @method('delete')

            <div>
                <label for="delete_password" class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 block">
                    Confirmez avec votre mot de passe
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-xl">lock</span>
                    <input id="delete_password" name="password" type="password"
                           placeholder="Votre mot de passe"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10
                                  bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                  text-sm font-semibold placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all"/>
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
    const modal    = document.getElementById('deleteModal');
    const modalBox = document.getElementById('deleteModalBox');
    const overlay  = document.getElementById('deleteModalOverlay');

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

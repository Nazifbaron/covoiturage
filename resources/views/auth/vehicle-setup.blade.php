@extends('layouts.auth')

@section('title', 'Enregistrer mon véhicule - Covoiturage Bénin')

@push('styles')
<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(16px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .step-content { animation: slideIn 0.3s ease forwards; }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    .animate-spin { animation: spin 1s linear infinite; }

    .type-card, .document-card { min-height: 88px; -webkit-tap-highlight-color: transparent; }
    .document-card             { min-height: 72px; }
    .bottom-action             { padding-bottom: max(1.25rem, env(safe-area-inset-bottom)); }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto py-4 sm:py-8 px-3 sm:px-4">

    {{-- ── Erreurs de validation Laravel ── --}}
    @if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4">
        <ul class="text-sm text-red-600 space-y-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ── Indicateur d'étapes ── --}}
    <div class="flex items-center justify-center gap-3 sm:gap-8 mb-6 sm:mb-8">
        @foreach([['num'=>1,'label'=>'Infos'],['num'=>2,'label'=>'Docs'],['num'=>3,'label'=>'Confirmation']] as $s)
        <div class="flex flex-col items-center gap-1" id="step{{ $s['num'] }}-indicator">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full
                        {{ $s['num'] === 1
                            ? 'bg-gradient-to-r from-[#16a34a] to-[#0891b2] text-white shadow-lg'
                            : 'bg-slate-200 dark:bg-white/10 text-slate-500' }}
                        flex items-center justify-center font-bold text-base sm:text-lg transition-all">
                {{ $s['num'] }}
            </div>
            <span class="text-[10px] sm:text-xs font-semibold
                         {{ $s['num'] === 1 ? 'text-[#16a34a]' : 'text-slate-400' }}">
                {{ $s['label'] }}
            </span>
        </div>
        @if($s['num'] < 3)
        <div class="flex-1 max-w-[2.5rem] sm:max-w-[4rem] h-0.5 bg-slate-200 dark:bg-white/10"
             id="step{{ $s['num'] }}-line"></div>
        @endif
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════
         FORMULAIRE PRINCIPAL — encapsule les 3 étapes
    ══════════════════════════════════════════════════ --}}
    <form id="vehicleForm"
          action="{{ route('driver.vehicle.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">

            {{-- ════════════════════════════════════════
                 Étape 1 : Informations du véhicule
            ════════════════════════════════════════ --}}
            <div id="step1" class="step-content">

                <div class="p-4 sm:p-6">
                    <div class="text-center mb-5 sm:mb-6">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-[#16a34a] to-[#0891b2]
                                    flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <span class="material-symbols-outlined text-white text-2xl sm:text-3xl">directions_car</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
                            Informations de votre véhicule
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 px-2">
                            Visibles par les passagers lors de vos trajets
                        </p>
                    </div>

                    <div class="space-y-4 sm:space-y-5">

                        {{-- Type de véhicule --}}
                        <div>
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-2">
                                Type de véhicule *
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="type-card border-2 rounded-xl p-3 sm:p-4 text-center cursor-pointer
                                            transition-all border-slate-200 dark:border-white/10
                                            hover:border-[#16a34a] hover:bg-green-50 dark:hover:bg-green-900/20
                                            active:scale-[0.97] {{ old('type') === 'tricycle' ? 'border-[#16a34a] bg-green-50' : '' }}"
                                     data-type="tricycle">
                                    <span class="material-symbols-outlined text-3xl sm:text-4xl
                                                 {{ old('type') === 'tricycle' ? 'text-[#16a34a]' : 'text-slate-400' }}">
                                        electric_rickshaw
                                    </span>
                                    <p class="text-sm font-semibold mt-1.5
                                              {{ old('type') === 'tricycle' ? 'text-[#16a34a]' : 'text-slate-700 dark:text-slate-300' }}">
                                        Tricycle
                                    </p>
                                </div>
                                <div class="type-card border-2 rounded-xl p-3 sm:p-4 text-center cursor-pointer
                                            transition-all border-slate-200 dark:border-white/10
                                            hover:border-[#16a34a] hover:bg-green-50 dark:hover:bg-green-900/20
                                            active:scale-[0.97] {{ old('type') === 'voiture' ? 'border-[#16a34a] bg-green-50' : '' }}"
                                     data-type="voiture">
                                    <span class="material-symbols-outlined text-3xl sm:text-4xl
                                                 {{ old('type') === 'voiture' ? 'text-[#16a34a]' : 'text-slate-400' }}">
                                        directions_car
                                    </span>
                                    <p class="text-sm font-semibold mt-1.5
                                              {{ old('type') === 'voiture' ? 'text-[#16a34a]' : 'text-slate-700 dark:text-slate-300' }}">
                                        Voiture
                                    </p>
                                </div>
                            </div>
                            <input type="hidden" name="type" id="vehicleType" value="{{ old('type') }}">
                            <p class="text-xs text-red-500 mt-1.5 text-center {{ $errors->has('type') ? '' : 'hidden' }}" id="typeError">
                                Veuillez sélectionner un type de véhicule
                            </p>
                        </div>

                        {{-- Marque / Modèle --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            @foreach([
                                ['id'=>'brand', 'label'=>'Marque', 'ph'=>'Ex: Toyota, Bajaj, Kia…'],
                                ['id'=>'model', 'label'=>'Modèle', 'ph'=>'Ex: Corolla, CBF, Keke…'],
                            ] as $f)
                            <div>
                                <label for="{{ $f['id'] }}"
                                       class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                    {{ $f['label'] }} *
                                </label>
                                <input type="text"
                                       id="{{ $f['id'] }}"
                                       name="{{ $f['id'] }}"
                                       value="{{ old($f['id']) }}"
                                       placeholder="{{ $f['ph'] }}"
                                       class="w-full px-4 py-3 rounded-xl border
                                              {{ $errors->has($f['id']) ? 'border-red-400' : 'border-slate-200 dark:border-white/10' }}
                                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                              text-sm font-semibold focus:outline-none focus:ring-2
                                              focus:ring-[#16a34a]/30 focus:border-[#16a34a] transition-all">
                            </div>
                            @endforeach
                        </div>

                        {{-- Couleur / Immatriculation --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            @foreach([
                                ['id'=>'color', 'label'=>'Couleur',        'ph'=>'Ex: Blanc, Rouge, Noir…', 'extra'=>''],
                                ['id'=>'plate', 'label'=>'Immatriculation', 'ph'=>'Ex: BJ-1234-AB',         'extra'=>'uppercase'],
                            ] as $f)
                            <div>
                                <label for="{{ $f['id'] }}"
                                       class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-1.5">
                                    {{ $f['label'] }} *
                                </label>
                                <input type="text"
                                       id="{{ $f['id'] }}"
                                       name="{{ $f['id'] }}"
                                       value="{{ old($f['id']) }}"
                                       placeholder="{{ $f['ph'] }}"
                                       class="w-full px-4 py-3 rounded-xl border
                                              {{ $errors->has($f['id']) ? 'border-red-400' : 'border-slate-200 dark:border-white/10' }}
                                              bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white
                                              text-sm font-semibold {{ $f['extra'] }} focus:outline-none focus:ring-2
                                              focus:ring-[#16a34a]/30 focus:border-[#16a34a] transition-all">
                            </div>
                            @endforeach
                        </div>

                        {{-- Photo du véhicule --}}
                        <div>
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-2">
                                Photo du véhicule <span class="text-slate-400 font-normal">(optionnel)</span>
                            </label>
                            <div id="photoCard"
                                 class="border-2 border-dashed border-slate-200 dark:border-white/10 rounded-xl p-4
                                        cursor-pointer transition-all hover:border-[#16a34a] hover:bg-green-50
                                        dark:hover:bg-green-900/20 active:scale-[0.99]"
                                 onclick="document.getElementById('vehicle_photo').click()">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/10
                                                    flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-xl text-slate-400" id="photoIcon">
                                                photo_camera
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-sm">Photo du véhicule</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG · max 5 Mo</p>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1" id="photo-status">
                                        <span class="material-symbols-outlined text-sm">upload</span>Ajouter
                                    </span>
                                </div>
                                <div id="photo-preview" class="mt-2.5 hidden"></div>
                            </div>
                            <input type="file" id="vehicle_photo" name="vehicle_photo"
                                   accept=".jpg,.jpeg,.png" class="hidden">
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-white/10 p-4 sm:p-6 bottom-action flex justify-end">
                    <button type="button" onclick="goToStep2()"
                            class="w-full sm:w-auto px-6 py-3.5 sm:py-3
                                   bg-gradient-to-r from-[#16a34a] to-[#0891b2]
                                   text-white font-bold rounded-xl hover:opacity-90
                                   active:scale-[0.98] transition-all shadow-md shadow-green-500/20">
                        Continuer →
                    </button>
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 Étape 2 : Documents (avec dates d'expiration)
            ════════════════════════════════════════ --}}
            <div id="step2" class="step-content hidden">

                <div class="p-4 sm:p-6">
                    <div class="text-center mb-5 sm:mb-6">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-[#16a34a] to-[#0891b2]
                                    flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <span class="material-symbols-outlined text-white text-2xl sm:text-3xl">description</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
                            Documents du véhicule
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 px-2">
                            Requis pour la vérification de votre dossier
                        </p>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        @php
                            $documents = [
                                'insurance'         => ['label'=>'Assurance véhicule', 'icon'=>'verified',    'color'=>'text-blue-500', 'has_expiry' => true],
                                'registration'      => ['label'=>'Carte grise',         'icon'=>'description', 'color'=>'text-green-500', 'has_expiry' => true],
                                'technical_control' => ['label'=>'Contrôle technique',  'icon'=>'engineering', 'color'=>'text-orange-500', 'has_expiry' => false],
                                'driver_license'    => ['label'=>'Permis de conduire',  'icon'=>'badge',       'color'=>'text-purple-500', 'has_expiry' => false],
                            ];
                        @endphp

                        @foreach($documents as $key => $doc)
                        <div class="border-2 border-dashed rounded-xl overflow-hidden
                                    {{ $errors->has($key) ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-slate-200 dark:border-white/10' }}
                                    transition-all hover:border-[#16a34a]">

                            {{-- Upload card --}}
                            <div class="document-card p-3.5 sm:p-4 cursor-pointer transition-all hover:bg-green-50 dark:hover:bg-green-900/20 active:scale-[0.99]"
                                 data-doc="{{ $key }}">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/10
                                                    flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-xl {{ $doc['color'] }}">
                                                {{ $doc['icon'] }}
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-slate-900 dark:text-white text-sm truncate">
                                                {{ $doc['label'] }}
                                            </h3>
                                            <p class="text-[11px] text-slate-400 mt-0.5">PDF, JPG, PNG · max 5 Mo</p>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-400 flex-shrink-0 flex items-center gap-1"
                                          id="{{ $key }}-status">
                                        <span class="material-symbols-outlined text-sm">upload</span>
                                        Ajouter
                                    </span>
                                </div>
                                <div id="{{ $key }}-preview" class="mt-2.5 hidden"></div>
                            </div>

                            <input type="file"
                                   id="{{ $key }}"
                                   name="{{ $key }}"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="hidden">

                            {{-- Date d'expiration uniquement pour insurance et registration --}}
                            @if($doc['has_expiry'])
                            <div class="p-3.5 sm:p-4 bg-slate-50 dark:bg-white/5 border-t border-slate-200 dark:border-white/10">
                                <label for="{{ $key }}_expires_at" class="text-xs font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                    Date d'expiration *
                                </label>
                                <input type="date"
                                       id="{{ $key }}_expires_at"
                                       name="{{ $key }}_expires_at"
                                       value="{{ old($key . '_expires_at') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-white/10
                                              bg-white dark:bg-white/5 text-slate-900 dark:text-white
                                              text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/30
                                              focus:border-[#16a34a] transition-all">
                                @error($key . '_expires_at')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-white/10 p-4 sm:p-6 bottom-action
                            flex flex-col-reverse sm:flex-row gap-3 sm:justify-between">
                    <button type="button" onclick="goToStep1()"
                            class="w-full sm:w-auto px-6 py-3.5 sm:py-3
                                   border-2 border-slate-200 dark:border-white/10
                                   text-slate-600 dark:text-slate-400 font-bold rounded-xl
                                   hover:bg-slate-50 dark:hover:bg-white/5 active:scale-[0.98] transition-all">
                        ← Retour
                    </button>
                    <button type="button" onclick="goToStep3()"
                            class="w-full sm:w-auto px-6 py-3.5 sm:py-3
                                   bg-gradient-to-r from-[#16a34a] to-[#0891b2]
                                   text-white font-bold rounded-xl hover:opacity-90
                                   active:scale-[0.98] transition-all shadow-md shadow-green-500/20">
                        Vérifier et soumettre →
                    </button>
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 Étape 3 : Confirmation
            ════════════════════════════════════════ --}}
            <div id="step3" class="step-content hidden">

                <div class="p-4 sm:p-6">
                    <div class="text-center mb-5 sm:mb-6">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-[#16a34a] to-[#0891b2]
                                    flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <span class="material-symbols-outlined text-white text-2xl sm:text-3xl">check_circle</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
                            Vérification finale
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 px-2">
                            Vérifiez que toutes les informations sont correctes avant l'envoi
                        </p>
                    </div>

                    <div id="confirmationContent" class="space-y-3 sm:space-y-4">
                        {{-- rempli dynamiquement --}}
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-white/10 p-4 sm:p-6 bottom-action
                            flex flex-col-reverse sm:flex-row gap-3 sm:justify-between">
                    <button type="button" onclick="goToStep2()"
                            class="w-full sm:w-auto px-6 py-3.5 sm:py-3
                                   border-2 border-slate-200 dark:border-white/10
                                   text-slate-600 dark:text-slate-400 font-bold rounded-xl
                                   hover:bg-slate-50 dark:hover:bg-white/5 active:scale-[0.98] transition-all">
                        ← Modifier
                    </button>

                    <button type="submit" id="submitBtn"
                            class="w-full sm:w-auto px-6 py-3.5 sm:py-3
                                   bg-gradient-to-r from-[#16a34a] to-[#0891b2]
                                   text-white font-bold rounded-xl hover:opacity-90
                                   active:scale-[0.98] transition-all shadow-md shadow-green-500/20
                                   flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">check</span>
                        Enregistrer et soumettre
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
    // ── État global des fichiers ───
    let selectedFiles = {
        insurance: null, registration: null,
        technical_control: null, driver_license: null
    };

    // ── Sélection du type de véhicule ────────────────────────────────────
    document.querySelectorAll('.type-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.type-card').forEach(c => {
                c.classList.remove('border-[#16a34a]', 'bg-green-50', 'dark:bg-green-900/20');
                c.querySelector('.material-symbols-outlined').classList.replace('text-[#16a34a]', 'text-slate-400');
                c.querySelector('p').classList.remove('text-[#16a34a]');
                c.querySelector('p').classList.add('text-slate-700', 'dark:text-slate-300');
            });
            this.classList.add('border-[#16a34a]', 'bg-green-50', 'dark:bg-green-900/20');
            this.querySelector('.material-symbols-outlined').classList.replace('text-slate-400', 'text-[#16a34a]');
            const label = this.querySelector('p');
            label.classList.add('text-[#16a34a]');
            label.classList.remove('text-slate-700', 'dark:text-slate-300');
            document.getElementById('vehicleType').value = this.dataset.type;
            document.getElementById('typeError').classList.add('hidden');
        });
    });

    // ── Gestion des documents ────────────────────────────────────────────
    document.querySelectorAll('.document-card').forEach(card => {
        const docType  = card.dataset.doc;
        const fileInput = document.getElementById(docType);

        card.addEventListener('click', e => {
            if (!e.target.closest('.remove-file')) fileInput.click();
        });
        fileInput.addEventListener('change', function () { previewFile(this, docType); });
    });

    function previewFile(input, docType) {
        const file = input.files[0];
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            showToast('Le fichier ne doit pas dépasser 5 Mo', 'error');
            input.value = '';
            return;
        }

        selectedFiles[docType] = file;

        const statusEl = document.getElementById(`${docType}-status`);
        statusEl.innerHTML = `<span class="material-symbols-outlined text-sm">check_circle</span> Ajouté`;
        statusEl.classList.replace('text-slate-400', 'text-green-500');

        const container = document.querySelector(`[data-doc="${docType}"]`).parentElement;
        container.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20', '!border-solid');
        container.classList.remove('border-slate-200', 'dark:border-white/10', 'border-red-500', 'bg-red-50');

        const previewDiv = document.getElementById(`${docType}-preview`);
        previewDiv.classList.remove('hidden');

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                previewDiv.innerHTML = buildPreview(
                    `<img src="${e.target.result}" class="w-16 h-12 object-cover rounded-lg border">`,
                    docType
                );
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.innerHTML = buildPreview(
                `<div class="px-3 py-2 bg-slate-100 dark:bg-white/10 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                    <span class="text-xs truncate max-w-[140px]">${file.name}</span>
                </div>`,
                docType
            );
        }
    }

    function buildPreview(content, docType) {
        return `<div class="relative inline-flex items-center">
            ${content}
            <button type="button" class="remove-file ml-2 w-6 h-6 rounded-full bg-red-500 text-white
                           flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                    onclick="removeFile('${docType}', event)">✕</button>
        </div>`;
    }

    function removeFile(docType, event) {
        event.stopPropagation();
        selectedFiles[docType] = null;
        document.getElementById(docType).value = '';

        const statusEl = document.getElementById(`${docType}-status`);
        statusEl.innerHTML = `<span class="material-symbols-outlined text-sm">upload</span> Ajouter`;
        statusEl.classList.replace('text-green-500', 'text-slate-400');

        const container = document.querySelector(`[data-doc="${docType}"]`).parentElement;
        container.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/20', '!border-solid');
        container.classList.add('border-slate-200', 'dark:border-white/10');

        const previewDiv = document.getElementById(`${docType}-preview`);
        previewDiv.classList.add('hidden');
        previewDiv.innerHTML = '';
    }

    // ── Validation ───────────────────────────────────────────────────────
    function validateStep1() {
        const fields = [
            { id: 'vehicleType', isHidden: true },
            { id: 'brand',  msg: 'Veuillez entrer la marque du véhicule' },
            { id: 'model',  msg: 'Veuillez entrer le modèle du véhicule' },
            { id: 'color',  msg: 'Veuillez entrer la couleur du véhicule' },
            { id: 'plate',  msg: "Veuillez entrer l'immatriculation" },
        ];
        for (const f of fields) {
            if (!document.getElementById(f.id).value.trim()) {
                if (f.isHidden) document.getElementById('typeError').classList.remove('hidden');
                else showToast(f.msg, 'error');
                return false;
            }
        }
        return true;
    }

    function validateStep2() {
        const required = ['insurance', 'registration', 'technical_control', 'driver_license'];
        let valid = true;

        required.forEach(doc => {
            const container = document.querySelector(`[data-doc="${doc}"]`).parentElement;
            if (!selectedFiles[doc]) {
                container.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                showToast(`Veuillez charger le fichier pour ${doc}`, 'error');
                valid = false;
            } else {
                container.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
            }
        });

        // Vérifier les dates d'expiration pour insurance et registration
        const expiryDocs = ['insurance', 'registration'];
        expiryDocs.forEach(doc => {
            const expiryInput = document.getElementById(`${doc}_expires_at`);
            if (expiryInput && !expiryInput.value) {
                showToast(`Veuillez entrer la date d'expiration pour ${doc === 'insurance' ? 'l\'assurance' : 'la carte grise'}`, 'error');
                valid = false;
            }
        });

        return valid;
    }

    // ── Navigation entre étapes ──────────────────────────────────────────
    function updateStepIndicators(current) {
        [1, 2, 3].forEach(s => {
            const indicator = document.getElementById(`step${s}-indicator`);
            const circle    = indicator.querySelector('.rounded-full');
            const label     = indicator.querySelector('span:last-child');
            const line      = document.getElementById(`step${s}-line`);

            circle.className = 'w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-base sm:text-lg transition-all ';
            if (s < current) {
                circle.className += 'bg-green-500 text-white';
                label.className   = 'text-[10px] sm:text-xs font-semibold text-green-500';
                if (line) line.classList.add('bg-green-500');
            } else if (s === current) {
                circle.className += 'bg-gradient-to-r from-[#16a34a] to-[#0891b2] text-white shadow-lg';
                label.className   = 'text-[10px] sm:text-xs font-semibold text-[#16a34a]';
                if (line) line.classList.remove('bg-green-500');
            } else {
                circle.className += 'bg-slate-200 dark:bg-white/10 text-slate-500';
                label.className   = 'text-[10px] sm:text-xs font-semibold text-slate-400';
                if (line) line.classList.remove('bg-green-500');
            }
        });
    }

    function showStep(show, hide1, hide2) {
        [hide1, hide2].filter(Boolean).forEach(id => document.getElementById(id).classList.add('hidden'));
        document.getElementById(show).classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goToStep2() {
        if (!validateStep1()) return;
        showStep('step2', 'step1', 'step3');
        updateStepIndicators(2);
    }

    function goToStep1() {
        showStep('step1', 'step2', 'step3');
        updateStepIndicators(1);
    }

    function goToStep3() {
        if (!validateStep2()) return;

        const typeLabels = { tricycle: 'Tricycle', voiture: 'Voiture' };
        const type       = document.getElementById('vehicleType').value;

        const docNames = {
            insurance: 'Assurance',
            registration: 'Carte grise',
            technical_control: 'Contrôle technique',
            driver_license: 'Permis de conduire'
        };

        const esc = s => s ? String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;') : '';

        // Récupérer les dates d'expiration
        const insuranceExpiry = document.getElementById('insurance_expires_at')?.value || '';
        const registrationExpiry = document.getElementById('registration_expires_at')?.value || '';

        const formatExpiry = (date) => date ? new Date(date).toLocaleDateString('fr-FR') : '';

        const docsHTML = Object.entries(docNames).map(([key, label]) => {
            let extraInfo = '';
            if (key === 'insurance' && insuranceExpiry) {
                extraInfo = `<span class="text-xs text-slate-400 ml-2">(exp. ${formatExpiry(insuranceExpiry)})</span>`;
            } else if (key === 'registration' && registrationExpiry) {
                extraInfo = `<span class="text-xs text-slate-400 ml-2">(exp. ${formatExpiry(registrationExpiry)})</span>`;
            }

            return `
            <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-white/5 last:border-0">
                <span class="text-slate-500 text-sm">${esc(label)}${extraInfo}</span>
                <span class="text-green-500 text-sm font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">check_circle</span> Fourni
                </span>
            </div>`;
        }).join('');

        document.getElementById('confirmationContent').innerHTML = `
            <div class="bg-slate-50 dark:bg-white/5 rounded-xl p-4">
                <h3 class="font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#16a34a]">directions_car</span>
                    Informations du véhicule
                </h3>
                ${[
                    ['Type',           typeLabels[type] ?? type],
                    ['Marque',         document.getElementById('brand').value],
                    ['Modèle',         document.getElementById('model').value],
                    ['Couleur',        document.getElementById('color').value],
                    ['Immatriculation',document.getElementById('plate').value.toUpperCase()],
                ].map(([k, v]) => `
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-white/5 last:border-0">
                    <span class="text-slate-500 text-sm">${esc(k)}</span>
                    <span class="font-semibold text-slate-900 dark:text-white text-sm">${esc(v)}</span>
                </div>`).join('')}
            </div>
            <div class="bg-slate-50 dark:bg-white/5 rounded-xl p-4">
                <h3 class="font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#16a34a]">description</span>
                    Documents
                </h3>
                ${docsHTML}
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl p-3.5 flex gap-3">
                <span class="material-symbols-outlined text-amber-500 flex-shrink-0">info</span>
                <p class="text-xs text-amber-700 dark:text-amber-300">
                    Votre véhicule sera vérifié par notre équipe sous <strong>24 à 48 h</strong>.
                    Vous serez notifié par SMS et e-mail.
                </p>
            </div>`;

        showStep('step3', 'step2', 'step1');
        updateStepIndicators(3);
    }

    // ── Toast minimaliste ────────────────────────────────────────────────
    function showToast(msg, type = 'info') {
        const existing = document.getElementById('toast');
        if (existing) existing.remove();

        const colors = { error: 'bg-red-500', success: 'bg-green-500', info: 'bg-slate-700' };
        const toast  = document.createElement('div');
        toast.id = 'toast';
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50
                           ${colors[type] ?? colors.info} text-white
                           px-5 py-3 rounded-xl shadow-lg text-sm font-semibold
                           flex items-center gap-2 max-w-xs text-center
                           transition-all duration-300 opacity-0`;
        toast.textContent = msg;
        document.body.appendChild(toast);
        requestAnimationFrame(() => toast.classList.replace('opacity-0', 'opacity-100'));
        setTimeout(() => {
            toast.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // ── Loading du bouton submit ─────────────────────────────────────────
    document.getElementById('vehicleForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">progress_activity</span><span>Envoi en cours…</span>';
    });

    // ── Si retour avec erreurs Laravel → afficher la bonne étape ────────
    @if ($errors->hasAny(['type','brand','model','color','plate']))
        updateStepIndicators(1);
    @elseif ($errors->hasAny(['insurance','registration','technical_control','driver_license',
                             'insurance_expires_at','registration_expires_at']))
        showStep('step2', 'step1', 'step3');
        updateStepIndicators(2);
    @endif
</script>
@endsection

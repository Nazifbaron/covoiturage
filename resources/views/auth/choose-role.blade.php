@extends('layouts.auth')

@section('title', 'Choisir mon rôle - Covoiturage Benin')

{{-- ── STYLES SPÉCIFIQUES ── --}}
@section('styles')
<style>
    .role-card {
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .role-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(19, 236, 73, 0.15);
    }
    .role-card.selected {
        border-color: #13ec49 !important;
        box-shadow: 0 0 0 2px #13ec49, 0 20px 40px rgba(19, 236, 73, 0.2);
    }
    .role-card.selected .role-icon-bg {
        background-color: #13ec49;
    }
    .role-card.selected .role-icon-bg span {
        color: #102215;
    }
    .role-card.selected .check-badge {
        opacity: 1;
        transform: scale(1);
    }
    .check-badge {
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .step-done {
        animation: stepPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes stepPop {
        from { transform: scale(0.8); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }
    .btn-ready {
        animation: subtlePulse 2s ease-in-out infinite;
    }
    @keyframes subtlePulse {
        0%,  100% { box-shadow: 0 0 0 0   rgba(19, 236, 73, 0.4); }
        50%        { box-shadow: 0 0 0 8px rgba(19, 236, 73, 0);   }
    }
</style>
@endsection

{{-- ── INDICATEUR D'ÉTAPES dans le header ── --}}
@section('header-extra')
    <div class="flex items-center gap-2 text-sm font-semibold text-slate-500 dark:text-slate-400">
        <span class="step-done flex items-center justify-center w-6 h-6 rounded-full bg-primary text-background-dark text-xs font-black">✓</span>
        <div class="w-8 h-0.5 bg-slate-300 dark:bg-primary/30"></div>
        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-background-dark text-xs font-black">2</span>
        <div class="w-8 h-0.5 bg-slate-200 dark:bg-white/10"></div>
        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 dark:bg-white/10 text-slate-400 text-xs font-black">3</span>
    </div>
@endsection

{{-- ── CONTENT ── --}}
@section('content')
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-16">
        <div class="w-full max-w-2xl space-y-10">

            {{-- Header --}}
            <div class="text-center space-y-3">
              
                <h1 class="text-4xl font-black tracking-tight">
                    Comment comptez-vous <br/>
                    <span class="text-primary">utiliser la plateforme ?</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-lg max-w-md mx-auto">
                    Sélectionnez votre rôle. Vous pourrez toujours changer cela plus tard dans vos paramètres.
                </p>
            </div>

            {{-- Formulaire de sélection --}}
            <form method="POST" action="{{ route('user.role.store') }}" id="roleForm">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value=""/>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Card Passager --}}
                    <div class="role-card rounded-2xl border-2 border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 p-8 flex flex-col gap-5"
                         onclick="selectRole('passenger', this)">
                        <div class="check-badge absolute top-4 right-4 w-7 h-7 rounded-full bg-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-background-dark text-sm font-black">check</span>
                        </div>
                        <div class="role-icon-bg w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/10 flex items-center justify-center transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-300">airline_seat_recline_normal</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black">Passager</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                Trouvez des trajets près de chez vous, rejoignez des conducteurs et économisez sur vos déplacements quotidiens.
                            </p>
                        </div>
                        <ul class="space-y-2 mt-auto">
                            @foreach(['Recherchez des trajets disponibles', 'Réservez en quelques secondes', 'Partagez les frais de carburant'] as $feature)
                                <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <span class="w-5 h-5 rounded-full bg-primary/15 text-primary flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-xs">check</span>
                                    </span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Card Conducteur --}}
                    <div class="role-card rounded-2xl border-2 border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 p-8 flex flex-col gap-5"
                         onclick="selectRole('driver', this)">
                        <div class="check-badge absolute top-4 right-4 w-7 h-7 rounded-full bg-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-background-dark text-sm font-black">check</span>
                        </div>
                        <div class="role-icon-bg w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/10 flex items-center justify-center transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-300">directions_car</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black">Conducteur</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                Proposez des trajets, rentabilisez vos voyages et rencontrez de nouvelles personnes sur la route.
                            </p>
                        </div>
                        <ul class="space-y-2 mt-auto">
                            @foreach(['Publiez vos trajets facilement', 'Gérez vos réservations', 'Réduisez vos frais de route'] as $feature)
                                <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <span class="w-5 h-5 rounded-full bg-primary/15 text-primary flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-xs">check</span>
                                    </span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                {{-- Bouton submit --}}
                <div class="mt-8">
                    <button type="submit" id="submitBtn"
                        class="w-full bg-primary hover:bg-primary/90 text-background-dark py-4 rounded-xl font-black text-lg transition-all shadow-lg shadow-primary/10 flex items-center justify-center gap-2 opacity-40 cursor-not-allowed"
                        disabled>
                        Continuer vers mon tableau de bord
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                    <p id="hintText" class="text-center text-sm text-slate-400 mt-4">
                        Sélectionnez un rôle pour continuer
                    </p>
                </div>
            </form>

        </div>
    </div>
@endsection

{{-- ── SCRIPTS SPÉCIFIQUES ── --}}
@section('scripts')
<script>
    function selectRole(role, cardEl) {
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
        cardEl.classList.add('selected');
        document.getElementById('selectedRole').value = role;

        const btn = document.getElementById('submitBtn');
        btn.disabled = false;
        btn.classList.remove('opacity-40', 'cursor-not-allowed');
        btn.classList.add('btn-ready');

        const label = role === 'passenger' ? 'Passager' : 'Conducteur';
        document.getElementById('hintText').innerHTML =
            `<span class="text-primary font-semibold">${label}</span> sélectionné — Prêt à continuer !`;
    }
</script>
@endsection

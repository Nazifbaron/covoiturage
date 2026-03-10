{{--
    ══════════════════════════════════════════════════
    TOAST NOTIFICATIONS — À coller dans layouts/app.blade.php
    juste avant la balise </body>
    ══════════════════════════════════════════════════

    Utilisation dans les contrôleurs :
        return redirect()->route('dashboard')
            ->with('success', 'Votre demande a été publiée !');
        return redirect()->back()
            ->with('error', 'Une erreur est survenue.');
--}}

{{-- ── Conteneur des toasts (coin haut-droit) ── --}}
<div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none" style="max-width: 360px; width: calc(100vw - 40px);">
</div>

@if(session('success') || session('error') || session('warning') || session('info'))
<script>
(function () {

    const toasts = [
        @if(session('success'))
        { type: 'success', message: @json(session('success')) },
        @endif
        @if(session('error'))
        { type: 'error',   message: @json(session('error')) },
        @endif
        @if(session('warning'))
        { type: 'warning', message: @json(session('warning')) },
        @endif
        @if(session('info'))
        { type: 'info',    message: @json(session('info')) },
        @endif
    ];

    const config = {
        success: {
            icon:    'check_circle',
            bar:     'bg-emerald-500',
            iconCls: 'text-emerald-500',
            bg:      'bg-white dark:bg-card-dark',
            border:  'border-emerald-200 dark:border-emerald-500/30',
        },
        error: {
            icon:    'cancel',
            bar:     'bg-red-500',
            iconCls: 'text-red-500',
            bg:      'bg-white dark:bg-card-dark',
            border:  'border-red-200 dark:border-red-500/30',
        },
        warning: {
            icon:    'warning',
            bar:     'bg-orange-400',
            iconCls: 'text-orange-400',
            bg:      'bg-white dark:bg-card-dark',
            border:  'border-orange-200 dark:border-orange-400/30',
        },
        info: {
            icon:    'info',
            bar:     'bg-blue-500',
            iconCls: 'text-blue-500',
            bg:      'bg-white dark:bg-card-dark',
            border:  'border-blue-200 dark:border-blue-500/30',
        },
    };

    function showToast({ type, message }) {
        const c      = config[type] || config.info;
        const container = document.getElementById('toast-container');

        const toast = document.createElement('div');
        toast.className = [
            'pointer-events-auto relative overflow-hidden',
            'rounded-2xl border shadow-xl',
            'flex items-start gap-3 p-4',
            c.bg, c.border,
            'transition-all duration-300',
            'opacity-0 translate-x-8',
        ].join(' ');
        toast.style.willChange = 'transform, opacity';

        toast.innerHTML = `
            <!-- Barre colorée gauche -->
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl ${c.bar}"></div>

            <!-- Icône -->
            <span class="material-symbols-outlined filled ${c.iconCls} text-2xl flex-shrink-0 mt-0.5">${c.icon}</span>

            <!-- Texte -->
            <p class="flex-1 text-sm font-semibold text-slate-800 dark:text-slate-100 leading-snug pr-2">${message}</p>

            <!-- Bouton fermer -->
            <button onclick="this.closest('[data-toast]').remove()"
                    class="flex-shrink-0 w-6 h-6 rounded-lg flex items-center justify-center
                           text-slate-400 hover:text-slate-600 hover:bg-slate-100
                           dark:hover:text-slate-200 dark:hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined" style="font-size:16px">close</span>
            </button>

            <!-- Barre de progression -->
            <div class="progress-bar absolute bottom-0 left-0 h-0.5 ${c.bar} opacity-40"
                 style="width:100%; transition: width linear"></div>
        `;

        toast.setAttribute('data-toast', '');
        container.appendChild(toast);

        // Animation entrée
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-x-8');
                toast.classList.add('opacity-100', 'translate-x-0');
            });
        });

        // Barre de progression
        const bar = toast.querySelector('.progress-bar');
        const duration = 4500;

        setTimeout(() => {
            bar.style.transitionDuration = duration + 'ms';
            bar.style.width = '0%';
        }, 50);

        // Auto-fermeture
        const timer = setTimeout(() => dismiss(toast), duration + 100);

        // Pause au hover
        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            bar.style.transitionDuration = '0ms';
        });
        toast.addEventListener('mouseleave', () => {
            const remaining = (parseFloat(bar.style.width) / 100) * duration;
            bar.style.transitionDuration = remaining + 'ms';
            bar.style.width = '0%';
            setTimeout(() => dismiss(toast), remaining + 100);
        });
    }

    function dismiss(toast) {
        toast.classList.remove('opacity-100', 'translate-x-0');
        toast.classList.add('opacity-0', 'translate-x-8');
        setTimeout(() => toast.remove(), 300);
    }

    // Afficher tous les toasts avec un léger décalage
    toasts.forEach((t, i) => setTimeout(() => showToast(t), i * 150));

})();
</script>
@endif

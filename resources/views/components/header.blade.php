<header class="sticky top-0 z-[100] w-full border-b border-slate-200 dark:border-white/10 bg-white/70 dark:bg-background-dark/70 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between relative">

        {{-- Logo --}}
        <div class="flex items-center shrink-0">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Covoiturage Bénin" class="w-14 h-14 sm:w-20 sm:h-20 object-contain">
            </a>
        </div>

        {{-- Nav desktop --}}
        <nav class="hidden lg:flex items-center gap-8 xl:gap-12 absolute left-1/2 -translate-x-1/2">
            <a href="/"       class="text-sm font-semibold transition-colors {{ request()->is('/')       ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">Accueil</a>
            <a href="/result" class="text-sm font-semibold transition-colors {{ request()->is('search.results') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">Trouver un trajet</a>
            <a href="/marche" class="text-sm font-semibold transition-colors {{ request()->is('marche') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">Comment ça marche</a>
            <a href="/about"  class="text-sm font-semibold transition-colors {{ request()->is('about')  ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">À propos</a>
            <a href="/contact"class="text-sm font-semibold transition-colors {{ request()->is('contact')? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}">Contact</a>
        </nav>

        {{-- Actions droite --}}
        <div class="flex items-center gap-2">
            {{-- Theme toggle --}}
            <button aria-label="Changer le thème"
                    onclick="document.body.classList.toggle('dark')"
                    class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition-all text-slate-500 dark:text-slate-400">
                <span class="material-symbols-outlined light-icon text-xl">light_mode</span>
                <span class="material-symbols-outlined dark-icon text-xl">dark_mode</span>
            </button>

            {{-- Bouton connexion desktop --}}
            <div class="hidden sm:flex items-center gap-3">
                @guest
                    <a href="/login"
                       class="bg-primary text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-secondary transition-all shadow-sm hover:shadow-md">
                        Se connecter
                    </a>
                @endguest
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300 text-sm font-bold px-4 py-2 rounded-lg hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 transition-all">
                            Déconnexion
                        </button>
                    </form>
                @endauth
            </div>

            {{-- Burger button --}}
            <button id="mobileMenuBtn"
                    aria-label="Ouvrir le menu"
                    aria-expanded="false"
                    class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition-all text-slate-500 dark:text-slate-400">
                <span id="menuIconOpen"  class="material-symbols-outlined text-2xl">menu</span>
                <span id="menuIconClose" class="material-symbols-outlined text-2xl hidden">close</span>
            </button>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div id="mobileMenu"
         class="hidden lg:hidden border-t border-slate-100 dark:border-white/10 bg-white dark:bg-background-dark shadow-xl">
        <nav class="flex flex-col px-4 py-4 gap-1">
            <a href="/"        class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->is('/')        ? 'bg-primary/10 text-primary' : 'hover:bg-slate-100 dark:hover:bg-white/5 text-slate-700 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-xl">home</span> Accueil
            </a>
            <a href="/search"  class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->is('search')  ? 'bg-primary/10 text-primary' : 'hover:bg-slate-100 dark:hover:bg-white/5 text-slate-700 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-xl">search</span> Trouver un trajet
            </a>
            <a href="/marche"  class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->is('marche')  ? 'bg-primary/10 text-primary' : 'hover:bg-slate-100 dark:hover:bg-white/5 text-slate-700 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-xl">help_outline</span> Comment ça marche
            </a>
            <a href="/about"   class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->is('about')   ? 'bg-primary/10 text-primary' : 'hover:bg-slate-100 dark:hover:bg-white/5 text-slate-700 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-xl">info</span> À propos
            </a>
            <a href="/contact" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->is('contact') ? 'bg-primary/10 text-primary' : 'hover:bg-slate-100 dark:hover:bg-white/5 text-slate-700 dark:text-slate-300' }}">
                <span class="material-symbols-outlined text-xl">mail</span> Contact
            </a>
        </nav>

        <div class="px-4 pb-5 pt-1 border-t border-slate-100 dark:border-white/5">
            @guest
                <a href="/login"
                   class="flex items-center justify-center gap-2 w-full py-3.5 font-bold bg-primary text-white rounded-xl hover:bg-secondary transition-all text-sm shadow-md shadow-primary/20">
                    <span class="material-symbols-outlined text-xl">login</span>
                    Se connecter
                </a>
            @endguest
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center justify-center gap-2 w-full py-3.5 font-bold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-100 transition-all text-sm">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        Déconnexion
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>

<script>
(function () {
    const btn       = document.getElementById('mobileMenuBtn');
    const menu      = document.getElementById('mobileMenu');
    const iconOpen  = document.getElementById('menuIconOpen');
    const iconClose = document.getElementById('menuIconClose');

    btn.addEventListener('click', function () {
        const isOpen = !menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
        btn.setAttribute('aria-expanded', String(!isOpen));
    });

    // Fermer en cliquant en dehors
    document.addEventListener('click', function (e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>

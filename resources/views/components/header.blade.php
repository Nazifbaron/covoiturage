<header class="sticky top-0 z-[100] w-full border-b border-slate-200 dark:border-white/10 bg-white/70 dark:bg-background-dark/70 backdrop-blur-md ">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between relative ">
                <div class="flex items-center gap-2 shrink-0 px-2">

                    <div class="p-1.5 rounded-lg flex items-center justify-center">
                        <!--<span class="material-symbols-outlined text-background-dark font-bold">directions_car</span>-->
                        <img src="{{ asset('images/logo.png')}}" alt="" class="w-20 h-20 object-contain">
                    </div>

                    <h2 class="text-deep-blue dark:text-white text-2xl font-extrabold tracking-tight">Covoiturage</h2>
                </div>
                <nav class="hidden lg:flex items-center gap-8 absolute left-1/2 -translate-x-1/2">
                    <a href="/" class="text-sm font-semibold transition-colors {{ request()->is('/') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-primary' }}"> Accueil </a>
                    <a href="/search" class="text-sm font-semibold transition-colors {{ request()->is('search') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-secondary' }}"> Trouver un trajet </a>
                    <a href="/marche" class="text-sm font-semibold transition-colors {{ request()->is('marche') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-secondary' }}"> Comment ça marche</a>
                    <a href="/about" class="text-sm font-semibold transition-colors {{ request()->is('about') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-secondary' }}"> A propos </a>
                    <a href="/contact" class="text-sm font-semibold transition-colors {{ request()->is('contact') ? 'text-primary border-b-2 border-primary pb-1' : 'hover:text-secondary' }}"> Contact </a>
                </nav>
                <div class="flex items-center gap-2 md:gap-4">
                    <button aria-label="Toggle theme" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition-all text-slate-500 dark:text-slate-400" onclick="document.body.classList.toggle('dark')">
                        <span class="material-symbols-outlined light-icon">light_mode</span>
                        <span class="material-symbols-outlined dark-icon">dark_mode</span>
                    </button>
                    <div class="hidden sm:flex items-center gap-3 px-2">
                        @guest
                            <a class="bg-primary text-white hover:bg-accent text-sm font-bold px-5 py-2.5 rounded-lg hover:shadow-lg  transition-all" href="/login">Se connecter</a>
                        @endguest
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-primary-button>Déconnexion</x-primary-button>
                            </form>
                        @endauth
                    </div>
                    <input class="hidden" id="mobile-menu-toggle" type="checkbox"/>
                    <label class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 text-slate-500 dark:text-slate-400" for="mobile-menu-toggle">
                        <span class="material-symbols-outlined menu-open">menu</span>
                        <span class="material-symbols-outlined menu-close hidden">close</span>
                    </label>
                    <div class="hidden absolute top-20 left-0 w-full bg-white dark:bg-charcoal border-b border-slate-200 dark:border-white/10 flex-col p-6 gap-6 lg:hidden shadow-xl" id="mobile-menu">
                        <nav class="flex flex-col gap-4">
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="/">Accueil</a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Trouver un trajet</a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Comment ça marche</a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="/about">A propos</a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="/contact">Contact</a>
                        </nav>
                        <hr class="border-slate-100 dark:border-white/5"/>
                        <div class="flex flex-col gap-3">
                            @guest
                                <a class="w-full text-center py-4 font-bold bg-primary text-white hover:bg-accent rounded-xl" href="/login">Se connecter</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </header>

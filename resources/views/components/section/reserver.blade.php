<section id="reserver" class="relative min-h-[700px] flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 z-0 overflow-hidden">
                    <div class="carousel-track h-full">
                        <div class="w-1/3 h-full relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 z-10"></div>
                            <img alt="Diverse friends in car" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDixtjMIdazC3WKIJXJIPUt7fdceA64LRT9InOMQ8ig8uSM0BO5bDxu2J0H5SxLLXB90pSm7a9hSNlDxRCqeuRcJiCfmw5jGJ1fDloGAVbxVKbuFAaCivT7dooBh9UtaA4JhdvW3RPWEU1O75uEDUFvGChV6gqutFX2ZacqvRHm77fgvH-p3ygBWLWQU7MJ8S5-buETrlHwwVbncJ5ZYkVkbvk5TFMDuAWApvEY_r3IoOiHaxklLWf86o_arsbW4P_FPH6A5Yrdb9qG"/>
                        </div>
                        <div class="w-1/3 h-full relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 z-10"></div>
                            <img alt="Scenic highway sunset" class="w-full h-full object-cover" src="{{ asset('images/carousel/amazon.jpg') }}"/>
                        </div>
                        <div class="w-1/3 h-full relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 z-10"></div>
                            <img alt="Friendly handshake" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAY24EbjPtNOzbDxbW3Hk4B-uXepWGnEYjt9lQTCVX6zmZUzImBIAUslt27wZuvdIaldZ6zk6-iryxo7v36gD_BOdH_Si-nRNuvK6b-j1dZC22EDZeVcVx7ZQN-geZS1djYHtZdJLhv60wa9G4omg24kUDwArzhnqSgZQ0T7cKSwvYXQXkHo6jRVtNgbL9FdWlTvsVKPGfTNGhOrH3HqQRIUBgsFXK_TTiM1NgcryKT4BmEGa4RDlF728w7UdCxfsKYEWXtfEe8aZTl"/>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 z-20 px-6 flex justify-between pointer-events-none">
                    <button class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white transition-all pointer-events-auto border border-white/10">
                    <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white transition-all pointer-events-auto border border-white/10">
                    <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3">
                    <div class="pagination-dot dot-1"></div>
                    <div class="pagination-dot dot-2"></div>
                    <div class="pagination-dot dot-3"></div>
                </div>
                <div class="relative z-10 max-w-5xl w-full px-6 py-20">
                    <div class="text-center mb-12">
                        <h1 class="text-white text-5xl md:text-7xl font-extrabold mb-6 leading-tight drop-shadow-lg">Votre prochain voyage, <span class="text-primary">partagé.</span></h1>
                        <p class="text-slate-100 text-lg md:text-xl max-w-2xl mx-auto font-medium drop-shadow-md">Entrez en contact avec des chauffeurs fiables et économisez sur vos frais de déplacement tout en réduisant votre empreinte carbone.</p>
                    </div>
                    <div class="bg-white dark:bg-charcoal p-3 rounded-2xl shadow-2xl flex flex-col md:flex-row items-stretch gap-2">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-2">
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[16px]">location_on</span>
                                <input class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Ville de départ" type="text"/>
                            </div>
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">near_me</span>
                                <input class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Ville de destination" type="text"/>
                            </div>
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">calendar_month</span>
                                <input class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Aujourd'hui" type="text"/>
                            </div>
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">person</span>
                                <select class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium appearance-none">
                                    <option>1 passager</option>
                                    <option>2 passagers</option>
                                    <option>3 passagers</option>
                                    <option>4+ passagers</option>
                                </select>
                            </div>
                        </div>
                        <button class="bg-primary text-background-dark font-bold px-10 py-5 rounded-xl hover:bg-primary/90 transition-all text-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span> Rechercher
                        </button>
                    </div>
                </div>
            </section>

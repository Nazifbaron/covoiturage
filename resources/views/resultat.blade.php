<x-layout>
    <x-header></x-header>
    <main class="max-w-7xl mx-auto w-full px-6 lg:px-20 py-8">
                <!-- Résumé de la recherche -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                    <div>
                        <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-primary mb-2">
                            <span>Home</span>
                            <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                            <span class="text-slate-400">Résultats de recherche</span>
                        </nav>
                        <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                            Cotonou <span class="text-primary">→</span> Calavi
                        </h1>
                        <p class="mt-2 text-slate-500 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">calendar_today</span> 1 Mar, 2026
                            <span class="mx-1">•</span>
                            <span class="material-symbols-outlined text-sm">person</span> 1 passager
                        </p>
                    </div>
                    <a href="/search">
                        <button class="flex items-center gap-2 bg-primary text-slate-900 px-6 py-3 rounded-xl font-bold hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-xl">edit_calendar</span>
                            Modifier la recherche
                        </button>
                    </a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Filtres de la barre latérale -->
                    <aside class="lg:col-span-3 space-y-8">
                        <div class="bg-white dark:bg-background-dark/50 p-6 rounded-2xl border border-primary/5 shadow-sm">
                            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">tune</span> Filtres
                            </h3>
                            <!-- Price Range -->
                            <div class="mb-8">
                                <label class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 block">Gamme de prix</label>
                                <div class="relative h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full mb-6">
                                    <div class="absolute h-full bg-primary rounded-full left-[20%] right-[30%]"></div>
                                    <div class="absolute top-1/2 -translate-y-1/2 left-[20%] size-5 bg-white border-2 border-primary rounded-full shadow-md cursor-pointer"></div>
                                    <div class="absolute top-1/2 -translate-y-1/2 right-[30%] size-5 bg-white border-2 border-primary rounded-full shadow-md cursor-pointer"></div>
                                </div>
                                <div class="flex justify-between text-sm font-bold">
                                    <span>1000F CFA</span>
                                    <span>1500F CFA</span>
                                </div>
                            </div>
                            <!-- Departure Time -->
                            <div class="mb-8">
                                <label class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 block">Heure de départ</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button class="p-2 text-xs font-bold border-2 border-primary/20 rounded-lg hover:border-primary transition-all">Matin</button>
                                    <button class="p-2 text-xs font-bold border-2 border-primary bg-primary/10 rounded-lg">Après-midi</button>
                                    <button class="p-2 text-xs font-bold border-2 border-primary/20 rounded-lg hover:border-primary transition-all">Soirée</button>
                                    <button class="p-2 text-xs font-bold border-2 border-primary/20 rounded-lg hover:border-primary transition-all">Nuit</button>
                                </div>
                            </div>
                            <!-- Comfort Level -->
                            <div class="mb-8">
                                <label class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 block">Niveau de confort</label>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5" type="checkbox"/>
                                        <span class="text-sm font-medium group-hover:text-primary transition-colors">Max 2 à l'arrière</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5" type="checkbox"/>
                                        <span class="text-sm font-medium group-hover:text-primary transition-colors">Climatisation</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5" type="checkbox"/>
                                        <span class="text-sm font-medium group-hover:text-primary transition-colors">Animaux acceptés</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Driver Rating -->
                            <div>
                                <label class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 block">Note minimale</label>
                                <div class="flex items-center gap-1 text-primary">
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined">star</span>
                                    <span class="text-slate-400 text-sm font-bold ml-2">4.0+</span>
                                </div>
                            </div>
                            <!-- Filter Actions -->
                            <div class="pt-6 border-t border-primary/10 flex flex-col gap-3">

                                <button
                                    class="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                                    <span class="material-symbols-outlined">filter_alt</span>
                                    Appliquer les filtres
                                </button>

                            </div>
                        </div>
                    </aside>
                    <!-- Results Area -->
                    <div class="lg:col-span-9 space-y-6">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-bold text-slate-500">12 attractions disponibles</p>
                            <!--<div class="flex items-center gap-2 text-sm font-bold cursor-pointer">
                                                Trier par: <span class="text-primary">Cheapest</span>
                                <span class="material-symbols-outlined text-base">expand_more</span>
                            </div> -->
                        </div>
                        <!-- Trip Card 1 -->
                        <div class="bg-white dark:bg-background-dark/50 rounded-2xl border border-primary/10 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all group cursor-pointer overflow-hidden">
                            <div class="flex flex-col md:flex-row">
                                <div class="flex-1 p-6">
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="flex items-center gap-3">
                                            <div class="size-12 rounded-xl bg-slate-100 overflow-hidden border-2 border-primary/20" data-alt="Professional portrait of a male driver" style="background-image: url('{{ asset('images/avatar/ava.png') }}'); background-size: cover;">
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-lg">Thomas B.</h4>
                                                <div class="flex items-center gap-1 text-primary text-sm font-bold">
                                                    <span class="material-symbols-outlined text-sm fill-1">star</span> 4.9 <span class="text-slate-400 font-normal">(128 avis)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-black text-primary">20F CFA</div>
                                            <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">par siège</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                                        <div class="col-span-2 flex items-center gap-6">
                                            <div class="text-center">
                                                <div class="text-xl font-extrabold">08:30</div>
                                                <div class="text-xs font-bold text-slate-400">Cotonou</div>
                                            </div>
                                            <div class="flex-1 relative flex flex-col items-center">
                                                <div class="w-full h-px bg-slate-200 border-dashed border-b-2 border-slate-300 relative">
                                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 size-2 bg-primary rounded-full"></div>
                                                    <div class="absolute right-0 top-1/2 -translate-y-1/2 size-2 bg-primary rounded-full"></div>
                                                    <span class="absolute left-1/2 -translate-x-1/2 -top-6 material-symbols-outlined text-primary text-xl">directions_car</span>
                                                </div>
                                                <span class="mt-2 text-[10px] font-black uppercase text-slate-400 tracking-widest">4h 15m temps de trajet</span>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xl font-extrabold">12:45</div>
                                                <div class="text-xs font-bold text-slate-400">Calavi</div>
                                            </div>
                                        </div>
                                        <div class="bg-primary/5 rounded-xl p-3 border border-primary/10">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-bold text-slate-500">Places restantes</span>
                                                <span class="text-sm font-black text-primary">2/4</span>
                                            </div>
                                            <div class="flex gap-1">
                                                <div class="h-1.5 flex-1 bg-primary rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-primary rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-slate-200 rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-slate-200 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-56 flex flex-col">
                                    <!-- Map preview -->
                                    <div class="bg-slate-100 relative min-h-[120px]">
                                        <div class="absolute inset-0 grayscale hover:grayscale-0 transition-all duration-500"
                                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDwTV9Q84aDOUgXVkXPzeha1SHVFWOiG2NtcESPN7pu18IEpS23zfMHq9N_vtGy2FZout3Ji5njPvoxk_sI8b3tQ2K4sTelwIOOQDyjeLKMTBWqXIo_xjaq1QtaJjD1m3tb_d4f47SQMlf8-v5zKGa2tlK6DB5jV2w8C62cjTC2c5Jx0N_XITwfYQvA-pfghvF6umzkchO_xDA19gB8eHTjQiuwrqJ9LI6EnUBLRV3jdGGkc8-U_zJNh0DCsXfE8axv2eOn3YZzPk0L');
                                            background-size: cover;">
                                        </div>
                                        <div class="absolute inset-0 bg-primary/10"></div>
                                        <div class="absolute bottom-3 left-3 bg-white/90 px-2 py-1 rounded text-[10px] font-bold uppercase">
                                            Aperçu de l'itinéraire
                                        </div>
                                    </div>
                                    <!-- Reserve button -->
                                    <button class="mt-4 bg-primary text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                                        Réserver
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Trip Card 2 -->
                        <div class="bg-white dark:bg-background-dark/50 rounded-2xl border border-primary/10 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all group cursor-pointer overflow-hidden">
                            <div class="flex flex-col md:flex-row">
                                <div class="flex-1 p-6">
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="flex items-center gap-3">
                                            <div class="size-12 rounded-xl bg-slate-100 overflow-hidden border-2 border-primary/20" data-alt="User profile photo of a friendly woman" style="background-image: url('{{ asset('images/avatar/avata.png')}}'); background-size: cover;"></div>
                                            <div>
                                                <h4 class="font-bold text-lg">Sophie L.</h4>
                                                <div class="flex items-center gap-1 text-primary text-sm font-bold">
                                                    <span class="material-symbols-outlined text-sm fill-1">star</span> 5.0 <span class="text-slate-400 font-normal">(42 avis)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-black text-primary">18F CFA</div>
                                            <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">par siège</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                                        <div class="col-span-2 flex items-center gap-6">
                                            <div class="text-center">
                                                <div class="text-xl font-extrabold">14:15</div>
                                                <div class="text-xs font-bold text-slate-400">Cotonou</div>
                                            </div>
                                            <div class="flex-1 relative flex flex-col items-center">
                                                <div class="w-full h-px bg-slate-200 border-dashed border-b-2 border-slate-300 relative">
                                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 size-2 bg-primary rounded-full"></div>
                                                    <div class="absolute right-0 top-1/2 -translate-y-1/2 size-2 bg-primary rounded-full"></div>
                                                    <span class="absolute left-1/2 -translate-x-1/2 -top-6 material-symbols-outlined text-primary text-xl">bolt</span>
                                                </div>
                                                <span class="mt-2 text-[10px] font-black uppercase text-slate-400 tracking-widest">3h 55m temps de trajet</span>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xl font-extrabold">18:10</div>
                                                <div class="text-xs font-bold text-slate-400">Calavi</div>
                                            </div>
                                        </div>
                                        <div class="bg-primary/5 rounded-xl p-3 border border-primary/10">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-bold text-slate-500">Places restantes</span>
                                                <span class="text-sm font-black text-primary">1/3</span>
                                            </div>
                                            <div class="flex gap-1">
                                                <div class="h-1.5 flex-1 bg-primary rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-slate-200 rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-slate-200 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-56 flex flex-col">
                                    <!-- Map preview -->
                                    <div class="bg-slate-100 relative min-h-[120px]">
                                        <div class="absolute inset-0 grayscale hover:grayscale-0 transition-all duration-500"
                                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDwTV9Q84aDOUgXVkXPzeha1SHVFWOiG2NtcESPN7pu18IEpS23zfMHq9N_vtGy2FZout3Ji5njPvoxk_sI8b3tQ2K4sTelwIOOQDyjeLKMTBWqXIo_xjaq1QtaJjD1m3tb_d4f47SQMlf8-v5zKGa2tlK6DB5jV2w8C62cjTC2c5Jx0N_XITwfYQvA-pfghvF6umzkchO_xDA19gB8eHTjQiuwrqJ9LI6EnUBLRV3jdGGkc8-U_zJNh0DCsXfE8axv2eOn3YZzPk0L');
                                            background-size: cover;">
                                        </div>
                                        <div class="absolute inset-0 bg-primary/10"></div>
                                        <div class="absolute bottom-3 left-3 bg-white/90 px-2 py-1 rounded text-[10px] font-bold uppercase">
                                            Aperçu de l'itinéraire
                                        </div>
                                    </div>
                                    <!-- Reserve button -->
                                    <button class="mt-4 bg-primary text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                                        Réserver
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Trip Card 3 -->
                        <div class="bg-white dark:bg-background-dark/50 rounded-2xl border border-primary/10 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all group cursor-pointer overflow-hidden opacity-80">
                            <div class="flex flex-col md:flex-row">
                                <div class="flex-1 p-6">
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="flex items-center gap-3">
                                            <div class="size-12 rounded-xl bg-slate-100 overflow-hidden border-2 border-primary/20" data-alt="Driver profile image in a car" style="background-image: url('{{ asset('images/avatar/avar.png')}}'); background-size: cover;"></div>
                                            <div>
                                                <h4 class="font-bold text-lg">Marc D.</h4>
                                                <div class="flex items-center gap-1 text-primary text-sm font-bold">
                                                    <span class="material-symbols-outlined text-sm fill-1">star</span> 4.7 <span class="text-slate-400 font-normal">(89 vue)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-black text-slate-400">32F CFA</div>
                                            <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">par siège</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                                        <div class="col-span-2 flex items-center gap-6">
                                            <div class="text-center">
                                                <div class="text-xl font-extrabold">17:00</div>
                                                <div class="text-xs font-bold text-slate-400">Cotonou</div>
                                            </div>
                                            <div class="flex-1 relative flex flex-col items-center">
                                                <div class="w-full h-px bg-slate-200 border-dashed border-b-2 border-slate-300 relative">
                                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 size-2 bg-slate-300 rounded-full"></div>
                                                    <div class="absolute right-0 top-1/2 -translate-y-1/2 size-2 bg-slate-300 rounded-full"></div>
                                                </div>
                                                <span class="mt-2 text-[10px] font-black uppercase text-slate-400 tracking-widest">4h 30m temps de trajet</span>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-xl font-extrabold">21:30</div>
                                                <div class="text-xs font-bold text-slate-400">Calavi</div>
                                            </div>
                                        </div>
                                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-bold text-red-500">Complet</span>
                                                <span class="text-sm font-black text-slate-400">4/4</span>
                                            </div>
                                            <div class="flex gap-1">
                                                <div class="h-1.5 flex-1 bg-slate-300 rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-slate-300 rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-slate-300 rounded-full"></div>
                                                <div class="h-1.5 flex-1 bg-slate-300 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-56 flex flex-col">
                                    <!-- Map preview -->
                                    <div class="bg-slate-100 relative min-h-[120px]">
                                        <div class="absolute inset-0 grayscale hover:grayscale-0 transition-all duration-500"
                                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDwTV9Q84aDOUgXVkXPzeha1SHVFWOiG2NtcESPN7pu18IEpS23zfMHq9N_vtGy2FZout3Ji5njPvoxk_sI8b3tQ2K4sTelwIOOQDyjeLKMTBWqXIo_xjaq1QtaJjD1m3tb_d4f47SQMlf8-v5zKGa2tlK6DB5jV2w8C62cjTC2c5Jx0N_XITwfYQvA-pfghvF6umzkchO_xDA19gB8eHTjQiuwrqJ9LI6EnUBLRV3jdGGkc8-U_zJNh0DCsXfE8axv2eOn3YZzPk0L');
                                            background-size: cover;">
                                        </div>
                                        <div class="absolute inset-0 bg-primary/10"></div>
                                        <div class="absolute bottom-3 left-3 bg-white/90 px-2 py-1 rounded text-[10px] font-bold uppercase">
                                            Aperçu de l'itinéraire
                                        </div>
                                    </div>
                                    <!-- Reserve button -->
                                    <button class="mt-4 bg-primary text-white font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                                        Réserver
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Load More -->
                        <button class="w-full py-4 border-2 border-dashed border-primary/20 rounded-2xl text-slate-400 font-bold hover:bg-primary/5 hover:border-primary/50 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">expand_more</span>
                                                Afficher plus de résultats
                        </button>
                    </div>
                </div>
            </main>
             <script>
            const menuBtn = document.getElementById("menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");

            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
            });
        </script>
</x-layout>

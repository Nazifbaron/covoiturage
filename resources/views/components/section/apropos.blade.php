 <section class="relative bg-white dark:bg-background-dark py-24 overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="max-w-7xl mx-auto px-6">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <!-- Image Side -->
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-tr from-primary/20 to-blue-500/20 rounded-3xl blur-2xl opacity-50"></div>

                            <img src="{{ asset('images/about/propo.jpg') }}" alt="Car illustration" class="relative rounded-3xl shadow-2xl object-cover w-full">
                        </div>
                        <!-- Content Side -->
                        <div>
                            <h2 class="text-4xl font-bold text-deep-blue dark:text-white mb-8">
                                À propos de nous
                            </h2>
                            <div class="space-y-8">
                                <!-- Item 1 -->
                                <div class="flex gap-6 group">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary transition">
                                        <img src="{{ asset('images/about/about-1.svg') }}" class="w-7 h-7" alt="">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            Des millions de trajets
                                        </h4>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                            Chaque jour, des milliers de voyageurs trouvent un trajet sécurisé et économique grâce à notre communauté active et engagée.
                                        </p>
                                    </div>
                                </div>
                                <!-- Item 2 -->
                                <div class="flex gap-6 group">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center group-hover:bg-primary transition">
                                        <img src="{{ asset('images/about/about-2.svg') }}" class="w-7 h-7" alt="">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            Leader du covoiturage intelligent
                                        </h4>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                            Nous connectons conducteurs et passagers via une plateforme moderne, rapide et fiable adaptée aux besoins actuels.
                                        </p>
                                    </div>
                                </div>
                                <!-- Item 3 -->
                                <div class="flex gap-6 group">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-green-500/10 flex items-center justify-center group-hover:bg-green-500 transition">
                                        <img src="{{ asset('images/about/about-3.svg') }}" class="w-7 h-7" alt="">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            Expérience simple & moderne
                                        </h4>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                            Une interface intuitive, des paiements sécurisés et une gestion simplifiée pour un voyage sans stress.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- CTA -->
                            <div class="mt-10">
                                <a href="#" class="inline-block bg-primary text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                    Découvrir nos trajets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

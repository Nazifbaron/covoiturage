 <section class="relative bg-white dark:bg-background-dark py-24 overflow-hidden">
     <!-- Background decoration -->
     <div class="absolute -top-20 -left-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
     <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
     <div class="max-w-7xl mx-auto px-6">
         <div class="grid lg:grid-cols-2 gap-16 items-start">
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
                     <p class="text-slate-700 dark:text-slate-400 leading-relaxed">
                         Notre plateforme de covoiturage a été conçue pour simplifier vos déplacements
                         au quotidien au Bénin. Nous mettons en relation conducteurs et passagers
                         afin de proposer des trajets économiques, sécurisés et conviviaux.
                         Que ce soit pour vos déplacements professionnels ou personnels,
                         notre objectif est de rendre chaque voyage plus accessible,
                         plus confortable et plus humain.
                     </p>
                 </div>

             </div>
         </div>
         <div class="flex flex-col md:flex-row justify-between gap-8 pt-16">

             <!-- Item 1 -->
             <div class="flex gap-4 max-w-sm">
                 <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                     <img src="{{ asset('images/about/about-1.svg') }}" class="w-7 h-7" alt="">
                 </div>
                 <div>
                     <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                         Des trajets accessibles
                     </h4>
                     <p class="text-slate-500 dark:text-slate-400 text-sm">
                         Trouvez facilement un trajet adapté à votre budget et à vos besoins partout au Bénin.
                     </p>
                 </div>
             </div>

             <!-- Item 2 -->
             <div class="flex gap-4 max-w-sm">
                 <div class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center">
                     <img src="{{ asset('images/about/about-2.svg') }}" class="w-7 h-7" alt="">
                 </div>
                 <div>
                     <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                         Plateforme intelligente
                     </h4>
                     <p class="text-slate-500 dark:text-slate-400 text-sm">
                         Notre système optimise la recherche de trajets grâce à une technologie moderne et rapide.
                     </p>
                 </div>
             </div>

             <!-- Item 3 -->
             <div class="flex gap-4 max-w-sm">
                 <div class="w-14 h-14 rounded-xl bg-green-500/10 flex items-center justify-center">
                     <img src="{{ asset('images/about/about-3.svg') }}" class="w-7 h-7" alt="">
                 </div>
                 <div>
                     <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                         Expérience sécurisée
                     </h4>
                     <p class="text-slate-500 dark:text-slate-400 text-sm">
                         Profitez d’un environnement fiable avec des profils vérifiés et des trajets sécurisés.
                     </p>
                 </div>
             </div>

         </div>
         <!-- CTA -->
         <div class="mt-16 text-center">
             <a href="#"
                 class="inline-block bg-primary text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                 Découvrir nos trajets
             </a>
         </div>
     </div>
 </section>

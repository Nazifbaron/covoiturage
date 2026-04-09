<section class="relative py-12 sm:py-20 bg-slate-950 overflow-hidden">
  <!-- Glow background -->
  <div class="absolute -top-40 -left-40 w-96 h-96 bg-orange-500/30 blur-[120px] rounded-full"></div>
  <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-pink-500/30 blur-[120px] rounded-full"></div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6">

    <!-- Title -->
    <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
      <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white leading-tight">
        Découvrez vos
        <span class="bg-gradient-to-r from-orange-400 to-pink-500 bg-clip-text text-transparent">
          avantages personnalisés
        </span>
        🙌
      </h2>
      <p class="mt-3 text-gray-400 text-sm sm:text-lg">
        Choisissez votre rôle et découvrez comment le covoiturage peut
        transformer vos trajets quotidiens.
      </p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-10">

      <!-- PASSAGER -->
      <a href="#"
        class="group relative rounded-2xl sm:rounded-3xl p-[1px] bg-gradient-to-br from-orange-400/40 to-pink-500/40">
        <div class="relative flex flex-col sm:flex-row items-center sm:justify-between h-full rounded-2xl sm:rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 p-6 sm:p-10 overflow-hidden transition duration-500 group-hover:scale-[1.02] gap-5 sm:gap-0">

          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-r from-orange-500/10 to-pink-500/10"></div>

          <div class="relative z-10 text-center sm:text-left">
            <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-orange-500/20 text-orange-300 text-sm font-semibold">
              🚗 Passager
            </span>
            <p class="mt-4 sm:mt-6 text-lg sm:text-xl text-white font-medium leading-relaxed">
              Le confort de la voiture au prix
              d'un ticket de bus 🚌
            </p>
            <span class="inline-block mt-4 sm:mt-8 text-orange-400 font-semibold group-hover:translate-x-1 transition text-sm">
              Découvrir mes avantages →
            </span>
          </div>

          <img src="{{ asset('images/passager1.png') }}"
               class="relative w-32 sm:w-56 flex-shrink-0 transform group-hover:scale-110 transition duration-500"
               alt="Passager covoiturage"/>
        </div>
      </a>

      <!-- CONDUCTEUR -->
      <a href="/login"
        class="group relative rounded-2xl sm:rounded-3xl p-[1px] bg-gradient-to-br from-pink-400/40 to-orange-400/40">
        <div class="relative flex flex-col sm:flex-row items-center sm:justify-between h-full rounded-2xl sm:rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 p-6 sm:p-10 overflow-hidden transition duration-500 group-hover:scale-[1.02] gap-5 sm:gap-0">

          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-r from-pink-500/10 to-orange-500/10"></div>

          <div class="relative z-10 text-center sm:text-left">
            <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-pink-500/20 text-pink-300 text-sm font-semibold">
              🚘 Conducteur
            </span>
            <p class="mt-4 sm:mt-6 text-lg sm:text-xl text-white font-medium leading-relaxed">
              Gagnez en moyenne
              <span class="text-pink-400 font-bold">100 000F CFA par mois</span>
              en partageant vos trajets 💸
            </p>
            <span class="inline-block mt-4 sm:mt-8 text-pink-400 font-semibold group-hover:translate-x-1 transition text-sm">
              Découvrir mes avantages →
            </span>
          </div>

          <img src="{{ asset('images/passager2.png') }}"
               class="relative w-32 sm:w-56 flex-shrink-0 transform group-hover:scale-110 transition duration-500"
               alt="Conducteur covoiturage"/>
        </div>
      </a>

    </div>
  </div>
</section>

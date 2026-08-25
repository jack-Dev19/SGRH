
    <!-- ============================================= -->
    <!-- NAVBAR — sticky top-0 = reste fixe au scroll   -->
    <!-- ============================================= -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white text-sm">👥</div>
                <div class="leading-tight">
                    <p class="font-bold text-slate-900 text-sm">SGRH</p>
                    <p class="text-[10px] text-slate-400 -mt-0.5">Technologies</p>
                </div>
            </div>

            <!-- Liens (cachés sur mobile) -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="#Accueil" class="text-blue-600">Accueil</a>
                <a href="#apropos" class="text-slate-500 hover:text-slate-900 transition">À propos</a>
                <a href="#carrieres" class="text-slate-500 hover:text-slate-900 transition">Carrières</a>
                <a href="#contact" class="text-slate-500 hover:text-slate-900 transition">Contact</a>
            </nav>

            <!-- Bouton + burger mobile -->
            <div class="flex items-center gap-3">
                <button
                    class="hidden sm:flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Espace RH <span>→</span>
                </button>
                <button
                    class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600">
                    ☰
                </button>
            </div>

        </div>
    </header>

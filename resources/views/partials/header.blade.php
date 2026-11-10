{{-- ============================================= --}}
{{-- HEADER PRINCIPAL (Violet sombre premium)       --}}
{{-- ============================================= --}}
<header class="sticky top-0 z-50 px-4 md:px-6 py-3">

    <div class="max-w-7xl mx-auto bg-gradient-to-r from-purple-900 via-purple-800 to-purple-900 backdrop-blur-xl border border-white/10 rounded-2xl lg:rounded-full shadow-xl shadow-purple-900/40 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-900/50">

        <div class="px-6 md:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- LOGO --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 p-2 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-white text-sm md:text-base tracking-tight leading-tight">DIGITAL AGENCY</span>
                        <span class="text-[9px] text-purple-300 uppercase tracking-widest font-medium hidden sm:block">Solutions digitales</span>
                    </div>
                </a>

                {{-- NAVIGATION DESKTOP --}}
                <nav class="hidden lg:flex items-center gap-1 text-sm font-medium text-white/80">
                    <a href="{{ route('home') }}#accueil" class="px-4 py-2 rounded-full hover:bg-white/10 hover:text-white transition-all duration-200">Accueil</a>
                    <a href="{{ route('home') }}#apropos" class="px-4 py-2 rounded-full hover:bg-white/10 hover:text-white transition-all duration-200">À propos</a>
                    <a href="{{ route('home') }}#services" class="px-4 py-2 rounded-full hover:bg-white/10 hover:text-white transition-all duration-200">Services</a>
                    <a href="{{ route('home') }}#offres" class="px-4 py-2 rounded-full hover:bg-white/10 hover:text-white transition-all duration-200">Carrières</a>
                    <a href="{{ route('home') }}#contact" class="px-4 py-2 rounded-full hover:bg-white/10 hover:text-white transition-all duration-200">Contact</a>
                </nav>

                {{-- BOUTONS À DROITE --}}
                <div class="flex items-center gap-2 md:gap-3">

                    @auth
                        @php
                            $roleMapping = [
                                'Administrateur'      => 'admin',
                                'Responsable RH'      => 'rh',
                                'Chef de département' => 'chef',
                                'Employé'             => 'employe',
                            ];
                            $roleKey = $roleMapping[Auth::user()->role->nom ?? ''] ?? null;
                        @endphp

                        @if($roleKey && Route::has($roleKey . '.dashboard'))
                            <a href="{{ route($roleKey . '.dashboard') }}"
                               class="hidden md:flex items-center gap-2 text-sm text-white hover:text-white transition px-3 py-2 rounded-full hover:bg-white/10">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-[10px] font-bold ring-2 ring-purple-900 shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'NA', 0, 2)) }}
                                </div>
                                <span class="font-semibold truncate max-w-[120px]">Bonjour, {{ Auth::user()->name }}</span>
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="inline-flex items-center gap-2 bg-white hover:bg-purple-50 text-purple-900 px-3 md:px-4 py-2 rounded-full text-xs md:text-sm font-bold transition shadow-lg shadow-black/30 hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    @else
                        {{-- Bouton Espace RH --}}
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 bg-white hover:bg-purple-50 text-purple-900 px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-bold transition transform hover:-translate-y-0.5 shadow-lg shadow-black/30 group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Espace RH
                        </a>
                    @endauth

                    {{-- Bouton burger mobile --}}
                    <button id="mobile-menu-toggle" class="lg:hidden p-2 rounded-full hover:bg-white/10 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                </div>

            </div>
        </div>

        {{-- MENU MOBILE --}}
        <div id="mobile-menu" class="hidden lg:hidden border-t border-white/10 rounded-b-2xl">
            <nav class="px-4 py-4 space-y-1 text-sm font-medium text-white">
                <a href="{{ route('home') }}#accueil" class="block px-4 py-2.5 rounded-xl hover:bg-white/10 transition">Accueil</a>
                <a href="{{ route('home') }}#apropos" class="block px-4 py-2.5 rounded-xl hover:bg-white/10 transition">À propos</a>
                <a href="{{ route('home') }}#services" class="block px-4 py-2.5 rounded-xl hover:bg-white/10 transition">Services</a>
                <a href="{{ route('home') }}#offres" class="block px-4 py-2.5 rounded-xl hover:bg-white/10 transition">Carrières</a>
                <a href="{{ route('home') }}#contact" class="block px-4 py-2.5 rounded-xl hover:bg-white/10 transition">Contact</a>

                @guest
                    <a href="{{ route('login') }}" class="block text-center bg-white hover:bg-purple-50 text-purple-900 py-2.5 rounded-full font-bold transition mt-3 shadow-md shadow-black/30">
                        Espace RH
                    </a>
                @endguest
            </nav>
        </div>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('mobile-menu-toggle');
        const menu   = document.getElementById('mobile-menu');

        if (toggle && menu) {
            toggle.addEventListener('click', function () {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>
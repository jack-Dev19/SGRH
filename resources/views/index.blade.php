@extends('layouts.app')

@section('title', 'Accueil - Agence Digitale')

@section('content')

    {{-- ============================================= --}}
    {{-- SECTION HERO --}}
    {{-- ============================================= --}}
    <section id="accueil"
        class="relative bg-gradient-to-br from-purple-100 via-pink-50 to-purple-100 px-6 md:px-[100px] py-16 md:py-24 overflow-hidden">

        {{-- Formes décoratives --}}
        <div
            class="absolute -top-32 -right-32 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none">
        </div>
        <div
            class="absolute -bottom-32 -left-32 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-[100px] items-center">

                {{-- Colonne gauche : Texte --}}
                <div>
                    {{-- Badge --}}
                    <div
                        class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-full px-4 py-1.5 mb-6 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                        <span class="text-xs font-bold tracking-widest text-purple-700 uppercase">Hello, We are a
                            Creative</span>
                    </div>

                    {{-- Titre --}}
                    <h1 class="text-5xl md:text-7xl font-extrabold leading-[1.05] mb-8">
                        <span class="block text-purple-500">DIGITAL</span>
                        <span class="block text-slate-900">AGENCY</span>
                    </h1>

                    {{-- Sous-titre --}}
                    <p class="text-slate-600 text-base md:text-lg leading-relaxed max-w-lg mb-10">
                        Nous créons des sites web fonctionnels, esthétiques et pensés pour convertir.
                        Design moderne, performance et expérience utilisateur au cœur de chaque projet.
                    </p>

                    {{-- Boutons --}}
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="#offres"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold px-6 py-3.5 rounded-full transition transform hover:-translate-y-0.5 shadow-lg shadow-purple-600/30">
                            Voir nos offres
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="#contact"
                            class="inline-flex items-center gap-2 border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white font-medium px-6 py-3 rounded-full transition">
                            Nous contacter
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Colonne droite : Image --}}
                <div class="relative">
                    <div
                        class="absolute -inset-4 bg-gradient-to-br from-purple-300 to-pink-300 rounded-3xl blur-3xl opacity-40">
                    </div>

                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&q=80"
                        alt="Équipe de l'agence travaillant sur un projet digital"
                        class="relative w-full h-auto rounded-3xl shadow-2xl object-cover aspect-[4/3]">

                    {{-- Badge flottant bas-gauche --}}
                    <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-xl">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">25+ Experts</p>
                                <p class="text-xs text-slate-500">à votre service</p>
                            </div>
                        </div>
                    </div>

                    {{-- Badge flottant haut-droite --}}
                    <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-xl">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-xs font-bold text-slate-900">Disponible</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================= --}}
    {{-- SECTION STATISTIQUES (3D + design pro) --}}
    {{-- ============================================= --}}
    <section class="relative bg-white py-16 px-6 md:px-[100px] -mt-8 z-10 overflow-hidden">
        <div class="max-w-7xl mx-auto">

            {{-- En-tête --}}
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center gap-2 bg-purple-100 border border-purple-200 rounded-full px-4 py-1.5 mb-4">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest text-purple-700 uppercase">Nos chiffres</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-3">
                    Des résultats qui <span class="text-purple-500">parlent d'eux-mêmes.</span>
                </h2>
                <p class="text-slate-500 max-w-xl mx-auto text-sm">
                    Chiffres clés de notre activité depuis 2019.
                </p>
            </div>

            {{-- ============================================= --}}
            {{-- GRILLE PRINCIPALE : Stats + Image 3D --}}
            {{-- ============================================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

                {{-- ============================================= --}}
                {{-- COLONNE GAUCHE : 4 Stats en 2x2 --}}
                {{-- ============================================= --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- Carte 1 : Années --}}
                    <div
                        class="group bg-gradient-to-br from-purple-600 via-purple-600 to-purple-700 rounded-3xl p-6 shadow-xl shadow-purple-500/30 hover:shadow-2xl hover:shadow-purple-500/50 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">

                        {{-- Formes décoratives --}}
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/5 rounded-full"></div>

                        <div class="relative">
                            {{-- Icône --}}
                            <div
                                class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            {{-- Chiffre --}}
                            <div class="flex items-baseline gap-0.5 mb-1">
                                <p class="text-4xl font-extrabold text-white">6</p>
                                <span class="text-xl font-bold text-purple-200">+</span>
                            </div>

                            {{-- Label --}}
                            <p class="text-[10px] font-bold text-purple-100 uppercase tracking-wider">Années d'expérience
                            </p>

                            {{-- Petit indicateur --}}
                            <div class="mt-3 flex items-center gap-1">
                                <div class="w-1 h-1 rounded-full bg-white"></div>
                                <div class="w-1 h-1 rounded-full bg-white/60"></div>
                                <div class="w-1 h-1 rounded-full bg-white/40"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Carte 2 : Projets --}}
                    <div
                        class="group bg-gradient-to-br from-pink-500 via-pink-500 to-pink-600 rounded-3xl p-6 shadow-xl shadow-pink-500/30 hover:shadow-2xl hover:shadow-pink-500/50 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/5 rounded-full"></div>

                        <div class="relative">
                            <div
                                class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>

                            <div class="flex items-baseline gap-0.5 mb-1">
                                <p class="text-4xl font-extrabold text-white">40</p>
                                <span class="text-xl font-bold text-pink-200">+</span>
                            </div>

                            <p class="text-[10px] font-bold text-pink-100 uppercase tracking-wider">Projets complétés</p>

                            <div class="mt-3 flex items-center gap-1">
                                <div class="w-1 h-1 rounded-full bg-white"></div>
                                <div class="w-1 h-1 rounded-full bg-white/60"></div>
                                <div class="w-1 h-1 rounded-full bg-white/40"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Carte 3 : Clients --}}
                    <div
                        class="group bg-gradient-to-br from-blue-500 via-blue-500 to-blue-600 rounded-3xl p-6 shadow-xl shadow-blue-500/30 hover:shadow-2xl hover:shadow-blue-500/50 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/5 rounded-full"></div>

                        <div class="relative">
                            <div
                                class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>

                            <div class="flex items-baseline gap-0.5 mb-1">
                                <p class="text-4xl font-extrabold text-white">18</p>
                                <span class="text-xl font-bold text-blue-200">+</span>
                            </div>

                            <p class="text-[10px] font-bold text-blue-100 uppercase tracking-wider">Clients satisfaits</p>

                            <div class="mt-3 flex items-center gap-1">
                                <div class="w-1 h-1 rounded-full bg-white"></div>
                                <div class="w-1 h-1 rounded-full bg-white/60"></div>
                                <div class="w-1 h-1 rounded-full bg-white/40"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Carte 4 : Prix --}}
                    <div
                        class="group bg-gradient-to-br from-amber-500 via-orange-500 to-orange-600 rounded-3xl p-6 shadow-xl shadow-amber-500/30 hover:shadow-2xl hover:shadow-amber-500/50 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/5 rounded-full"></div>

                        <div class="relative">
                            <div
                                class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>

                            <div class="flex items-baseline gap-0.5 mb-1">
                                <p class="text-4xl font-extrabold text-white">12</p>
                            </div>

                            <p class="text-[10px] font-bold text-amber-100 uppercase tracking-wider">Prix remportés</p>

                            <div class="mt-3 flex items-center gap-1">
                                <div class="w-1 h-1 rounded-full bg-white"></div>
                                <div class="w-1 h-1 rounded-full bg-white/60"></div>
                                <div class="w-1 h-1 rounded-full bg-white/40"></div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ============================================= --}}
                {{-- COLONNE DROITE : Image illustration 3D --}}
                {{-- ============================================= --}}
                <div class="relative">

                    {{-- Formes décoratives derrière l'image --}}
                    <div
                        class="absolute -inset-4 bg-gradient-to-br from-purple-300 via-pink-300 to-purple-400 rounded-[3rem] blur-3xl opacity-30">
                    </div>

                    {{-- Image illustration 3D --}}
                    <div
                        class="relative bg-gradient-to-br from-purple-50 via-pink-50 to-purple-100 rounded-[2rem] p-6 shadow-2xl shadow-purple-500/20 border border-purple-100 overflow-hidden">

                        {{-- Décorations --}}
                        <div class="absolute top-4 right-4 w-20 h-20 bg-purple-200 rounded-full opacity-40 blur-2xl"></div>
                        <div class="absolute bottom-4 left-4 w-24 h-24 bg-pink-200 rounded-full opacity-40 blur-2xl"></div>

                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80"
                            alt="Illustration de statistiques et graphiques 3D"
                            class="relative w-full h-auto rounded-2xl shadow-lg object-cover aspect-[4/3]">

                        {{-- Badge flottant "En direct" --}}
                        <div class="absolute top-10 left-10 bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 shadow-lg">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-slate-900 uppercase tracking-wider">En direct</span>
                            </div>
                        </div>

                        {{-- Badge flottant "+12% ce mois" --}}
                        <div
                            class="absolute bottom-10 right-10 bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 shadow-lg">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Croissance
                                    </p>
                                    <p class="text-xs font-extrabold text-slate-900">+12% ce mois</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Mini card flottante en bas --}}
                    <div
                        class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-2xl p-4 border border-slate-100 max-w-[200px] hidden md:block">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">25 Experts</p>
                                <p class="text-[10px] text-slate-500">Équipe passionnée</p>
                            </div>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"
                                style="width: 78%"></div>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1.5">78% de satisfaction</p>
                    </div>

                </div>

            </div>

        </div>
    </section>

    {{-- ============================================= --}}
    {{-- SECTION À PROPOS --}}
    {{-- ============================================= --}}
    <section id="apropos" class="bg-white px-6 md:px-[100px] py-20">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-[100px] items-center">

                <div class="relative">
                    <div
                        class="absolute -inset-4 bg-gradient-to-br from-purple-200 to-pink-200 rounded-3xl blur-2xl opacity-40">
                    </div>
                    <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=800&q=80"
                        alt="Réunion d'équipe dans un bureau moderne"
                        class="relative w-full h-auto rounded-3xl shadow-xl object-cover aspect-[4/3]">
                </div>

                <div>
                    <div
                        class="inline-flex items-center gap-2 bg-purple-100 border border-purple-200 rounded-full px-4 py-1.5 mb-4">
                        <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                        <span class="text-xs font-bold tracking-widest text-purple-700 uppercase">Qui sommes-nous</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6">
                        Une équipe passionnée <br>
                        <span class="text-purple-500">depuis 2019.</span>
                    </h2>

                    <p class="text-slate-600 leading-relaxed mb-4">
                        <strong class="text-slate-900">Digital Agency</strong> est une entreprise marocaine spécialisée
                        dans la création de sites web, d'applications mobiles et de solutions digitales sur mesure.
                    </p>
                    <p class="text-slate-600 leading-relaxed mb-8">
                        Nous accompagnons startups, PME et grands comptes dans leur transformation digitale
                        en leur offrant des produits modernes, performants et adaptés à leurs besoins.
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 shrink-0 rounded-full bg-purple-100 flex items-center justify-center mt-0.5">
                                <svg class="h-3.5 w-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600">Design moderne et centré utilisateur</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 shrink-0 rounded-full bg-purple-100 flex items-center justify-center mt-0.5">
                                <svg class="h-3.5 w-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600">Technologies performantes et sécurisées</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 shrink-0 rounded-full bg-purple-100 flex items-center justify-center mt-0.5">
                                <svg class="h-3.5 w-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600">Accompagnement personnalisé de A à Z</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

   {{-- ============================================= --}}
{{-- SECTION SERVICES (images plus visibles)        --}}
{{-- ============================================= --}}
<section id="services" class="relative bg-gradient-to-b from-slate-50 via-white to-slate-50 px-6 md:px-[100px] py-24 overflow-hidden">

    {{-- Formes décoratives de fond --}}
    <div class="absolute top-20 -left-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>
    <div class="absolute bottom-20 -right-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">

        {{-- En-tête --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 bg-purple-100 border border-purple-200 rounded-full px-4 py-1.5 mb-4">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                <span class="text-xs font-bold tracking-widest text-purple-700 uppercase">Notre expertise</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4">
                Nos <span class="text-purple-500">services</span>
            </h2>
            <p class="text-slate-500 max-w-xl mx-auto leading-relaxed">
                Des solutions digitales complètes pour développer votre présence en ligne.
            </p>
        </div>

        {{-- ============================================= --}}
        {{-- GRILLE DES SERVICES                            --}}
        {{-- ============================================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pt-4">

            {{-- ============================================= --}}
            {{-- Service 1 : UI/UX Design                       --}}
            {{-- ============================================= --}}
            <div class="relative group">

                {{-- Badge flottant --}}
                <div class="absolute -top-4 -right-3 z-30 bg-slate-900 text-white rounded-full px-3 py-1.5 shadow-2xl shadow-slate-900/40 flex items-center gap-1.5 border-2 border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider">UI/UX</span>
                </div>

                {{-- Card --}}
                <div class="relative bg-[#1a1a1e] rounded-[1.75rem] overflow-hidden shadow-2xl shadow-slate-900/20 hover:shadow-purple-500/30 hover:-translate-y-2 transition-all duration-500">

                    {{-- Image illustration (plus lumineuse et contrastée) --}}
                    <div class="relative h-40 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1559028012-481c04fa702d?w=600&q=90"
                            alt="UI/UX Design"
                            class="w-full h-full object-cover brightness-110 contrast-125 saturate-125 opacity-80 group-hover:brightness-125 group-hover:contrast-150 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700"
                        >

                        {{-- Dégradé allégé (seulement en bas pour la transition) --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1a1e] via-[#1a1a1e]/30 via-50% to-transparent"></div>

                        {{-- Teinte de couleur subtile (optionnelle) --}}
                        <div class="absolute inset-0 bg-purple-900/20 mix-blend-overlay"></div>

                        {{-- Icône --}}
                        <div class="absolute top-4 left-4 w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/50 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                    </div>

                    {{-- Contenu --}}
                    <div class="relative px-5 pb-5 pt-3">
                        <h3 class="text-white font-bold text-lg mb-4 leading-tight">UI/UX Design</h3>

                        <div class="grid grid-cols-3 gap-2 mb-4 pb-4 border-b border-white/10">
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Durée</p>
                                <p class="text-[11px] font-semibold text-white">2-4 sem.</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Outil</p>
                                <p class="text-[11px] font-semibold text-white">Figma</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Type</p>
                                <p class="text-[11px] font-semibold text-white">Design</p>
                            </div>
                        </div>

                        <p class="text-[11px] text-white/60 leading-relaxed mb-5">
                            Interfaces modernes, maquettes interactives et design centré utilisateur.
                        </p>

                        <a href="#contact" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-400 to-lime-400 hover:from-emerald-500 hover:to-lime-500 text-slate-900 text-xs font-bold py-3 rounded-full transition shadow-lg shadow-emerald-500/20">
                            En savoir plus
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- Service 2 : Branding                           --}}
            {{-- ============================================= --}}
            <div class="relative group">

                <div class="absolute -top-4 -right-3 z-30 bg-slate-900 text-white rounded-full px-3 py-1.5 shadow-2xl shadow-slate-900/40 flex items-center gap-1.5 border-2 border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c1.1 0 2-.9 2-2 0-.53-.2-1.03-.54-1.41-.34-.38-.53-.87-.53-1.37 0-1.1.9-2 2-2h2c3.31 0 6-2.69 6-6 0-5.52-4.48-10-10-10z"/>
                    </svg>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider">Brand</span>
                </div>

                <div class="relative bg-[#1a1a1e] rounded-[1.75rem] overflow-hidden shadow-2xl shadow-slate-900/20 hover:shadow-pink-500/30 hover:-translate-y-2 transition-all duration-500">

                    <div class="relative h-40 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=90"
                            alt="Branding"
                            class="w-full h-full object-cover brightness-110 contrast-125 saturate-125 opacity-80 group-hover:brightness-125 group-hover:contrast-150 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1a1e] via-[#1a1a1e]/30 via-50% to-transparent"></div>
                        <div class="absolute inset-0 bg-pink-900/20 mix-blend-overlay"></div>

                        <div class="absolute top-4 left-4 w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center shadow-lg shadow-pink-500/50 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative px-5 pb-5 pt-3">
                        <h3 class="text-white font-bold text-lg mb-4 leading-tight">Branding</h3>

                        <div class="grid grid-cols-3 gap-2 mb-4 pb-4 border-b border-white/10">
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Durée</p>
                                <p class="text-[11px] font-semibold text-white">1-3 sem.</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Outil</p>
                                <p class="text-[11px] font-semibold text-white">Illustrator</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Type</p>
                                <p class="text-[11px] font-semibold text-white">Identité</p>
                            </div>
                        </div>

                        <p class="text-[11px] text-white/60 leading-relaxed mb-5">
                            Logos, chartes graphiques et direction artistique pour une identité forte.
                        </p>

                        <a href="#contact" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-400 to-lime-400 hover:from-emerald-500 hover:to-lime-500 text-slate-900 text-xs font-bold py-3 rounded-full transition shadow-lg shadow-emerald-500/20">
                            En savoir plus
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- Service 3 : Web Design                         --}}
            {{-- ============================================= --}}
            <div class="relative group">

                <div class="absolute -top-4 -right-3 z-30 bg-slate-900 text-white rounded-full px-3 py-1.5 shadow-2xl shadow-slate-900/40 flex items-center gap-1.5 border-2 border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/>
                    </svg>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider">Web</span>
                </div>

                <div class="relative bg-[#1a1a1e] rounded-[1.75rem] overflow-hidden shadow-2xl shadow-slate-900/20 hover:shadow-blue-500/30 hover:-translate-y-2 transition-all duration-500">

                    <div class="relative h-40 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1547658719-da2b51169166?w=600&q=90"
                            alt="Web Design"
                            class="w-full h-full object-cover brightness-110 contrast-125 saturate-125 opacity-80 group-hover:brightness-125 group-hover:contrast-150 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1a1e] via-[#1a1a1e]/30 via-50% to-transparent"></div>
                        <div class="absolute inset-0 bg-blue-900/20 mix-blend-overlay"></div>

                        <div class="absolute top-4 left-4 w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/50 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative px-5 pb-5 pt-3">
                        <h3 class="text-white font-bold text-lg mb-4 leading-tight">Web Design</h3>

                        <div class="grid grid-cols-3 gap-2 mb-4 pb-4 border-b border-white/10">
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Durée</p>
                                <p class="text-[11px] font-semibold text-white">4-6 sem.</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Stack</p>
                                <p class="text-[11px] font-semibold text-white">React</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Type</p>
                                <p class="text-[11px] font-semibold text-white">Site</p>
                            </div>
                        </div>

                        <p class="text-[11px] text-white/60 leading-relaxed mb-5">
                            Sites web responsifs, rapides et optimisés SEO avec les dernières technologies.
                        </p>

                        <a href="#contact" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-400 to-lime-400 hover:from-emerald-500 hover:to-lime-500 text-slate-900 text-xs font-bold py-3 rounded-full transition shadow-lg shadow-emerald-500/20">
                            En savoir plus
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- Service 4 : Mobile Design                      --}}
            {{-- ============================================= --}}
            <div class="relative group">

                <div class="absolute -top-4 -right-3 z-30 bg-slate-900 text-white rounded-full px-3 py-1.5 shadow-2xl shadow-slate-900/40 flex items-center gap-1.5 border-2 border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 1H7c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2zm0 18H7V5h10v14z"/>
                    </svg>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider">Mobile</span>
                </div>

                <div class="relative bg-[#1a1a1e] rounded-[1.75rem] overflow-hidden shadow-2xl shadow-slate-900/20 hover:shadow-emerald-500/30 hover:-translate-y-2 transition-all duration-500">

                    <div class="relative h-40 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&q=90"
                            alt="Mobile Design"
                            class="w-full h-full object-cover brightness-110 contrast-125 saturate-125 opacity-80 group-hover:brightness-125 group-hover:contrast-150 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1a1e] via-[#1a1a1e]/30 via-50% to-transparent"></div>
                        <div class="absolute inset-0 bg-emerald-900/20 mix-blend-overlay"></div>

                        <div class="absolute top-4 left-4 w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/50 group-hover:scale-110 group-hover:rotate-6 transition duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative px-5 pb-5 pt-3">
                        <h3 class="text-white font-bold text-lg mb-4 leading-tight">Mobile Design</h3>

                        <div class="grid grid-cols-3 gap-2 mb-4 pb-4 border-b border-white/10">
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Durée</p>
                                <p class="text-[11px] font-semibold text-white">6-8 sem.</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Platform</p>
                                <p class="text-[11px] font-semibold text-white">iOS/And.</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-white/40 uppercase tracking-wider mb-0.5">Type</p>
                                <p class="text-[11px] font-semibold text-white">App</p>
                            </div>
                        </div>

                        <p class="text-[11px] text-white/60 leading-relaxed mb-5">
                            Applications mobiles natives ou cross-platform, intuitives et performantes.
                        </p>

                        <a href="#contact" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-400 to-lime-400 hover:from-emerald-500 hover:to-lime-500 text-slate-900 text-xs font-bold py-3 rounded-full transition shadow-lg shadow-emerald-500/20">
                            En savoir plus
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

    {{-- ============================================= --}}
    {{-- SECTION OFFRES D'EMPLOI --}}
    {{-- ============================================= --}}
    <section id="offres" class="bg-gradient-to-b from-purple-50 to-white px-6 md:px-[100px] py-20">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-14">
                <div
                    class="inline-flex items-center gap-2 bg-purple-100 border border-purple-200 rounded-full px-4 py-1.5 mb-4">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest text-purple-700 uppercase">Recrutement</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4">
                    Rejoignez notre <span class="text-purple-500">équipe</span>
                </h2>
                <p class="text-slate-500 max-w-2xl mx-auto leading-relaxed">
                    Nous recherchons des talents passionnés pour construire les sites web de demain.
                    Découvrez nos offres ouvertes et postulez en quelques clics.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Offre 1 --}}
                <div
                    class="group bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-purple-300 transition duration-300 overflow-hidden relative">
                    <div
                        class="absolute -top-16 -right-16 w-40 h-40 bg-purple-100 rounded-full opacity-40 group-hover:opacity-70 transition">
                    </div>

                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-purple-700 bg-purple-100 px-3 py-1 rounded-full">CDI</span>
                            <span class="text-xs text-slate-400">Il y a 2 jours</span>
                        </div>

                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>

                        <h3 class="font-bold text-slate-900 text-lg mb-2">Développeur Front-End Senior</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-4">
                            Vous maîtrisez React, Tailwind CSS et l'intégration responsive.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mb-5">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Casablanca
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                3+ ans
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                15 Nov
                            </span>
                        </div>

                        <button type="button" onclick="openModal('Développeur Front-End Senior')"
                            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold py-3 rounded-full transition shadow-lg shadow-purple-600/20">
                            Postuler
                        </button>
                    </div>
                </div>

                {{-- Offre 2 --}}
                <div
                    class="group bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-pink-300 transition duration-300 overflow-hidden relative">
                    <div
                        class="absolute -top-16 -right-16 w-40 h-40 bg-pink-100 rounded-full opacity-40 group-hover:opacity-70 transition">
                    </div>

                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-pink-700 bg-pink-100 px-3 py-1 rounded-full">CDI</span>
                            <span class="text-xs text-slate-400">Il y a 5 jours</span>
                        </div>

                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>

                        <h3 class="font-bold text-slate-900 text-lg mb-2">Designer UI/UX</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-4">
                            Vous concevez des maquettes modernes sur Figma et aimez l'UX.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mb-5">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Casablanca
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                2+ ans
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                20 Nov
                            </span>
                        </div>

                        <button type="button" onclick="openModal('Designer UI/UX')"
                            class="w-full bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white text-sm font-bold py-3 rounded-full transition shadow-lg shadow-pink-600/20">
                            Postuler
                        </button>
                    </div>
                </div>

                {{-- Offre 3 --}}
                <div
                    class="group bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-blue-300 transition duration-300 overflow-hidden relative">
                    <div
                        class="absolute -top-16 -right-16 w-40 h-40 bg-blue-100 rounded-full opacity-40 group-hover:opacity-70 transition">
                    </div>

                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full">CDD</span>
                            <span class="text-xs text-slate-400">Il y a 1 semaine</span>
                        </div>

                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <h3 class="font-bold text-slate-900 text-lg mb-2">Chef de Projet Digital</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-4">
                            Vous coordonnez les projets web et gérez la relation client.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mb-5">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Rabat
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                4+ ans
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                30 Nov
                            </span>
                        </div>

                        <button type="button" onclick="openModal('Chef de Projet Digital')"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-bold py-3 rounded-full transition shadow-lg shadow-blue-600/20">
                            Postuler
                        </button>
                    </div>
                </div>

            </div>

            <div class="text-center mt-12">
                <a href="#"
                    class="inline-flex items-center gap-2 border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white font-medium px-8 py-3.5 rounded-full transition">
                    Voir toutes les offres
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

        </div>
    </section>

    {{-- ============================================= --}}
    {{-- SECTION CTA (bandeau dégradé + vagues) --}}
    {{-- ============================================= --}}
    <section
        class="relative bg-gradient-to-r from-purple-600 via-purple-700 to-pink-600 px-6 md:px-[100px] pt-20 pb-40 overflow-hidden">

        {{-- Décoration --}}
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                Créons quelque chose<br>d'extraordinaire ensemble.
            </h2>
            <p class="text-purple-100 text-lg mb-10 max-w-2xl mx-auto">
                Un projet en tête ? Discutons-en. Nous vous répondons sous 24h.
            </p>
            <a href="#contact"
                class="inline-flex items-center gap-2 bg-white text-purple-700 hover:bg-slate-100 font-bold px-8 py-4 rounded-full transition transform hover:-translate-y-1 shadow-2xl">
                Démarrer un projet
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

        {{-- Vagues en bas --}}
        <div class="absolute bottom-0 left-0 right-0 h-32 pointer-events-none">
            <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0,80 C150,140 350,20 500,80 L500,150 L0,150 Z" fill="#faf5ff" opacity="0.4" />
            </svg>
            <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0,110 C150,170 350,50 500,110 L500,150 L0,150 Z" fill="#fdf2f8" opacity="0.7" />
            </svg>
            <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0,130 C150,180 350,80 500,130 L500,150 L0,150 Z" fill="#ffffff" opacity="1" />
            </svg>
        </div>

    </section>

    {{-- ============================================= --}}
    {{-- SECTION CONTACT (design schéma) --}}
    {{-- ============================================= --}}
    <section id="contact"
        class="relative bg-gradient-to-br from-purple-100 via-pink-50 to-purple-100 px-6 md:px-[100px] py-20 overflow-hidden">

        {{-- Formes décoratives flottantes --}}
        <div
            class="absolute top-20 -left-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none">
        </div>
        <div
            class="absolute bottom-20 -right-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none">
        </div>

        <div class="max-w-6xl mx-auto relative z-10">

            {{-- En-tête --}}
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-full px-4 py-1.5 mb-4 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest text-purple-700 uppercase">Contact</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4">
                    Parlons de votre <span class="text-purple-500">projet</span>
                </h2>
                <p class="text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais.
                </p>
            </div>

            {{-- ============================================= --}}
            {{-- GRILLE : 2 COLONNES (schéma Login/SignUp) --}}
            {{-- ============================================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

                {{-- ============================================= --}}
                {{-- COLONNE GAUCHE : CARTE VIOLETTE (Coordonnées) --}}
                {{-- ============================================= --}}
                <div
                    class="relative bg-gradient-to-br from-purple-600 via-purple-600 to-purple-700 rounded-[2rem] shadow-2xl shadow-purple-500/40 overflow-hidden flex flex-col">

                    {{-- Contenu --}}
                    <div class="relative z-10 p-8 md:p-10">

                        {{-- Titre --}}
                        <div class="text-center mb-8">
                            <h3 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">
                                Contact
                            </h3>
                            <p class="text-sm text-purple-200 uppercase tracking-widest">
                                Nos coordonnées
                            </p>
                        </div>

                        {{-- Coordonnées --}}
                        <div class="space-y-5">

                            {{-- Adresse --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-11 h-11 shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="pt-1">
                                    <p class="text-xs font-bold tracking-widest text-purple-200 uppercase mb-1">Adresse</p>
                                    <p class="text-sm text-white leading-relaxed">Tour Casablanca Finance City,<br>Bloc A,
                                        Casablanca 20000</p>
                                </div>
                            </div>

                            {{-- Téléphone --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-11 h-11 shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="pt-1">
                                    <p class="text-xs font-bold tracking-widest text-purple-200 uppercase mb-1">Téléphone
                                    </p>
                                    <p class="text-sm text-white">+212 5 22 12 34 56</p>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-11 h-11 shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="pt-1">
                                    <p class="text-xs font-bold tracking-widest text-purple-200 uppercase mb-1">Email</p>
                                    <p class="text-sm text-white">contact@digitalagency.ma</p>
                                </div>
                            </div>

                            {{-- Horaires --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-11 h-11 shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="pt-1">
                                    <p class="text-xs font-bold tracking-widest text-purple-200 uppercase mb-1">Horaires</p>
                                    <p class="text-sm text-white">Lun–Ven : 08h30 – 17h30</p>
                                </div>
                            </div>

                        </div>

                        {{-- Réseaux sociaux --}}
                        <div class="mt-8 pt-6 border-t border-white/20">
                            <p class="text-xs font-bold tracking-widest text-purple-200 uppercase mb-4">Suivez-nous</p>
                            <div class="flex gap-3">
                                <a href="#"
                                    class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm flex items-center justify-center transition border border-white/30">
                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                    </svg>
                                </a>
                                <a href="#"
                                    class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm flex items-center justify-center transition border border-white/30">
                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                    </svg>
                                </a>
                                <a href="#"
                                    class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm flex items-center justify-center transition border border-white/30">
                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </div>

                    {{-- Vagues décoratives --}}
                    <div class="absolute bottom-0 left-0 right-0 h-32 pointer-events-none">
                        <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150"
                            preserveAspectRatio="none">
                            <path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.4" />
                        </svg>
                        <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150"
                            preserveAspectRatio="none">
                            <path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#d8b4fe" opacity="0.6" />
                        </svg>
                        <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150"
                            preserveAspectRatio="none">
                            <path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#c084fc" opacity="0.8" />
                        </svg>
                    </div>

                </div>

                {{-- ============================================= --}}
                {{-- COLONNE DROITE : FORMULAIRE BLANC --}}
                {{-- ============================================= --}}
                <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-purple-500/20 overflow-hidden flex flex-col">

                    {{-- Formulaire --}}
                    <form class="relative z-10 p-8 md:p-10 pb-32 flex-1">

                        {{-- Titre --}}
                        <div class="text-center mb-8">
                            <h3 class="text-3xl font-extrabold text-purple-700 uppercase tracking-wider mb-2">
                                Message
                            </h3>
                            <p class="text-sm text-purple-400 uppercase tracking-widest">
                                Envoyez-nous un message
                            </p>
                        </div>

                        {{-- Champs (style pilule) --}}
                        <div class="space-y-4">

                            {{-- Nom --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" placeholder="Votre nom complet"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                            </div>

                            {{-- Email --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email" placeholder="votre@email.com"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                            </div>

                            {{-- Téléphone --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input type="tel" placeholder="+212 6 00 00 00 00"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                            </div>

                            {{-- Sujet --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h10M7 12h10M7 17h6" />
                                    </svg>
                                </div>
                                <select
                                    class="w-full pl-12 pr-10 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition appearance-none cursor-pointer">
                                    <option>Choisir un sujet</option>
                                    <option>Demande de devis</option>
                                    <option>Question générale</option>
                                    <option>Partenariat</option>
                                    <option>Support technique</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Message --}}
                            <div class="relative">
                                <div class="absolute left-4 top-4 text-purple-600 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <textarea rows="3" placeholder="Décrivez votre projet..."
                                    class="w-full pl-12 pr-4 py-3.5 rounded-3xl bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 resize-none focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition"></textarea>
                            </div>

                        </div>

                        {{-- Checkbox RGPD --}}
                        <div class="flex items-start gap-3 mt-5 px-1">
                            <input type="checkbox" id="rgpd"
                                class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-slate-300 rounded cursor-pointer">
                            <label for="rgpd" class="text-xs text-slate-500 cursor-pointer leading-relaxed">
                                J'accepte que mes données soient utilisées pour traiter ma demande,
                                conformément à la <a href="#"
                                    class="text-purple-600 hover:underline font-semibold">politique de confidentialité</a>.
                            </label>
                        </div>

                        {{-- Bouton --}}
                        <button type="button"
                            class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold uppercase tracking-wider transition shadow-lg shadow-purple-500/30 transform hover:-translate-y-0.5 inline-flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Envoyer le message
                        </button>

                    </form>

                    {{-- Vagues décoratives --}}
                    <div class="absolute bottom-0 left-0 right-0 h-32 pointer-events-none">
                        <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150"
                            preserveAspectRatio="none">
                            <path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.6" />
                        </svg>
                        <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150"
                            preserveAspectRatio="none">
                            <path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7" />
                        </svg>
                        <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150"
                            preserveAspectRatio="none">
                            <path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#9333ea" opacity="0.8" />
                        </svg>
                    </div>

                </div>

            </div>

        </div>
    </section>

    {{-- ============================================= --}}
    {{-- MODALE DE CANDIDATURE --}}
    {{-- ============================================= --}}
    <div id="applyModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">

            <button type="button" onclick="closeModal()"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="mb-6">
                <p class="text-xs font-bold tracking-widest text-purple-600 uppercase mb-2">Postuler</p>
                <h3 class="text-2xl font-extrabold text-slate-900">Formulaire de candidature</h3>
                <p class="text-sm text-slate-500 mt-2">
                    Poste : <span id="jobTitle" class="font-semibold text-slate-800">-</span>
                </p>
            </div>

            <form class="space-y-4">

                <div>
                    <label class="block text-xs font-semibold tracking-wide text-slate-600 mb-2">NOM COMPLET *</label>
                    <input type="text" required placeholder="Votre nom et prénom"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold tracking-wide text-slate-600 mb-2">EMAIL *</label>
                        <input type="email" required placeholder="votre@email.com"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wide text-slate-600 mb-2">TÉLÉPHONE *</label>
                        <input type="tel" required placeholder="+212 6 00 00 00 00"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide text-slate-600 mb-2">CV (PDF) *</label>
                    <input type="file" accept=".pdf,.doc,.docx" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold tracking-wide text-slate-600 mb-2">LETTRE DE
                        MOTIVATION</label>
                    <textarea rows="4" placeholder="Quelques lignes sur votre motivation..."
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 resize-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                </div>

                <button type="button"
                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold py-4 rounded-full transition transform hover:-translate-y-0.5 shadow-lg shadow-purple-600/30">
                    Envoyer ma candidature
                </button>
            </form>

        </div>
    </div>

    {{-- ============================================= --}}
    {{-- SCRIPT MODALE --}}
    {{-- ============================================= --}}
    <script>
        function openModal(jobTitle) {
            document.getElementById('jobTitle').textContent = jobTitle;
            const modal = document.getElementById('applyModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('applyModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('applyModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

@endsection
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Digital Agency</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen w-full">

    <div class="flex min-h-screen w-full">

        {{-- ============================================= --}}
        {{-- SECTION GAUCHE : ILLUSTRATION + PRÉSENTATION   --}}
        {{-- Cachée sur mobile (< 1024px)                   --}}
        {{-- ============================================= --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-purple-700 via-purple-600 to-pink-600 text-white flex-col justify-between p-8 relative overflow-hidden">

            {{-- Logo en haut --}}
            <div class="flex items-center gap-3 z-10">
                <div class="bg-white/20 backdrop-blur-sm p-2 rounded-xl">
                    {{-- Icône : Groupe d'utilisateurs (logo) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold tracking-wide">DIGITAL AGENCY</h1>
                    <p class="text-[10px] text-purple-200 uppercase tracking-wider">Espace interne</p>
                </div>
            </div>

            {{-- Contenu central --}}
            <div class="z-10 my-auto">

                {{-- Titre --}}
                <h2 class="text-2xl xl:text-3xl font-extrabold leading-tight mb-3">
                    Bienvenue dans<br>
                    votre <span class="text-pink-200">espace RH.</span>
                </h2>

                {{-- Sous-titre --}}
                <p class="text-purple-100 text-sm mb-6 max-w-md">
                    Gérez vos employés, contrats, congés et recrutements.
                </p>

                {{-- ============================================= --}}
                {{-- ILLUSTRATION SVG (schéma visuel réduit)       --}}
                {{-- ============================================= --}}
                <div class="relative max-w-sm mx-auto">
                    <svg viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-h-72">

                        {{-- Fond circulaire décoratif --}}
                        <circle cx="250" cy="200" r="180" fill="white" fill-opacity="0.05" />
                        <circle cx="250" cy="200" r="130" fill="white" fill-opacity="0.05" />

                        {{-- Nœud central --}}
                        <g transform="translate(250, 200)">
                            <circle r="45" fill="white" fill-opacity="0.15" />
                            <circle r="35" fill="white" />
                            <g transform="translate(-15, -15)">
                                <path d="M10 2a3 3 0 00-3 3v1H5a3 3 0 00-3 3v2a3 3 0 003 3h2v1a3 3 0 003 3h2a3 3 0 003-3v-1h2a3 3 0 003-3v-2a3 3 0 00-3-3h-2V5a3 3 0 00-3-3h-2z" fill="#7c3aed"/>
                                <circle cx="10" cy="10" r="3" fill="white"/>
                            </g>
                        </g>

                        {{-- Nœud 1 : Employés --}}
                        <g transform="translate(100, 80)">
                            <line x1="0" y1="0" x2="100" y2="80" stroke="white" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.2" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <circle cx="8" cy="5" r="3" fill="#7c3aed"/>
                                <path d="M2 15c0-3 2.5-5 6-5s6 2 6 5v1H2v-1z" fill="#7c3aed"/>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Employés</text>
                        </g>

                        {{-- Nœud 2 : Contrats --}}
                        <g transform="translate(400, 80)">
                            <line x1="0" y1="0" x2="-100" y2="80" stroke="white" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.2" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <rect x="3" y="2" width="10" height="14" rx="1" fill="#ec4899"/>
                                <line x1="6" y1="6" x2="10" y2="6" stroke="white" stroke-width="1.2"/>
                                <line x1="6" y1="9" x2="10" y2="9" stroke="white" stroke-width="1.2"/>
                                <line x1="6" y1="12" x2="9" y2="12" stroke="white" stroke-width="1.2"/>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Contrats</text>
                        </g>

                        {{-- Nœud 3 : Congés --}}
                        <g transform="translate(100, 320)">
                            <line x1="0" y1="0" x2="100" y2="-80" stroke="white" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.2" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <rect x="2" y="3" width="12" height="12" rx="1.5" fill="#3b82f6"/>
                                <rect x="2" y="3" width="12" height="3" rx="1.5" fill="#1e40af"/>
                                <circle cx="6" cy="10" r="1" fill="white"/>
                                <circle cx="10" cy="10" r="1" fill="white"/>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Congés</text>
                        </g>

                        {{-- Nœud 4 : Salaires --}}
                        <g transform="translate(400, 320)">
                            <line x1="0" y1="0" x2="-100" y2="-80" stroke="white" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.2" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <circle cx="8" cy="8" r="7" fill="#10b981"/>
                                <text x="8" y="12" text-anchor="middle" fill="white" font-size="10" font-weight="bold">$</text>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Salaires</text>
                        </g>

                        {{-- Nœud 5 : Évaluations --}}
                        <g transform="translate(60, 200)">
                            <line x1="0" y1="0" x2="140" y2="0" stroke="white" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="22" fill="white" fill-opacity="0.2" />
                            <circle r="17" fill="white" />
                            <g transform="translate(-7, -7)">
                                <path d="M7 0l2 4.5 5 .4-3.5 3.3 1 5L7 10.8 2.5 13.2l1-5L0 4.9l5-.4z" fill="#f59e0b"/>
                            </g>
                            <text x="0" y="38" text-anchor="middle" fill="white" font-size="9" font-weight="600">Évaluations</text>
                        </g>

                        {{-- Nœud 6 : Recrutement --}}
                        <g transform="translate(440, 200)">
                            <line x1="0" y1="0" x2="-140" y2="0" stroke="white" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="22" fill="white" fill-opacity="0.2" />
                            <circle r="17" fill="white" />
                            <g transform="translate(-7, -7)">
                                <circle cx="6" cy="6" r="4" fill="none" stroke="#ec4899" stroke-width="1.8"/>
                                <line x1="9" y1="9" x2="13" y2="13" stroke="#ec4899" stroke-width="2" stroke-linecap="round"/>
                            </g>
                            <text x="0" y="38" text-anchor="middle" fill="white" font-size="9" font-weight="600">Recrutement</text>
                        </g>

                    </svg>
                </div>
            </div>

            {{-- Pied de section gauche --}}
            <div class="z-10 text-xs text-purple-200">
                © 2026 Digital Agency — Tous droits réservés
            </div>

            {{-- Formes décoratives flottantes --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- SECTION DROITE : FORMULAIRE DE CONNEXION      --}}
        {{-- ============================================= --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-slate-50">
            <div class="w-full max-w-md">

                {{-- Logo mobile --}}
                <div class="flex lg:hidden items-center gap-3 mb-8 justify-center">
                    <div class="bg-purple-600 p-2 rounded-xl">
                        {{-- Icône : Groupe d'utilisateurs --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-900">DIGITAL AGENCY</span>
                </div>

                {{-- En-tête --}}
                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Connexion</h2>
                    <p class="text-slate-500">Bienvenue ! Connectez-vous à votre espace.</p>
                </div>

                {{-- Affichage des erreurs --}}
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl" role="alert">
                        <p class="font-bold mb-1">Erreur de connexion</p>
                        <ul class="text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulaire --}}
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Email / Matricule --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Email ou Matricule
                        </label>
                        <div class="relative">
                            {{-- Icône : Enveloppe --}}
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="admin@digitalagency.ma"
                                required
                                autofocus
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition duration-200"
                            >
                        </div>
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            {{-- Icône : Cadenas --}}
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition duration-200"
                            >
                        </div>
                    </div>

                    {{-- Options --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-slate-300 rounded cursor-pointer"
                            >
                            <span class="ml-2 text-sm text-slate-600">Se souvenir de moi</span>
                        </label>
                        <a href="#" class="text-sm font-medium text-purple-600 hover:text-purple-800 hover:underline transition">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    {{-- Bouton Se connecter --}}
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-full shadow-lg shadow-purple-600/30 text-sm font-bold text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-300 transform hover:-translate-y-0.5"
                    >
                        Se connecter
                        {{-- Icône : Flèche vers la droite --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>

                </form>

                {{-- Lien retour --}}
                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-purple-600 transition inline-flex items-center gap-1">
                        {{-- Icône : Flèche vers la gauche --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>

                {{-- Pied de page --}}
                <div class="mt-6 text-center text-xs text-slate-400">
                    © 2026 Digital Agency — Tous droits réservés
                </div>

            </div>
        </div>

    </div>

</body>
</html>
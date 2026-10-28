<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Digital Agency</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- ⭐ Alpine.js (nécessaire pour l'icône œil) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen w-full">

    <div class="flex min-h-screen w-full">

        {{-- ============================================= --}}
        {{-- SECTION GAUCHE : ILLUSTRATION + PRÉSENTATION   --}}
        {{-- ============================================= --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-purple-950 to-slate-900 text-white flex-col justify-between p-10 relative overflow-hidden">

            <div class="flex items-center gap-3 z-10">
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-2.5 rounded-xl shadow-lg shadow-purple-500/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-extrabold tracking-wide">DIGITAL AGENCY</h1>
                    <p class="text-[10px] text-purple-300 uppercase tracking-[0.2em] font-medium">Espace interne</p>
                </div>
            </div>

            <div class="z-10 my-auto">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[10px] font-bold tracking-widest text-purple-200 uppercase">Plateforme sécurisée</span>
                </div>

                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight mb-4">
                    Bienvenue dans<br>
                    votre <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">espace RH.</span>
                </h2>

                <p class="text-slate-300 text-sm mb-8 max-w-md leading-relaxed">
                    Gérez vos employés, contrats, congés et recrutements depuis une interface centralisée et sécurisée.
                </p>

                <div class="relative max-w-sm mx-auto">
                    <svg viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-h-72">
                        <circle cx="250" cy="200" r="180" fill="white" fill-opacity="0.03" />
                        <circle cx="250" cy="200" r="130" fill="white" fill-opacity="0.03" />
                        <g transform="translate(250, 200)">
                            <circle r="45" fill="white" fill-opacity="0.1" />
                            <circle r="35" fill="white" />
                            <g transform="translate(-15, -15)">
                                <path d="M10 2a3 3 0 00-3 3v1H5a3 3 0 00-3 3v2a3 3 0 003 3h2v1a3 3 0 003 3h2a3 3 0 003-3v-1h2a3 3 0 003-3v-2a3 3 0 00-3-3h-2V5a3 3 0 00-3-3h-2z" fill="#7c3aed"/>
                                <circle cx="10" cy="10" r="3" fill="white"/>
                            </g>
                        </g>
                        <g transform="translate(100, 80)">
                            <line x1="0" y1="0" x2="100" y2="80" stroke="white" stroke-opacity="0.15" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.15" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <circle cx="8" cy="5" r="3" fill="#7c3aed"/>
                                <path d="M2 15c0-3 2.5-5 6-5s6 2 6 5v1H2v-1z" fill="#7c3aed"/>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Employés</text>
                        </g>
                        <g transform="translate(400, 80)">
                            <line x1="0" y1="0" x2="-100" y2="80" stroke="white" stroke-opacity="0.15" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.15" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <rect x="3" y="2" width="10" height="14" rx="1" fill="#ec4899"/>
                                <line x1="6" y1="6" x2="10" y2="6" stroke="white" stroke-width="1.2"/>
                                <line x1="6" y1="9" x2="10" y2="9" stroke="white" stroke-width="1.2"/>
                                <line x1="6" y1="12" x2="9" y2="12" stroke="white" stroke-width="1.2"/>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Contrats</text>
                        </g>
                        <g transform="translate(100, 320)">
                            <line x1="0" y1="0" x2="100" y2="-80" stroke="white" stroke-opacity="0.15" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.15" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <rect x="2" y="3" width="12" height="12" rx="1.5" fill="#3b82f6"/>
                                <rect x="2" y="3" width="12" height="3" rx="1.5" fill="#1e40af"/>
                                <circle cx="6" cy="10" r="1" fill="white"/>
                                <circle cx="10" cy="10" r="1" fill="white"/>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Congés</text>
                        </g>
                        <g transform="translate(400, 320)">
                            <line x1="0" y1="0" x2="-100" y2="-80" stroke="white" stroke-opacity="0.15" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="26" fill="white" fill-opacity="0.15" />
                            <circle r="20" fill="white" />
                            <g transform="translate(-8, -8)">
                                <circle cx="8" cy="8" r="7" fill="#10b981"/>
                                <text x="8" y="12" text-anchor="middle" fill="white" font-size="10" font-weight="bold">$</text>
                            </g>
                            <text x="0" y="42" text-anchor="middle" fill="white" font-size="10" font-weight="600">Salaires</text>
                        </g>
                        <g transform="translate(60, 200)">
                            <line x1="0" y1="0" x2="140" y2="0" stroke="white" stroke-opacity="0.15" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="22" fill="white" fill-opacity="0.15" />
                            <circle r="17" fill="white" />
                            <g transform="translate(-7, -7)">
                                <path d="M7 0l2 4.5 5 .4-3.5 3.3 1 5L7 10.8 2.5 13.2l1-5L0 4.9l5-.4z" fill="#f59e0b"/>
                            </g>
                            <text x="0" y="38" text-anchor="middle" fill="white" font-size="9" font-weight="600">Évaluations</text>
                        </g>
                        <g transform="translate(440, 200)">
                            <line x1="0" y1="0" x2="-140" y2="0" stroke="white" stroke-opacity="0.15" stroke-width="1.5" stroke-dasharray="5,5"/>
                            <circle r="22" fill="white" fill-opacity="0.15" />
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

            <div class="z-10 text-xs text-slate-500">
                © 2026 Digital Agency — Tous droits réservés
            </div>

            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-600 rounded-full filter blur-3xl opacity-20"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-pink-600 rounded-full filter blur-3xl opacity-20"></div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- SECTION DROITE : FORMULAIRE DE CONNEXION      --}}
        {{-- ============================================= --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-slate-50">
            <div class="w-full max-w-md">

                <div class="flex lg:hidden items-center gap-3 mb-8 justify-center">
                    <div class="bg-gradient-to-br from-purple-600 to-pink-600 p-2.5 rounded-xl shadow-lg shadow-purple-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-slate-900">DIGITAL AGENCY</span>
                </div>

                <div class="mb-8">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Connexion sécurisée
                    </div>
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Connexion</h2>
                    <p class="text-slate-500">Bienvenue ! Connectez-vous à votre espace.</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl bg-emerald-50 border border-emerald-100 p-4">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-sm text-emerald-800">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-xl bg-red-50 border border-red-100 p-4">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-sm text-red-800">
                            <p class="font-bold mb-1">Erreur de connexion</p>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- ⭐ FORMULAIRE avec Alpine.js --}}
                <form action="{{ route('login') }}" method="POST" class="space-y-5" x-data="{ showPassword: false }" x-cloak>
                    @csrf

                    {{-- Email / Matricule --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Email ou Matricule
                        </label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-purple-600 pointer-events-none transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="admin@madatech.test ou ADM001"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition-all duration-200"
                            >
                        </div>
                    </div>

                    {{-- Mot de passe avec œil --}}
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Mot de passe
                        </label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-purple-600 pointer-events-none transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            
                            {{-- ✅ Input avec type fixe password au départ, puis dynamique --}}
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                                class="w-full pl-11 pr-12 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition-all duration-200"
                            >
                            
                            {{-- ✅ Bouton œil --}}
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500/30"
                                :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                                tabindex="-1"
                            >
                                {{-- Œil ouvert (mot de passe masqué) --}}
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{-- Œil barré (mot de passe visible) --}}
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Se souvenir de moi --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-slate-300 rounded cursor-pointer"
                            >
                            <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Se souvenir de moi</span>
                        </label>
                    </div>

                    {{-- Bouton Se connecter --}}
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl shadow-lg shadow-purple-600/30 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-4 focus:ring-purple-500/30 transition-all duration-300 transform hover:-translate-y-0.5"
                    >
                        Se connecter
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>

                </form>

                @if (config('app.env') === 'local')
                    <div class="mt-8 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                        <p class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            Comptes de test
                        </p>
                        <ul class="text-xs text-slate-700 space-y-1.5">
                            <li><strong class="text-purple-700">Admin :</strong> admin@madatech.test / Test1234!</li>
                            <li><strong class="text-purple-700">RH :</strong> rh@madatech.test / Test1234!</li>
                            <li><strong class="text-purple-700">Employé :</strong> mialy@madatech.test / Test1234!</li>
                            <li><strong class="text-purple-700">Par matricule :</strong> ADM001 / Test1234!</li>
                        </ul>
                    </div>
                @endif

                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-purple-600 transition inline-flex items-center gap-1.5 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>

                <div class="mt-6 text-center text-xs text-slate-400">
                    © 2026 Digital Agency — Tous droits réservés
                </div>

            </div>
        </div>

    </div>

</body>
</html>
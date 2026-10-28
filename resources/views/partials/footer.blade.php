{{-- ============================================= --}}
{{-- FOOTER PRINCIPAL (couleur violette)            --}}
{{-- ============================================= --}}
<footer class="relative bg-slate-950 text-white pt-16 pb-8 overflow-hidden">
    
    {{-- Formes décoratives d'arrière-plan --}}
    <div class="pointer-events-none absolute -top-32 -right-32 w-96 h-96 bg-purple-600 rounded-full mix-blend-screen filter blur-3xl opacity-10"></div>
    <div class="pointer-events-none absolute -bottom-32 -left-32 w-96 h-96 bg-pink-600 rounded-full mix-blend-screen filter blur-3xl opacity-10"></div>

    <div class="relative max-w-7xl mx-auto px-6 md:px-[100px]">

        {{-- Grille principale --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-14">

            {{-- ============================================= --}}
            {{-- Colonne 1 : Logo + description + réseaux      --}}
            {{-- ============================================= --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="bg-gradient-to-br from-purple-600 to-pink-600 p-2 rounded-xl shadow-lg shadow-purple-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-base text-white tracking-tight leading-tight">DIGITAL AGENCY</span>
                        <span class="text-[9px] text-purple-300 uppercase tracking-widest font-medium">Solutions digitales</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed mb-5">
                    Agence digitale marocaine spécialisée dans la création de sites web modernes et performants.
                </p>

                {{-- Réseaux sociaux --}}
                <div class="flex gap-2">

                    {{-- LinkedIn --}}
                    <a href="https://linkedin.com" target="_blank" rel="noopener" class="group w-9 h-9 rounded-xl bg-white/5 ring-1 ring-inset ring-white/10 hover:bg-purple-600 hover:ring-purple-500 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5" title="LinkedIn">
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>

                    {{-- Twitter / X --}}
                    <a href="https://twitter.com" target="_blank" rel="noopener" class="group w-9 h-9 rounded-xl bg-white/5 ring-1 ring-inset ring-white/10 hover:bg-purple-600 hover:ring-purple-500 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5" title="Twitter / X">
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://facebook.com" target="_blank" rel="noopener" class="group w-9 h-9 rounded-xl bg-white/5 ring-1 ring-inset ring-white/10 hover:bg-purple-600 hover:ring-purple-500 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5" title="Facebook">
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://instagram.com" target="_blank" rel="noopener" class="group w-9 h-9 rounded-xl bg-white/5 ring-1 ring-inset ring-white/10 hover:bg-pink-600 hover:ring-pink-500 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5" title="Instagram">
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- Colonne 2 : Nos services                      --}}
            {{-- ============================================= --}}
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-purple-300 mb-4">Services</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#offres" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>UI/UX Design</a></li>
                    <li><a href="#offres" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Branding</a></li>
                    <li><a href="#offres" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Web Design</a></li>
                    <li><a href="#offres" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Mobile Design</a></li>
                    <li><a href="#offres" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Développement</a></li>
                </ul>
            </div>

            {{-- ============================================= --}}
            {{-- Colonne 3 : Notre entreprise                  --}}
            {{-- ============================================= --}}
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-purple-300 mb-4">Entreprise</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="{{ route('home') }}#apropos" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>À propos</a></li>
                    <li><a href="{{ route('home') }}#offres" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Carrières</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Blog</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Partenaires</a></li>
                    <li><a href="{{ route('home') }}#contact" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Contact</a></li>
                </ul>
            </div>

            {{-- ============================================= --}}
            {{-- Colonne 4 : Ressources                        --}}
            {{-- ============================================= --}}
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-purple-300 mb-4">Ressources</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Documentation</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Centre d'aide</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>API</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Statut du service</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200 inline-flex items-center gap-1.5 group"><span class="w-1 h-1 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>Webinaires</a></li>
                </ul>
            </div>

            {{-- ============================================= --}}
            {{-- Colonne 5 : Newsletter                        --}}
            {{-- ============================================= --}}
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-purple-300 mb-4">Restez informé</h4>
                <p class="text-xs text-slate-400 mb-4 leading-relaxed">Les nouveautés produit, une fois par mois.</p>
                <form action="#" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <input type="email" name="email" required placeholder="Votre email"
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    <button type="submit" class="group w-full inline-flex items-center justify-center gap-1.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white text-xs font-bold py-2.5 rounded-lg transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-600/30">
                        <span>S'abonner</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>

        </div>

        {{-- ============================================= --}}
        {{-- Séparateur + Copyright                        --}}
        {{-- ============================================= --}}
        <div class="border-t border-white/5 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                © {{ date('Y') }} <span class="font-semibold text-slate-400">Digital Agency</span>. Tous droits réservés.
            </p>
            <div class="flex flex-wrap items-center gap-1 text-xs text-slate-500">
                <a href="#" class="px-2.5 py-1 rounded-lg hover:bg-white/5 hover:text-white transition-colors duration-200">Mentions légales</a>
                <span class="text-slate-700">·</span>
                <a href="#" class="px-2.5 py-1 rounded-lg hover:bg-white/5 hover:text-white transition-colors duration-200">Confidentialité</a>
                <span class="text-slate-700">·</span>
                <a href="#" class="px-2.5 py-1 rounded-lg hover:bg-white/5 hover:text-white transition-colors duration-200">CGU</a>
                <span class="text-slate-700">·</span>
                <a href="#" class="px-2.5 py-1 rounded-lg hover:bg-white/5 hover:text-white transition-colors duration-200">Cookies</a>
            </div>
        </div>

    </div>
</footer>
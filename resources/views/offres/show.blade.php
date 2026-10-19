@extends('layouts.app')

@section('title', $offre->titre)

@section('content')

@php
    // ⭐ EMAIL DE RÉCEPTION DES CANDIDATURES
    $emailCandidature = 'jackyrakotarison19@gmail.com';

    $badgeColor = match($offre->type_contrat) {
        'CDI'       => ['bg' => 'bg-purple-100',  'text' => 'text-purple-700',  'gradient' => 'from-purple-500 to-purple-600'],
        'CDD'       => ['bg' => 'bg-pink-100',    'text' => 'text-pink-700',    'gradient' => 'from-pink-500 to-pink-600'],
        'Stage'     => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'gradient' => 'from-blue-500 to-blue-600'],
        'Freelance' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'gradient' => 'from-emerald-500 to-emerald-600'],
        'Intérim'   => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'gradient' => 'from-amber-500 to-amber-600'],
        default     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'gradient' => 'from-slate-500 to-slate-600'],
    };

    $sujetEmail = urlencode("Candidature : {$offre->titre}");
    $corpsEmail = urlencode(
        "Bonjour,\n\n" .
        "Je souhaite postuler à l'offre suivante publiée sur votre site :\n\n" .
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
        "📌 Poste : {$offre->titre}\n" .
        "💼 Type de contrat : {$offre->type_contrat}\n" .
        "📍 Lieu : {$offre->lieu}\n" .
        "🔖 Référence : {$offre->slug}\n" .
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n" .
        "Vous trouverez mon CV et ma lettre de motivation en pièces jointes.\n\n" .
        "Cordialement,"
    );
    $mailtoLink = "https://mail.google.com/mail/?view=cm&fs=1&to={$emailCandidature}&su={$sujetEmail}&body={$corpsEmail}";
@endphp

{{-- ============================================= --}}
{{-- HEADER AVEC FIL D'ARIANE --}}
{{-- ============================================= --}}
<section class="bg-gradient-to-br from-purple-100 via-pink-50 to-purple-100 px-6 md:px-[100px] py-12">
    <div class="max-w-5xl mx-auto">
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Retour à l'accueil
        </a>
    </div>
</section>

{{-- ============================================= --}}
{{-- CONTENU PRINCIPAL --}}
{{-- ============================================= --}}
<section class="bg-white px-6 md:px-[100px] pb-24">
    <div class="max-w-5xl mx-auto -mt-8">

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

            {{-- EN-TÊTE DÉGRADÉ --}}
            <div class="bg-gradient-to-r {{ $badgeColor['gradient'] }} p-8 md:p-10 text-white">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="text-xs font-bold bg-white/20 backdrop-blur-sm border border-white/30 px-3 py-1.5 rounded-full uppercase tracking-wider">
                        {{ $offre->type_contrat }}
                    </span>
                    <span class="text-xs text-white/90">
                        Publié {{ $offre->publiee_le?->diffForHumans() ?? $offre->created_at->diffForHumans() }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold mb-4">
                    {{ $offre->titre }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-sm text-white/90">
                    <span class="flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $offre->lieu }}
                    </span>
                    @if($offre->experience)
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $offre->experience }}
                        </span>
                    @endif
                    @if($offre->salaire)
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $offre->salaire }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- CORPS --}}
            <div class="p-8 md:p-10">

                {{-- RÉSUMÉ RAPIDE --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Type</p>
                        <p class="text-sm font-bold text-slate-800">{{ $offre->type_contrat }}</p>
                    </div>

                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Lieu</p>
                        <p class="text-sm font-bold text-slate-800">{{ $offre->lieu }}</p>
                    </div>

                    @if($offre->experience)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Expérience</p>
                            <p class="text-sm font-bold text-slate-800">{{ $offre->experience }}</p>
                        </div>
                    @endif

                    @if($offre->niveau_etude)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Niveau</p>
                            <p class="text-sm font-bold text-slate-800">{{ $offre->niveau_etude }}</p>
                        </div>
                    @endif
                </div>

                {{-- DESCRIPTION DU POSTE --}}
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-extrabold text-slate-900">
                            Description du poste
                        </h2>
                    </div>
                    <div class="bg-slate-50 border-l-4 {{ str_replace('bg-', 'border-', $badgeColor['bg']) }} rounded-r-2xl p-5 md:p-6">
                        <div class="text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $offre->description }}
                        </div>
                    </div>
                </div>

                {{-- MISSIONS --}}
                @if($offre->missions)
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-extrabold text-slate-900">
                                Missions principales
                            </h2>
                        </div>
                        <div class="bg-slate-50 border-l-4 {{ str_replace('bg-', 'border-', $badgeColor['bg']) }} rounded-r-2xl p-5 md:p-6">
                            <div class="text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ $offre->missions }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PROFIL RECHERCHÉ --}}
                @if($offre->profil_recherche)
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-extrabold text-slate-900">
                                Profil recherché
                            </h2>
                        </div>
                        <div class="bg-slate-50 border-l-4 {{ str_replace('bg-', 'border-', $badgeColor['bg']) }} rounded-r-2xl p-5 md:p-6">
                            <div class="text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ $offre->profil_recherche }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- CRITÈRES RÉSUMÉS --}}
                @if($offre->experience || $offre->niveau_etude || $offre->salaire || $offre->date_limite)
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-extrabold text-slate-900">
                                Critères du poste
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($offre->experience)
                                <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md transition">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Expérience requise</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $offre->experience }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($offre->niveau_etude)
                                <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md transition">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Niveau d'étude</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $offre->niveau_etude }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($offre->salaire)
                                <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md transition">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Salaire</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $offre->salaire }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($offre->date_limite)
                                <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md transition">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br {{ $badgeColor['gradient'] }} flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Date limite</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $offre->date_limite->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- CTA POSTULER --}}
                <div class="pt-8 border-t border-slate-100">
                    <div class="bg-gradient-to-br {{ $badgeColor['gradient'] }} rounded-2xl p-6 md:p-8 text-center text-white">
                        <h3 class="text-xl font-extrabold mb-2">
                            Intéressé(e) par ce poste ?
                        </h3>
                        <p class="text-sm text-white/90 mb-6">
                            Envoyez-nous votre candidature par email avec votre CV et lettre de motivation.
                        </p>

                        <a href="{{ $mailtoLink }}" target="_blank"
                            class="inline-flex items-center justify-center gap-2 bg-white text-slate-900 hover:bg-slate-100 font-bold px-8 py-4 rounded-full transition transform hover:-translate-y-1 shadow-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Postuler maintenant
                        </a>

                        <p class="text-xs text-white/70 mt-4">
                            Gmail s'ouvrira avec l'objet pré-rempli. N'oubliez pas de joindre votre CV.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- AUTRES OFFRES --}}
        @if(isset($autresOffres) && $autresOffres->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-extrabold text-slate-900 mb-6">Autres offres similaires</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($autresOffres as $autre)
                        @php
                            $autreBadge = match($autre->type_contrat) {
                                'CDI'       => ['bg' => 'bg-purple-100',  'text' => 'text-purple-700'],
                                'CDD'       => ['bg' => 'bg-pink-100',    'text' => 'text-pink-700'],
                                'Stage'     => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700'],
                                'Freelance' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                                'Intérim'   => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700'],
                                default     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700'],
                            };
                        @endphp

                        <a href="{{ route('offres.show', $autre->slug) }}"
                            class="group bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-lg hover:border-purple-300 transition">
                            <span class="text-xs font-bold {{ $autreBadge['bg'] }} {{ $autreBadge['text'] }} px-2.5 py-1 rounded-full inline-block mb-3">
                                {{ $autre->type_contrat }}
                            </span>
                            <h3 class="font-bold text-slate-900 text-sm mb-2 group-hover:text-purple-600 transition line-clamp-2">
                                {{ $autre->titre }}
                            </h3>
                            <p class="text-xs text-slate-500 flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $autre->lieu }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

@endsection
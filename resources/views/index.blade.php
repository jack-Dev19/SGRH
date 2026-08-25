@extends('layouts.app');
@section('title', 'Accueil')
@section('content')
    <!-- ============================================= -->
    <!-- HERO SECTION                                   -->
    <!-- ============================================= -->
    <section id="Accueil" class="bg-gradient-to-b from-[#0a1730] to-[#0f2148] px-6 py-24">
        <div class="max-w-3xl mx-auto text-center">

            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-1.5 mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span class="text-xs font-medium text-blue-200">Système de Gestion RH v2.5</span>
            </div>

            <!-- Titre -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-[1.1] mb-8">
                <span class="text-white">Gérez vos ressources</span><br>
                <span class="text-white">humaines avec</span><br>
                <span class="text-blue-500">intelligence et précision.</span>
            </h1>

            <!-- Sous-titre -->
            <p class="text-slate-400 text-base sm:text-lg leading-relaxed max-w-xl mx-auto mb-10">
                SGRH centralise toutes vos données RH — employés, contrats, congés,
                paie, recrutement — dans une interface unifiée, sécurisée et intuitive.
            </p>

            <!-- Boutons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                <button
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-medium px-6 py-3.5 rounded-xl transition flex items-center justify-center gap-2">
                    Accéder à l'application <span>→</span>
                </button>
                <button
                    class="w-full sm:w-auto bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium px-6 py-3.5 rounded-xl transition">
                    Voir les offres d'emploi
                </button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="bg-white/5 border border-white/10 rounded-2xl py-6">
                    <p class="text-3xl font-extrabold text-blue-500 mb-1">52</p>
                    <p class="text-xs text-slate-400">Employés</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl py-6">
                    <p class="text-3xl font-extrabold text-blue-500 mb-1">6</p>
                    <p class="text-xs text-slate-400">Départements</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl py-6">
                    <p class="text-3xl font-extrabold text-blue-500 mb-1">2012</p>
                    <p class="text-xs text-slate-400">Fondée en</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl py-6">
                    <p class="text-3xl font-extrabold text-blue-500 mb-1">98%</p>
                    <p class="text-xs text-slate-400">Satisfaction</p>
                </div>

            </div>

        </div>
    </section>

    <!-- ============================================= -->
    <!-- SECTION FONCTIONNALITÉS                        -->
    <!-- ============================================= -->
    <section  class="max-w-5xl mx-auto px-6 py-24">

        <div class="text-center mb-14">
            <p class="text-xs font-bold tracking-widest text-blue-600 mb-3">FONCTIONNALITÉS</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">Tout ce dont votre RH a besoin</h2>
            <p class="text-slate-500 max-w-lg mx-auto leading-relaxed">
                12 modules intégrés pour gérer l'ensemble du cycle de vie de vos employés.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-100 mb-4 text-lg">🧑‍💼</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Gestion des employés</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Dossiers complets, matricules, photos, historique des
                    postes et des contrats.</p>
            </div>

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-violet-50 mb-4 text-lg">📄</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Contrats & Congés</h3>
                <p class="text-sm text-slate-500 leading-relaxed">CDI, CDD, Stage. Workflow de validation des congés en 3
                    niveaux d'approbation.</p>
            </div>

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-50 mb-4 text-lg">⏰</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Présences & Pointage</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Suivi des arrivées, départs, retards et absences. Calcul
                    automatique des heures.</p>
            </div>

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 mb-4 text-lg">🏅</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Salaires & Fiches de paie</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Calcul automatique salaire net = base + primes + heures
                    supp − retenues. PDF.</p>
            </div>

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-pink-50 mb-4 text-lg">🔍</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Recrutement</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Offres d'emploi, candidatures, gestion des CV et
                    planification des entretiens.</p>
            </div>

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-yellow-50 mb-4 text-lg">⭐</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Évaluations</h3>
                <p class="text-sm text-slate-500 leading-relaxed">5 critères de performance avec barres de progression et
                    note globale.</p>
            </div>

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-50 mb-4 text-lg">📊</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Tableau de bord</h3>
                <p class="text-sm text-slate-500 leading-relaxed">KPI en temps réel, graphiques de tendance, masse
                    salariale, effectifs.</p>
            </div>

            <div class="rounded-xl border border-slate-200 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-orange-50 mb-4 text-lg">🔐</div>
                <h3 class="font-semibold text-slate-900 mb-1.5">Sécurité & Rôles</h3>
                <p class="text-sm text-slate-500 leading-relaxed">RBAC à 4 niveaux, journal d'activité, anti-brute-force,
                    sessions sécurisées.</p>
            </div>

        </div>

    </section>

    <!-- ============================================= -->
    <!-- SECTION CTA (bandeau sombre)                   -->
    <!-- ============================================= -->
    <section class="bg-gradient-to-b from-[#0a1730] to-[#0f2148] px-6 py-20">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Prêt à moderniser votre RH ?</h2>
            <p class="text-slate-400 leading-relaxed mb-8">
                Rejoignez les entreprises qui font confiance à
                <span class="text-blue-400">SGRH</span>
                pour piloter leurs ressources humaines.
            </p>
            <button
                class="bg-blue-600 hover:bg-blue-500 text-white font-medium px-6 py-3.5 rounded-xl transition inline-flex items-center gap-2">
                Accéder à l'application <span>→</span>
            </button>
        </div>
    </section>

    <!-- Page A propos -->
    <section id="apropos" class="max-w-5xl mx-auto px-6 py-20">

        <!-- En-tête -->
        <div class="text-center mb-16">
            <p class="text-xs font-bold tracking-widest text-blue-600 mb-3">NOTRE ENTREPRISE</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">À propos de SGRH</h1>
            <p class="text-slate-500 max-w-xl mx-auto leading-relaxed">
                Fondée en 2012 à Casablanca, SGRH Technologies est spécialisée dans le
                développement de solutions de gestion des ressources humaines pour les
                entreprises marocaines et africaines.
            </p>
        </div>

        <!-- Notre mission + stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-20">

            <div>
                <h2 class="text-xl font-bold text-slate-900 mb-4">Notre mission</h2>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    Nous croyons que chaque entreprise mérite des outils RH modernes,
                    intuitifs et sécurisés. Notre mission est de démocratiser la gestion des
                    ressources humaines en offrant une solution complète, abordable et
                    adaptée au contexte local.
                </p>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Depuis 2012, nous accompagnons plus de 200 entreprises dans leur
                    transformation digitale RH, de la PME à la grande entreprise, dans tous
                    les secteurs d'activité.
                </p>
            </div>

            <div class="bg-gradient-to-br from-[#0a1730] to-[#0f2148] rounded-2xl p-8">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-3xl font-extrabold text-blue-400 mb-1">200+</p>
                        <p class="text-xs text-slate-400">Entreprises clientes</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-blue-400 mb-1">52</p>
                        <p class="text-xs text-slate-400">Employés SGRH</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-blue-400 mb-1">12</p>
                        <p class="text-xs text-slate-400">Ans d'expérience</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-blue-400 mb-1">98%</p>
                        <p class="text-xs text-slate-400">Taux de satisfaction</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Nos valeurs -->
        <div class="mb-20">
            <h2 class="text-xl font-bold text-slate-900 text-center mb-8">Nos valeurs</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">

                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                    <div class="text-2xl mb-3">🚀</div>
                    <h3 class="font-semibold text-slate-900 text-sm mb-1.5">Innovation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Nous adoptons les meilleures technologies pour rester
                        compétitifs dans un marché en constante évolution.</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                    <div class="text-2xl mb-3">🤝</div>
                    <h3 class="font-semibold text-slate-900 text-sm mb-1.5">Collaboration</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Chaque réussite est le fruit d'un travail d'équipe. La
                        synergie entre nos départements est notre force.</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                    <div class="text-2xl mb-3">⭐</div>
                    <h3 class="font-semibold text-slate-900 text-sm mb-1.5">Excellence</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Nous visons la qualité dans chacune de nos actions, de
                        la relation client au service interne.</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                    <div class="text-2xl mb-3">🌱</div>
                    <h3 class="font-semibold text-slate-900 text-sm mb-1.5">Développement</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Nous investissons dans la formation continue et
                        l'évolution de chaque membre de notre équipe.</p>
                </div>

            </div>
        </div>

        <!-- Notre équipe de direction -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 text-center mb-8">Notre équipe de direction</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

                <div class="rounded-xl border border-slate-200 p-6 text-center">
                    <div
                        class="w-14 h-14 mx-auto flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold mb-3">
                        AM</div>
                    <p class="font-semibold text-slate-900 text-sm">Ahmed Mansouri</p>
                    <p class="text-xs text-slate-500">Directeur Général</p>
                </div>

                <div class="rounded-xl border border-slate-200 p-6 text-center">
                    <div
                        class="w-14 h-14 mx-auto flex items-center justify-center rounded-full bg-red-500 text-white font-bold mb-3">
                        NT</div>
                    <p class="font-semibold text-slate-900 text-sm">Nadia Tazi</p>
                    <p class="text-xs text-slate-500">Responsable RH</p>
                </div>

                <div class="rounded-xl border border-slate-200 p-6 text-center">
                    <div
                        class="w-14 h-14 mx-auto flex items-center justify-center rounded-full bg-purple-500 text-white font-bold mb-3">
                        AB</div>
                    <p class="font-semibold text-slate-900 text-sm">Amira Bensalem</p>
                    <p class="text-xs text-slate-500">Directrice Financière</p>
                </div>

                <div class="rounded-xl border border-slate-200 p-6 text-center">
                    <div
                        class="w-14 h-14 mx-auto flex items-center justify-center rounded-full bg-slate-800 text-white font-bold mb-3">
                        ML</div>
                    <p class="font-semibold text-slate-900 text-sm">Mehdi Larbi</p>
                    <p class="text-xs text-slate-500">Responsable IT</p>
                </div>

            </div>
        </div>

    </section>

    <!-- ============================================= -->
    <!-- SECTION RECRUTEMENT                            -->
    <!-- ============================================= -->
    <section id="carrieres" class="max-w-5xl mx-auto px-6 py-20">

        <div class="text-center mb-14">
            <p class="text-xs font-bold tracking-widest text-blue-600 mb-3">RECRUTEMENT</p>
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4">Rejoignez notre équipe</h2>
            <p class="text-slate-500 max-w-xl mx-auto leading-relaxed">
                Nous recrutons des talents passionnés pour renforcer nos équipes.<br>
                Découvrez nos offres d'emploi actuelles.
            </p>
        </div>

        <!-- Avantages -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-16">

            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                <div
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 mb-3 text-lg">
                    🏥</div>
                <h3 class="font-semibold text-slate-900 mb-1">Mutuelle santé</h3>
                <p class="text-sm text-slate-500">Couverture médicale complète</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                <div
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 mb-3 text-lg">
                    📚</div>
                <h3 class="font-semibold text-slate-900 mb-1">Formation continue</h3>
                <p class="text-sm text-slate-500">Budget formation annuel</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                <div
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 mb-3 text-lg">
                    ⚖️</div>
                <h3 class="font-semibold text-slate-900 mb-1">Équilibre vie pro/perso</h3>
                <p class="text-sm text-slate-500">Télétravail partiel possible</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                <div
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 mb-3 text-lg">
                    🎯</div>
                <h3 class="font-semibold text-slate-900 mb-1">Évolution de carrière</h3>
                <p class="text-sm text-slate-500">Plans de carrière structurés</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                <div
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 mb-3 text-lg">
                    🎉</div>
                <h3 class="font-semibold text-slate-900 mb-1">Événements d'équipe</h3>
                <p class="text-sm text-slate-500">Team building réguliers</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5">
                <div
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 mb-3 text-lg">
                    💼</div>
                <h3 class="font-semibold text-slate-900 mb-1">Environnement moderne</h3>
                <p class="text-sm text-slate-500">Locaux confortables à CFC</p>
            </div>

        </div>

        <!-- Offres ouvertes -->
        <div>
            <h3 class="text-xl font-bold text-slate-900 mb-5">Offres ouvertes (3)</h3>

            <div class="space-y-4">

                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-slate-200 rounded-xl p-5">
                    <div>
                        <h4 class="font-semibold text-slate-900 mb-1.5">Développeur React Senior</h4>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1.5">🖥️ Informatique</span>
                            <span class="flex items-center gap-1.5">📄 CDI</span>
                            <span class="flex items-center gap-1.5">📅 Limite : 2025-08-15</span>
                            <span class="flex items-center gap-1.5">👥 14 candidatures</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span
                            class="text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">Ouverte</span>
                        <button
                            class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Postuler</button>
                    </div>
                </div>

                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-slate-200 rounded-xl p-5">
                    <div>
                        <h4 class="font-semibold text-slate-900 mb-1.5">Chef de projet digital</h4>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1.5">🖥️ Informatique</span>
                            <span class="flex items-center gap-1.5">📄 CDI</span>
                            <span class="flex items-center gap-1.5">📅 Limite : 2025-09-01</span>
                            <span class="flex items-center gap-1.5">👥 8 candidatures</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span
                            class="text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">Ouverte</span>
                        <button
                            class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Postuler</button>
                    </div>
                </div>

                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-slate-200 rounded-xl p-5">
                    <div>
                        <h4 class="font-semibold text-slate-900 mb-1.5">Chargé(e) de recrutement</h4>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1.5">🖥️ Ressources Humaines</span>
                            <span class="flex items-center gap-1.5">📄 CDD</span>
                            <span class="flex items-center gap-1.5">📅 Limite : 2025-09-20</span>
                            <span class="flex items-center gap-1.5">👥 3 candidatures</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span
                            class="text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">Ouverte</span>
                        <button
                            class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">Postuler</button>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <!-- ============================================= -->
    <!-- SECTION CONTACT                                -->
    <!-- ============================================= -->
    <section id="contact" class="max-w-5xl mx-auto px-6 py-20 border-t border-slate-100">

        <div class="text-center mb-14">
            <p class="text-xs font-bold tracking-widest text-blue-600 mb-3">CONTACT</p>
            <h2 class="text-4xl font-extrabold text-slate-900">Contactez-nous</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            <!-- Coordonnées -->
            <div>
                <h3 class="font-bold text-slate-900 mb-6">Nos coordonnées</h3>

                <div class="space-y-6">

                    <div class="flex gap-4">
                        <div class="w-9 h-9 shrink-0 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            📍</div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">ADRESSE</p>
                            <p class="text-sm text-slate-700 leading-relaxed">Tour Casablanca Finance City, Bloc
                                A,<br>Casablanca 20000</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-9 h-9 shrink-0 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            📞</div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">TÉLÉPHONE</p>
                            <p class="text-sm text-slate-700">+212 5 22 12 34 56</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-9 h-9 shrink-0 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            ✉️</div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">EMAIL</p>
                            <p class="text-sm text-slate-700">contact@sgrh.ma</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-9 h-9 shrink-0 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            🕒</div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">HORAIRES</p>
                            <p class="text-sm text-slate-700">Lun–Ven : 08h30 – 17h30</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Formulaire -->
            <div class="border border-slate-200 rounded-2xl p-8">
                <form class="space-y-5">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NOM COMPLET</label>
                            <input type="text" placeholder="Votre nom"
                                class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">EMAIL</label>
                            <input type="email" placeholder="votre@email.com"
                                class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">SUJET</label>
                        <select
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Choisir un sujet</option>
                            <option>Question générale</option>
                            <option>Recrutement</option>
                            <option>Partenariat</option>
                            <option>Support</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">MESSAGE</label>
                        <textarea rows="5" placeholder="Votre message..."
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <button type="button"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium py-3 rounded-lg transition">
                        Envoyer le message
                    </button>

                </form>
            </div>

        </div>

    </section>
@endsection
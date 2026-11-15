@extends('layouts.dashboard')
@section('title', 'Fiche employé — ' . $employe->nom_complet)

@section('content')

<div class="max-w-6xl mx-auto py-6 px-4">

    {{-- FIL D'ARIANE --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">

        <a href="{{ route('gestion.employes.index') }}"
           class="hover:text-purple-600 transition">
            Employés
        </a>

        <span>/</span>

        <span class="font-semibold text-slate-700">
            Fiche de {{ $employe->nom_complet }}
        </span>

    </div>
    {{-- EN-TÊTE DE LA FICHE EMPLOYÉ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

            {{-- IDENTITÉ --}}
            <div class="flex items-center gap-4">

                {{-- Initiales de l'employé --}}
                <div class="w-16 h-16 rounded-2xl bg-purple-100 text-purple-700
                            flex items-center justify-center text-xl font-extrabold">
                    {{ $employe->initiales }}
                </div>

                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800">
                        {{ $employe->nom_complet }}
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Matricule :
                        <span class="font-mono font-bold text-slate-700">
                            {{ $employe->matricule }}
                        </span>
                    </p>
                </div>

            </div>

            {{-- STATUT ET ACTIONS --}}
            <div class="flex flex-wrap items-center gap-3">

                {{-- Statut --}}
                @if($employe->statut === 'actif')
                    <span class="px-3 py-1.5 rounded-full text-sm font-bold
                                 bg-emerald-100 text-emerald-700">
                        Actif
                    </span>
                @elseif($employe->statut === 'inactif')
                    <span class="px-3 py-1.5 rounded-full text-sm font-bold
                                 bg-amber-100 text-amber-700">
                        Inactif
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-full text-sm font-bold
                                 bg-red-100 text-red-700">
                        Sorti
                    </span>
                @endif

                {{-- Consulter le QR Code --}}
                <a href="{{ route('gestion.employes.show', $employe) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5
                          bg-purple-600 hover:bg-purple-700 text-white
                          text-sm font-semibold rounded-xl transition">
                        
                    Voir le QR Code
                </a>

                {{-- Modifier --}}
                <a href="{{ route('gestion.employes.edit', $employe) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5
                          bg-purple-600 hover:bg-purple-700 text-white
                          text-sm font-bold rounded-xl transition">
                    Modifier
                </a>


            </div>

        </div>

    </div>
        {{-- INFORMATIONS PERSONNELLES --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">

        {{-- Titre de la section --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600
                        flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Informations personnelles
                </h2>
                <p class="text-sm text-slate-500">
                    Informations générales de l'employé
                </p>
            </div>
        </div>

        {{-- Grille des informations --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Sexe --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Sexe
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->sexe ?? 'Non renseigné' }}
                </p>
            </div>

            {{-- Date de naissance --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Date de naissance
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}
                </p>
            </div>

            {{-- Téléphone --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Téléphone
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->telephone ?? 'Non renseigné' }}
                </p>
            </div>

            {{-- Email --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Adresse e-mail
                </p>
                <p class="text-sm font-semibold text-slate-700 break-all">
                    {{ $employe->email ?? 'Non renseignée' }}
                </p>
            </div>

            {{-- Adresse --}}
            <div class="md:col-span-2">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Adresse
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->adresse ?? 'Non renseignée' }}
                </p>
            </div>

        </div>
    </div>
    {{-- INFORMATIONS PROFESSIONNELLES --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">

        {{-- Titre de la section --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600
                        flex items-center justify-center">
                {{-- Icône professionnelle --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Informations professionnelles
                </h2>
                <p class="text-sm text-slate-500">
                    Affectation et situation professionnelle
                </p>
            </div>
        </div>

        {{-- Grille des informations --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Département --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Département
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->departement?->nom ?? 'Non renseigné' }}
                </p>
            </div>

            {{-- Poste --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Poste occupé
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->poste?->nom ?? 'Non renseigné' }}
                </p>
            </div>

            {{-- Date d'embauche --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Date d'embauche
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->date_embauche?->format('d/m/Y') ?? 'Non renseignée' }}
                </p>
            </div>

            {{-- Date de sortie --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                    Date de sortie
                </p>
                <p class="text-sm font-semibold text-slate-700">
                    {{ $employe->date_sortie?->format('d/m/Y') ?? 'Non renseignée' }}
                </p>
            </div>

        </div>
    </div>
    {{-- STATISTIQUES DE POINTAGE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">

        {{-- Titre --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600
                        flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Statistiques de pointage
                </h2>
                <p class="text-sm text-slate-500">
                    Activité de l'employé pour le mois en cours
                </p>
            </div>
        </div>

        {{-- Cartes statistiques --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Total des pointages --}}
            <div class="rounded-xl bg-blue-50 border border-blue-100 p-5">
                <p class="text-sm font-medium text-blue-600">
                    Total des pointages
                </p>

                <p class="text-3xl font-extrabold text-blue-800 mt-2">
                    {{ $statsPointages['total_mois'] }}
                </p>

                <p class="text-xs text-blue-500 mt-1">
                    Ce mois-ci
                </p>
            </div>

            {{-- Retards / anomalies --}}
            <div class="rounded-xl bg-amber-50 border border-amber-100 p-5">
                <p class="text-sm font-medium text-amber-600">
                    Retards / anomalies
                </p>

                <p class="text-3xl font-extrabold text-amber-800 mt-2">
                    {{ $statsPointages['retards_mois'] }}
                </p>

                <p class="text-xs text-amber-500 mt-1">
                    Ce mois-ci
                </p>
            </div>

        </div>
    </div>
    {{-- ACTIONS DE NAVIGATION --}}
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">

        {{-- Retour à la liste des employés --}}
        <a href="{{ route('gestion.employes.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5
                  bg-slate-100 hover:bg-slate-200 text-slate-700
                  text-sm font-semibold rounded-xl transition">

            &larr; Retour à la liste
        </a>

        {{-- Consulter le QR Code --}}
        <a href="{{ route('gestion.employes.show', $employe) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5
                  bg-purple-600 hover:bg-purple-700 text-white
                  text-sm font-semibold rounded-xl transition">

            Voir le QR Code
        </a>

    </div>
</div>

@endsection
@extends('layouts.dashboard')

@section('title', 'Documents' . ($employe ? ' — ' . $employe->nom_complet : ''))

@section('content')

@php
    $stats = $stats ?? ['total' => 0, 'photos' => 0, 'cin' => 0, 'contrats' => 0, 'fiches' => 0];
    $moisNoms = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
@endphp

<div class="max-w-7xl mx-auto">

    {{-- FIL D'ARIANE --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition">Tableau de bord</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        @if($employe)
            <a href="{{ route('gestion.employes.show', $employe) }}" class="hover:text-purple-600 transition">
                {{ $employe->nom_complet }}
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif
        <span class="font-semibold text-slate-700">Documents</span>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- EN-TÊTE --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                @if($employe)
                    Documents de {{ $employe->nom_complet }}
                @else
                    Tous les documents
                @endif
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Photos, CIN, contrats, fiches de paie et autres pièces justificatives.
            </p>
        </div>

        <a href="{{ $employe
                ? route('gestion.employes.documents.create', $employe)
                : route('gestion.documents.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter un document
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Photos</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['photos'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">CIN</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['cin'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Contrats</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['contrats'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Fiches paie</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['fiches'] }}</p>
        </div>
    </div>

    {{-- FILTRES --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 mb-6 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Titre ou nom du fichier…"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Catégorie</label>
                <select name="categorie" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    <option value="">Toutes</option>
                    <option value="photo_identite" {{ request('categorie') === 'photo_identite' ? 'selected' : '' }}>Photo d'identité</option>
                    <option value="photo_cin"      {{ request('categorie') === 'photo_cin'      ? 'selected' : '' }}>Photo CIN</option>
                    <option value="contrat"        {{ request('categorie') === 'contrat'        ? 'selected' : '' }}>Contrat</option>
                    <option value="fiche_paie"     {{ request('categorie') === 'fiche_paie'     ? 'selected' : '' }}>Fiche de paie</option>
                    <option value="diplome"        {{ request('categorie') === 'diplome'        ? 'selected' : '' }}>Diplôme</option>
                    <option value="attestation"    {{ request('categorie') === 'attestation'    ? 'selected' : '' }}>Attestation</option>
                    <option value="autre"          {{ request('categorie') === 'autre'          ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-slate-900 hover:bg-black text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Rechercher
                </button>
                @if(request()->hasAny(['search', 'categorie']))
                    <a href="{{ $employe
                            ? route('gestion.employes.documents.index', $employe)
                            : route('gestion.documents.index') }}"
                        class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-5 py-2.5 rounded-xl transition">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- LISTE --}}
    @if($documents->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($documents as $doc)
                @php
                    $icone = $doc->icone;
                    $couleur = $doc->couleur;
                @endphp

                <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition">

                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $couleur }}-100 flex items-center justify-center shrink-0">
                            @if($icone === 'image')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $couleur }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $couleur }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate" title="{{ $doc->titre }}">
                                {{ $doc->titre }}
                            </p>
                            <p class="text-[10px] font-semibold text-{{ $couleur }}-600 uppercase tracking-wide">
                                {{ $doc->categorie_label }}
                            </p>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 space-y-1 mb-3">
                        @if($doc->mois && $doc->annee)
                            <p>📅 {{ $moisNoms[$doc->mois - 1] ?? '' }} {{ $doc->annee }}</p>
                        @endif
                        <p>📦 {{ $doc->taille_humaine }}</p>
                        <p>🕒 {{ $doc->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="flex items-center gap-1 pt-3 border-t border-slate-100">
                        @if(str_contains($doc->fichier_mime, 'image') || str_contains($doc->fichier_mime, 'pdf'))
                            <a href="{{ route('gestion.documents.preview', $doc) }}" target="_blank"
                                title="Aperçu"
                                class="p-2 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        @endif

                        <a href="{{ route('gestion.documents.download', $doc) }}"
                            title="Télécharger"
                            class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>

                        <a href="{{ route('gestion.documents.edit', $doc) }}"
                            title="Modifier"
                            class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>

                        <form action="{{ route('gestion.documents.destroy', $doc) }}"
                            method="POST" class="inline ml-auto"
                            onsubmit="return confirm('Supprimer ce document ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Supprimer"
                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($documents->hasPages())
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Aucun document</h3>
            <p class="text-sm text-slate-500 mb-5">
                Commencez par ajouter un premier document.
            </p>
            <a href="{{ $employe
                    ? route('gestion.employes.documents.create', $employe)
                    : route('gestion.documents.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter un document
            </a>
        </div>
    @endif

</div>

@endsection
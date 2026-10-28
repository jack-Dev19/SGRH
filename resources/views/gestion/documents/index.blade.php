@extends('layouts.dashboard')

@section('title', 'Documents' . ($employe ? ' — ' . $employe->nom_complet : ''))

@section('content')

@php
    $stats = $stats ?? ['total' => 0, 'photos' => 0, 'cin' => 0, 'contrats' => 0, 'fiches' => 0];
    $moisNoms = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    categorieOpen: false,
    selectedCategorie: '{{ request('categorie') }}',
    categoriesList: [
        { id: '', nom: 'Toutes les catégories' },
        { id: 'photo_identite', nom: 'Photo d\'identité' },
        { id: 'photo_cin', nom: 'Photo CIN' },
        { id: 'contrat', nom: 'Contrat' },
        { id: 'fiche_paie', nom: 'Fiche de paie' },
        { id: 'diplome', nom: 'Diplôme' },
        { id: 'attestation', nom: 'Attestation' },
        { id: 'autre', nom: 'Autre' }
    ]
}">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-purple-700 transition-colors">Tableau de bord</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        @if($employe)
            <a href="{{ route('gestion.employes.show', $employe) }}" class="hover:text-purple-700 transition-colors">{{ $employe->nom_complet }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        @endif
        <span class="text-purple-900 font-bold">Documents</span>
    </div>

    {{-- Messages Flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
            <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-50 border border-red-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <span class="text-sm font-semibold text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    Bibliothèque
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                @if($employe) Documents de {{ $employe->nom_complet }} @else Tous les documents @endif
            </h1>
            <p class="text-sm text-slate-500 mt-1">Photos, CIN, contrats, fiches de paie et autres pièces justificatives.</p>
        </div>

        <a href="{{ $employe ? route('gestion.employes.documents.create', $employe) : route('gestion.documents.create') }}" class="group inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Ajouter un document
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Photos</p>
            <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ $stats['photos'] }}</p>
        </div>
        <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">CIN</p>
            <p class="text-2xl font-extrabold text-orange-700 mt-1">{{ $stats['cin'] }}</p>
        </div>
        <div class="rounded-2xl border border-purple-200 bg-purple-50 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">Contrats</p>
            <p class="text-2xl font-extrabold text-purple-700 mt-1">{{ $stats['contrats'] }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Fiches paie</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $stats['fiches'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="hidden" name="categorie" x-model="selectedCategorie">
            
            <div class="flex-1 min-w-[280px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par titre ou nom du fichier…" class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- Dropdown Catégorie --}}
            <div class="min-w-[220px] relative" @click.away="categorieOpen = false">
                <button type="button" @click="categorieOpen = !categorieOpen" class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="categoriesList.find(c => c.id === selectedCategorie)?.nom || 'Toutes les catégories'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"><svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="categorieOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
                </button>
                <div x-show="categorieOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par catégorie</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <template x-for="cat in categoriesList" :key="cat.id">
                            <li @click="selectedCategorie = cat.id; categorieOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedCategorie === cat.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="cat.nom"></span>
                                <div :class="selectedCategorie === cat.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedCategorie === cat.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <button type="submit" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">Filtrer</button>

            @if(request()->hasAny(['search', 'categorie']))
                <a href="{{ $employe ? route('gestion.employes.documents.index', $employe) : route('gestion.documents.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Liste --}}
    @if($documents->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($documents as $doc)
                @php
                    $icone = $doc->icone;
                    $couleur = $doc->couleur;
                @endphp
                <div class="group bg-white rounded-2xl border border-slate-200 p-4 shadow-sm hover:shadow-md hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $couleur }}-100 flex items-center justify-center shrink-0">
                            @if($icone === 'image')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $couleur }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $couleur }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate" title="{{ $doc->titre }}">{{ $doc->titre }}</p>
                            <p class="text-[10px] font-bold text-{{ $couleur }}-600 uppercase tracking-wider mt-0.5">{{ $doc->categorie_label }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 space-y-1.5 mb-3 flex-1">
                        @if($doc->mois && $doc->annee)
                            <div class="flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>{{ $moisNoms[$doc->mois - 1] ?? '' }} {{ $doc->annee }}</span></div>
                        @endif
                        <div class="flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg><span>{{ $doc->taille_humaine }}</span></div>
                        <div class="flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ $doc->created_at->format('d/m/Y H:i') }}</span></div>
                    </div>
                    <div class="flex items-center gap-1 pt-3 border-t border-slate-100">
                        @if(str_contains($doc->fichier_mime, 'image') || str_contains($doc->fichier_mime, 'pdf'))
                            <a href="{{ route('gestion.documents.preview', $doc) }}" target="_blank" title="Aperçu" class="p-2 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        @endif
                        <a href="{{ route('gestion.documents.download', $doc) }}" title="Télécharger" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></a>
                        <a href="{{ route('gestion.documents.edit', $doc) }}" title="Modifier" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                        <form action="{{ route('gestion.documents.destroy', $doc) }}" method="POST" class="inline ml-auto" onsubmit="return confirm('Supprimer ce document ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Supprimer" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        @if($documents->hasPages())
            <div class="mt-6">{{ $documents->links() }}</div>
        @endif
    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Aucun document trouvé</h3>
            <p class="text-sm text-slate-500 mb-5">@if(request()->hasAny(['search', 'categorie'])) Aucun résultat ne correspond à votre recherche. @else Commencez par ajouter un premier document. @endif</p>
            <div class="flex items-center justify-center gap-2">
                @if(request()->hasAny(['search', 'categorie']))
                    <a href="{{ $employe ? route('gestion.employes.documents.index', $employe) : route('gestion.documents.index') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-5 py-2.5 rounded-lg transition">Effacer les filtres</a>
                @else
                    <a href="{{ $employe ? route('gestion.employes.documents.create', $employe) : route('gestion.documents.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold px-5 py-2.5 rounded-lg transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Ajouter un document</a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>

@endsection
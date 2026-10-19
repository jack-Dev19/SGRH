@extends('layouts.dashboard')

@section('title', $document->titre)

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.documents.index') }}" class="hover:text-purple-600 transition">Documents</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">{{ $document->titre }}</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">{{ $document->titre }}</h1>
                    <p class="text-purple-100 text-sm">{{ $document->categorie_label }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            {{-- Infos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Employé</p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $document->employe?->nom_complet ?? '—' }}
                    </p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Taille</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $document->taille_humaine }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Nom du fichier</p>
                    <p class="text-sm text-slate-800 break-all">{{ $document->fichier_nom_original }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Ajouté le</p>
                    <p class="text-sm text-slate-800">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($document->mois && $document->annee)
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Période</p>
                        <p class="text-sm text-slate-800">{{ $document->mois_label }} {{ $document->annee }}</p>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            @if($document->description)
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Description</p>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $document->description }}</p>
                </div>
            @endif

            {{-- Aperçu --}}
            @if(str_contains($document->fichier_mime, 'image'))
                <div class="bg-slate-50 rounded-xl p-4 mb-6 text-center">
                    <img src="{{ route('gestion.documents.preview', $document) }}"
                        alt="{{ $document->titre }}"
                        class="max-w-full h-auto rounded-lg shadow-sm mx-auto"
                        style="max-height: 500px;">
                </div>
            @elseif(str_contains($document->fichier_mime, 'pdf'))
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <iframe src="{{ route('gestion.documents.preview', $document) }}"
                        class="w-full rounded-lg"
                        style="height: 600px; border: none;"></iframe>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.documents.download', $document) }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger
                </a>

                <a href="{{ route('gestion.documents.edit', $document) }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>

                <a href="{{ route('gestion.employes.documents.index', $document->employe_id) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 transition ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour aux documents
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
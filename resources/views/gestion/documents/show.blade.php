@extends('layouts.dashboard')

@section('title', $document->titre)

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.documents.index') }}" class="hover:text-purple-700 transition-colors">Documents</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold truncate">{{ $document->titre }}</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Icône --}}
            <div class="mx-auto w-24 h-24 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            <h1 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $document->titre }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $document->categorie_label }}</p>

            {{-- Badge Période (si applicable) --}}
            @if($document->mois && $document->annee)
                <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-3 py-1 text-xs font-bold text-purple-600 ring-1 ring-inset ring-purple-200">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $document->mois_label }} {{ $document->annee }}
                    </span>
                </div>
            @endif
        </div>

        {{-- Corps --}}
        <div class="px-8 pb-10 relative z-10">
            
            {{-- Section Informations --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Informations</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                {{-- Employé --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Employé</p>
                    </div>
                    <p class="text-sm font-semibold text-slate-800">{{ $document->employe?->nom_complet ?? '—' }}</p>
                </div>

                {{-- Taille --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Taille</p>
                    </div>
                    <p class="text-sm font-semibold text-slate-800">{{ $document->taille_humaine }}</p>
                </div>

                {{-- Nom du fichier --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4 md:col-span-2">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Nom du fichier</p>
                    </div>
                    <p class="text-sm font-mono text-slate-700 break-all">{{ $document->fichier_nom_original }}</p>
                </div>

                {{-- Date d'ajout --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Ajouté le</p>
                    </div>
                    <p class="text-sm font-medium text-slate-800">{{ $document->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                {{-- Période (si applicable) --}}
                @if($document->mois && $document->annee)
                    <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Période</p>
                        </div>
                        <p class="text-sm font-medium text-slate-800">{{ $document->mois_label }} {{ $document->annee }}</p>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            @if($document->description)
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-8">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Description</p>
                    </div>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $document->description }}</p>
                </div>
            @endif

            {{-- Aperçu --}}
            @if(str_contains($document->fichier_mime, 'image') || str_contains($document->fichier_mime, 'pdf'))
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">2</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Aperçu</h3>
                </div>

                @if(str_contains($document->fichier_mime, 'image'))
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-4 mb-8 flex items-center justify-center">
                        <img src="{{ route('gestion.documents.preview', $document) }}"
                            alt="{{ $document->titre }}"
                            class="max-w-full h-auto rounded-2xl shadow-sm"
                            style="max-height: 500px;">
                    </div>
                @elseif(str_contains($document->fichier_mime, 'pdf'))
                    <div class="rounded-3xl bg-slate-50 border border-slate-200 p-2 mb-8">
                        <iframe src="{{ route('gestion.documents.preview', $document) }}"
                            class="w-full rounded-2xl"
                            style="height: 600px; border: none;"></iframe>
                    </div>
                @endif
            @endif

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                
                <a href="{{ route('gestion.employes.documents.index', $document->employe_id) }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour aux documents
                </a>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    {{-- Télécharger --}}
                    <a href="{{ route('gestion.documents.download', $document) }}"
                        class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-sm font-bold px-5 py-3 rounded-full transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Télécharger
                    </a>

                    {{-- Modifier --}}
                    <a href="{{ route('gestion.documents.edit', $document) }}"
                        class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                </div>
            </div>
        </div>

        {{-- Vagues décoratives --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none overflow-hidden h-40">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full">
                <path fill="rgba(167, 139, 250, 0.2)" d="M0,100 C150,150 350,50 500,100 L500,150 L0,150 Z"></path>
                <path fill="rgba(139, 92, 246, 0.3)" d="M0,120 C150,170 350,70 500,120 L500,150 L0,150 Z"></path>
                <path fill="rgba(124, 58, 237, 0.15)" d="M0,140 C150,190 350,90 500,140 L500,150 L0,150 Z"></path>
            </svg>
        </div>
    </div>
</div>

@endsection
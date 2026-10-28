@extends('layouts.dashboard')

@section('title', 'Pointage QR Code')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Messages Flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-50 border border-red-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    Pointage
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pointage QR Code</h1>
            <p class="text-sm text-slate-500 mt-1">Scannez le QR code de l'employé pour enregistrer son arrivée ou son départ.</p>
        </div>
    </div>

    {{-- Container Scanner --}}
    <div id="attendance-scanner" data-scan-url="{{ route('gestion.pointage.enregistrer') }}" class="mb-8">
        <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">
            
            {{-- En-tête du scanner --}}
            <div class="px-8 pt-8 pb-4 text-center">
                <h2 class="text-xl font-extrabold text-purple-700 uppercase tracking-wide">Scanner</h2>
                <p class="text-xs text-slate-400 uppercase tracking-widest mt-1">Prêt à scanner</p>
            </div>

            {{-- Résultat du scan --}}
            <div id="scan-result" class="hidden mx-6 mb-4 p-4 rounded-2xl border"></div>

            {{-- Zone de la caméra --}}
            <div class="mx-6 mb-6 relative bg-slate-900 rounded-3xl overflow-hidden" style="min-height: 400px;">
                <div id="reader" class="w-full"></div>
            </div>

            {{-- Boutons --}}
            <div class="px-6 pb-8 flex gap-3 justify-center">
                <button id="start-scanner"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3.5 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    DÉMARRER LA CAMÉRA
                </button>
                <button id="stop-scanner"
                    class="hidden inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-6 py-3.5 rounded-full shadow-[0_10px_20px_-10px_rgba(220,38,38,0.6)] transition-all hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    ARRÊTER
                </button>
            </div>

            {{-- Vagues décoratives --}}
            <div class="absolute bottom-0 left-0 right-0 pointer-events-none overflow-hidden h-24">
                <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full">
                    <path fill="rgba(167, 139, 250, 0.2)" d="M0,100 C150,150 350,50 500,100 L500,150 L0,150 Z"></path>
                    <path fill="rgba(139, 92, 246, 0.3)" d="M0,120 C150,170 350,70 500,120 L500,150 L0,150 Z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Liste des pointages du jour --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        
        {{-- En-tête de la liste --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <h3 class="font-bold text-slate-900">Derniers pointages</h3>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-[10px] font-semibold text-slate-500 uppercase tracking-wider shadow-sm ring-1 ring-inset ring-slate-200">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Auto 5s
            </span>
        </div>

        {{-- Liste --}}
        <div id="pointages-list" class="max-h-96 overflow-y-auto divide-y divide-slate-50">
            @forelse($pointagesJour as $p)
                <div class="group px-4 py-3 hover:bg-slate-50/80 transition-colors flex items-center gap-3">
                    {{-- Avatar --}}
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-sm">
                        {{ strtoupper(substr($p->employe?->prenom ?? '??', 0, 1) . substr($p->employe?->nom ?? '', 0, 1)) }}
                    </div>

                    {{-- Infos --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ $p->employe?->nom_complet ?? 'Inconnu' }}</p>
                        <p class="text-[10px] text-slate-400 font-mono truncate">{{ $p->employe?->matricule }} · {{ $p->date_heure?->format('H:i') }}</p>
                    </div>

                    {{-- Badge Type --}}
                    @if($p->type === 'entree')
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            Arrivée
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700 ring-1 ring-inset ring-blue-200">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            Départ
                        </span>
                    @endif

                    {{-- Bouton supprimer --}}
                    <form action="{{ route('gestion.pointage.supprimer', $p->id) }}" method="POST" onsubmit="return confirm('Supprimer ce pointage ?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Supprimer"
                            class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors opacity-60 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Aucun pointage</h3>
                    <p class="text-sm text-slate-500">Aucun pointage enregistré aujourd'hui.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<style>
    #reader {
        width: 100% !important;
        border: none !important;
        padding: 0 !important;
    }

    #reader video {
        width: 100% !important;
        height: auto !important;
        border-radius: 0 !important;
        object-fit: cover !important;
    }

    #reader img {
        display: none !important;
    }
</style>

@endsection
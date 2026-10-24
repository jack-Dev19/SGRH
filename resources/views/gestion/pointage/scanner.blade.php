@extends('layouts.dashboard')

@section('title', 'Pointage QR Code')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- ============================================= --}}
    {{-- MESSAGES FLASH --}}
    {{-- ============================================= --}}
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

    {{-- ============================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ============================================= --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Pointage QR Code</h1>
        <p class="text-sm text-slate-500">Scannez le QR code de l'employé pour enregistrer son arrivée ou son départ.</p>
    </div>

    {{-- ============================================= --}}
    {{-- CONTAINER SCANNER --}}
    {{-- ============================================= --}}
    <div id="attendance-scanner" data-scan-url="{{ route('gestion.pointage.enregistrer') }}">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div id="scan-result" class="hidden p-6 m-4 rounded-xl border border-slate-100"></div>

            <div class="relative bg-slate-900" style="min-height: 400px;">
                <div id="reader" class="w-full"></div>
            </div>

            <div class="p-6 flex gap-3 justify-center">
                <button id="start-scanner"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg transition">
                    📷 Démarrer la caméra
                </button>
                <button id="stop-scanner"
                    class="hidden inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg transition">
                    ⏹️ Arrêter
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- LISTE DES POINTAGES DU JOUR --}}
    {{-- ============================================= --}}
    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900">Derniers pointages</h3>
            <span class="text-[10px] font-semibold text-slate-400 uppercase">Auto 5s</span>
        </div>

        <div id="pointages-list" class="max-h-96 overflow-y-auto">
            @forelse($pointagesJour as $p)
                <div class="px-4 py-3 border-b border-slate-50 hover:bg-slate-50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                        {{ strtoupper(substr($p->employe?->prenom ?? '??', 0, 1) . substr($p->employe?->nom ?? '', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">
                            {{ $p->employe?->nom_complet ?? 'Inconnu' }}
                        </p>
                        <p class="text-xs text-slate-400 truncate">
                            {{ $p->employe?->matricule }} · {{ $p->date_heure?->format('H:i') }}
                        </p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full
                        {{ $p->type === 'entree' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $p->type === 'entree' ? 'Arrivée' : 'Départ' }}
                    </span>

                    {{-- ⭐ Bouton supprimer --}}
                    <form action="{{ route('gestion.pointage.supprimer', $p->id) }}"
                          method="POST"
                          onsubmit="return confirm('Supprimer ce pointage ?');"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Supprimer"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-sm">
                    Aucun pointage aujourd'hui
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
@extends('layouts.dashboard')

@section('title', 'QR Code — ' . $employe->nom_complet)

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- ============================================= --}}
    {{-- FIL D'ARIANE --}}
    {{-- ============================================= --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.employes.index') }}" class="hover:text-purple-600 transition">Employés</a>
        <span>/</span>
        <a href="{{ route('gestion.employes.show', $employe) }}" class="hover:text-purple-600 transition">
            {{ $employe->nom_complet }}
        </a>
        <span>/</span>
        <span class="font-semibold text-slate-700">QR Code</span>
    </div>

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
    {{-- CARTE PRINCIPALE --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white text-center">
            <h1 class="text-xl font-extrabold">QR Code de pointage</h1>
            <p class="text-purple-100 text-sm mt-1">{{ $employe->nom_complet }}</p>
        </div>

        <div class="p-8 text-center">

            @if($qrCode && $qrCode->actif)

                {{-- ============================================= --}}
                {{-- QR CODE — Généré côté client via canvas --}}
                {{-- ============================================= --}}
                <div class="inline-block p-4 bg-white rounded-2xl border-2 border-purple-100 shadow-sm mb-6">
                    <canvas
                        class="employee-qr"
                        data-code="{{ $employe->matricule }}"
                        width="300"
                        height="300">
                    </canvas>
                </div>

                {{-- ============================================= --}}
                {{-- MÉTADONNÉES --}}
                {{-- ============================================= --}}
                <div class="bg-slate-50 rounded-xl p-4 mb-6 text-left space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Nom complet :</span>
                        <span class="font-bold text-slate-800">{{ $employe->nom_complet }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Matricule :</span>
                        <span class="font-mono font-bold text-slate-800">{{ $employe->matricule }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Contenu du QR :</span>
                        <span class="font-mono font-bold text-purple-600">{{ $employe->matricule }}</span>
                    </div>
                    @if($qrCode->genere_le)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Généré le :</span>
                            <span class="text-slate-800">{{ $qrCode->genere_le->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Nombre de scans :</span>
                        <span class="font-bold text-slate-800">{{ $qrCode->scan_count ?? 0 }}</span>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- ACTIONS --}}
                {{-- ============================================= --}}
                <div class="flex flex-wrap gap-2 justify-center">
                    {{-- Télécharger --}}
                    <button type="button" onclick="downloadQr()"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Télécharger le QR
                    </button>

                    {{-- Régénérer --}}
                    <form action="{{ route('gestion.employes.qr.generer', $employe) }}" method="POST" class="inline"
                        onsubmit="return confirm('Régénérer le QR Code ?');">
                        @csrf
                        <input type="hidden" name="renouveler" value="1">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-5 py-2.5 rounded-xl transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Régénérer
                        </button>
                    </form>

                    {{-- Révoquer --}}
                    <form action="{{ route('gestion.employes.qr.revoquer', $employe) }}" method="POST" class="inline"
                        onsubmit="return confirm('Révoquer définitivement ce QR Code ?');">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 text-sm font-bold px-5 py-2.5 rounded-xl transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Révoquer
                        </button>
                    </form>
                </div>

            @else

                {{-- ============================================= --}}
                {{-- AUCUN QR ACTIF --}}
                {{-- ============================================= --}}
                <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Aucun QR Code actif</h3>
                <p class="text-sm text-slate-500 mb-5">
                    Générez le QR Code pour cet employé.
                </p>

                <form action="{{ route('gestion.employes.qr.generer', $employe) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Générer le QR Code
                    </button>
                </form>

            @endif

            {{-- ============================================= --}}
            {{-- RETOUR --}}
            {{-- ============================================= --}}
            <div class="mt-6 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.employes.show', $employe) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour à la fiche employé
                </a>
            </div>

        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- SCRIPT : Télécharger le QR --}}
{{-- ============================================= --}}
<script>
    function downloadQr() {
        const canvas = document.querySelector('.employee-qr');
        if (!canvas) {
            alert('Aucun QR Code disponible.');
            return;
        }

        // Générer l'image PNG depuis le canvas
        const link = document.createElement('a');
        link.download = 'qr-{{ $employe->matricule }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }
</script>

@endsection
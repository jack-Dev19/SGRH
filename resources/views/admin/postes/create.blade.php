@extends('layouts.dashboard')

@section('title', 'Nouveau poste')
@section('page-title', 'Nouveau poste')

@section('content')

@php
    $departements = collect([
        (object) ['id' => 1, 'nom' => 'Informatique'],
        (object) ['id' => 2, 'nom' => 'Comptabilité'],
        (object) ['id' => 3, 'nom' => 'Ressources Humaines'],
        (object) ['id' => 4, 'nom' => 'Marketing'],
        (object) ['id' => 5, 'nom' => 'Commercial'],
        (object) ['id' => 6, 'nom' => 'Direction Générale'],
    ]);
@endphp

<div class="max-w-5xl mx-auto py-8">

    <a href="{{ route('admin.postes.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
        Retour aux postes
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        <div class="relative bg-purple-600 rounded-[2rem] shadow-2xl shadow-purple-500/30 overflow-hidden min-h-[600px] flex flex-col">
            <div class="relative z-10 px-10 pt-12 text-center">
                <h2 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">Nouveau Poste</h2>
                <p class="text-sm text-purple-200 uppercase tracking-widest">Créer un poste</p>
            </div>
            <div class="relative z-10 flex-1 px-10 py-12 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-white font-bold text-lg mb-2">Définissez un nouveau poste</p>
                    <p class="text-purple-100 text-sm leading-relaxed max-w-xs mx-auto">Ajoutez un poste et affectez-le à un département.</p>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.3"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#d8b4fe" opacity="0.5"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7"/></svg>
            </div>
        </div>

        <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-purple-500/20 overflow-hidden min-h-[600px] flex flex-col">
            <form action="{{ route('admin.postes.store') }}" method="POST" class="relative z-10 px-8 pt-10 pb-32 flex-1">
                @csrf

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-extrabold text-purple-700 uppercase tracking-wider mb-2">Créer</h2>
                    <p class="text-sm text-purple-400 uppercase tracking-widest">Un nouveau poste</p>
                </div>

                <div class="space-y-4">
                    {{-- Intitulé --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="text" name="titre" required placeholder="Intitulé du poste"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                    </div>

                    {{-- ⭐ DÉPARTEMENT — DROPDOWN CUSTOM ⭐ --}}
                    <div class="relative" data-dropdown="departement">
                        <input type="hidden" name="departement" id="input-departement" value="{{ old('departement') }}">
                        <button type="button" id="dropdown-departement-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span id="dropdown-departement-label" class="truncate text-left">
                                {{ old('departement') ?: 'Sélectionner un département' }}
                            </span>
                            <svg id="dropdown-departement-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-departement-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 max-h-64 overflow-y-auto">
                            <button type="button" onclick="selectDropdown('departement', '', 'Sélectionner un département')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-500 hover:bg-slate-50 transition">Aucun</button>
                            @foreach($departements as $dept)
                                <button type="button" onclick="selectDropdown('departement', '{{ $dept->nom }}', '{{ $dept->nom }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3 border-t border-slate-100">
                                    <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <span class="font-medium">{{ $dept->nom }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Nombre max --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="number" name="max" min="1" value="10" placeholder="Nombre max d'employés"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                    </div>

                    {{-- Description --}}
                    <div class="relative">
                        <div class="absolute left-4 top-4 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <textarea name="description" rows="3" placeholder="Description du poste..."
                            class="w-full pl-12 pr-4 py-3.5 rounded-3xl bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 resize-none focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition"></textarea>
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold uppercase tracking-wider hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300/50 transition shadow-lg shadow-purple-500/30 transform hover:-translate-y-0.5">
                    Créer le poste
                </button>
            </form>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.6"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#9333ea" opacity="0.8"/></svg>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    window.selectDropdown = function(type, value, label) {
        document.getElementById('input-' + type).value = value;
        document.getElementById('dropdown-' + type + '-label').textContent = label;
        document.getElementById('dropdown-' + type + '-menu').classList.add('hidden');
        document.getElementById('dropdown-' + type + '-chevron').style.transform = 'rotate(0deg)';
    };
    window.toggleDropdown = function(type) {
        const menu = document.getElementById('dropdown-' + type + '-menu');
        const chevron = document.getElementById('dropdown-' + type + '-chevron');
        const isHidden = menu.classList.contains('hidden');
        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
        if (isHidden) { menu.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
    };
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('dropdown-departement-btn');
        if (btn) btn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown('departement'); });
        document.addEventListener('click', () => {
            document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
        });
        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.addEventListener('click', e => e.stopPropagation()));
    });
</script>
@endpush
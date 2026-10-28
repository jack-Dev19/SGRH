@extends('layouts.dashboard')

@section('title', 'Anomalies de pointage')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-orange-600 uppercase tracking-wider ring-1 ring-inset ring-orange-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                    Attention requise
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Anomalies de pointage</h1>
            <p class="text-sm text-slate-500 mt-1">Pointages nécessitant une vérification du service RH.</p>
        </div>
    </div>

    {{-- Filtre date --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="flex-1 min-w-[220px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" name="date" value="{{ $date }}"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filtrer
            </button>

            <a href="{{ route('gestion.pointage.historique') }}"
                class="ml-auto inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Historique complet
            </a>
        </form>
    </div>

    {{-- Liste --}}
    @if(count($anomalies) > 0)
        <div class="bg-white rounded-2xl border border-orange-200 overflow-hidden shadow-sm">
            {{-- En-tête de la carte --}}
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-orange-100 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-100 text-orange-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-orange-800">{{ count($anomalies) }} anomalie(s) détectée(s)</p>
                    <p class="text-[10px] text-orange-600 font-medium uppercase tracking-wider mt-0.5">Le {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- Liste des anomalies --}}
            <div class="divide-y divide-slate-100">
                @foreach($anomalies as $a)
                    <div class="group p-4 hover:bg-slate-50/80 transition-colors flex items-center gap-4">
                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center shrink-0 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        {{-- Infos --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-900 truncate">{{ $a['employe'] }}</p>
                            <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase mt-0.5">
                                {{ $a['matricule'] }} · {{ $a['heure'] }}
                            </p>
                            <p class="text-xs text-orange-700 mt-1 font-semibold">{{ $a['description'] }}</p>
                        </div>

                        {{-- Action --}}
                        <a href="{{ route('gestion.pointage.corriger', $a['id']) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 px-4 py-2 rounded-full transition-colors ring-1 ring-inset ring-purple-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Corriger
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- État vide --}}
        <div class="bg-white rounded-2xl border border-emerald-200 p-12 text-center shadow-sm">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-emerald-700 mb-1">Aucune anomalie</h3>
            <p class="text-sm text-slate-500">Tous les pointages de cette journée sont valides.</p>
        </div>
    @endif

</div>

@endsection
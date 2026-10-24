@extends('layouts.dashboard')

@section('title', 'Anomalies de pointage')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Anomalies de pointage</h1>
        <p class="text-sm text-slate-500">Pointages nécessitant une vérification du service RH</p>
    </div>

    {{-- Filtre date --}}
    <form method="GET" class="bg-white rounded-2xl border border-slate-100 p-4 mb-6 shadow-sm flex items-center gap-3">
        <div class="min-w-[200px]">
            <input type="date" name="date" value="{{ $date }}"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
        </div>
        <button type="submit"
            class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition">
            Filtrer
        </button>
        <a href="{{ route('gestion.pointage.historique') }}"
            class="ml-auto text-sm text-slate-500 hover:text-purple-600 font-medium">
            ← Historique complet
        </a>
    </form>

    {{-- Liste --}}
    @if(count($anomalies) > 0)
        <div class="bg-white rounded-2xl border border-orange-200 shadow-sm overflow-hidden">
            <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-bold text-orange-800">
                    {{ count($anomalies) }} anomalie(s) détectée(s)
                </p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($anomalies as $a)
                    <div class="p-4 hover:bg-slate-50 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-900">{{ $a['employe'] }}</p>
                            <p class="text-xs text-slate-500">{{ $a['matricule'] }} · {{ $a['heure'] }}</p>
                            <p class="text-xs text-orange-700 mt-1 font-semibold">{{ $a['description'] }}</p>
                        </div>
                        <a href="{{ route('gestion.pointage.corriger', $a['id']) }}"
                            class="text-xs font-bold text-purple-600 hover:text-purple-800 bg-purple-50 hover:bg-purple-100 px-4 py-2 rounded-xl transition">
                            Corriger
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-emerald-200 p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-lg font-bold text-emerald-700 mb-1">Aucune anomalie</p>
            <p class="text-sm text-slate-500">Tous les pointages de cette journée sont valides.</p>
        </div>
    @endif

</div>

@endsection
@extends('layouts.dashboard')

@section('title', 'Détails employé')
@section('page-title', 'Détails employé')

@section('content')

<div class="max-w-4xl mx-auto">

    <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour à la liste
    </a>

    {{-- Carte principale --}}
    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden">

        {{-- En-tête dégradé --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-2xl border-2 border-white/40">
                    {{ strtoupper(substr($employee->prenom, 0, 1) . substr($employee->nom, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold">{{ $employee->prenom }} {{ $employee->nom }}</h1>
                    <p class="text-purple-100 text-sm mt-1">{{ $employee->matricule }}</p>
                </div>
                <div class="ml-auto">
                    @if($employee->actif)
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-4 py-2 rounded-full">Actif</span>
                    @else
                        <span class="text-xs font-bold text-slate-700 bg-slate-100 px-4 py-2 rounded-full">Inactif</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Corps : Infos --}}
        <div class="p-8">

            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Informations personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Email</p>
                    <p class="text-sm text-slate-800">{{ $employee->email }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Téléphone</p>
                    <p class="text-sm text-slate-800">{{ $employee->telephone ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Sexe</p>
                    <p class="text-sm text-slate-800">{{ $employee->sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Département</p>
                    <p class="text-sm text-slate-800">{{ $employee->departement->nom ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Salaire de base</p>
                    <p class="text-sm font-bold text-slate-800">{{ number_format($employee->salaire_base, 0, ',', ' ') }} MAD</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date d'embauche</p>
                    <p class="text-sm text-slate-800">{{ $employee->date_embauche?->format('d/m/Y') ?? '—' }}</p>
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.employees.edit', $employee) }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
            </div>

        </div>

    </div>

</div>

@endsection
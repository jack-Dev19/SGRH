@extends('layouts.dashboard')

@section('title', 'Employés')
@section('page-title', 'Employés')

@section('content')

{{-- ============================================= --}}
{{-- MESSAGE DE SUCCÈS                              --}}
{{-- ============================================= --}}
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
@endif

{{-- ============================================= --}}
{{-- BARRE DE RECHERCHE + FILTRES                   --}}
{{-- ============================================= --}}
<div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6">

    <form method="GET" action="{{ route('admin.employees.index') }}" class="flex flex-wrap items-center gap-3">

        {{-- Recherche --}}
        <div class="flex-1 min-w-[250px]">
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Rechercher par nom, matricule, email..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>
        </div>

        {{-- Filtre département --}}
        <select name="departement" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            <option value="">Tous les départements</option>
            @foreach($departements as $dept)
                <option value="{{ $dept->id }}" {{ request('departement') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->nom }}
                </option>
            @endforeach
        </select>

        {{-- Filtre statut --}}
        <select name="statut" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            <option value="">Tous les statuts</option>
            <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
            <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
        </select>

        {{-- Bouton filtrer --}}
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
            Filtrer
        </button>

        {{-- Bouton reset --}}
        @if(request()->hasAny(['search', 'departement', 'statut']))
            <a href="{{ route('admin.employees.index') }}" class="text-sm text-slate-500 hover:text-purple-600 transition px-3 py-2.5">
                Réinitialiser
            </a>
        @endif

        {{-- Bouton Nouvel employé --}}
        <a href="{{ route('admin.employees.create') }}"
            class="ml-auto inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel employé
        </a>

    </form>

</div>

{{-- ============================================= --}}
{{-- COMPTEUR                                       --}}
{{-- ============================================= --}}
<p class="text-xs text-slate-500 mb-3 px-1">
    <span class="font-bold text-slate-700">{{ $employees->total() }}</span> employé(s) trouvé(s)
</p>

{{-- ============================================= --}}
{{-- TABLEAU DES EMPLOYÉS                           --}}
{{-- ============================================= --}}
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-6 py-3 font-semibold">Employé</th>
                    <th class="text-left px-6 py-3 font-semibold">Département</th>
                    <th class="text-left px-6 py-3 font-semibold">Poste</th>
                    <th class="text-left px-6 py-3 font-semibold">Salaire</th>
                    <th class="text-left px-6 py-3 font-semibold">Embauche</th>
                    <th class="text-left px-6 py-3 font-semibold">Statut</th>
                    <th class="text-right px-6 py-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($employees as $emp)
                    <tr class="hover:bg-purple-50/40 transition">

                        {{-- Avatar + Nom + Matricule --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($emp->prenom, 0, 1) . substr($emp->nom, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 truncate">
                                        {{ $emp->prenom }} {{ $emp->nom }}
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $emp->matricule }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Département --}}
                        <td class="px-6 py-4 text-slate-600">
                            {{ $emp->departement->nom ?? '—' }}
                        </td>

                        {{-- Poste (on met un placeholder, à remplacer plus tard) --}}
                        <td class="px-6 py-4 text-slate-600">
                            {{ $emp->poste ?? 'Non défini' }}
                        </td>

                        {{-- Salaire --}}
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-800">
                                {{ number_format($emp->salaire_base, 0, ',', ' ') }}
                            </span>
                            <span class="text-xs text-slate-400">MAD</span>
                        </td>

                        {{-- Date d'embauche --}}
                        <td class="px-6 py-4 text-slate-600">
                            {{ $emp->date_embauche ? $emp->date_embauche->format('Y-m-d') : '—' }}
                        </td>

                        {{-- Statut --}}
                        <td class="px-6 py-4">
                            @if($emp->actif)
                                <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                    Actif
                                </span>
                            @else
                                <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
                                    Inactif
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                {{-- Détails --}}
                                <a href="{{ route('admin.employees.show', $emp) }}"
                                    class="text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-lg transition">
                                    Détails
                                </a>

                                {{-- Modifier --}}
                                <a href="{{ route('admin.employees.edit', $emp) }}"
                                    class="text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                                    Modifier
                                </a>

                                {{-- Supprimer (form) --}}
                                <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cet employé ?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                        Suppr.
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p>Aucun employé trouvé</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ============================================= --}}
    {{-- PAGINATION                                     --}}
    {{-- ============================================= --}}
    @if($employees->hasPages())
        <div class="border-t border-slate-100 p-4">
            {{ $employees->links() }}
        </div>
    @endif

</div>

@endsection
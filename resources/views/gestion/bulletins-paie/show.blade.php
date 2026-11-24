@extends('layouts.dashboard')

@section('title', 'Détail du bulletin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6">

    <div class="mb-6">
        <a href="{{ route('gestion.bulletins-paie.index') }}"
           class="text-sm font-semibold text-slate-500 hover:text-slate-900">
            ← Retour aux bulletins
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="mb-5 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm font-semibold text-blue-800">
            {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-purple-600">
                Détail de la rémunération
            </p>

            <h1 class="mt-1 text-3xl font-extrabold text-slate-900">
                {{ $bulletin->periode }} {{ $bulletin->annee }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $bulletin->employe?->nom_complet }}
                — {{ $bulletin->employe?->matricule }}
            </p>
        </div>

        @if($bulletin->statut === 'publie')
            <span class="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-sm font-bold text-emerald-700">
                Bulletin publié
            </span>
        @else
            <span class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-700">
                Brouillon
            </span>
        @endif
    </div>

    {{-- Informations principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase text-slate-400">Salaire brut</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">
                {{ number_format($bulletin->salaire_brut, 2, ',', ' ') }} MGA
            </p>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase text-emerald-600">Salaire net</p>
            <p class="mt-2 text-2xl font-extrabold text-emerald-700">
                {{ number_format($bulletin->salaire_net, 2, ',', ' ') }} MGA
            </p>
        </div>
    </div>

    {{-- Détails --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm mb-6">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-extrabold text-slate-900">Détail des éléments de paie</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Type</th>
                        <th class="px-5 py-4">Désignation</th>
                        <th class="px-5 py-4 text-right">Montant</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-5 py-4 text-slate-500">Base</td>
                        <td class="px-5 py-4 font-semibold text-slate-700">Salaire de base</td>
                        <td class="px-5 py-4 text-right font-semibold">
                            {{ number_format($bulletin->salaire_base, 2, ',', ' ') }} MGA
                        </td>
                    </tr>

                    @forelse($bulletin->lignes as $ligne)
                        <tr>
                            <td class="px-5 py-4">
                                @if($ligne->type === 'prime')
                                    <span class="text-emerald-600 font-semibold">Prime</span>
                                @else
                                    <span class="text-red-600 font-semibold">Retenue</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-700">{{ $ligne->libelle }}</td>

                            <td class="px-5 py-4 text-right font-semibold text-slate-700">
                                {{ number_format($ligne->montant, 2, ',', ' ') }} MGA
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-6 text-center text-slate-400">
                                Aucune ligne détaillée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 border-t border-slate-100 bg-slate-50 p-5 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Total des primes</span>
                <strong>{{ number_format($bulletin->total_primes, 2, ',', ' ') }} MGA</strong>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Heures supplémentaires</span>
                <strong>{{ number_format($bulletin->total_heures_supplementaires, 2, ',', ' ') }} MGA</strong>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Total des retenues</span>
                <strong class="text-red-600">
                    {{ number_format($bulletin->total_retenues, 2, ',', ' ') }} MGA
                </strong>
            </div>

            <div class="flex justify-between border-t border-slate-200 pt-4 text-base">
                <span class="font-extrabold text-slate-900">Net à payer</span>
                <strong class="text-xl font-extrabold text-emerald-700">
                    {{ number_format($bulletin->salaire_net, 2, ',', ' ') }} MGA
                </strong>
            </div>
        </div>
    </div>

    {{-- Publication --}}
    @if($bulletin->statut === 'brouillon')
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <h3 class="font-extrabold text-amber-900">Publication du bulletin</h3>

            <p class="mt-1 text-sm text-amber-800">
                Après publication, le bulletin sera disponible dans l'espace employé
                et une notification lui sera envoyée.
            </p>

            <form method="POST"
                  action="{{ route('gestion.bulletins-paie.publier', $bulletin->id) }}"
                  class="mt-4"
                  onsubmit="return confirm('Confirmer la publication de ce bulletin ?')">
                @csrf

                <button type="submit"
                        class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                    Publier le bulletin
                </button>
            </form>
        </div>
    @endif

</div>
@endsection
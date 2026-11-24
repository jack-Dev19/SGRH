@extends('layouts.dashboard')

@section('title', 'Bulletins de paie')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Messages --}}
    @foreach (['success' => 'emerald', 'error' => 'red', 'info' => 'blue'] as $key => $color)
        @if(session($key))
            <div class="mb-5 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 p-4 text-sm font-semibold text-{{ $color }}-800">
                {{ session($key) }}
            </div>
        @endif
    @endforeach

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-purple-600">Rémunération</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Bulletins de paie</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ $bulletins->total() }} bulletin(s) enregistré(s)
            </p>
        </div>

        <a href="{{ route('gestion.bulletins-paie.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg hover:bg-slate-800 transition">
            <span class="text-lg">+</span>
            Générer un bulletin
        </a>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total bulletins</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $stats['total'] }}</p>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-600">En brouillon</p>
            <p class="text-3xl font-extrabold text-amber-700 mt-2">{{ $stats['brouillons'] }}</p>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Publiés</p>
            <p class="text-3xl font-extrabold text-emerald-700 mt-2">{{ $stats['publies'] }}</p>
        </div>
    </div>

    {{-- Recherche et filtres --}}
    <form method="GET" action="{{ route('gestion.bulletins-paie.index') }}"
          class="flex flex-wrap gap-3 rounded-2xl border border-slate-200 bg-white p-4 mb-6 shadow-sm">

        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher un employé..."
               class="flex-1 min-w-[200px] rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-800">

        <select name="statut"
                class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm">
            <option value="">Tous les statuts</option>
            <option value="brouillon" @selected(request('statut') === 'brouillon')>Brouillon</option>
            <option value="publie" @selected(request('statut') === 'publie')>Publié</option>
        </select>

        <input type="number" name="annee" value="{{ request('annee') }}"
               placeholder="Année"
               class="w-28 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm">

        <button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-slate-700">
            Filtrer
        </button>

        <a href="{{ route('gestion.bulletins-paie.index') }}"
           class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
            Réinitialiser
        </a>
    </form>

    {{-- Tableau --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Employé</th>
                        <th class="px-5 py-4">Période</th>
                        <th class="px-5 py-4">Salaire brut</th>
                        <th class="px-5 py-4">Salaire net</th>
                        <th class="px-5 py-4">Statut</th>
                        <th class="px-5 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($bulletins as $bulletin)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-800">
                                    {{ $bulletin->employe?->nom_complet ?? 'Employé inconnu' }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $bulletin->employe?->matricule ?? '—' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 font-medium text-slate-700">
                                {{ $bulletin->periode }} {{ $bulletin->annee }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ number_format($bulletin->salaire_brut, 2, ',', ' ') }} MGA
                            </td>

                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ number_format($bulletin->salaire_net, 2, ',', ' ') }} MGA
                            </td>

                            <td class="px-5 py-4">
                                @if($bulletin->statut === 'publie')
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                        Publié
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                        Brouillon
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('gestion.bulletins-paie.show', $bulletin->id) }}"
                                   class="font-bold text-indigo-600 hover:text-indigo-800">
                                    Consulter →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                Aucun bulletin de paie trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">
            {{ $bulletins->links() }}
        </div>
    </div>
</div>
@endsection

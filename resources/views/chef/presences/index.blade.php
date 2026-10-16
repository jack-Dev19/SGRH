@extends('layouts.dashboard')

@section('title', 'Présences de mon équipe')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Présences de mon équipe</h1>
        <p class="text-sm text-slate-500">{{ $presences->total() }} enregistrement(s)</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Équipe</p>
            <p class="text-2xl font-extrabold text-purple-600">{{ $stats['equipe'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Présents</p>
            <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['presents'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Absents</p>
            <p class="text-2xl font-extrabold text-red-600">{{ $stats['absents'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Retards</p>
            <p class="text-2xl font-extrabold text-orange-600">{{ $stats['retards'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('chef.presences.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="min-w-[180px]">
                <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none">
            </div>

            <div class="min-w-[180px]">
                <select name="statut"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none">
                    <option value="">Tous les statuts</option>
                    <option value="present" {{ request('statut') === 'present' ? 'selected' : '' }}>Présent</option>
                    <option value="absent" {{ request('statut') === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="retard" {{ request('statut') === 'retard' ? 'selected' : '' }}>Retard</option>
                    <option value="conge" {{ request('statut') === 'conge' ? 'selected' : '' }}>Congé</option>
                </select>
            </div>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition">
                Filtrer
            </button>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Employé</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Entrée</th>
                        <th class="px-6 py-3 text-left">Sortie</th>
                        <th class="px-6 py-3 text-left">Heures</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($presences as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                        {{ $p->employe?->initiales ?? '??' }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $p->employe?->nom_complet }}</div>
                                        <div class="text-xs text-slate-400">{{ $p->employe?->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-slate-700 font-semibold">{{ $p->date?->format('d/m/Y') }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $p->heure_entree ? substr($p->heure_entree, 0, 5) : '—' }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $p->heure_sortie ? substr($p->heure_sortie, 0, 5) : '—' }}</td>
                            <td class="px-6 py-3 font-semibold text-slate-700">
                                {{ $p->heures_travaillees ? number_format($p->heures_travaillees, 1) . 'h' : '—' }}
                            </td>
                            <td class="px-6 py-3">
                                @php $sc = ['present' => 'emerald', 'absent' => 'red', 'retard' => 'orange', 'conge' => 'blue'][$p->statut] ?? 'slate'; @endphp
                                <span class="inline-block text-xs font-medium px-2 py-1 rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-700">
                                    {{ ucfirst($p->statut) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Aucune présence enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($presences->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $presences->links() }}</div>
        @endif
    </div>

</div>

@endsection
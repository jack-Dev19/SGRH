@extends('layouts.dashboard')

@section('title', 'Mes présences')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Mes présences</h1>
        <p class="text-sm text-slate-500">{{ $presences->total() }} enregistrement(s)</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Présent</p>
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['present'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Absent</p>
                <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-red-600">{{ $stats['absent'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Retard</p>
                <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-orange-600">{{ $stats['retard'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Heures totales</p>
                <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-purple-600">{{ number_format($stats['heures_tot'], 0) }}h</p>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Entrée</th>
                        <th class="px-6 py-3 text-left">Sortie</th>
                        <th class="px-6 py-3 text-left">Heures</th>
                        <th class="px-6 py-3 text-left">Retard</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($presences as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-semibold text-slate-800">{{ $p->date?->format('d/m/Y') }}</td>
                            <td class="px-6 py-3 text-slate-600">
                                {{ $p->heure_entree ? substr($p->heure_entree, 0, 5) : '—' }}
                            </td>
                            <td class="px-6 py-3 text-slate-600">
                                {{ $p->heure_sortie ? substr($p->heure_sortie, 0, 5) : '—' }}
                            </td>
                            <td class="px-6 py-3 font-semibold text-slate-700">
                                {{ $p->heures_travaillees ? number_format($p->heures_travaillees, 1) . 'h' : '—' }}
                            </td>
                            <td class="px-6 py-3">
                                @if($p->retard_minutes > 0)
                                    <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">
                                        +{{ $p->retard_minutes }}min
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
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
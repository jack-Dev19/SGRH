@extends('layouts.dashboard')

@section('title', 'Mes congés')

@section('content')

<div class="max-w-7xl mx-auto">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Mes congés</h1>
            <p class="text-sm text-slate-500">Historique de mes demandes</p>
        </div>
        <a href="{{ route('employe.conges.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-bold px-5 py-3 rounded-full shadow-lg shadow-purple-600/30 hover:shadow-xl transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle demande
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach([['label'=>'Total','val'=>$stats['total'],'color'=>'slate'], ['label'=>'En attente','val'=>$stats['en_attente'],'color'=>'yellow'], ['label'=>'Approuvées','val'=>$stats['approuve'],'color'=>'emerald'], ['label'=>'Refusées','val'=>$stats['refuse'],'color'=>'red']] as $s)
            <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $s['label'] }}</p>
                <p class="text-2xl font-extrabold text-{{ $s['color'] }}-600">{{ $s['val'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Période</th>
                        <th class="px-6 py-3 text-left">Durée</th>
                        <th class="px-6 py-3 text-left">Motif</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($conges as $conge)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <span class="inline-block text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                    {{ $conge->typeConge?->nom ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-slate-700 text-xs">
                                {{ $conge->date_debut?->format('d/m/Y') }} → {{ $conge->date_fin?->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-3 font-semibold text-slate-700">{{ $conge->nombre_jours }} j</td>
                            <td class="px-6 py-3 text-xs text-slate-500">{{ Str::limit($conge->motif, 40) ?: '—' }}</td>
                            <td class="px-6 py-3">
                                @php $sc = ['en_attente' => 'yellow', 'approuve' => 'emerald', 'refuse' => 'red'][$conge->statut] ?? 'slate'; @endphp
                                <span class="inline-block text-xs font-medium px-2 py-1 rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-700">
                                    {{ str_replace('_', ' ', ucfirst($conge->statut)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Aucune demande de congé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($conges->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $conges->links() }}</div>
        @endif
    </div>

</div>

@endsection
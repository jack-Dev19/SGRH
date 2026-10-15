@extends('layouts.dashboard')

@section('title', 'Congés de mon équipe')

@section('content')

<div class="max-w-7xl mx-auto">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Congés de mon équipe</h1>
        <p class="text-sm text-slate-500">{{ $conges->total() }} demande(s)</p>
    </div>

    {{-- Onglets stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        @php
            $tabs = [
                ['key' => '',           'label' => 'Toutes',      'count' => $stats['total'],      'color' => 'slate'],
                ['key' => 'en_attente', 'label' => 'En attente',  'count' => $stats['en_attente'], 'color' => 'yellow'],
                ['key' => 'approuve',   'label' => 'Approuvées',  'count' => $stats['approuve'],   'color' => 'emerald'],
                ['key' => 'refuse',     'label' => 'Refusées',    'count' => $stats['refuse'],     'color' => 'red'],
            ];
            $currentStatut = request('statut', '');
            $colors = [
                'slate'   => ['active' => 'bg-slate-800 text-white border-slate-800',     'inactive' => 'bg-white text-slate-700 border-slate-200 hover:border-slate-400'],
                'yellow'  => ['active' => 'bg-yellow-500 text-white border-yellow-500',   'inactive' => 'bg-white text-yellow-700 border-slate-200 hover:border-yellow-300'],
                'emerald' => ['active' => 'bg-emerald-600 text-white border-emerald-600', 'inactive' => 'bg-white text-emerald-700 border-slate-200 hover:border-emerald-300'],
                'red'     => ['active' => 'bg-red-600 text-white border-red-600',         'inactive' => 'bg-white text-red-700 border-slate-200 hover:border-red-300'],
            ];
        @endphp

        @foreach($tabs as $tab)
            @php $cc = $colors[$tab['color']]; $isActive = $currentStatut === $tab['key']; @endphp
            <a href="{{ route('chef.conges.index', $tab['key'] ? ['statut' => $tab['key']] : []) }}"
               class="rounded-2xl border-2 p-4 transition hover:-translate-y-0.5 {{ $isActive ? $cc['active'] : $cc['inactive'] }}">
                <p class="text-[10px] font-bold uppercase tracking-wider mb-1">{{ $tab['label'] }}</p>
                <p class="text-2xl font-extrabold">{{ $tab['count'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Employé</th>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Période</th>
                        <th class="px-6 py-3 text-left">Durée</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($conges as $c)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                        {{ $c->employe?->initiales ?? '??' }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $c->employe?->nom_complet }}</div>
                                        <div class="text-xs text-slate-400">{{ $c->employe?->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-block text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                    {{ $c->typeConge?->nom ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-xs text-slate-700">
                                {{ $c->date_debut?->format('d/m/Y') }} → {{ $c->date_fin?->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-3 font-semibold text-slate-700">{{ $c->nombre_jours }} j</td>
                            <td class="px-6 py-3">
                                @php $sc = ['en_attente' => 'yellow', 'approuve' => 'emerald', 'refuse' => 'red'][$c->statut] ?? 'slate'; @endphp
                                <span class="inline-block text-xs font-medium px-2 py-1 rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-700">
                                    {{ str_replace('_', ' ', ucfirst($c->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('chef.conges.show', $c->id) }}"
                                       class="text-xs font-medium text-purple-600 hover:underline px-2">
                                        Voir
                                    </a>

                                    @if($c->statut === 'en_attente')
                                        <form action="{{ route('chef.conges.approuver', $c->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Approuver ?');"
                                                class="text-xs font-medium px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                                                ✓
                                            </button>
                                        </form>
                                        <form action="{{ route('chef.conges.refuser', $c->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Refuser ?');"
                                                class="text-xs font-medium px-3 py-1 rounded-full bg-red-50 text-red-700 hover:bg-red-100 transition">
                                                ✕
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Aucune demande de congé.</td></tr>
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
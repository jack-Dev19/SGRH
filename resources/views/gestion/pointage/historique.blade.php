@extends('layouts.dashboard')

@section('title', 'Historique des pointages')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Historique des pointages</h1>
            <p class="text-sm text-slate-500">{{ $pointages->total() }} enregistrement(s)</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('gestion.pointage.scanner') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Scanner
            </a>

            <a href="{{ route('gestion.pointage.anomalies') }}"
                class="inline-flex items-center gap-2 bg-orange-50 hover:bg-orange-100 text-orange-700 text-sm font-bold px-5 py-3 rounded-xl transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Anomalies
            </a>
        </div>
    </div>

    {{-- Stats du jour --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total</p>
            <p class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Arrivées</p>
            <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['arrivees'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Départs</p>
            <p class="text-2xl font-extrabold text-blue-600">{{ $stats['departs'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Anomalies</p>
            <p class="text-2xl font-extrabold text-orange-600">{{ $stats['anomalies'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.pointage.historique') }}" class="flex flex-wrap items-center gap-3">

            <div class="min-w-[180px]">
                <input type="date" name="date" value="{{ $date }}"
                    class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
            </div>

            <div class="min-w-[200px]">
                <select name="employe_id"
                    class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    <option value="">Tous les employés</option>
                    @foreach($employes as $emp)
                        <option value="{{ $emp->id }}" {{ request('employe_id') === $emp->id ? 'selected' : '' }}>
                            {{ $emp->nom_complet }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[140px]">
                <select name="type"
                    class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    <option value="">Tous</option>
                    <option value="entree" {{ request('type') === 'entree' ? 'selected' : '' }}>Arrivées</option>
                    <option value="sortie" {{ request('type') === 'sortie' ? 'selected' : '' }}>Départs</option>
                </select>
            </div>

            <label class="inline-flex items-center gap-2 px-4 py-3 bg-slate-50 rounded-xl border-2 border-slate-200 cursor-pointer hover:bg-white">
                <input type="checkbox" name="anomalies" value="1" {{ request('anomalies') ? 'checked' : '' }}
                    class="h-4 w-4 text-purple-600 rounded">
                <span class="text-sm font-semibold text-slate-700">Anomalies seulement</span>
            </label>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition">
                Filtrer
            </button>

            @if(request()->hasAny(['date', 'employe_id', 'type', 'anomalies']))
                <a href="{{ route('gestion.pointage.historique') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 px-4 py-3 rounded-xl">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Employé</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-center">Heure</th>
                        <th class="px-4 py-3 text-center">Type</th>
                        <th class="px-4 py-3 text-center">Source</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pointages as $p)
                        <tr class="hover:bg-slate-50 {{ $p->est_anomalie ? 'bg-orange-50/50' : '' }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($p->employe?->prenom ?? '??', 0, 1) . substr($p->employe?->nom ?? '', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 truncate text-sm">
                                            {{ $p->employe?->nom_complet ?? '—' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $p->employe?->matricule }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-700 font-semibold text-xs">
                                {{ $p->date_heure?->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-slate-800">
                                {{ $p->date_heure?->format('H:i:s') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block text-[10px] font-bold px-2 py-1 rounded-full
                                    {{ $p->type === 'entree' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $p->type === 'entree' ? '↓ Arrivée' : '↑ Départ' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block text-[10px] font-bold px-2 py-1 rounded-full
                                    {{ $p->type_scan === 'qr_code' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $p->type_scan === 'qr_code' ? '📷 QR' : '✏️ Manuel' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($p->est_anomalie)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-1 rounded-full bg-orange-100 text-orange-700">
                                        ⚠️ Anomalie
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
                                        ✓ Valide
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('gestion.pointage.corriger', $p->id) }}"
                                    class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-600 hover:text-purple-800 bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Corriger
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Aucun pointage pour cette période
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pointages->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $pointages->links() }}</div>
        @endif
    </div>

</div>

@endsection
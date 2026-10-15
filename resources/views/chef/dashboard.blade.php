@extends('layouts.dashboard')

@section('title', 'Tableau de bord Chef')

@section('content')

<div class="max-w-7xl mx-auto">

    @if(!$departement)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-6 text-center">
            <p class="font-bold">Vous n'êtes responsable d'aucun département.</p>
            <p class="text-sm mt-1">Contactez l'administrateur.</p>
        </div>
    @else

        {{-- En-tête --}}
        <div class="bg-gradient-to-br from-purple-600 via-purple-500 to-pink-500 rounded-3xl p-6 md:p-8 mb-6 overflow-hidden relative shadow-xl">
            <div class="absolute -top-16 -right-16 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-pink-400/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col md:flex-row items-start md:items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white text-3xl font-extrabold">
                    {{ $employe->initiales }}
                </div>
                <div class="flex-1">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Chef de département</p>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-white">Bonjour {{ $employe->nom_complet }} 👋</h1>
                    <p class="text-purple-100 text-sm mt-1">{{ $departement->nom }}</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Mon équipe</p>
                <p class="text-3xl font-extrabold text-purple-600">{{ $stats['equipe'] }}</p>
                <p class="text-xs text-slate-400 mt-1">employés</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Présents aujourd'hui</p>
                <p class="text-3xl font-extrabold text-emerald-600">{{ $stats['presents_today'] }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $stats['absents_today'] }} absent(s)</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Congés à valider</p>
                <p class="text-3xl font-extrabold text-yellow-600">{{ $stats['conges_a_valider'] }}</p>
                <p class="text-xs text-slate-400 mt-1">demande(s)</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Taux de présence</p>
                <p class="text-3xl font-extrabold text-blue-600">{{ $stats['taux_presence'] }}%</p>
                <p class="text-xs text-slate-400 mt-1">ce mois</p>
            </div>
        </div>

        {{-- Grille --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Congés à valider --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">Congés à valider</h2>
                    <a href="{{ route('chef.conges.index') }}" class="text-xs font-semibold text-purple-600 hover:underline">Voir tout</a>
                </div>

                <div class="space-y-3">
                    @forelse($congesEnAttente as $c)
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ $c->employe?->initiales ?? '??' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">
                                    {{ $c->employe?->nom_complet }}
                                </p>
                                <p class="text-xs text-slate-500 truncate">
                                    {{ $c->typeConge?->nom }} · {{ $c->date_debut?->format('d/m') }} → {{ $c->date_fin?->format('d/m') }}
                                </p>
                            </div>
                            <div class="flex gap-1 shrink-0">
                                <form action="{{ route('chef.conges.approuver', $c) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Approuver ce congé ?');"
                                        class="w-8 h-8 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700 flex items-center justify-center transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                                <form action="{{ route('chef.conges.refuser', $c) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Refuser ce congé ?');"
                                        class="w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 flex items-center justify-center transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Aucune demande en attente.</p>
                    @endforelse
                </div>
            </div>

            {{-- Présences du jour --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">Présences du jour</h2>
                    <a href="{{ route('chef.presences.index') }}" class="text-xs font-semibold text-purple-600 hover:underline">Voir tout</a>
                </div>

                <div class="space-y-2">
                    @forelse($presencesJour as $p)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-[11px] shrink-0">
                                    {{ $p->employe?->initiales ?? '??' }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $p->employe?->nom_complet }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $p->heure_entree ? substr($p->heure_entree, 0, 5) : '—' }}
                                        → {{ $p->heure_sortie ? substr($p->heure_sortie, 0, 5) : '—' }}
                                    </p>
                                </div>
                            </div>
                            @php $sc = ['present' => 'emerald', 'absent' => 'red', 'retard' => 'orange', 'conge' => 'blue'][$p->statut] ?? 'slate'; @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-700">
                                {{ ucfirst($p->statut) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Aucune présence enregistrée aujourd'hui.</p>
                    @endforelse
                </div>
            </div>
        </div>

    @endif

</div>

@endsection
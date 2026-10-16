@extends('layouts.dashboard')

@section('title', 'Mon espace')

@section('content')

<div class="max-w-7xl mx-auto">

    @if(!$employe)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-bold">Aucun profil employé associé à votre compte.</p>
            <p class="text-sm mt-1">Contactez l'administrateur.</p>
        </div>
    @else

        {{-- En-tête --}}
        <div class="bg-gradient-to-br from-purple-600 via-purple-500 to-pink-500 rounded-3xl p-6 md:p-8 mb-6 overflow-hidden relative shadow-xl">
            <div class="absolute -top-16 -right-16 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-pink-400/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col md:flex-row items-start md:items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white text-3xl font-extrabold shadow-lg">
                    {{ $employe->initiales }}
                </div>
                <div class="flex-1">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Mon espace</p>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-white">Bonjour {{ $employe->nom_complet }} 👋</h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $employe->poste?->nom ?? 'Poste non défini' }}
                        @if($employe->departement)
                            · {{ $employe->departement->nom }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Cartes stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Présences ce mois</p>
                <p class="text-3xl font-extrabold text-emerald-600">{{ $stats['presences_mois'] }}</p>
                <p class="text-xs text-slate-400 mt-1">jours</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Congés en attente</p>
                <p class="text-3xl font-extrabold text-yellow-600">{{ $stats['conges_attente'] }}</p>
                <p class="text-xs text-slate-400 mt-1">demande(s)</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Solde de congés</p>
                <p class="text-3xl font-extrabold text-blue-600">{{ $stats['solde_conges'] }}</p>
                <p class="text-xs text-slate-400 mt-1">jours restants</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Contrat</p>
                @if($contratActif)
                    <p class="text-lg font-extrabold text-purple-600">{{ $contratActif->type }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                        depuis {{ $contratActif->date_debut?->format('m/Y') }}
                    </p>
                @else
                    <p class="text-lg font-extrabold text-slate-400">—</p>
                    <p class="text-xs text-slate-400 mt-1">Aucun contrat</p>
                @endif
            </div>
        </div>

        {{-- Grille contenu --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Dernières présences --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">Mes dernières présences</h2>
                    <a href="{{ route('employe.presences.index') }}" class="text-xs font-semibold text-purple-600 hover:underline">Voir tout</a>
                </div>
                <div class="space-y-2">
                    @forelse($dernieresPresences as $p)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $p->date?->format('d/m/Y') }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $p->heure_entree ? substr($p->heure_entree, 0, 5) : '—' }}
                                        →
                                        {{ $p->heure_sortie ? substr($p->heure_sortie, 0, 5) : '—' }}
                                    </p>
                                </div>
                            </div>
                            @php
                                $sc = ['present' => 'emerald', 'absent' => 'red', 'retard' => 'orange', 'conge' => 'blue'][$p->statut] ?? 'slate';
                            @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-700">
                                {{ ucfirst($p->statut) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Aucune présence enregistrée.</p>
                    @endforelse
                </div>
            </div>

            {{-- Derniers congés --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">Mes derniers congés</h2>
                    <a href="{{ route('employe.conges.index') }}" class="text-xs font-semibold text-purple-600 hover:underline">Voir tout</a>
                </div>
                <div class="space-y-2">
                    @forelse($derniersConges as $c)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $c->typeConge?->nom ?? 'Congé' }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $c->date_debut?->format('d/m') }} → {{ $c->date_fin?->format('d/m/Y') }}
                                        · {{ $c->nombre_jours }}j
                                    </p>
                                </div>
                            </div>
                            @php
                                $sc = ['en_attente' => 'yellow', 'approuve' => 'emerald', 'refuse' => 'red'][$c->statut] ?? 'slate';
                            @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-700">
                                {{ str_replace('_', ' ', ucfirst($c->statut)) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Aucune demande de congé.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('employe.conges.create') }}"
                class="group bg-white hover:bg-purple-50 rounded-2xl border border-slate-200 hover:border-purple-300 p-5 flex items-center gap-4 transition shadow-sm hover:shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Demander un congé</p>
                    <p class="text-xs text-slate-500">Créer une nouvelle demande</p>
                </div>
            </a>

            <a href="{{ route('employe.paie.index') }}"
                class="group bg-white hover:bg-emerald-50 rounded-2xl border border-slate-200 hover:border-emerald-300 p-5 flex items-center gap-4 transition shadow-sm hover:shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Mes fiches de paie</p>
                    <p class="text-xs text-slate-500">Consulter mes bulletins</p>
                </div>
            </a>

            <a href="{{ route('employe.profil') }}"
                class="group bg-white hover:bg-blue-50 rounded-2xl border border-slate-200 hover:border-blue-300 p-5 flex items-center gap-4 transition shadow-sm hover:shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Mon profil</p>
                    <p class="text-xs text-slate-500">Voir mes informations</p>
                </div>
            </a>
        </div>

    @endif

</div>

@endsection
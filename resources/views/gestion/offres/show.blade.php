@extends('layouts.dashboard')

@section('title', $offre->titre)

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.offres.index') }}" class="hover:text-purple-600 transition">Offres</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">{{ $offre->titre }}</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold">{{ $offre->titre }}</h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $offre->type_contrat }} · {{ $offre->lieu }}
                    </p>
                </div>
                <span class="text-xs font-bold px-4 py-2 rounded-full
                    @if($offre->statut === 'publiee') bg-emerald-400 text-emerald-900
                    @elseif($offre->statut === 'brouillon') bg-amber-400 text-amber-900
                    @else bg-slate-300 text-slate-700 @endif">
                    {{ ucfirst($offre->statut) }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <h2 class="text-sm font-bold text-slate-400 uppercase mb-2">Description</h2>
                <p class="text-slate-700 whitespace-pre-line">{{ $offre->description }}</p>
            </div>

            @if($offre->missions)
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase mb-2">Missions</h2>
                    <p class="text-slate-700 whitespace-pre-line">{{ $offre->missions }}</p>
                </div>
            @endif

            @if($offre->profil_recherche)
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase mb-2">Profil recherché</h2>
                    <p class="text-slate-700 whitespace-pre-line">{{ $offre->profil_recherche }}</p>
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Email</p>
                    <p class="text-sm text-slate-800">{{ $offre->email_contact }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Deadline</p>
                    <p class="text-sm text-slate-800">{{ $offre->deadline?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Expérience</p>
                    <p class="text-sm text-slate-800">{{ $offre->experience ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Salaire</p>
                    <p class="text-sm text-slate-800">{{ $offre->salaire ?? '—' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.offres.edit', $offre) }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl">
                    ✏️ Modifier
                </a>

                @if($offre->statut !== 'publiee')
                    <form action="{{ route('gestion.offres.publier', $offre) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl">
                            ✅ Publier
                        </button>
                    </form>
                @endif

                <a href="{{ route('offres.show', $offre->slug) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-5 py-2.5 rounded-xl ml-auto">
                    🔗 Voir sur le site
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
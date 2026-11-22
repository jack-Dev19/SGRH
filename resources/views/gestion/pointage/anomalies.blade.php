
@extends('layouts.dashboard')

@section('title', 'Anomalies de pointage')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="mb-8">
        <span class="text-xs font-bold uppercase tracking-wider text-orange-600">
            Attention requise
        </span>

        <h1 class="text-3xl font-extrabold text-slate-900 mt-2">
            Anomalies de pointage
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Liste des pointages nécessitant une vérification RH.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Filtre --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">

            <input
                type="date"
                name="date"
                value="{{ $date }}"
                class="flex-1 min-w-[200px] rounded-lg border border-slate-200 px-4 py-2.5 text-sm"
            >

            <button
                type="submit"
                class="bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold"
            >
                Filtrer
            </button>

            <a
                href="{{ route('gestion.pointage.historique') }}"
                class="px-4 py-2.5 rounded-lg bg-slate-100 text-slate-700 text-sm"
            >
                Historique
            </a>
        </form>
    </div>

    {{-- Liste --}}
    @if(count($anomalies) > 0)

        <div class="bg-white rounded-2xl border border-orange-200 overflow-hidden">

            <div class="bg-orange-50 px-6 py-4 border-b border-orange-100">
                <h2 class="font-bold text-orange-800">
                    {{ count($anomalies) }} anomalie(s) détectée(s)
                </h2>

                <p class="text-xs text-orange-600 mt-1">
                    Le {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </p>
            </div>

            <div class="divide-y divide-slate-100">

                @foreach($anomalies as $a)

                    <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">

                        <div class="w-11 h-11 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                            !
                        </div>

                        <div class="flex-1 min-w-0">

                            <p class="font-bold text-slate-900">
                                {{ $a['employe'] }}
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Matricule : {{ $a['matricule'] }}
                                · Heure : {{ $a['heure'] }}
                            </p>

                            <p class="text-sm font-semibold text-orange-700 mt-2">
                                {{ $a['description'] }}
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                Type :
                                {{ $a['type'] === 'entree' ? 'Arrivée' : 'Départ' }}
                            </p>

                        </div>

                        <a
                            href="{{ route('gestion.pointage.corriger', $a['id']) }}"
                            class="inline-flex justify-center px-4 py-2 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 text-sm font-bold"
                        >
                            Corriger
                        </a>

                    </div>

                @endforeach

            </div>
        </div>

    @else

        <div class="bg-white rounded-2xl border border-emerald-200 p-12 text-center">

            <div class="text-4xl text-emerald-600 mb-4">
                ✓
            </div>

            <h3 class="font-bold text-emerald-700">
                Aucune anomalie
            </h3>

            <p class="text-sm text-slate-500 mt-2">
                Aucun pointage anormal n'a été enregistré pour cette journée.
            </p>

        </div>

    @endif

</div>

@endsection

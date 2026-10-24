@extends('layouts.dashboard')

@section('title', 'Modifier absence')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.absences.index') }}" class="hover:text-purple-600 transition">Absences</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('gestion.absences.show', $absence) }}" class="hover:text-purple-600 transition">
            {{ $absence->employe?->nom_complet }}
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">Modifier</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Modifier l'absence</h1>
        <p class="text-sm text-slate-500">
            {{ $absence->employe?->nom_complet }} — {{ $absence->date_debut?->format('d/m/Y') }}
        </p>
    </div>

    {{-- Formulaire --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
            <h2 class="text-lg font-extrabold">Modifier les informations</h2>
            <p class="text-blue-100 text-sm mt-1">Toute modification est enregistrée pour audit.</p>
        </div>

        <form action="{{ route('gestion.absences.update', $absence) }}" method="POST"
              enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-4 rounded-xl">
                    <p class="font-bold mb-2">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Employé --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Employé concerné</h3>

                <select name="employe_id" required
                    class="w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    @foreach($employes as $emp)
                        <option value="{{ $emp->id }}" {{ old('employe_id', $absence->employe_id) === $emp->id ? 'selected' : '' }}>
                            {{ $emp->nom_complet }} ({{ $emp->matricule }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Période --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Période</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Date début <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_debut" required
                            value="{{ old('date_debut', $absence->date_debut?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Date fin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_fin" required
                            value="{{ old('date_fin', $absence->date_fin?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- Détails --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Détails</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Motif <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="motif" required maxlength="150"
                            value="{{ old('motif', $absence->motif) }}"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="3" maxlength="500"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none resize-none">{{ old('description', $absence->description) }}</textarea>
                    </div>

                    {{-- Justificatif actuel --}}
                    @if($absence->justificatif)
                        <div>
                            <p class="text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Justificatif actuel</p>
                            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <a href="{{ Storage::url($absence->justificatif) }}" target="_blank"
                                   class="text-sm text-emerald-700 hover:underline font-semibold">
                                    Voir le justificatif actuel
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Nouveau justificatif --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            {{ $absence->justificatif ? 'Remplacer le justificatif' : 'Ajouter un justificatif' }}
                        </label>
                        <input type="file" name="justificatif" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-4 py-3 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-purple-100 file:text-purple-700 file:font-semibold file:text-xs hover:file:bg-purple-200">
                        <p class="text-xs text-slate-400 mt-1">
                            Formats : PDF, JPG, PNG (max 2 Mo).
                            @if($absence->justificatif)
                                Laisser vide pour conserver l'actuel.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Statut actuel --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Statut</h3>

                @php
                    $statuts = [
                        'en_attente'   => ['label' => 'En attente',    'color' => 'yellow'],
                        'justifie'     => ['label' => 'Justifiée',     'color' => 'emerald'],
                        'non_justifie' => ['label' => 'Non justifiée', 'color' => 'red'],
                        'autorise'     => ['label' => 'Autorisée',     'color' => 'blue'],
                        'annule'       => ['label' => 'Annulée',       'color' => 'slate'],
                    ];
                    $colors = [
                        'yellow'  => ['bg' => 'bg-yellow-50',  'border' => 'border-yellow-200',  'text' => 'text-yellow-800',  'sub' => 'text-yellow-600'],
                        'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'sub' => 'text-emerald-600'],
                        'red'     => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'text' => 'text-red-800',     'sub' => 'text-red-600'],
                        'blue'    => ['bg' => 'bg-blue-50',    'border' => 'border-blue-200',    'text' => 'text-blue-800',    'sub' => 'text-blue-600'],
                        'slate'   => ['bg' => 'bg-slate-50',   'border' => 'border-slate-200',   'text' => 'text-slate-800',   'sub' => 'text-slate-600'],
                    ];
                @endphp

                <div class="space-y-3">
                    @foreach($statuts as $key => $statut)
                        @php $cc = $colors[$statut['color']]; @endphp
                        <label class="block {{ $cc['bg'] }} border {{ $cc['border'] }} rounded-xl p-4 cursor-pointer hover:shadow-md transition">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="statut" value="{{ $key }}"
                                    {{ old('statut', $absence->statut) === $key ? 'checked' : '' }} class="mt-1">
                                <div>
                                    <p class="font-semibold {{ $cc['text'] }}">{{ $statut['label'] }}</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                <form action="{{ route('gestion.absences.destroy', $absence) }}" method="POST"
                    onsubmit="return confirm('Voulez-vous vraiment supprimer cette absence ?');"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 text-sm font-bold px-5 py-2.5 rounded-xl transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                    </button>
                </form>

                <div class="flex items-center gap-3">
                    <a href="{{ route('gestion.absences.show', $absence) }}"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-blue-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

@endsection
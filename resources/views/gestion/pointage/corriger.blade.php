@extends('layouts.dashboard')

@section('title', 'Corriger le pointage')

@section('content')

<div class="max-w-2xl mx-auto">

    <a href="{{ route('gestion.pointage.historique') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour à l'historique
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Corriger le pointage</h1>
            <p class="text-purple-100 text-sm mt-1">Toute correction est enregistrée pour audit.</p>
        </div>

        {{-- Infos pointage --}}
        <div class="p-6 bg-slate-50 border-b border-slate-100">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Employé</p>
                    <p class="text-sm font-bold text-slate-900">{{ $pointage->employe?->nom_complet }}</p>
                    <p class="text-xs text-slate-500 font-mono">{{ $pointage->employe?->matricule }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pointage actuel</p>
                    <p class="text-sm font-bold text-slate-900 font-mono">
                        {{ $pointage->date_heure?->format('d/m/Y H:i:s') }}
                    </p>
                    <p class="text-xs text-slate-500">
                        Type : {{ $pointage->type === 'entree' ? 'Arrivée' : 'Départ' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('gestion.pointage.updateCorrection', $pointage->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs p-3 rounded-xl">
                    @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
                </div>
            @endif

            {{-- Nouvelle date/heure --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Nouvelle date et heure <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="date_heure" required
                    value="{{ old('date_heure', $pointage->date_heure?->format('Y-m-d\TH:i')) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Type <span class="text-red-500">*</span>
                </label>
                <select name="type" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <option value="entree" {{ old('type', $pointage->type) === 'entree' ? 'selected' : '' }}>Arrivée</option>
                    <option value="sortie" {{ old('type', $pointage->type) === 'sortie' ? 'selected' : '' }}>Départ</option>
                </select>
            </div>

            {{-- Raison (obligatoire) --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Raison de la correction <span class="text-red-500">*</span>
                </label>
                <textarea name="raison" rows="3" required minlength="5" maxlength="255"
                    placeholder="Ex : Panne de caméra, employé oublié son badge..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none resize-none">{{ old('raison') }}</textarea>
                <p class="text-[10px] text-slate-400 mt-1">Minimum 5 caractères. La raison sera tracée dans l'historique.</p>
            </div>

            {{-- Info audit --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-xs text-blue-700">
                    <p class="font-bold mb-1">Traçabilité</p>
                    <p>Cette correction sera enregistrée dans l'historique avec votre nom, l'ancienne et la nouvelle valeur.</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.pointage.historique') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                    Annuler
                </a>
                <button type="submit"
                    onclick="return confirm('Confirmer la correction de ce pointage ?');"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer la correction
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
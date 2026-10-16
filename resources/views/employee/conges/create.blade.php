@extends('layouts.dashboard')

@section('title', 'Demander un congé')

@section('content')

<div class="max-w-2xl mx-auto">

    <a href="{{ route('employe.conges.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour à mes congés
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Demander un congé</h1>
            <p class="text-purple-100 text-sm mt-1">Remplissez le formulaire pour créer votre demande.</p>
        </div>

        <form action="{{ route('employe.conges.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs p-3 rounded-xl">
                    @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
                </div>
            @endif

            {{-- Type de congé --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Type de congé <span class="text-red-500">*</span>
                </label>
                <select name="type_conge_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <option value="">— Sélectionner —</option>
                    @foreach($typesConges as $t)
                        <option value="{{ $t->id }}" {{ old('type_conge_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                        Date de début <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_debut" required
                        value="{{ old('date_debut', now()->addDay()->format('Y-m-d')) }}"
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                        Date de fin <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_fin" required
                        value="{{ old('date_fin', now()->addDays(3)->format('Y-m-d')) }}"
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>
            </div>

            {{-- Motif --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Motif
                </label>
                <textarea name="motif" rows="3" placeholder="Raison de la demande (optionnel)..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none resize-none">{{ old('motif') }}</textarea>
            </div>

            {{-- Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-xs text-blue-700">
                    <p class="font-bold mb-1">À savoir :</p>
                    <p>Votre demande sera envoyée à votre chef de département et au RH pour validation.</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('employe.conges.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Envoyer la demande
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
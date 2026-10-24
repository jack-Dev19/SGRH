@extends('layouts.dashboard')

@section('title', $user->name)

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.users.index') }}" class="hover:text-purple-600">Utilisateurs</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">{{ $user->name }}</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/40 flex items-center justify-center text-white font-extrabold text-xl">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">{{ $user->name }}</h1>
                    <p class="text-purple-100 text-sm">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Informations du compte</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Nom complet</p>
                    <p class="text-sm font-medium text-slate-800">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Email</p>
                    <p class="text-sm text-slate-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Rôle</p>
                    @php
                        $badge = match($user->role?->nom) {
                            'Administrateur'      => 'bg-red-100 text-red-700',
                            'Responsable RH'      => 'bg-purple-100 text-purple-700',
                            'Chef de département' => 'bg-blue-100 text-blue-700',
                            'Employé'             => 'bg-emerald-100 text-emerald-700',
                            default               => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $badge }}">
                        {{ $user->role?->nom ?? 'Aucun' }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Statut</p>
                    @if($user->statut == 1)
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Actif</span>
                    @else
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700">Inactif</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Matricule lié</p>
                    <p class="text-sm font-mono text-slate-800">{{ $user->matricule ?? '—' }}</p>
                </div>
                @if($user->employe)
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Employé</p>
                        <a href="{{ route('gestion.employes.show', $user->employe) }}"
                            class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                            {{ $user->employe->nom_complet }} →
                        </a>
                    </div>
                @endif
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Créé le</p>
                    <p class="text-sm text-slate-800">{{ $user->created_at?->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.users.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour à la liste
                </a>

                <div class="flex items-center gap-3">
                    <form action="{{ route('gestion.users.destroy', $user) }}" method="POST" class="inline"
                        onsubmit="return confirm('Supprimer ce compte ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 text-sm font-bold px-5 py-2.5 rounded-xl transition">
                            Supprimer
                        </button>
                    </form>

                    <a href="{{ route('gestion.users.edit', $user) }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg">
                        Modifier
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
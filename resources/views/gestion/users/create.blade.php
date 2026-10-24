@extends('layouts.dashboard')

@section('title', 'Nouveau compte')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.users.index') }}" class="hover:text-purple-600">Utilisateurs</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Nouveau</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Créer un compte utilisateur</h1>
            <p class="text-purple-100 text-sm mt-1">
                Rôles disponibles : {{ $rolesAutorises->pluck('nom')->join(', ') }}
            </p>
        </div>

        <form action="{{ route('gestion.users.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
                    <p class="text-sm font-bold mb-2">Erreurs :</p>
                    <ul class="text-xs list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Nom --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                    Nom complet *
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Ex : Andry Rakoto"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                    Email *
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="votre@email.com"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            {{-- ⭐ Rôle --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                    Rôle *
                </label>
                <select name="role_id" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    <option value="">-- Sélectionner un rôle --</option>
                    @foreach($rolesAutorises as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->nom }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">
                    Le rôle détermine l'espace auquel l'utilisateur aura accès.
                </p>
            </div>

            {{-- ⭐ Employé lié --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                    Employé lié <span class="text-slate-400 font-normal">(obligatoire pour Chef / Employé)</span>
                </label>
                <select name="matricule"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    <option value="">-- Aucun --</option>
                    @foreach($employes as $emp)
                        <option value="{{ $emp->matricule }}" {{ old('matricule') == $emp->matricule ? 'selected' : '' }}>
                            {{ $emp->nom_complet }} ({{ $emp->matricule }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">
                    L'employé doit avoir un dossier existant dans la table `employes`.
                </p>
            </div>

            {{-- Mot de passe --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                        Mot de passe *
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                        Confirmer *
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
            </div>

            {{-- Statut --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                    Statut
                </label>
                <select name="statut"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    <option value="1" {{ old('statut', 1) == 1 ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ old('statut') == 0 ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.users.index') }}"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg">
                    Créer le compte
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
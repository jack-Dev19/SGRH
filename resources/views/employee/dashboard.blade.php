@extends('layouts.dashboard')

@section('title', 'Dashboard Employé')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">
            Bonjour {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-slate-500 mt-1">
            Espace Employé — Matricule : <strong>{{ auth()->user()->matricule }}</strong>
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <p class="text-slate-600">
            Le contenu de votre espace personnel sera construit à l'étape suivante.
        </p>
    </div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Dashboard Chef')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">
            Bienvenue {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-slate-500 mt-1">
            Espace Chef de département — Matricule : <strong>{{ auth()->user()->matricule }}</strong>
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <p class="text-slate-600">
            Le contenu du dashboard Chef sera construit à l'étape suivante.
        </p>
    </div>
@endsection
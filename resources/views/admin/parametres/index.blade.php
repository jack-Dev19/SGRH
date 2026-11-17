
@extends('layouts.dashboard')

@section('title', 'Paramètres')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- En-tête du centre de paramètres --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Paramètres
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Configurez et gérez les paramètres généraux de votre application SGRH.
            </p>
        </div>

        {{-- Actions globales --}}
        <div class="flex flex-wrap items-center gap-2">

            <button
                type="button"
                class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition"
            >
                Annuler
            </button>

            <button
                type="button"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"
            >
                Enregistrer les modifications
            </button>

        </div>

    </div>

    {{-- Navigation et contenu des paramètres --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Navigation latérale --}}
        <aside class="lg:col-span-3">
            <div class="bg-white border border-slate-200 rounded-xl p-3">

                <h2 class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    Configuration
                </h2>

                <nav class="space-y-1">

                    {{-- Les liens seront rendus interactifs à l'étape suivante --}}
                    <a href="#general" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-blue-700 bg-blue-50">
                        Général
                    </a>

                    <a href="#notifications" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                        Notifications
                    </a>

                    <a href="#securite" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                        Sécurité
                    </a>

                    <a href="#paie" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                        Paie
                    </a>

                    <a href="#conges" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                        Congés
                    </a>

                    <a href="#presence" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                        Présence
                    </a>

                    <a href="#performance" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                        Performance
                    </a>

                    <a href="#recrutement" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                        Recrutement
                    </a>

                </nav>

            </div>
        </aside>

        {{-- Zone principale --}}
        <main class="lg:col-span-9">

            <div class="bg-white border border-slate-200 rounded-xl p-6 min-h-[400px]">
            
                {{-- Section Général --}}
                @include('admin.parametres.partials.general')
            
                {{-- Section Notifications --}}
                @include('admin.parametres.partials.notifications')
            
            </div>
        
        </main>

    </div>

</div>

@endsection

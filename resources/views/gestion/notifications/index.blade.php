
@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ==========================================================
        MESSAGES FLASH
    =========================================================== --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-50 border border-red-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-red-800">
                {{ session('error') }}
            </span>
        </div>
    @endif

    {{-- ==========================================================
        EN-TÊTE DE LA PAGE
    =========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                    Centre
                </span>
            </div>

            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Notifications
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Consultez toutes vos notifications et suivez les dernières activités.
            </p>
        </div>

        {{-- Action : tout marquer comme lu --}}
        @if($nombreNonLues > 0)
            <form action="{{ route('notifications.tout-lire') }}" method="POST">
                @csrf
                @method('PATCH')

                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">

                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 12l5 5L20 6"/>
                    </svg>

                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    {{-- ==========================================================
        STATISTIQUES
    =========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Total
                    </p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1">
                        {{ $notifications->total() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.17V11a6 6 0 00-12 0v3.17c0 .538-.214 1.055-.595 1.425L4 17h5m3-14v2m-4 16a3 3 0 006 0"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Non lues --}}
        <div class="bg-white rounded-2xl border border-purple-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-purple-600">
                        Non lues
                    </p>
                    <p class="text-3xl font-extrabold text-purple-700 mt-1">
                        {{ $nombreNonLues }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 8a6 6 0 00-12 0v4c0 2-2 4-2 4h16s-2-2-2-4V8zM10 20h4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Lues --}}
        <div class="bg-white rounded-2xl border border-emerald-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">
                        Lues
                    </p>
                    <p class="text-3xl font-extrabold text-emerald-700 mt-1">
                        {{ $notifications->total() - $nombreNonLues }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ==========================================================
        LISTE DES NOTIFICATIONS
    =========================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Titre de la liste --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 p-5">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Toutes vos notifications
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $notifications->total() }} notification(s) enregistrée(s)
                </p>
            </div>

            <span class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                {{ $nombreNonLues }} non lue(s)
            </span>
        </div>

        {{-- Contenu --}}
        @forelse($notifications as $notification)

            <div class="flex gap-4 p-5 border-b border-slate-100 transition-colors
                {{ !$notification->lue ? 'bg-purple-50/40 hover:bg-purple-50/70' : 'bg-white hover:bg-slate-50' }}">

                {{-- Icône --}}
                <div class="shrink-0">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        {{ !$notification->lue ? 'bg-purple-100 text-purple-600' : 'bg-slate-100 text-slate-500' }}">

                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 8a6 6 0 00-12 0v4c0 2-2 4-2 4h16s-2-2-2-4V8zM10 20h4"/>
                        </svg>
                    </div>
                </div>

                {{-- Texte --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">
                                    {{ $notification->titre }}
                                </h3>

                                {{-- Badge statut --}}
                                @if(!$notification->lue)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-bold text-purple-700 ring-1 ring-inset ring-purple-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                                        Nouvelle
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500">
                                        Lue
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                                {{ $notification->message }}
                            </p>

                            {{-- Date --}}
                            <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>

                                    {{ $notification->created_at?->format('d/m/Y à H:i') ?? 'Date inconnue' }}
                                </span>

                                @if($notification->date_lecture)
                                    <span class="inline-flex items-center gap-1.5 text-emeremerald-600">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Lue le {{ $notification->date_lecture->format('d/m/Y à H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex shrink-0 items-center gap-2">
                            @if(!$notification->lue)
                                <form action="{{ route('notifications.lire', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        title="Marquer comme lue"
                                        class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-white text-purple-600 ring-1 ring-inset ring-purple-200 transition hover:bg-purple-50 hover:ring-purple-300">

                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        @empty

            {{-- État vide --}}
            <div class="py-16 px-6 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 8a6 6 0 00-12 0v4c0 2-2 4-2 4h16s-2-2-2-4V8zM10 20h4"/>
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-slate-900">
                    Aucune notification
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Vous n'avez aucune notification pour le moment.
                </p>
            </div>

        @endforelse

    </div>

    {{-- ==========================================================
        PAGINATION
    =========================================================== --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif

</div>

@endsection
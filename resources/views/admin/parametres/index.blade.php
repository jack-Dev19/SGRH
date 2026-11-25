@extends('layouts.dashboard')

@section('title', 'Paramètres')

@section('content')

<div
    class="max-w-7xl mx-auto space-y-6"
    x-data="{ activeTab: @js(session('activeTab', 'general')) }"
>

    {{-- En-tête du centre de paramètres --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Paramètres
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Configurez et gérez les paramètres de votre application SGRH.
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

                <nav class="space-y-1" aria-label="Rubriques des paramètres">

                    <button
                        type="button"
                        @click="activeTab = 'general'"
                        :class="activeTab === 'general'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'general' ? 'page' : null"
                    >
                        Général
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'notifications'"
                        :class="activeTab === 'notifications'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'notifications' ? 'page' : null"
                    >
                        Notifications
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'securite'"
                        :class="activeTab === 'securite'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'securite' ? 'page' : null"
                    >
                        Sécurité
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'paie'"
                        :class="activeTab === 'paie'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'paie' ? 'page' : null"
                    >
                        Paie
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'conges'"
                        :class="activeTab === 'conges'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'conges' ? 'page' : null"
                    >
                        Congés
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'presence'"
                        :class="activeTab === 'presence'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'presence' ? 'page' : null"
                    >
                        Présence
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'performance'"
                        :class="activeTab === 'performance'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'performance' ? 'page' : null"
                    >
                        Performance
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'recrutement'"
                        :class="activeTab === 'recrutement'
                            ? 'bg-blue-50 text-blue-700'
                            : 'text-slate-600 hover:bg-slate-50'"
                        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium transition"
                        :aria-current="activeTab === 'recrutement' ? 'page' : null"
                    >
                        Recrutement
                    </button>

                </nav>

            </div>
        </aside>

        {{-- Zone principale --}}
        <main class="lg:col-span-9">

            <div class="bg-white border border-slate-200 rounded-xl p-6 min-h-[400px]">

                {{-- Contenu Général --}}
                <div x-show="activeTab === 'general'" x-cloak>
                    @include('admin.parametres.partials.general')
                </div>

                {{-- Contenu Notifications --}}
                <div x-show="activeTab === 'notifications'" x-cloak>
                    @include('admin.parametres.partials.notifications')
                </div>

                {{-- Contenu Sécurité --}}
                <div x-show="activeTab === 'securite'" x-cloak>
                    @include('admin.parametres.partials.securite')
                </div>

                {{-- Contenu Congés --}}
                <div x-show="activeTab === 'conges'" x-cloak>
                    @include('admin.parametres.partials.conges')
                </div>

                {{-- Contenus à développer --}}
                <div
                    x-show="!['general', 'notifications', 'securite', 'conges'].includes(activeTab)"
                    x-cloak
                    class="py-10 text-center"
                >
                    <h2 class="text-lg font-semibold text-slate-800">
                        <span x-text="{
                            securite: 'Sécurité',
                            paie: 'Paie',
                            conges: 'Congés',
                            presence: 'Présence',
                            performance: 'Performance',
                            recrutement: 'Recrutement'
                        }[activeTab]"></span>
                    </h2>

                    <p class="text-sm text-slate-500 mt-2">
                        Le contenu de cette rubrique sera ajouté lors de son développement.
                    </p>
                </div>

            </div>

        </main>

    </div>

</div>

@endsection
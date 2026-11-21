
@extends('layouts.dashboard')

@section('title', 'Mes fiches de paie')

@section('content')

@php
    $devise = \App\Models\Parametre::obtenir('general', 'devise', 'MGA');
    $conversionDevise = app(\App\Services\ConversionDeviseService::class);
    $tauxConversion = $conversionDevise->obtenirTaux($devise);

    $formatterMontant = function ($montant) use ($tauxConversion, $devise) {
        $montantConverti = (float) $montant * (float) $tauxConversion;

        return number_format($montantConverti, 0, ',', ' ') . ' ' . $devise;
    };
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Mes fiches de paie
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Consultez et téléchargez vos bulletins de paie.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-12V5m0 14v-2m-7-5h14"/>
            </svg>

            <span class="text-sm font-semibold">
                {{ $bulletins->total() }} bulletin(s)
            </span>
        </div>
    </div>

    {{-- Liste des bulletins --}}
    @forelse($bulletins as $bulletin)

        @php
            $mois = (int) $bulletin->periode;

            $nomMois = \Carbon\Carbon::create()
                ->month($mois)
                ->locale('fr')
                ->translatedFormat('F');

            $statut = strtolower((string) $bulletin->statut);

            $couleurs = [
                'gradient' => 'from-indigo-600 to-blue-600',
                'border' => 'border-indigo-100',
            ];

            if (in_array($statut, ['payee', 'payé', 'payee'])) {
                $couleurs = [
                    'gradient' => 'from-emerald-600 to-green-600',
                    'border' => 'border-emerald-100',
                ];
            }
        @endphp

        <div class="bg-white border {{ $couleurs['border'] }} rounded-2xl shadow-sm hover:shadow-md transition p-5 mb-5">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                {{-- Informations bulletin --}}
                <div class="flex items-start gap-4">

                    <div class="w-14 h-14 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-7 h-7 text-indigo-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M14 3v5h5M9 13h6M9 17h6"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800 capitalize">
                            Bulletin de {{ $nomMois }} {{ $bulletin->annee }}
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Généré le :
                            {{ $bulletin->date_generation
                                ? \Carbon\Carbon::parse($bulletin->date_generation)->format('d/m/Y')
                                : 'Non renseigné' }}
                        </p>

                        <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold
                            {{ in_array($statut, ['payee', 'payé'])
                                ? 'bg-green-100 text-green-700'
                                : ($statut === 'validee'
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($bulletin->statut ?? 'Disponible') }}
                        </span>
                    </div>

                </div>

                {{-- Montants --}}
                <div class="grid grid-cols-2 gap-4 md:gap-8">

                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Salaire brut
                        </p>

                        <p class="text-sm font-bold text-gray-800">
                            {{ $formatterMontant($bulletin->salaire_brut) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Salaire net
                        </p>

                        <p class="text-lg font-bold text-emerald-600">
                            {{ $formatterMontant($bulletin->salaire_net) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Salaire de base
                        </p>

                        <p class="text-sm font-semibold text-gray-700">
                            {{ $formatterMontant($bulletin->salaire_base) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Total retenues
                        </p>

                        <p class="text-sm font-semibold text-red-600">
                            {{ $formatterMontant($bulletin->total_retenues) }}
                        </p>
                    </div>

                </div>

                {{-- Téléchargement PDF : disponible pour tous les bulletins --}}
                <div class="flex-shrink-0">

                    <a href="{{ route('employe.paie.telecharger', ['bulletin' => $bulletin->id]) }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r {{ $couleurs['gradient'] }} hover:opacity-90 transition shadow-sm w-full md:w-auto"
                       title="Télécharger le bulletin de paie">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 3v12m0 0l-4-4m4 4l4-4M5 17v3h14v-3"/>
                        </svg>

                        Télécharger PDF
                    </a>

                </div>

            </div>

        </div>

    @empty

        {{-- Aucun bulletin --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">

            <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-8 h-8 text-gray-400"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                </svg>
            </div>

            <h3 class="text-lg font-bold text-gray-700">
                Aucun bulletin disponible
            </h3>

            <p class="text-sm text-gray-500 mt-2">
                Vos bulletins de paie apparaîtront ici dès leur génération.
            </p>

        </div>

    @endforelse

    {{-- Pagination --}}
    @if($bulletins->hasPages())
        <div class="mt-6">
            {{ $bulletins->links() }}
        </div>
    @endif

</div>

@endsection

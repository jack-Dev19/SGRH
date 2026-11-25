@extends('layouts.dashboard')

@section('content')

<style>
    [x-cloak] { display: none !important; }

    .employee-page {
        background:
            radial-gradient(ellipse at 5% 5%, rgba(168,85,247,.09), transparent 35%),
            radial-gradient(ellipse at 95% 85%, rgba(91,33,182,.07), transparent 35%),
            #f8f7fc;
    }

    .purple-gradient {
        background: linear-gradient(120deg, #7e22ce 0%, #4c1d95 48%, #111827 100%);
    }

    .gradient-text {
        background: linear-gradient(110deg, #7e22ce, #312e81, #111827);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .premium-card {
        background: rgba(255,255,255,.94);
        border: 1px solid #e9e2f3;
        box-shadow: 0 8px 30px rgba(48,25,75,.045);
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        animation: cardEnter .65s ease both;
    }

    .premium-card:hover {
        border-color: #d8b4fe;
        box-shadow: 0 14px 35px rgba(91,33,182,.09);
    }

    .premium-input {
        width: 100%;
        border: 1px solid #e2e0e8;
        border-radius: 11px;
        background: #fff;
        padding: 12px 14px;
        color: #252039;
        font-size: 14px;
        outline: none;
        transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
    }

    .premium-input::placeholder { color: #aaa5b5; }

    .premium-input:focus {
        border-color: #9333ea;
        box-shadow: 0 0 0 4px rgba(147,51,234,.11);
        transform: translateY(-1px);
    }

    .premium-input:disabled {
        background: #f5f3f8;
        cursor: not-allowed;
        color: #a8a2b2;
    }

    .field-label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 650;
        color: #383047;
    }

    .section-icon {
        background: linear-gradient(135deg,#f3e8ff,#ede9fe);
        color: #6b21a8;
        transition: transform .3s ease;
    }

    .premium-card:hover .section-icon {
        transform: rotate(-5deg) scale(1.06);
    }

    .primary-button {
        background: linear-gradient(115deg,#7e22ce,#4c1d95 55%,#111827);
        background-size: 180% 180%;
        transition: all .3s ease;
        box-shadow: 0 6px 18px rgba(91,33,182,.2);
    }

    .primary-button:hover {
        background-position: 100% 50%;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(91,33,182,.3);
    }

    .primary-button:active { transform: scale(.98); }

    .progress-fill {
        background: linear-gradient(90deg,#a855f7,#6d28d9,#171124);
        transition: width .4s ease;
    }

    .photo-frame {
        background: linear-gradient(135deg,#a855f7,#312e81,#111827);
        padding: 3px;
    }

    .animate-float { animation: floatSoft 4s ease-in-out infinite; }

    .section-enter { animation: cardEnter .65s ease both; }
    .section-delay-1 { animation-delay: .08s; }
    .section-delay-2 { animation-delay: .16s; }
    .section-delay-3 { animation-delay: .24s; }

    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes floatSoft {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div
    class="employee-page min-h-screen px-4 py-7 sm:px-6 lg:px-8"
    x-data="{
        selectedDept: @js(old('departement_id', '')),
        selectedPoste: @js(old('poste_id', '')),
        selectedStatut: @js(old('statut', 'actif')),
        photoPreview: null,
        progress: 0,

        postesData: @js(
            $postes->map(fn ($poste) => [
                'id' => (string) $poste->id,
                'nom' => $poste->nom,
                'departement_id' => (string) $poste->departement_id,
            ])->values()
        ),

        get filteredPostes() {
            return this.postesData.filter(
                poste => poste.departement_id === String(this.selectedDept)
            );
        },

        updateProgress() {
            const form = this.$refs.employeeForm;
            if (!form) return;

            const required = [...form.querySelectorAll('[required]')];
            const completed = required.filter(field => {
                return String(field.value ?? '').trim() !== '';
            }).length;

            this.progress = required.length
                ? Math.round((completed / required.length) * 100)
                : 0;
        },

        previewPhoto(event) {
            const file = event.target.files[0];

            if (this.photoPreview) {
                URL.revokeObjectURL(this.photoPreview);
            }

            this.photoPreview = file ? URL.createObjectURL(file) : null;
        }
    }"
    x-init="$nextTick(() => updateProgress())"
>
    <div class="mx-auto max-w-5xl">

        {{-- EN-TÊTE --}}
        <div class="mb-7 section-enter">

            <div class="mb-4 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('gestion.employes.index') }}"
                   class="transition hover:text-purple-700">
                    Employés
                </a>
                <span>/</span>
                <span class="font-semibold text-purple-800">Nouvel employé</span>
            </div>

            <div class="relative overflow-hidden rounded-2xl purple-gradient p-6 text-white shadow-xl sm:p-8">
                <div class="pointer-events-none absolute -right-12 -top-20 h-64 w-64 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -right-5 -top-12 h-48 w-48 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute bottom-[-75px] right-36 h-40 w-40 rounded-full bg-purple-400/20 blur-3xl"></div>

                <div class="relative z-10 flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                    <div>
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold tracking-wide text-purple-100">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-purple-300"></span>
                            ESPACE RESSOURCES HUMAINES
                        </div>

                        <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                            Nouvel employé
                        </h1>

                        <p class="mt-2 max-w-lg text-sm leading-6 text-purple-100">
                            Créez un nouveau profil et renseignez les informations nécessaires à son intégration dans l'entreprise.
                        </p>
                    </div>

                    <div class="animate-float hidden h-20 w-20 items-center justify-center rounded-2xl border border-white/20 bg-white/10 backdrop-blur-md sm:flex">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- PROGRESSION --}}
        <div class="premium-card mb-6 rounded-2xl p-5 section-enter section-delay-1">
            <div class="mb-3 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-slate-800">Progression du formulaire</p>
                    <p class="mt-1 text-xs text-slate-500">Complétez les champs obligatoires.</p>
                </div>

                <span class="gradient-text text-xl font-extrabold tabular-nums"
                      x-text="progress + '%'"></span>
            </div>

            <div class="h-2.5 overflow-hidden rounded-full bg-purple-100">
                <div class="progress-fill h-full rounded-full"
                     :style="`width: ${progress}%`"></div>
            </div>

            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-slate-500">
                <span><span class="font-bold text-red-500">*</span> Obligatoire</span>
                <span class="rounded-md bg-purple-50 px-2 py-1 text-purple-700">Facultatif</span>
                <span class="ml-auto" x-text="progress === 100 ? 'Tous les champs obligatoires sont renseignés' : 'Informations à compléter'"></span>
            </div>
        </div>

        {{-- ERREURS --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 section-enter">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v3m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                    </svg>

                    <div>
                        <h3 class="text-sm font-bold text-red-800">Vérifiez les informations saisies</h3>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form
            x-ref="employeeForm"
            action="{{ route('gestion.employes.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            {{-- SECTION 01 --}}
            <section class="premium-card rounded-2xl p-5 sm:p-7 section-enter section-delay-1">

                <div class="mb-7 flex items-center gap-4 border-b border-purple-100 pb-5">
                    <div class="section-icon flex h-12 w-12 items-center justify-center rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-purple-600">Étape 01</p>
                        <h2 class="text-lg font-extrabold text-slate-900">Informations personnelles</h2>
                        <p class="mt-1 text-xs text-slate-500">Identité et profil de l'employé</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label for="prenom" class="field-label">Prénom <span class="text-red-500">*</span></label>
                        <input id="prenom" name="prenom" type="text" required maxlength="100"
                               value="{{ old('prenom') }}" autocomplete="given-name"
                               placeholder="Ex. : Jean"
                               @input="updateProgress()"
                               class="premium-input @error('prenom') !border-red-400 @enderror">
                        @error('prenom') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nom" class="field-label">Nom <span class="text-red-500">*</span></label>
                        <input id="nom" name="nom" type="text" required maxlength="100"
                               value="{{ old('nom') }}" autocomplete="family-name"
                               placeholder="Ex. : Rakoto"
                               @input="updateProgress()"
                               class="premium-input @error('nom') !border-red-400 @enderror">
                        @error('nom') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sexe" class="field-label">
                            Sexe <span class="ml-1 font-normal text-slate-400">Facultatif</span>
                        </label>
                        <select id="sexe" name="sexe" class="premium-input">
                            <option value="">Sélectionner le sexe</option>
                            <option value="Homme" @selected(old('sexe') === 'Homme')>Homme</option>
                            <option value="Femme" @selected(old('sexe') === 'Femme')>Femme</option>
                        </select>
                        @error('sexe') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_naissance" class="field-label">
                            Date de naissance <span class="ml-1 font-normal text-slate-400">Facultatif</span>
                        </label>
                        <input id="date_naissance" name="date_naissance" type="date"
                               value="{{ old('date_naissance') }}"
                               max="{{ now()->format('Y-m-d') }}"
                               class="premium-input">
                        @error('date_naissance') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- PHOTO --}}
                    <div class="sm:col-span-2">
                        <label for="photo" class="field-label">
                            Photo de profil <span class="ml-1 font-normal text-slate-400">Facultatif</span>
                        </label>

                        <div class="flex flex-col gap-5 rounded-xl border border-dashed border-purple-200 bg-purple-50/40 p-5 sm:flex-row sm:items-center">
                            <div class="photo-frame h-28 w-28 flex-shrink-0 rounded-2xl shadow-lg">
                                <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-[13px] bg-white">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" alt="Aperçu de la photo"
                                             class="h-full w-full object-cover">
                                    </template>

                                    <template x-if="!photoPreview">
                                        <svg class="h-12 w-12 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M15 7h.01M4 21h16a1 1 0 0 0 1-1v-4l-4-4-4 4-3-3-6 6v1a1 1 0 0 0 1 1ZM4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14"/>
                                        </svg>
                                    </template>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="mb-2 text-sm font-bold text-slate-800">Ajouter une photo</p>
                                <input id="photo" name="photo" type="file"
                                       accept="image/jpeg,image/png,image/webp"
                                       @change="previewPhoto($event)"
                                       class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-purple-100 file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-purple-800 file:transition hover:file:bg-purple-200">
                                <p class="mt-2 text-xs text-slate-500">JPG, JPEG, PNG ou WEBP · Maximum 2 Mo</p>
                                @error('photo') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- SECTION 02 --}}
            <section class="premium-card rounded-2xl p-5 sm:p-7 section-enter section-delay-2">

                <div class="mb-7 flex items-center gap-4 border-b border-purple-100 pb-5">
                    <div class="section-icon flex h-12 w-12 items-center justify-center rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-purple-600">Étape 02</p>
                        <h2 class="text-lg font-extrabold text-slate-900">Coordonnées</h2>
                        <p class="mt-1 text-xs text-slate-500">Informations de contact de l'employé</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label for="email" class="field-label">
                            Adresse e-mail <span class="ml-1 font-normal text-slate-400">Facultatif</span>
                        </label>
                        <input id="email" name="email" type="email" maxlength="255"
                               value="{{ old('email') }}" autocomplete="email"
                               placeholder="exemple@entreprise.com"
                               class="premium-input @error('email') !border-red-400 @enderror">
                        @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telephone" class="field-label">
                            Téléphone <span class="ml-1 font-normal text-slate-400">Facultatif</span>
                        </label>
                        <input id="telephone" name="telephone" type="tel" maxlength="20"
                               value="{{ old('telephone') }}" autocomplete="tel"
                               placeholder="Ex. : 034 00 000 00"
                               class="premium-input @error('telephone') !border-red-400 @enderror">
                        @error('telephone') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="adresse" class="field-label">
                            Adresse complète <span class="ml-1 font-normal text-slate-400">Facultatif</span>
                        </label>
                        <textarea id="adresse" name="adresse" rows="3"
                                  placeholder="Lot, rue, quartier, ville..."
                                  class="premium-input resize-y">{{ old('adresse') }}</textarea>
                        @error('adresse') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- SECTION 03 --}}
            <section class="premium-card rounded-2xl p-5 sm:p-7 section-enter section-delay-3">

                <div class="mb-7 flex items-center gap-4 border-b border-purple-100 pb-5">
                    <div class="section-icon flex h-12 w-12 items-center justify-center rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 7h18M5 7V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v2M5 7v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7M9 12h6m-6 4h6"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-purple-600">Étape 03</p>
                        <h2 class="text-lg font-extrabold text-slate-900">Informations professionnelles</h2>
                        <p class="mt-1 text-xs text-slate-500">Affectation et situation professionnelle</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <label for="date_embauche" class="field-label">
                            Date d'embauche <span class="text-red-500">*</span>
                        </label>
                        <input id="date_embauche" name="date_embauche" type="date" required
                               value="{{ old('date_embauche', now()->format('Y-m-d')) }}"
                               @change="updateProgress()"
                               class="premium-input @error('date_embauche') !border-red-400 @enderror">
                        @error('date_embauche') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="statut" class="field-label">
                            Statut de l'employé <span class="text-red-500">*</span>
                        </label>
                        <select id="statut" name="statut" x-model="selectedStatut"
                                required @change="updateProgress()"
                                class="premium-input @error('statut') !border-red-400 @enderror">
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                            <option value="sorti">Sorti</option>
                        </select>
                        @error('statut') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="departement_id" class="field-label">
                            Département <span class="text-red-500">*</span>
                        </label>
                        <select id="departement_id" name="departement_id"
                                x-model="selectedDept"
                                @change="selectedPoste = ''; updateProgress()"
                                required
                                class="premium-input @error('departement_id') !border-red-400 @enderror">
                            <option value="">Sélectionner un département</option>
                            @foreach ($departements as $departement)
                                <option value="{{ $departement->id }}">{{ $departement->nom }}</option>
                            @endforeach
                        </select>
                        @error('departement_id') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="poste_id" class="field-label">
                            Poste occupé <span class="text-red-500">*</span>
                        </label>
                        <select id="poste_id" name="poste_id"
                                x-model="selectedPoste"
                                :disabled="!selectedDept || filteredPostes.length === 0"
                                @change="updateProgress()"
                                required
                                class="premium-input @error('poste_id') !border-red-400 @enderror">
                            <option value="">Sélectionner un poste</option>
                            <template x-for="poste in filteredPostes" :key="poste.id">
                                <option :value="poste.id" x-text="poste.nom"></option>
                            </template>
                        </select>

                        <p class="mt-2 text-xs text-slate-500"
                           x-show="!selectedDept">
                            Sélectionnez d'abord un département.
                        </p>

                        <p class="mt-2 text-xs font-medium text-amber-600"
                           x-cloak
                           x-show="selectedDept && filteredPostes.length === 0">
                            Aucun poste disponible dans ce département.
                        </p>

                        @error('poste_id') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- MATRICULE --}}
                    <div class="sm:col-span-2">
                        <div class="relative overflow-hidden rounded-xl border border-purple-200 bg-gradient-to-r from-purple-50 via-white to-slate-50 p-5">
                            <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-purple-200/30 blur-2xl"></div>

                            <div class="relative flex items-center gap-4">
                                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl purple-gradient text-white shadow-md">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M8 5h8M8 9h8M8 13h5m-8 8h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-extrabold text-slate-800">Matricule automatique</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Le système générera automatiquement le matricule après l'enregistrement.
                                    </p>
                                </div>

                                <span class="ml-auto hidden rounded-lg bg-purple-100 px-3 py-1.5 text-xs font-bold text-purple-800 sm:inline-flex">
                                    AUTO
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ACTIONS --}}
            <div class="premium-card flex flex-col-reverse gap-3 rounded-2xl p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <a href="{{ route('gestion.employes.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-600 transition hover:border-purple-200 hover:bg-purple-50 hover:text-purple-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>
                    </svg>
                    Annuler
                </a>

                <button type="submit"
                        class="primary-button inline-flex items-center justify-center gap-2 rounded-xl px-7 py-3.5 text-sm font-bold text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer l'employé
                </button>
            </div>

            <p class="pb-3 text-center text-xs text-slate-400">
                SGRH · Gestion des ressources humaines
            </p>
        </form>
    </div>
</div>
@endsection
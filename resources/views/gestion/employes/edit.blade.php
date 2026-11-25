
@extends('layouts.dashboard')

@section('title', 'Modifier ' . $employe->nom_complet)

@section('content')

@php
    $photoActuelle = $employe->photo
        ? \Illuminate\Support\Facades\Storage::url($employe->photo)
        : null;
@endphp

<div
    class="employee-page min-h-screen px-4 py-8 sm:px-6 lg:px-8"
    x-data="{
        selectedDept: @js(old('departement_id', $employe->departement_id)),
        selectedPoste: @js(old('poste_id', $employe->poste_id)),
        existingPhoto: @js($photoActuelle),
        photoPreview: null,
        progress: 0,

        postesData: @js($postes->map(fn($p) => [
            'id' => (string) $p->id,
            'nom' => $p->nom,
            'departement_id' => (string) $p->departement_id,
        ])->values()),

        get filteredPostes() {
            return this.postesData.filter(
                poste => poste.departement_id == this.selectedDept
            );
        },

        updateProgress() {
            const form = this.$refs.employeeForm;

            if (!form) return;

            const requiredFields = [...form.querySelectorAll('[required]')];

            const completed = requiredFields.filter(field => {
                return field.value && field.value.trim() !== '';
            }).length;

            this.progress = requiredFields.length
                ? Math.round((completed / requiredFields.length) * 100)
                : 0;
        },

        changeDepartment() {
            const currentPoste = this.postesData.find(
                poste => poste.id == this.selectedPoste
            );

            if (!currentPoste || currentPoste.departement_id != this.selectedDept) {
                this.selectedPoste = '';
            }

            this.updateProgress();
        },

        previewPhoto(event) {
            const file = event.target.files[0];

            if (!file) return;

            if (this.photoPreview) {
                URL.revokeObjectURL(this.photoPreview);
            }

            this.photoPreview = URL.createObjectURL(file);
        }
    }"
    x-init="$nextTick(() => updateProgress())"
>

    {{-- Fil d'Ariane --}}
    <div class="max-w-5xl mx-auto mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('gestion.employes.index') }}"
           class="font-semibold text-purple-600 hover:text-purple-900 transition">
            Employés
        </a>

        <span class="text-slate-300">/</span>

        <span class="font-semibold text-slate-600">
            Modifier un employé
        </span>
    </div>

    <div class="max-w-5xl mx-auto">

        {{-- En-tête --}}
        <div class="purple-gradient rounded-3xl p-6 sm:p-8 mb-7 shadow-xl relative overflow-hidden">
            <div class="absolute -right-12 -top-16 w-64 h-64 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -left-10 -bottom-20 w-56 h-56 rounded-full bg-purple-300/20 blur-2xl"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">

                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-purple-100 text-xs font-semibold mb-4">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        Gestion des employés
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white">
                        Modifier un employé
                    </h1>

                    <p class="text-purple-100/80 text-sm mt-2">
                        Actualisez les informations personnelles et professionnelles.
                    </p>
                </div>

                <div class="bg-white/10 border border-white/20 rounded-2xl p-4 min-w-40">
                    <p class="text-purple-100 text-xs mb-1">Matricule</p>
                    <p class="text-white text-xl font-bold font-mono">
                        {{ $employe->matricule }}
                    </p>
                    <p class="text-purple-100/70 text-xs mt-1">
                        Identifiant non modifiable
                    </p>
                </div>

            </div>
        </div>

        {{-- Progression --}}
        <div class="premium-card p-5 mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-bold text-slate-800">
                        Progression du formulaire
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        Complétion des champs obligatoires
                    </p>
                </div>

                <span class="text-sm font-extrabold text-purple-700"
                      x-text="progress + '%'"></span>
            </div>

            <div class="h-2.5 bg-purple-50 rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full"
                     :style="`width: ${progress}%`"></div>
            </div>
        </div>

        {{-- Formulaire --}}
        <form
            x-ref="employeeForm"
            action="{{ route('gestion.employes.update', $employe) }}"
            method="POST"
            enctype="multipart/form-data"
            @input="updateProgress()"
            @change="updateProgress()"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- Erreurs --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
                    <div class="flex items-center gap-2 text-red-700 font-bold text-sm mb-2">
                        <span>⚠</span>
                        Vérifiez les informations saisies
                    </div>

                    <ul class="list-disc list-inside space-y-1 text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SECTION 1 : IDENTITÉ --}}
            <section class="premium-card">

                <div class="section-heading">
                    <div class="section-icon">01</div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-800">
                            Informations personnelles
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Identité et informations individuelles de l'employé
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Matricule --}}
                    <div class="md:col-span-2">
                        <label class="field-label">
                            Matricule
                            <span class="field-optional">Non modifiable</span>
                        </label>

                        <input
                            type="text"
                            value="{{ $employe->matricule }}"
                            disabled
                            class="premium-input bg-slate-100 text-slate-500 cursor-not-allowed font-mono font-bold"
                        >
                    </div>

                    {{-- Prénom --}}
                    <div>
                        <label for="prenom" class="field-label">
                            Prénom <span class="required-mark">*</span>
                        </label>

                        <input
                            id="prenom"
                            type="text"
                            name="prenom"
                            value="{{ old('prenom', $employe->prenom) }}"
                            required
                            maxlength="100"
                            placeholder="Ex. : Jean"
                            class="premium-input"
                        >

                        @error('prenom')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nom --}}
                    <div>
                        <label for="nom" class="field-label">
                            Nom <span class="required-mark">*</span>
                        </label>

                        <input
                            id="nom"
                            type="text"
                            name="nom"
                            value="{{ old('nom', $employe->nom) }}"
                            required
                            maxlength="100"
                            placeholder="Ex. : Rakoto"
                            class="premium-input"
                        >

                        @error('nom')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sexe --}}
                    <div>
                        <label for="sexe" class="field-label">
                            Sexe <span class="field-optional">Facultatif</span>
                        </label>

                        <select id="sexe" name="sexe" class="premium-input">
                            <option value="">Sélectionner le sexe</option>
                            <option value="Homme" @selected(old('sexe', $employe->sexe) === 'Homme')>
                                Homme
                            </option>
                            <option value="Femme" @selected(old('sexe', $employe->sexe) === 'Femme')>
                                Femme
                            </option>
                        </select>

                        @error('sexe')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date naissance --}}
                    <div>
                        <label for="date_naissance" class="field-label">
                            Date de naissance
                            <span class="field-optional">Facultatif</span>
                        </label>

                        <input
                            id="date_naissance"
                            type="date"
                            name="date_naissance"
                            value="{{ old('date_naissance', $employe->date_naissance?->format('Y-m-d')) }}"
                            class="premium-input"
                        >

                        @error('date_naissance')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Photo --}}
                    <div class="md:col-span-2">
                        <label class="field-label">
                            Photo de profil
                            <span class="field-optional">Facultatif</span>
                        </label>

                        <div class="flex flex-col sm:flex-row items-center gap-6 p-5 rounded-2xl border border-purple-100 bg-purple-50/40">

                            <div class="photo-frame w-32 h-32 flex-shrink-0">
                                <template x-if="photoPreview || existingPhoto">
                                    <img
                                        :src="photoPreview || existingPhoto"
                                        alt="Photo de l'employé"
                                        class="w-full h-full object-cover rounded-2xl"
                                    >
                                </template>

                                <template x-if="!photoPreview && !existingPhoto">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-purple-400">
                                        <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 0l-4.553 2.276A1 1 0 013 15.382V8.618a1 1 0 011.447-.894L9 10m6-3a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="text-xs font-semibold">Aucune photo</span>
                                    </div>
                                </template>
                            </div>

                            <div class="flex-1 text-center sm:text-left">
                                <p class="font-bold text-slate-800 text-sm">
                                    Modifier la photo de l'employé
                                </p>

                                <p class="text-xs text-slate-500 mt-2 mb-4">
                                    Choisissez une nouvelle photo pour remplacer l'actuelle.
                                    Formats JPG, JPEG, PNG ou WEBP. Maximum 2 Mo.
                                </p>

                                <label for="photo"
                                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl purple-gradient text-white text-xs font-bold cursor-pointer hover:opacity-90 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0L8 8m4-4l4 4"/>
                                    </svg>
                                    Choisir une photo
                                </label>

                                <input
                                    id="photo"
                                    type="file"
                                    name="photo"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    class="hidden"
                                    @change="previewPhoto($event)"
                                >
                            </div>
                        </div>

                        @error('photo')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- SECTION 2 : COORDONNÉES --}}
            <section class="premium-card">

                <div class="section-heading">
                    <div class="section-icon">02</div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-800">
                            Coordonnées
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Moyens de contact et adresse de l'employé
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label for="email" class="field-label">
                            Adresse e-mail
                            <span class="field-optional">Facultatif</span>
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $employe->email) }}"
                            placeholder="exemple@email.com"
                            class="premium-input"
                        >

                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone" class="field-label">
                            Téléphone
                            <span class="field-optional">Facultatif</span>
                        </label>

                        <input
                            id="telephone"
                            type="text"
                            name="telephone"
                            value="{{ old('telephone', $employe->telephone) }}"
                            maxlength="20"
                            placeholder="Ex. : 034 00 000 00"
                            class="premium-input"
                        >

                        @error('telephone')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="adresse" class="field-label">
                            Adresse complète
                            <span class="field-optional">Facultatif</span>
                        </label>

                        <textarea
                            id="adresse"
                            name="adresse"
                            rows="3"
                            placeholder="Saisir l'adresse complète..."
                            class="premium-input resize-y"
                        >{{ old('adresse', $employe->adresse) }}</textarea>

                        @error('adresse')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- SECTION 3 : AFFECTATION --}}
            <section class="premium-card">

                <div class="section-heading">
                    <div class="section-icon">03</div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-800">
                            Informations professionnelles
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Affectation, date d'embauche et situation professionnelle
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Date embauche --}}
                    <div>
                        <label for="date_embauche" class="field-label">
                            Date d'embauche <span class="required-mark">*</span>
                        </label>

                        <input
                            id="date_embauche"
                            type="date"
                            name="date_embauche"
                            value="{{ old('date_embauche', $employe->date_embauche?->format('Y-m-d')) }}"
                            required
                            class="premium-input"
                        >

                        @error('date_embauche')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div>
                        <label for="statut" class="field-label">
                            Statut <span class="required-mark">*</span>
                        </label>

                        <select
                            id="statut"
                            name="statut"
                            required
                            class="premium-input"
                        >
                            <option value="actif" @selected(old('statut', $employe->statut) === 'actif')>
                                Actif
                            </option>

                            <option value="inactif" @selected(old('statut', $employe->statut) === 'inactif')>
                                Inactif
                            </option>

                            <option value="sorti" @selected(old('statut', $employe->statut) === 'sorti')>
                                Sorti
                            </option>
                        </select>

                        @error('statut')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Département --}}
                    <div>
                        <label for="departement_id" class="field-label">
                            Département <span class="required-mark">*</span>
                        </label>

                        <select
                            id="departement_id"
                            name="departement_id"
                            x-model="selectedDept"
                            @change="changeDepartment()"
                            required
                            class="premium-input"
                        >
                            <option value="">Sélectionner un département</option>

                            @foreach($departements as $departement)
                                <option value="{{ $departement->id }}">
                                    {{ $departement->nom }}
                                </option>
                            @endforeach
                        </select>

                        @error('departement_id')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Poste --}}
                    <div>
                        <label for="poste_id" class="field-label">
                            Poste <span class="required-mark">*</span>
                        </label>

                        <select
                            id="poste_id"
                            name="poste_id"
                            x-model="selectedPoste"
                            required
                            :disabled="!selectedDept"
                            class="premium-input"
                        >
                            <option value="">Sélectionner un poste</option>

                            <template x-for="poste in filteredPostes" :key="poste.id">
                                <option :value="poste.id" x-text="poste.nom"></option>
                            </template>
                        </select>

                        @error('poste_id')
                            <p class="field-error">{{ $message }}</p>
                        @enderror

                        <p class="text-xs text-slate-400 mt-2">
                            Les postes proposés dépendent du département sélectionné.
                        </p>
                    </div>

                </div>
            </section>

            {{-- ACTIONS --}}
            <div class="premium-card p-5 sm:p-6">

                <div class="flex flex-col sm:flex-row gap-3">

                    <button
                        type="submit"
                        class="primary-button flex-1"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>

                        Enregistrer les modifications
                    </button>

                    <a
                        href="{{ route('gestion.employes.index') }}"
                        class="secondary-button"
                    >
                        Annuler
                    </a>

                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex justify-between items-center gap-3">
                    <p class="text-xs text-slate-400">
                        Les modifications seront enregistrées après confirmation.
                    </p>

                    <button
                        type="button"
                        onclick="if(confirm('Voulez-vous vraiment supprimer définitivement cet employé ?')) { document.getElementById('delete-form').submit(); }"
                        class="text-xs font-bold text-red-500 hover:text-red-700 transition"
                    >
                        Supprimer l'employé
                    </button>
                </div>

            </div>

        </form>

        {{-- Formulaire de suppression séparé --}}
        <form
            id="delete-form"
            action="{{ route('gestion.employes.destroy', $employe) }}"
            method="POST"
            class="hidden"
        >
            @csrf
            @method('DELETE')
        </form>

    </div>
</div>

<style>
    .employee-page {
        background:
            radial-gradient(circle at 5% 5%, rgba(139, 92, 246, .08), transparent 28%),
            radial-gradient(circle at 95% 30%, rgba(124, 58, 237, .06), transparent 30%),
            #f8fafc;
    }

    .purple-gradient {
        background: linear-gradient(115deg, #7c3aed 0%, #4c1d95 55%, #111827 100%);
    }

    .premium-card {
        background: #ffffff;
        border: 1px solid #eee8fa;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 35px rgba(46, 16, 101, .045);
        animation: cardEnter .55s ease both;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 20px;
        margin-bottom: 22px;
        border-bottom: 1px solid #f1ebfa;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(135deg, #7c3aed, #171024);
        color: white;
        font-size: 13px;
        font-weight: 900;
        box-shadow: 0 5px 15px rgba(124, 58, 237, .2);
    }

    .field-label {
        display: block;
        font-size: 13px;
        font-weight: 750;
        color: #334155;
        margin-bottom: 9px;
    }

    .required-mark {
        color: #dc2626;
        font-weight: 900;
        margin-left: 3px;
    }

    .field-optional {
        display: inline-block;
        margin-left: 6px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
    }

    .premium-input {
        width: 100%;
        min-height: 48px;
        padding: 12px 15px;
        border: 1.5px solid #e8def8;
        border-radius: 13px;
        background: #fff;
        color: #312044;
        font-size: 13px;
        outline: none;
        transition: all .2s ease;
    }

    .premium-input:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, .12);
    }

    .premium-input:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .field-error {
        color: #dc2626;
        font-size: 11px;
        margin-top: 7px;
    }

    .progress-fill {
        background: linear-gradient(90deg, #8b5cf6, #171024);
        transition: width .35s ease;
    }

    .photo-frame {
        padding: 4px;
        border-radius: 19px;
        background: linear-gradient(135deg, #8b5cf6, #171024);
        box-shadow: 0 8px 20px rgba(124, 58, 237, .16);
        overflow: hidden;
    }

    .primary-button {
        min-height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 13px 22px;
        border-radius: 14px;
        background: linear-gradient(110deg, #7c3aed, #171024);
        color: white;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(91, 33, 182, .2);
        transition: all .2s ease;
    }

    .primary-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(91, 33, 182, .28);
    }

    .secondary-button {
        min-height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 13px 24px;
        border: 1px solid #e9d5ff;
        border-radius: 14px;
        color: #6d28d9;
        background: white;
        font-size: 13px;
        font-weight: 750;
        transition: all .2s ease;
    }

    .secondary-button:hover {
        background: #faf5ff;
        border-color: #c4b5fd;
    }

    @keyframes cardEnter {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 640px) {
        .premium-card {
            padding: 18px;
            border-radius: 19px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .premium-card {
            animation: none;
        }

        .premium-input,
        .primary-button,
        .secondary-button,
        .progress-fill {
            transition: none;
        }
    }
</style>

@endsection
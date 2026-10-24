@extends('layouts.dashboard')

@section('title', 'Employés')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- ============================================= --}}
    {{-- MESSAGES FLASH --}}
    {{-- ============================================= --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- EN-TÊTE --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Gestion des employés</h1>
            <p class="text-sm text-slate-500">{{ $employes->total() }} employé(s) enregistré(s)</p>
        </div>

        <a href="{{ route('gestion.employes.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel employé
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- BARRE DE RECHERCHE + FILTRES --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.employes.index') }}" class="flex flex-wrap items-center gap-3">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[280px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher par nom, matricule, email..."
                        class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition">
                </div>
            </div>

            {{-- Filtre Département --}}
            <div class="min-w-[220px]">
                <select name="departement"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition">
                    <option value="">Tous les départements</option>
                    @foreach($departements as $dept)
                        <option value="{{ $dept->id }}" {{ request('departement') === $dept->id ? 'selected' : '' }}>
                            {{ $dept->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre Statut --}}
            <div class="min-w-[190px]">
                <select name="statut"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition">
                    <option value="">Tous les statuts</option>
                    <option value="actif"   {{ request('statut') === 'actif'   ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="sorti"   {{ request('statut') === 'sorti'   ? 'selected' : '' }}>Sorti</option>
                </select>
            </div>

            {{-- Bouton Filtrer --}}
            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-full transition shadow-lg shadow-purple-600/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'departement', 'statut']))
                <a href="{{ route('gestion.employes.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- ============================================= --}}
    {{-- GRILLE DE CARDS --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

        @forelse($employes as $emp)
            @php
                // Palette de couleurs variées
                $palette = [
                    ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'gradient' => 'from-blue-500 to-blue-600',       'dot' => 'bg-blue-500'],
                    ['bg' => 'bg-violet-100',  'text' => 'text-violet-700',  'gradient' => 'from-violet-500 to-violet-600',   'dot' => 'bg-violet-500'],
                    ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'gradient' => 'from-red-500 to-red-600',         'dot' => 'bg-red-500'],
                    ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'gradient' => 'from-emerald-500 to-emerald-600', 'dot' => 'bg-emerald-500'],
                    ['bg' => 'bg-orange-100',  'text' => 'text-orange-700',  'gradient' => 'from-orange-500 to-orange-600',   'dot' => 'bg-orange-500'],
                    ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'gradient' => 'from-slate-600 to-slate-700',     'dot' => 'bg-slate-500'],
                ];

                $idx = crc32($emp->id) % count($palette);
                $c = $palette[$idx];

                // ⭐ Compter les dépendances pour le JS
                $pointagesCount = \App\Models\Pointage::where('employe_id', $emp->id)->count();
                $documentsCount = \Schema::hasTable('documents')
                    ? \DB::table('documents')->where('employe_id', $emp->id)->count()
                    : 0;
                $contratsCount = \Schema::hasTable('contrats')
                    ? \DB::table('contrats')->where('employe_id', $emp->id)->count()
                    : 0;
                $absencesCount = \Schema::hasTable('absences')
                    ? \DB::table('absences')->where('employe_id', $emp->id)->count()
                    : 0;
                $presencesCount = \Schema::hasTable('presences')
                    ? \DB::table('presences')->where('employe_id', $emp->id)->count()
                    : 0;
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-purple-200 hover:-translate-y-1 transition-all duration-300">
                <div class="relative h-24 bg-gradient-to-br {{ $c['gradient'] }} overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute top-3 right-3">
                        @if($emp->statut === 'actif')
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                <span class="w-1 h-1 rounded-full bg-emerald-300 animate-pulse"></span>
                                Actif
                            </span>
                        @elseif($emp->statut === 'inactif')
                            <span class="inline-flex items-center bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                Inactif
                            </span>
                        @else
                            <span class="inline-flex items-center bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                Sorti
                            </span>
                        @endif
                    </div>
                </div>

                <div class="relative px-4 pb-4">
                    <div class="flex justify-center -mt-10 mb-3">
                        <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg ring-1 ring-slate-100">
                            <div class="w-full h-full rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-xl">
                                {{ $emp->initiales }}
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <h3 class="font-bold text-slate-900 text-sm leading-tight mb-1">{{ $emp->nom_complet }}</h3>
                        <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">{{ $emp->matricule }}</p>
                    </div>

                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold {{ $c['bg'] }} {{ $c['text'] }} px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                            {{ $emp->departement?->nom ?? 'Sans département' }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[11px] text-slate-600 truncate">{{ $emp->email ?? '—' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-700 truncate">
                                {{ $emp->poste?->nom ?? 'Poste non défini' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[11px] text-slate-600">
                                Embauché le {{ $emp->date_embauche?->format('d/m/Y') ?? '—' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('gestion.employes.show', $emp) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Détails
                        </a>
                        <a href="{{ route('gestion.employes.edit', $emp) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>

                        {{-- ⭐ Formulaire avec data-* pour SweetAlert2 --}}
                        <form action="{{ route('gestion.employes.destroy', $emp) }}" method="POST"
                            data-delete-employe
                            data-employe-nom="{{ $emp->nom_complet }}"
                            data-pointages="{{ $pointagesCount }}"
                            data-documents="{{ $documentsCount }}"
                            data-contrats="{{ $contratsCount }}"
                            data-absences="{{ $absencesCount }}"
                            data-presences="{{ $presencesCount }}"
                            class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-red-700 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Suppr.
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-slate-500 font-medium mb-1">Aucun employé trouvé</p>
                <p class="text-slate-400 text-sm mb-4">Commencez par ajouter un nouvel employé.</p>
                <a href="{{ route('gestion.employes.create') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvel employé
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($employes->hasPages())
        <div class="mt-6">{{ $employes->links() }}</div>
    @endif
</div>

@endsection

{{-- ============================================= --}}
{{-- SCRIPTS : Confirmation suppression SweetAlert2 --}}
{{-- ============================================= --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ═══════════════════════════════════════════════════════════════
    // ⭐ Confirmation suppression employé (avec SweetAlert2)
    // ═══════════════════════════════════════════════════════════════
    document.querySelectorAll('form[data-delete-employe]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const nomEmploye = form.dataset.employeNom || 'cet employé';
            const pointages  = parseInt(form.dataset.pointages || 0);
            const documents  = parseInt(form.dataset.documents || 0);
            const contrats   = parseInt(form.dataset.contrats || 0);
            const absences   = parseInt(form.dataset.absences || 0);
            const presences  = parseInt(form.dataset.presences || 0);

            const dependances = [];
            if (pointages > 0) dependances.push(`📌 ${pointages} pointage(s)`);
            if (documents > 0) dependances.push(`📄 ${documents} document(s)`);
            if (contrats > 0)  dependances.push(`📝 ${contrats} contrat(s)`);
            if (absences > 0)  dependances.push(`⚠️ ${absences} absence(s)`);
            if (presences > 0) dependances.push(`✅ ${presences} présence(s)`);

            // ⭐ CAS 1 : Dépendances présentes → avertissement fort
            if (dependances.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Suppression impossible',
                    html: `
                        <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                            <p style="margin-bottom: 10px;">
                                L'employé <strong>${nomEmploye}</strong> ne peut pas être supprimé car il possède des enregistrements associés :
                            </p>
                            <ul style="margin: 10px 0 10px 20px; color: #dc2626; font-weight: 600;">
                                ${dependances.map(d => `<li>${d}</li>`).join('')}
                            </ul>
                            <p style="margin-top: 10px; color: #475569;">
                                Pour préserver l'historique, veuillez <strong>archiver</strong> ou <strong>désactiver</strong> cet employé plutôt que de le supprimer définitivement.
                            </p>
                        </div>
                    `,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Compris',
                    showCloseButton: true,
                    allowOutsideClick: true,
                });
                return;
            }

            // ⭐ CAS 2 : Aucune dépendance → confirmation standard
            Swal.fire({
                icon: 'warning',
                title: 'Confirmer la suppression',
                html: `Voulez-vous vraiment supprimer <strong>${nomEmploye}</strong> ?<br><br>
                       <span style="color: #dc2626; font-size: 12px;">⚠️ Cette action est irréversible.</span>`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
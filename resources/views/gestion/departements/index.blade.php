@extends('layouts.dashboard')

@section('title', 'Départements')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    statutOpen: false,
    selectedStatut: '{{ request('statut') }}',
    statutsList: [
        { id: '', nom: 'Tous les statuts' },
        { id: '1', nom: 'Actif' },
        { id: '0', nom: 'Inactif' }
    ]
}">

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-50 border border-red-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    Structure
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Départements</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $departements->total() }} département(s) enregistré(s) dans votre organisation.</p>
        </div>

        <a href="{{ route('gestion.departements.create') }}"
            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Nouveau département
        </a>
    </div>

    {{-- Barre de recherche + filtre --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.departements.index') }}" class="flex flex-wrap items-center gap-2">
            
            {{-- Champ caché pour la soumission du statut --}}
            <input type="hidden" name="statut" x-model="selectedStatut">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[280px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un département..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- ⭐ FILTRE STATUT DROPDOWN PRO ⭐ --}}
            <div class="min-w-[200px] relative" @click.away="statutOpen = false">
                <button type="button" @click="statutOpen = !statutOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="statutsList.find(s => s.id === selectedStatut)?.nom || 'Tous les statuts'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="statutOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>

                {{-- Menu flottant Statut --}}
                <div x-show="statutOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" 
                     style="display: none;">
                    
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par statut</p>
                    </div>

                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <template x-for="opt in statutsList" :key="opt.id">
                            <li @click="selectedStatut = opt.id; statutOpen = false"
                                class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150"
                                :class="selectedStatut === opt.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="opt.nom"></span>
                                <div :class="selectedStatut === opt.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedStatut === opt.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- Bouton Rechercher --}}
            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'statut']))
                <a href="{{ route('gestion.departements.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Grille de cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($departements as $dept)
            @php
                $palette = [
                    ['gradient' => 'from-blue-500 to-blue-600',       'bg' => 'bg-blue-50',    'text' => 'text-blue-700'],
                    ['gradient' => 'from-violet-500 to-violet-600',   'bg' => 'bg-violet-50',  'text' => 'text-violet-700'],
                    ['gradient' => 'from-red-500 to-red-600',         'bg' => 'bg-red-50',     'text' => 'text-red-700'],
                    ['gradient' => 'from-emerald-500 to-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700'],
                    ['gradient' => 'from-orange-500 to-orange-600',   'bg' => 'bg-orange-50',  'text' => 'text-orange-700'],
                    ['gradient' => 'from-slate-600 to-slate-700',     'bg' => 'bg-slate-50',   'text' => 'text-slate-700'],
                    ['gradient' => 'from-pink-500 to-pink-600',       'bg' => 'bg-pink-50',    'text' => 'text-pink-700'],
                    ['gradient' => 'from-cyan-500 to-cyan-600',       'bg' => 'bg-cyan-50',    'text' => 'text-cyan-700'],
                ];
                $idx = crc32($dept->id) % count($palette);
                $c = $palette[$idx];
            @endphp

            <div class="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">

                {{-- Bandeau supérieur --}}
                <div class="relative h-24 bg-gradient-to-br {{ $c['gradient'] }} overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>

                    {{-- Badge Statut --}}
                    <div class="absolute top-3 right-3">
                        @if($dept->statut == 1)
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                Inactif
                            </span>
                        @endif
                    </div>

                    {{-- Icône --}}
                    <div class="absolute bottom-3 left-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-slate-900 text-base leading-tight mb-1 truncate">
                        {{ $dept->nom }}
                    </h3>

                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 h-8">
                        {{ $dept->description ?: 'Aucune description disponible pour ce département.' }}
                    </p>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="{{ $c['bg'] }} rounded-xl p-2.5 text-center ring-1 ring-inset ring-white/50">
                            <p class="text-lg font-extrabold {{ $c['text'] }}">{{ $dept->employes_count }}</p>
                            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Employés</p>
                        </div>
                        <div class="{{ $c['bg'] }} rounded-xl p-2.5 text-center ring-1 ring-inset ring-white/50">
                            <p class="text-lg font-extrabold {{ $c['text'] }}">{{ $dept->postes_count ?? 0 }}</p>
                            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Postes</p>
                        </div>
                    </div>

                    {{-- Responsable --}}
                    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center gap-2 mb-4">
                        @if($dept->responsable)
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-[10px] shrink-0 shadow-sm">
                                {{ strtoupper(substr($dept->responsable->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">Responsable</p>
                                <p class="text-xs font-semibold text-slate-700 truncate">{{ $dept->responsable->name }}</p>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">Responsable</p>
                                <p class="text-xs text-slate-400 italic">Non assigné</p>
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('gestion.departements.show', $dept) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 text-[10px] font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 py-2 rounded-lg transition-colors ring-1 ring-inset ring-slate-200">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Voir
                        </a>
                        <a href="{{ route('gestion.departements.edit', $dept) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 text-[10px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition-colors ring-1 ring-inset ring-blue-200">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>
                        <form action="{{ route('gestion.departements.destroy', $dept) }}" method="POST"
                            data-delete-departement data-departement-nom="{{ $dept->nom }}"
                            data-employes="{{ $dept->employes_count }}" data-postes="{{ $dept->postes_count }}"
                            class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 text-[10px] font-bold text-red-700 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition-colors ring-1 ring-inset ring-red-200">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Suppr.
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Aucun département trouvé</h3>
                <p class="mt-1 text-sm text-slate-500">
                    @if(request()->hasAny(['search', 'statut']))
                        Aucun résultat ne correspond à votre recherche. Essayez d'autres critères.
                    @else
                        Commencez par créer votre premier département.
                    @endif
                </p>
                <div class="mt-5">
                    @if(request()->hasAny(['search', 'statut']))
                        <a href="{{ route('gestion.departements.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                            Effacer les filtres
                        </a>
                    @else
                        <a href="{{ route('gestion.departements.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Nouveau département
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    @if($departements->hasPages())
        <div class="mt-6">{{ $departements->links() }}</div>
    @endif
</div>

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ═══════════════════════════════════════════════════════════════
        // ⭐ Confirmation suppression département (avec SweetAlert2)
        // ═══════════════════════════════════════════════════════════════
        document.querySelectorAll('form[data-delete-departement]').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const nomDept = form.dataset.departementNom || 'ce département';
                const employes = parseInt(form.dataset.employes || 0);
                const postes = parseInt(form.dataset.postes || 0);

                // ⭐ CAS 1 : Employés dans le département → bloquer
                if (employes > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Suppression impossible',
                        html: `
                            <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                                <p style="margin-bottom: 10px;">
                                    Le département <strong>${nomDept}</strong> contient
                                    <strong style="color: #dc2626;">${employes} employé(s)</strong>.
                                </p>
                                <p style="color: #475569;">
                                    Réaffectez-les d'abord à un autre département avant de supprimer celui-ci.
                                </p>
                            </div>
                        `,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Compris',
                        showCloseButton: true,
                    });
                    return;
                }

                // ⭐ CAS 2 : Postes associés → avertir
                if (postes > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention',
                        html: `
                            <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                                <p style="margin-bottom: 10px;">
                                    Le département <strong>${nomDept}</strong> possède
                                    <strong style="color: #f59e0b;">${postes} poste(s)</strong> associé(s).
                                </p>
                                <p style="color: #475569;">
                                    Ces postes seront également supprimés. Continuer ?
                                </p>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler',
                    }).then((result) => {
                        if (result.isConfirmed) { form.submit(); }
                    });
                    return;
                }

                // ⭐ CAS 3 : Département vide → confirmation simple
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmer la suppression',
                    html: `Voulez-vous vraiment supprimer le département <strong>${nomDept}</strong> ?<br><br>
                           <span style="color: #dc2626; font-size: 12px;">⚠️ Cette action est irréversible.</span>`,
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                }).then((result) => {
                    if (result.isConfirmed) { form.submit(); }
                });
            });
        });
    });
</script>
@endpush
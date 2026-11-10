@extends('layouts.dashboard')

@section('title', 'Utilisateurs')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Messages Flash --}}
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
                    Administration
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Comptes utilisateurs</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ auth()->user()->isAdmin() ? 'Gérez tous les accès à la plateforme.' : 'Gérez les comptes Chef et Employé.' }}
            </p>
        </div>

        <a href="{{ route('gestion.users.create') }}"
            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Nouveau compte
        </a>
    </div>

    {{-- Conteneur principal (Tableau) --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        {{-- Barre d'outils (Recherche visuelle) --}}
        <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
            <div class="relative flex-1 max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Rechercher un utilisateur..." 
                       class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-slate-500">{{ $users->total() }} compte(s)</span>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Matricule</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $u)
                        @php
                            $initiales = strtoupper(substr($u->name, 0, 2));
                            
                            // Palette pour l'avatar
                            $palettes = [
                                'from-purple-500 to-indigo-500',
                                'from-blue-500 to-cyan-500',
                                'from-emerald-500 to-teal-500',
                                'from-orange-500 to-amber-500',
                                'from-pink-500 to-rose-500',
                            ];
                            $avatarColor = $palettes[crc32($u->id) % count($palettes)];

                            // Badge de rôle
                            $roleNom = $u->role?->nom;
                            $badge = match ($roleNom) {
                                'Administrateur' => 'bg-red-50 text-red-700 ring-red-200',
                                'Responsable RH' => 'bg-purple-50 text-purple-700 ring-purple-200',
                                'Chef de département' => 'bg-blue-50 text-blue-700 ring-blue-200',
                                'Employé' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                default => 'bg-slate-100 text-slate-600 ring-slate-200',
                            };
                        @endphp

                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            
                            {{-- Utilisateur (Avatar + Nom + Email) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br {{ $avatarColor }} flex items-center justify-center text-white text-xs font-bold shadow-sm ring-2 ring-white">
                                        {{ $initiales }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ $u->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Rôle --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $badge }}">
                                    {{ $roleNom ?? 'Aucun rôle' }}
                                </span>
                            </td>

                            {{-- Matricule --}}
                            <td class="px-6 py-4 hidden md:table-cell">
                                @if($u->matricule)
                                    <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 px-2 py-1 text-xs font-mono font-medium text-slate-600 ring-1 ring-inset ring-slate-200">
                                        {{ $u->matricule }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-xs">—</span>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td class="px-6 py-4">
                                @if($u->statut == 1)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-inset ring-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-red-700 shadow-sm ring-1 ring-inset ring-red-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Inactif
                                    </span>
                                @endif
                            </td>

                            {{-- Actions (Icônes SVG) --}}
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                    
                                    {{-- Voir --}}
                                    <a href="{{ route('gestion.users.show', $u) }}" title="Voir les détails"
                                        class="p-2 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    {{-- Modifier --}}
                                    <a href="{{ route('gestion.users.edit', $u) }}" title="Modifier le compte"
                                        class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- Supprimer (sauf soi-même) --}}
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('gestion.users.destroy', $u) }}" method="POST" data-delete-user
                                            data-user-nom="{{ $u->name }}" data-user-email="{{ $u->email }}"
                                            data-user-role="{{ $u->role?->nom ?? '' }}"
                                            data-is-self="{{ $u->id === auth()->id() ? 'true' : 'false' }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Supprimer le compte"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50">
                                    <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-3 text-sm font-bold text-slate-900">Aucun utilisateur</h3>
                                <p class="mt-1 text-sm text-slate-500">Commencez par créer un nouveau compte.</p>
                                <a href="{{ route('gestion.users.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white transition hover:bg-slate-800">
                                    Créer un compte
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pied de tableau / Pagination --}}
        @if($users->hasPages())
            <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ═══════════════════════════════════════════════════════════════
        // ⭐ Confirmation suppression utilisateur (avec SweetAlert2)
        // ═══════════════════════════════════════════════════════════════
        document.querySelectorAll('form[data-delete-user]').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const nomUser = form.dataset.userNom || 'cet utilisateur';
                const email = form.dataset.userEmail || '';
                const roleNom = form.dataset.userRole || '';
                const isSelf = form.dataset.isSelf === 'true';

                // ⭐ CAS 1 : Suppression de son propre compte → bloquer
                if (isSelf) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Action impossible',
                        html: `
                            <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                                <p style="color: #dc2626; font-weight: 600;">
                                    Vous ne pouvez pas supprimer votre propre compte.
                                </p>
                                <p style="margin-top: 8px; color: #475569;">
                                    Contactez un autre administrateur si nécessaire.
                                </p>
                            </div>
                        `,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Compris',
                        showCloseButton: true,
                    });
                    return;
                }

                // ⭐ CAS 2 : Confirmation standard
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmer la suppression',
                    html: `
                        <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                            <p style="margin-bottom: 8px;">
                                Voulez-vous vraiment supprimer le compte de
                                <strong>${nomUser}</strong> ?
                            </p>
                            <p style="color: #64748b; font-size: 12px;">
                                ${email}<br>
                                ${roleNom ? `Rôle : <strong>${roleNom}</strong>` : ''}
                            </p>
                            <p style="margin-top: 10px; color: #dc2626; font-size: 12px;">
                                ⚠️ Cette action est irréversible.
                            </p>
                        </div>
                    `,
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

@endsection
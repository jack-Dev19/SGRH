@extends('layouts.dashboard')

@section('title', 'Utilisateurs')

@section('content')

    <div class="max-w-7xl mx-auto">

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6">
                <span class="text-sm font-medium">✅ {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6">
                <span class="text-sm font-medium">❌ {{ session('error') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Comptes utilisateurs</h1>
                <p class="text-sm text-slate-500">
                    {{ auth()->user()->isAdmin() ? 'Vous pouvez créer tous les comptes.' : 'Vous pouvez créer des comptes Chef et Employé.' }}
                </p>
            </div>

            <a href="{{ route('gestion.users.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau compte
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Utilisateur</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Email</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Rôle</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Matricule</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Statut</th>
                        <th class="text-right px-6 py-4 text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $u->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $roleNom = $u->role?->nom;
                                    $badge = match ($roleNom) {
                                        'Administrateur' => 'bg-red-100 text-red-700',
                                        'Responsable RH' => 'bg-purple-100 text-purple-700',
                                        'Chef de département' => 'bg-blue-100 text-blue-700',
                                        'Employé' => 'bg-emerald-100 text-emerald-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $badge }}">
                                    {{ $roleNom ?? 'Aucun' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono">{{ $u->matricule ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($u->statut == 1)
                                    <span
                                        class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Actif</span>
                                @else
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('gestion.users.show', $u) }}" title="Voir"
                                        class="p-2 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition">👁</a>
                                    <a href="{{ route('gestion.users.edit', $u) }}" title="Modifier"
                                        class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition">✏️</a>
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('gestion.users.destroy', $u) }}" method="POST" data-delete-user
                                            data-user-nom="{{ $u->name }}" data-user-email="{{ $u->email }}"
                                            data-user-role="{{ $u->role?->nom ?? '' }}"
                                            data-is-self="{{ $u->id === auth()->id() ? 'true' : 'false' }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Supprimer"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                                                🗑
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">Aucun utilisateur</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-6">{{ $users->links() }}</div>
        @endif

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
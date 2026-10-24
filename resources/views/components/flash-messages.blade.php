{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MESSAGES FLASH - Composant réutilisable                        --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    html: @json(session('success')),
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Compris',
                    timer: 4000,
                    timerProgressBar: true,
                });
            }
        });
    </script>
@endif

@if(session('error_delete'))
    @php
        $erreur = is_array(session('error_delete'))
            ? session('error_delete')
            : ['titre' => 'Erreur', 'message' => session('error_delete'), 'icone' => 'error'];
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: @json($erreur['titre']),
                    html: @json($erreur['message']),
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Compris',
                    showCloseButton: true,
                    allowOutsideClick: true,
                });
            }
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    html: @json(session('error')),
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Compris',
                    showCloseButton: true,
                });
            }
        });
    </script>
@endif

@if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    html: @json(session('warning')),
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Compris',
                    showCloseButton: true,
                });
            }
        });
    </script>
@endif
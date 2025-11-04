@extends('layouts.app')

<div class="contenedor_nv">
    <!-- Aquí va tu contenido de administración si aplica -->
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Revisar si hay token en localStorage
    const token = localStorage.getItem('token');

    if (!token) {
        // Mostrar SweetAlert2 con mensaje 404
        Swal.fire({
            icon: 'error',
            title: '404 Not Found',
            text: 'No tienes acceso a esta página.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            // Redirigir al inicio o a otra página si quieres
            window.location.href = '/';
        });
    }
</script>

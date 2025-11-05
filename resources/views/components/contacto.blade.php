@extends('layouts.app')

@section('title', 'Contacto')

@section('content')

<div class="relative min-h-screen bg-transparent flex items-center justify-center py-10">

    <div class="relative z-10 w-full max-w-6xl p-6 md:p-10 bg-transparent">

        {{-- Iconos de redes --}}
        <div class="absolute top-0 right-0 p-4 md:p-10 space-y-4 hidden sm:block">

            <!-- WhatsApp -->
            <a href="https://wa.me/3107556494" target="_blank" 
               class="block w-16 h-16 bg-black rounded-xl shadow-lg flex items-center justify-center transition hover:scale-105 ml-auto">
                <img class="w-10 h-10" src="{{ asset('Assets/uim--whatsapp.svg') }}" alt="WhatsApp Icon">
            </a>

            <!-- Facebook y Teléfono -->
            <div class="flex flex-row justify-end space-x-4">
                <a href="https://www.facebook.com/profile.php?id=100066868365773" target="_blank" 
                   class="w-16 h-16 bg-black rounded-xl shadow-lg flex items-center justify-center transition hover:scale-105">
                    <img class="w-10 h-10" src="{{ asset('Assets/simple-icons--facebook.svg') }}" alt="Facebook Icon">
                </a>
                <a href="tel:3107556494" 
                   class="w-16 h-16 bg-black rounded-xl shadow-lg flex items-center justify-center transition hover:scale-105">
                    <img class="w-10 h-10" src="{{ asset('Assets/majesticons--phone.svg') }}" alt="Teléfono Icon">
                </a>
            </div>
        </div>

        <form class="space-y-12">

            {{-- Suscripción --}}
            <div class="flex items-center space-x-4">
                <label for="email_recordatorio" class="text-2xl font-semibold text-white shadow-text flex-shrink-0">
                    ¿Quieres recibir recordatorios?
                </label>
                <input type="email" id="email_recordatorio" name="email_recordatorio" 
                       placeholder="Ingresa tu correo" 
                       class="w-full max-w-lg p-4 rounded-full bg-white border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg">
                
                <button type="button" id="enviarCorreo" class="bg-transparent border-none p-0">
                    <svg class="w-8 h-8 transform rotate-20 text-white hover:text-orange-400 transition" 
                        fill="currentColor" 
                        viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>

            {{-- Comentarios --}}
            <div class="space-y-4">
                <h3 class="text-2xl font-semibold text-white shadow-text mb-4">
                    ¿Tienes algún comentario para nosotros?
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-6">
                        <input type="text" id="nombre_comentario" placeholder="Ingresa tu Nombre" 
                               class="w-full p-4 rounded-full bg-white border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg">
                        
                        <input type="email" id="correo_comentario" placeholder="Ingresa tu correo" 
                               class="w-full p-4 rounded-full bg-white border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg">
                    </div>

                    <div class="md:col-span-1 flex justify-center">
                        <textarea id="comentario" placeholder="Escribe tu comentario aquí..." rows="6" 
                                  class="w-full max-w-sm p-4 rounded-3xl bg-white border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg resize-none"></textarea>
                    </div>
                </div>

                <button type="button" id="enviarComentario"
                        class="flex items-center justify-center bg-white text-gray-800 font-medium py-2 px-4 rounded-full shadow-md transition hover:bg-gray-100 text-sm max-w-[120px]">
                    Enviar
                </button>

            </div>
        </form>

    </div>
</div>

<style>
.shadow-text {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.9);
}
textarea {
    border-radius: 1.5rem; 
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Suscripción
    const botonCorreo = document.getElementById('enviarCorreo');
    const inputCorreo = document.getElementById('email_recordatorio');

    botonCorreo.addEventListener('click', async (e) => {
        e.preventDefault();

        const correo = inputCorreo.value.trim();
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!correo) {
            Swal.fire({icon:'warning', title:'Correo requerido', text:'Ingresa tu correo.', confirmButtonColor:'#f97316'});
            return;
        }
        if (!regexCorreo.test(correo)) {
            Swal.fire({icon:'error', title:'Correo inválido', text:'Ingresa un correo válido.', confirmButtonColor:'#f97316'});
            return;
        }

        try {
            const res = await fetch('/api/suscripciones', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ correo: correo, tipo: 'misa' })
            });

            const data = await res.json();

            if (res.ok) {
                Swal.fire({icon:'success', title:'¡Correo registrado!', text:'Te enviaremos recordatorios pronto 😊', confirmButtonColor:'#f97316'});
                inputCorreo.value = '';
            } else {
                Swal.fire({icon:'error', title:'Error', text:data.message || 'Error al registrar el correo', confirmButtonColor:'#f97316'});
            }
        } catch (err) {
            console.error(err);
            Swal.fire({icon:'error', title:'Error', text:'No se pudo conectar al servidor', confirmButtonColor:'#f97316'});
        }
    });

    // Comentarios
    const botonComentario = document.getElementById('enviarComentario');
    const nombreInput = document.getElementById('nombre_comentario');
    const correoInput = document.getElementById('correo_comentario');
    const comentarioInput = document.getElementById('comentario');

    botonComentario.addEventListener('click', async (e) => {
        e.preventDefault();

        const nombre = nombreInput.value.trim();
        const correo = correoInput.value.trim();
        const comentario = comentarioInput.value.trim();
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!nombre || !correo || !comentario) {
            Swal.fire({icon:'warning', title:'Campos requeridos', text:'Completa todos los campos.', confirmButtonColor:'#f97316'});
            return;
        }

        if (!regexCorreo.test(correo)) {
            Swal.fire({icon:'error', title:'Correo inválido', text:'Ingresa un correo válido.', confirmButtonColor:'#f97316'});
            return;
        }

        try {
            const res = await fetch('/api/contacto/comentario', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' 
                },
                body: JSON.stringify({ nombre, correo, comentario })
            });

            const data = await res.json();

            if (res.ok) {
                Swal.fire({icon:'success', title:'¡Comentario enviado!', text:'Gracias por tus comentarios 😊', confirmButtonColor:'#f97316'});
                nombreInput.value = '';
                correoInput.value = '';
                comentarioInput.value = '';
            } else {
                Swal.fire({icon:'error', title:'Error', text:data.message || 'Error al enviar comentario', confirmButtonColor:'#f97316'});
            }

        } catch (err) {
            console.error(err);
            Swal.fire({icon:'error', title:'Error', text:'No se pudo conectar al servidor', confirmButtonColor:'#f97316'});
        }
    });

});
</script>

@endsection

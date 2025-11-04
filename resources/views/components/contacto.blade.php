@extends('layouts.app')

@section('title', 'Contacto')

@section('content')

<div class="relative min-h-screen **bg-transparent** flex items-center justify-center py-10">
    
   

    <div class="relative z-10 w-full max-w-6xl p-6 md:p-10 **bg-transparent**">
        
        <div class="absolute top-0 right-0 p-4 md:p-10 space-y-4 hidden sm:block">
    
    <!-- 1. WHATSAPP (Sigue solo) -->
    <a href="https://wa.me/3107556494" target="_blank" 
       class="block w-16 h-16 bg-black rounded-xl shadow-lg flex items-center justify-center transition hover:scale-105 ml-auto">
        <img class="w-10 h-10" src="{{ asset('Assets/uim--whatsapp.svg') }}" alt="WhatsApp Icon">
    </a>

    <!-- 2. FACEBOOK y TELÉFONO (Ahora en Fila) -->
    <div class="flex flex-row **justify-end** space-x-4">
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
            
            <div class="flex items-center space-x-4">
                <label for="email_recordatorio" class="text-2xl font-semibold text-white shadow-text **flex-shrink-0**">
                    ¿Quieres recibir recordatorios?
                </label>
                <input type="email" id="email_recordatorio" name="email_recordatorio" 
                       placeholder="Ingresa tu correo" 
                       class="w-full max-w-lg p-4 rounded-full **bg-white** border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg">
                
                <button type="submit" id="enviarCorreo" class="bg-transparent border-none p-0">
                    <svg class="w-8 h-8 transform **rotate-90** text-white hover:text-orange-400 transition" 
                        fill="currentColor" 
                        viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <h3 class="text-2xl font-semibold text-white shadow-text mb-4">
                    ¿Tienes algún comentario para nosotros?
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-6">
                        <input type="text" placeholder="Ingresa tu Nombre" 
                               class="w-full p-4 rounded-full **bg-white** border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg">
                        
                        <h4 class="text-xl font-medium text-white shadow-text">
                            Déjanos tu correo para contactarte
                        </h4>
                        <input type="email" placeholder="" 
                               class="w-full p-4 rounded-full **bg-white** border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg">
                    </div>

                   <div class="md:col-span-1 flex justify-center">
                      <textarea placeholder="Escribe tu comentario aquí..." rows="6" 
                          class="w-full **max-w-sm** p-4 rounded-3xl bg-white border-none shadow-lg focus:ring-orange-500 focus:border-orange-500 text-lg resize-none"></textarea>
                  </div>
               <button type="button" 
                  class="flex items-center justify-center bg-white text-gray-800 font-medium py-2 px-4 rounded-full shadow-md transition hover:bg-gray-100 text-sm max-w-[120px]">
                  Enviar
              </button>


                </div>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const boton = document.getElementById('enviarCorreo');
    const input = document.getElementById('email_recordatorio');

    boton.addEventListener('click', (e) => {
        e.preventDefault(); // Evita que se recargue la página
        
        const correo = input.value.trim();
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // estructura básica válida

        if (correo === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Correo requerido',
                text: 'Por favor ingresa tu correo electrónico antes de continuar.',
                confirmButtonColor: '#f97316'
            });
        } else if (!regexCorreo.test(correo)) {
            Swal.fire({
                icon: 'error',
                title: 'Correo inválido',
                text: 'Por favor ingresa un correo electrónico válido (ejemplo: usuario@dominio.com).',
                confirmButtonColor: '#f97316'
            });
        } else {
          //TODO implementar suscripcion llamada backend 
            Swal.fire({
                icon: 'success',
                title: '¡Correo registrado!',
                text: 'Te enviaremos recordatorios pronto 😊',
                confirmButtonColor: '#f97316'
            });
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Seleccionamos los campos y el botón
    const boton = document.querySelector('.flex.items-center.justify-center.bg-white.text-gray-800'); // botón "Enviar"
    const nombreInput = document.querySelector('input[placeholder="Ingresa tu Nombre"]');
    const correoInput = document.querySelector('input[type="email"]:not([id])'); // el email del comentario
    const comentarioInput = document.querySelector('textarea');

    boton.addEventListener('click', (e) => {
        e.preventDefault();

        const nombre = nombreInput.value.trim();
        const correo = correoInput.value.trim();
        const comentario = comentarioInput.value.trim();

        // Expresión regular básica para correo
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (nombre === '' || correo === '' || comentario === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos',
                text: 'Por favor completa todos los campos antes de enviar tu comentario.',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        if (!regexCorreo.test(correo)) {
            Swal.fire({
                icon: 'error',
                title: 'Correo inválido',
                text: 'Por favor ingresa un correo válido (ej: usuario@dominio.com).',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        //TODO implementar llamada backend guardar datos
        Swal.fire({
            icon: 'success',
            title: '¡Comentario enviado!',
            text: 'Gracias por tus comentarios 😊',
            confirmButtonColor: '#f97316'
        });

        // Opcional: limpiar campos después del envío
        nombreInput.value = '';
        correoInput.value = '';
        comentarioInput.value = '';
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
@extends('layouts.app')

@section('title', 'Parroquia Sagrada Familia')
@section('content')

<main class="relative min-h-[70vh] overflow-hidden">
    
    <div class="absolute inset-0 bg-yellow-50 opacity-50 z-0"></div>

    <div class="relative z-10 container mx-auto flex items-center min-h-[70vh] py-10 px-4">
        
        <div class="w-full lg:w-3/5 xl:w-1/2 p-4 flex justify-center">
            <div class="shadow-2xl rounded-lg overflow-hidden max-w-full">
                <img src="{{ asset('Assets/index.png') }}" 
                     class="w-full h-auto object-cover" 
                     alt="Altar de la Parroquia">
            </div>
        </div>

        <div class="w-full lg:w-2/5 xl:w-1/2 p-4 flex justify-end">
            <div class="text-gray-800 text-right max-w-lg">
                <p class="text-xl md:text-3xl font-semibold mb-6 leading-relaxed">
                    Somos una Parroquia de la Arquidiócesis de Tunja,<br>
                    presente en el barrio la Fuente para servir a la comunidad.
                </p>
                <a href="{{ route('contacto') }}" 
                    class="inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg shadow-xl transition duration-300 transform hover:scale-105">
                    Conócenos
                </a>
            </div>
        </div>
        
    </div>
</main>

---

{{-- MODIFICACIÓN: Color de fondo oscuro para que la sección de eventos se separe visualmente --}}
<div class="bg-gray-1000"> 
    <div class="container mx-auto py-10 px-4 text-white">
        <h2 class="text-3xl font-bold mb-4">Próximos Eventos</h2>

        <div id="eventosContainer" class="relative w-full overflow-hidden">
            <p id="noEventos" class="text-gray-400 text-center py-8 hidden">No hay eventos disponibles</p>

            <div id="carousel" class="flex transition-transform duration-500 ease-in-out space-x-4"></div>

            <button id="prevEvento" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 z-20">
                ‹
            </button>
            <button id="nextEvento" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 z-20">
                ›
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const API_BASE = 'http://127.0.0.1:8000/api/eventos';
        const carousel = document.getElementById('carousel');
        const noEventos = document.getElementById('noEventos');
        const prevBtn = document.getElementById('prevEvento');
        const nextBtn = document.getElementById('nextEvento');
        let currentIndex = 0;

        async function cargarEventos() {
            try {
                const res = await fetch(API_BASE);
                const data = await res.json();
                carousel.innerHTML = '';

                if (!data.data || data.data.length === 0) {
                    noEventos.classList.remove('hidden');
                    return;
                }

                noEventos.classList.add('hidden');

                data.data.forEach(evento => {
                    // Nota: Si solo usas la imagen, asegúrate de que el API te devuelva banners grandes.
                    const card = document.createElement('div');
                    
                    // CLASES MODIFICADAS: Usamos un ancho relativo (`min-w-[48%]`, `min-w-[96%]`) 
                    // para que se vean dos eventos a la vez en pantallas grandes, imitando el estilo de la imagen.
                    card.className = 'min-w-[96%] sm:min-w-[48%] flex-shrink-0 relative overflow-hidden rounded-lg shadow-xl transition duration-300 transform hover:scale-[1.01]';
                    
                    // NOTA: Para replicar el estilo de la imagen, usaremos la imagen como banner y
                    // superpondremos el título y la descripción, si son importantes.
                    // Si el banner ya contiene el texto (como en el ejemplo 'Día de Todos los Fieles Difuntos'), 
                    // simplemente mostramos la imagen.

                    const fecha = new Date(evento.fecha_inicio);
                    const fechaFormateada = fecha.toISOString().split('T')[0];

                    card.innerHTML = `
                        <img src="${evento.imagen ?? 'https://via.placeholder.com/600x300?text=Banner+de+Evento'}" 
                             class="w-full h-48 sm:h-64 object-cover">
                        
                        {{-- Superposición de texto sobre el banner (Opcional, si el banner es solo imagen) --}}
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col justify-end p-4 text-white hover:bg-opacity-20 transition duration-300">
                             <h3 class="font-bold text-xl">${evento.titulo}</h3>
                             <p class="text-sm">${evento.descripcion.substring(0, 50)}...</p>
                             <p class="text-xs mt-1">📅 ${fechaFormateada} | 📍 ${evento.lugar}</p>
                        </div>
                    `;
                    carousel.appendChild(card);
                });

                // Reiniciar posición del carrusel
                updateCarousel();
            } catch (err) {
                console.error('Error cargando eventos', err);
                noEventos.classList.remove('hidden');
                noEventos.textContent = 'Error al cargar los eventos';
            }
        }

        function updateCarousel() {
            // Se calcula el desplazamiento. El 16 corresponde a space-x-4
            const cardWidth = carousel.children[0]?.offsetWidth || 0;
            const spacing = 16; 
            const offset = -currentIndex * (cardWidth + spacing); 
            carousel.style.transform = `translateX(${offset}px)`;
        }

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });

        nextBtn.addEventListener('click', () => {
            // Asegúrate de que no vamos más allá del último elemento visible
            // Restamos 1 para móvil (min-w-[96%]) o 2 para desktop (min-w-[48%]) 
            // dependiendo de cuántos se muestren a la vez. Usamos 1 para simplificar.
            const visibleItems = window.innerWidth >= 640 ? 2 : 1; 
            if (currentIndex < carousel.children.length - visibleItems) {
                currentIndex++;
                updateCarousel();
            }
        });

        // Asegúrate de actualizar el carrusel si la ventana cambia de tamaño
        window.addEventListener('resize', updateCarousel);

        cargarEventos();
    });
</script>
@endsection
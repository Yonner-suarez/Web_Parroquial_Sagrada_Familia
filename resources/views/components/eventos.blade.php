@extends('layouts.app')

@section('title', 'Eventos')

@section('content')
<div class="contenedor w-full min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-center mb-8 text-white">Eventos</h1>

    {{-- Contenedor de eventos --}}
    <div id="eventosContainer" class="grid grid-cols-1 gap-6">
        <p id="cargandoEventos" class="col-span-full text-center text-gray-500">Cargando eventos...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = '/api/eventos'; // ruta relativa
    console.log('API Base Eventos:', API_BASE);

    const eventosContainer = document.getElementById('eventosContainer');
    const cargando = document.getElementById('cargandoEventos');

    async function cargarEventos() {
        try {
            const res = await fetch(API_BASE);
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            
            const data = await res.json();
            eventosContainer.innerHTML = '';

            const eventosArray = data.data || data;

            if (!eventosArray || eventosArray.length === 0) {
                eventosContainer.innerHTML = '<p class="col-span-full text-center text-gray-500 mt-10">No hay eventos registrados.</p>';
                return;
            }

            eventosArray.forEach(evento => {
                const card = document.createElement('div');
                const fecha = new Date(evento.fecha_inicio);
                const fechaFormateada = fecha.toISOString().split('T')[0];

                card.className =
                    'bg-white border-l-8 border-blue-500 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between rounded-lg shadow-lg';

                card.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <img src="${evento.imagen ?? 'https://via.placeholder.com/80x80?text=Evento'}"
                             class="w-16 h-16 rounded-full object-cover shadow-sm" alt="img">
                        <div>
                            <p class="text-gray-800 text-base leading-relaxed font-semibold">${evento.titulo}</p>
                            <p class="text-gray-600 text-sm">${evento.descripcion ?? ''}</p>
                            <p class="text-gray-500 text-sm mt-1">
                              📅 ${fechaFormateada} 🕒 ${evento.hora ?? '' }
                            </p>
                            <p class="text-gray-500 text-sm">📍 ${evento.lugar ?? ''}</p>
                        </div>
                    </div>
                `;
                eventosContainer.appendChild(card);
            });
        } catch (err) {
            console.error('Error cargando eventos', err);
            eventosContainer.innerHTML = '<p class="col-span-full text-center text-red-500 mt-10">Error al conectar con la API.</p>';
        }
    }

    cargarEventos();
});
</script>
@endsection

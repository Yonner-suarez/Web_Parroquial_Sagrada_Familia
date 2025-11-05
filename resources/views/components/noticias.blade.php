@extends('layouts.app')

@section('title', 'Noticias')

@section('content')
<div class="contenedor w-full min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-center mb-8 text-white">Noticias</h1>

    {{-- Contenedor de noticias --}}
    <div id="noticiasContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <p id="cargando" class="col-span-full text-center text-gray-500">Cargando noticias...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Usar ruta relativa evita problemas de CORS si Blade y API corren en el mismo dominio
    const API_BASE = '/api/noticias';
    console.log('API Base:', API_BASE);

    const noticiasContainer = document.getElementById('noticiasContainer');
    const cargando = document.getElementById('cargando');

    async function cargarNoticias() {
        try {
            const res = await fetch(API_BASE);
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

            const data = await res.json();
            noticiasContainer.innerHTML = '';

            // Ajuste: manejar si la API devuelve solo array sin "data"
            const noticiasArray = data.data || data;

            if (!noticiasArray || noticiasArray.length === 0) {
                noticiasContainer.innerHTML = '<p class="col-span-full text-center text-gray-500 mt-10">No hay noticias registradas.</p>';
                return;
            }

            noticiasArray.forEach(noticia => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-lg overflow-hidden flex flex-col';

                card.innerHTML = `
                    <img src="${noticia.imagen ?? 'https://via.placeholder.com/300x200?text=Imagen'}" 
                         alt="Imagen Noticia" 
                         class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h2 class="text-lg font-bold mb-2 text-gray-800">${noticia.titulo}</h2>
                        <p class="text-gray-700 text-sm">${noticia.descripcion ?? ''}</p>
                    </div>
                `;
                noticiasContainer.appendChild(card);
            });
        } catch (err) {
            console.error('Error cargando noticias', err);
            noticiasContainer.innerHTML = '<p class="col-span-full text-center text-red-500 mt-10">Error al conectar con la API.</p>';
        }
    }

    cargarNoticias();
});
</script>
@endsection

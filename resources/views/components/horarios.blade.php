@extends('layouts.app')

@section('title', 'Horarios')

@section('content')
<div class="flex flex-col items-center mt-8">

    <div class="flex items-center space-x-4 p-4 bg-transparent backdrop-filter w-fit mx-auto">

        <div class="flex items-center space-x-2">
            <label for="fecha" class="text-lg font-bold text-gray-800 hidden">Fecha</label>
            <input type="date" id="fecha" class="appearance-none bg-white border border-gray-300 rounded-full py-3 px-6 text-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 shadow-xl w-48">
        </div>

        <div class="flex items-center space-x-2">
            <label for="sector" class="text-lg font-bold text-gray-800 hidden">Sector</label>
            <select id="sector" class="appearance-none bg-white border border-gray-300 rounded-full py-3 px-6 text-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 shadow-xl w-48 pr-10 transition duration-150 ease-in-out">
                <option value="">Selecciona un sector</option>
            </select>
        </div>

        <button type="submit" id="buscarBtn" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-full shadow-xl transition duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 text-lg">
            Buscar
        </button>
    </div>

    <div id="horariosContainer" class="mt-8 w-full max-w-3xl space-y-4">
        <p class="text-center text-gray-500 text-white">Seleccione una fecha y un sector para ver los horarios.</p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const sectorSelect = document.getElementById('sector');
    const fechaInput = document.getElementById('fecha');
    const buscarBtn = document.getElementById('buscarBtn');
    const horariosContainer = document.getElementById('horariosContainer');

    // Cargar sectores en el select
    fetch('/api/horarios/sectores')
        .then(res => res.json())
        .then(data => {
            const sectores = data.data || [];
            sectores.forEach(s => {
                const option = document.createElement('option');
                option.value = s;
                option.textContent = s;
                sectorSelect.appendChild(option);
            });
        })
        .catch(err => console.error('Error cargando sectores', err));

    // Cuando el usuario hace click en Buscar
    buscarBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        const fecha = fechaInput.value;
        const sector = sectorSelect.value;

        if (!fecha || !sector) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Seleccione fecha y sector.',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        horariosContainer.innerHTML = '<p class="text-center text-gray-500">Cargando horarios...</p>';

        try {
            const res = await fetch(`/api/horarios/filtrar?fecha=${fecha}&lugar=${sector}`);
            const data = await res.json();
            const horariosArray = data.data || [];

            if (!horariosArray.length) {
                horariosContainer.innerHTML = '<p class="text-center text-gray-500 text-white">No hay eventos para esta fecha y sector.</p>';
                return;
            }

            horariosContainer.innerHTML = '';
            horariosArray.forEach(h => {
                const div = document.createElement('div');
                div.className = 'bg-white p-4 rounded-lg shadow flex justify-between items-center';
                div.innerHTML = `
                    <div>
                        <p class="font-semibold">${h.capilla} - ${h.dia}</p>
                        <p class="text-gray-600">${h.hora}</p>
                    </div>
                `;
                horariosContainer.appendChild(div);
            });

        } catch (err) {
            console.error('Error cargando horarios', err);
            horariosContainer.innerHTML = '<p class="text-center text-red-500">Error al cargar horarios.</p>';
        }
    });

});
</script>

@endsection

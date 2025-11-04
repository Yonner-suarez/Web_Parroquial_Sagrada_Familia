@extends('layouts.app')

@section('title', 'Horarios')

@section('styles')
    <style>
        
    </style>
@endsection

@section('content')
    <div class="flex flex-col items-center mt-8">
        
        <div class="flex items-center space-x-4 p-4 bg-transparent backdrop-filter w-fit mx-auto">
            
            <div class="flex items-center space-x-2">
                <label for="fecha" class="text-lg font-bold text-gray-800 hidden">Fecha</label>
                <div class="relative">
                    <input type="date" id="fecha" value="2025-10-02" 
                           class="appearance-none bg-white border border-gray-300 rounded-full py-3 px-6 
                                  text-lg text-gray-700 leading-tight focus:outline-none focus:ring-2 
                                  focus:ring-orange-500 shadow-xl w-48 transition duration-150 ease-in-out
                                  /* Opcional: ocultamos la etiqueta "Fecha" para coincidir con la segunda imagen */
                                  [&::-webkit-calendar-picker-indicator]:opacity-0 
                                  [&::-webkit-calendar-picker-indicator]:absolute 
                                  [&::-webkit-calendar-picker-indicator]:w-full 
                                  [&::-webkit-calendar-picker-indicator]:h-full 
                                  [&::-webkit-calendar-picker-indicator]:cursor-pointer">
                    
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <label for="sector" class="text-lg font-bold text-gray-800 hidden">Sector</label>
                <div class="relative">
                    <select id="sector" 
                            class="appearance-none bg-white border border-gray-300 rounded-full py-3 px-6 
                                   text-lg text-gray-700 leading-tight focus:outline-none focus:ring-2 
                                   focus:ring-orange-500 shadow-xl w-48 pr-10 transition duration-150 ease-in-out">
                        <option value="Florencia">Florencia</option>
                        <option value="Otro">Otro Sector</option>
                        <option value="Más" selected>Más Opciones</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <button type="submit" 
                    class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-full 
                           shadow-xl transition duration-150 ease-in-out transform hover:scale-105 focus:outline-none 
                           focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 text-lg">
                Buscar
            </button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const boton = document.querySelector('button[type="submit"]');
    const fechaInput = document.getElementById('fecha');
    const sectorSelect = document.getElementById('sector');

    boton.addEventListener('click', (e) => {
        e.preventDefault(); // Evita que se recargue la página

        const fecha = fechaInput.value.trim();
        const sector = sectorSelect.value.trim();

        if (fecha === '' || sector === '') {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor selecciona una fecha y un sector.',
                confirmButtonColor: '#f97316'
            });
            return;
        }
        //TODO llamar ep filtrado por fecha y lugar para los horarios
        Swal.fire({
            icon: 'success',
            title: 'Búsqueda lista',
            text: `Mostrando resultados para ${sector} en la fecha ${fecha}.`,
            confirmButtonColor: '#f97316'
        });
    });
});
</script>
@endsection
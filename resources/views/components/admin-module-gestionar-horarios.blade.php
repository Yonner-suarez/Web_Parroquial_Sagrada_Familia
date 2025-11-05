@extends('layouts.app')

@section('title', 'Gestión de Horarios')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="w-full max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 mt-56">

    {{-- Botón Agregar Horario --}}
    <div class="text-right mb-6">
        <button id="btnAgregarHorario"
            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out">
            Agregar
        </button>
    </div>

    {{-- Contenedor de horarios --}}
    <div id="horariosContainer" class="space-y-4">
        {{-- Se llenará dinámicamente con JS --}}
    </div>
</div>

{{-- 🧩 Modal para Agregar / Editar Horario --}}
<div id="modalHorario" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md relative">

        <h2 id="modalTitulo" class="text-2xl font-bold text-gray-800 mb-4">Agregar Horario</h2>

        <form id="formHorario" class="space-y-4">
            <input type="hidden" id="horarioId">

            {{-- Fecha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" id="fecha" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 p-2" required>
            </div>

            {{-- Hora --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
                <input type="time" id="hora" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 p-2" required>
            </div>

            {{-- Sector --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                <input type="text" id="sector" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 p-2"
                    placeholder="Ejemplo: Capilla San José" required>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="btnCancelar" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">
                    Cancelar
                </button>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-lg">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = 'http://127.0.0.1:8000/api/horarios';
    const modal = document.getElementById('modalHorario');
    const modalTitulo = document.getElementById('modalTitulo');
    const btnAgregar = document.getElementById('btnAgregarHorario');
    const btnCancelar = document.getElementById('btnCancelar');
    const form = document.getElementById('formHorario');
    const container = document.getElementById('horariosContainer');
    let modo = 'agregar';

    function abrirModal(titulo, data = null) {
        modal.classList.remove('hidden');
        modalTitulo.textContent = titulo;
        document.getElementById('fecha').value = data ? data.dia : '';
        document.getElementById('hora').value = data ? data.hora : '';
        document.getElementById('sector').value = data ? data.capilla : '';
        document.getElementById('horarioId').value = data ? data.id : '';
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        form.reset();
    }

    async function cargarHorarios() {
        try {
            const res = await fetch(`${API_BASE}`);
            const data = await res.json();
            container.innerHTML = '';

            data.data.forEach(horario => {
                const card = document.createElement('div');
                card.className = 'bg-white border-l-8 border-green-500 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between rounded-lg shadow-lg transition hover:shadow-xl';
                card.innerHTML = `
                    <div class="flex flex-col sm:flex-row items-start sm:items-center sm:space-x-4">
                        <div>
                            <p class="text-gray-800 font-semibold text-base">📅 ${horario.dia}</p>
                            <p class="text-gray-600 text-sm">🕒 ${horario.hora}</p>
                            <p class="text-gray-500 text-sm">📍 ${horario.capilla}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 mt-3 sm:mt-0">
                        <button class="text-gray-500 hover:text-green-500" title="Editar" onclick='editarHorario(${JSON.stringify(horario)})'>✏️</button>
                        <button class="text-gray-500 hover:text-red-600" title="Eliminar" onclick='eliminarHorario(${horario.id})'>🗑️</button>
                    </div>
                `;
                container.appendChild(card);
            });
        } catch (err) {
            console.error('Error al cargar horarios', err);
        }
    }

    async function agregarHorario(formData) {
        const res = await fetch(`${API_BASE}/agregar`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        return res.json();
    }

    async function actualizarHorario(formData) {
        const res = await fetch(`${API_BASE}/actualizar`, {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        return res.json();
    }

    async function eliminarHorario(id) {
        const confirm = await Swal.fire({
            title: '¿Eliminar horario?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar'
        });

        if (confirm.isConfirmed) {
            const res = await fetch(`${API_BASE}/eliminar`, {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id })
            });

            if (res.ok) {
                Swal.fire('Eliminado', 'El horario fue eliminado.', 'success');
                cargarHorarios();
            } else {
                Swal.fire('Error', 'No se pudo eliminar.', 'error');
            }
        }
    }

    btnAgregar.addEventListener('click', () => {
        modo = 'agregar';
        abrirModal('Agregar Horario');
    });

    btnCancelar.addEventListener('click', cerrarModal);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            fecha: document.getElementById('fecha').value,
            hora: document.getElementById('hora').value,
            sector: document.getElementById('sector').value,
            id: document.getElementById('horarioId').value
        };

        try {
            if (modo === 'agregar') {
                await agregarHorario(data);
                Swal.fire('Éxito', 'Horario agregado correctamente', 'success');
            } else {
                await actualizarHorario(data);
                Swal.fire('Actualizado', 'Horario actualizado correctamente', 'success');
            }
            cerrarModal();
            cargarHorarios();
        } catch (error) {
            Swal.fire('Error', 'Hubo un problema al guardar.', 'error');
        }
    });

    window.editarHorario = (horario) => {
        modo = 'editar';
        abrirModal('Editar Horario', horario);
    };
    window.eliminarHorario = eliminarHorario

    cargarHorarios();
});
</script>
@endsection

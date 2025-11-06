@extends('layouts.app')

@section('title', 'Gestión de Eventos')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 mt-56">

    {{-- Botón Agregar Evento --}}
    <div class="text-right mb-6">
        <button id="btnAgregarEvento"
            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out">
            Agregar
        </button>
    </div>

    {{-- Contenedor de eventos --}}
    <div id="eventosContainer" class="space-y-4">
        {{-- Eventos dinámicos se cargarán aquí --}}
    </div>
</div>

{{-- 🧩 Modal para Agregar / Editar Evento --}}
<div id="modalEvento" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-lg relative">

        <h2 id="modalTitulo" class="text-2xl font-bold text-gray-800 mb-4">Agregar Evento</h2>

        <form id="formEvento" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="eventoId">

            {{-- Título --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" id="titulo" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                    placeholder="Título del evento" required>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="descripcion"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                    rows="3" placeholder="Detalles del evento" required></textarea>
            </div>

            {{-- Fecha y hora --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" id="fecha" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
                    <input type="time" id="hora" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" required>
                </div>
            </div>

            {{-- Lugar --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lugar</label>
                <input type="text" id="lugar" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                    placeholder="Lugar del evento" required>
            </div>

            {{-- Imagen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Imagen (opcional)</label>
                <input type="file" id="imagen" accept="image/*"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                <img id="previewImagen" src="" alt="Vista previa"
                    class="mt-3 w-32 h-32 object-cover rounded-lg hidden">
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
    const API_BASE = 'http://127.0.0.1:8000/api/eventos';
    const token = localStorage.getItem('token');
    const modal = document.getElementById('modalEvento');
    const modalTitulo = document.getElementById('modalTitulo');
    const btnAgregar = document.getElementById('btnAgregarEvento');
    const btnCancelar = document.getElementById('btnCancelar');
    const form = document.getElementById('formEvento');
    const eventosContainer = document.getElementById('eventosContainer');
    const preview = document.getElementById('previewImagen');
    let modo = 'agregar';

    function abrirModal(titulo, data = null) {
      let fechaFormateada = '';
        if (!data || !data.fecha_inicio) {
            fechaFormateada = '';
        } else {
            const fecha = new Date(data.fecha_inicio);
            fechaFormateada = fecha.toISOString().split('T')[0];
        }
        modal.classList.remove('hidden');
        modalTitulo.textContent = titulo;
        document.getElementById('titulo').value = data ? data.titulo : '';
        document.getElementById('descripcion').value = data ? data.descripcion : '';
        document.getElementById('fecha').value = data ? fechaFormateada : '';
        document.getElementById('hora').value = data ? data.hora : '';
        document.getElementById('lugar').value = data ? data.lugar : '';
        document.getElementById('eventoId').value = data ? data.id : '';
        preview.src = data && data.imagen ? data.imagen : '';
        preview.classList.toggle('hidden', !data || !data.imagen);
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        form.reset();
        preview.classList.add('hidden');
    }

    document.getElementById('imagen').addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                preview.src = ev.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    async function cargarEventos() {
        try {
            const res = await fetch(`${API_BASE}`);
            const data = await res.json();
            eventosContainer.innerHTML = '';

            data.data.forEach(evento => {
                const card = document.createElement('div');
                const fecha = new Date(evento.fecha_inicio);
                const fechaFormateada = fecha.toISOString().split('T')[0]; // "2025-11-06"
                card.className =
                    'bg-white border-l-8 border-blue-500 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between rounded-lg shadow-lg';
                card.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <img src="${evento.imagen ?? 'https://via.placeholder.com/80x80?text=Evento'}"
                             class="w-16 h-16 rounded-full object-cover shadow-sm" alt="img">
                        <div>
                            <p class="text-gray-800 text-base leading-relaxed font-semibold">${evento.titulo}</p>
                            <p class="text-gray-600 text-sm">${evento.descripcion}</p>
                            <p class="text-gray-500 text-sm mt-1">
                              📅 ${fechaFormateada} 🕒 ${evento.hora}
                            </p>
                            <p class="text-gray-500 text-sm">📍 ${evento.lugar}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-blue-500" title="Editar" onclick='editarEvento(${JSON.stringify(evento)})'>✏️</button>
                        <button class="text-gray-500 hover:text-red-600" title="Eliminar" onclick='eliminarEvento(${evento.id})'>🗑️</button>
                    </div>
                `;
                eventosContainer.appendChild(card);
            });
        } catch (err) {
            console.error('Error cargando eventos', err);
        }
    }

    async function agregarEvento(formData) {
        const res = await fetch(`${API_BASE}/agregar`, {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}` // 👈 Envía el token aquí
            },
            body: formData
        });
        return res.json();
    }

    async function actualizarEvento(formData) {
      formData.append('_method', 'PUT');
        const res = await fetch(`${API_BASE}/actualizar`, {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}` // 👈 Envía el token aquí
            },
            body: formData
        });
        return res.json();
    }

    async function eliminarEvento(id) {
        const confirm = await Swal.fire({
            title: '¿Eliminar evento?',
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
                headers: {
    'Authorization': `Bearer ${token}` // 👈 Envía el token aquí
  },
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id })
            });

            if (res.ok) {
                Swal.fire('Eliminado', 'El evento fue eliminado.', 'success');
                cargarEventos();
            } else {
                Swal.fire('Error', 'No se pudo eliminar.', 'error');
            }
        }
    }

    btnAgregar.addEventListener('click', () => {
        modo = 'agregar';
        abrirModal('Agregar Evento');
    });

    btnCancelar.addEventListener('click', cerrarModal);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData();
        const id = document.getElementById('eventoId').value;

        formData.append('titulo', document.getElementById('titulo').value);
        formData.append('descripcion', document.getElementById('descripcion').value);
        formData.append('fecha', document.getElementById('fecha').value);
        formData.append('hora', document.getElementById('hora').value);
        formData.append('lugar', document.getElementById('lugar').value);

        const imagen = document.getElementById('imagen').files[0];
        if (imagen) formData.append('imagen', imagen);
        if (modo === 'editar') formData.append('id', id);

        try {
            if (modo === 'agregar') {
                await agregarEvento(formData);
                Swal.fire('Éxito', 'Evento agregado correctamente', 'success');
            } else {
                formData.append('_method', 'PUT');
                await actualizarEvento(formData);
                Swal.fire('Actualizado', 'Evento actualizado correctamente', 'success');
            }
            cerrarModal();
            cargarEventos();
        } catch (error) {
            Swal.fire('Error', 'Hubo un problema al guardar.', 'error');
        }
    });

    window.editarEvento = (evento) => {
        modo = 'editar';
        abrirModal('Editar Evento', evento);
    };
    window.eliminarEvento = eliminarEvento;

    cargarEventos();
});
</script>
@endsection

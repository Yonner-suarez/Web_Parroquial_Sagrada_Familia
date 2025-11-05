@extends('layouts.app')

@section('title', 'Gestión de Noticias')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 mt-56">

    {{-- Botón Agregar --}}
    <div class="text-right mb-6">
        <button id="btnAgregar"
            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out">
            Agregar
        </button>
    </div>

    {{-- Contenedor de noticias --}}
    <div id="noticiasContainer" class="space-y-4">
        {{-- Noticias dinámicas --}}
    </div>
</div>

{{-- ✅ MODAL (Agregar / Editar Noticia) --}}
<div id="modalNoticia" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-lg relative">

        <h2 id="modalTitulo" class="text-2xl font-bold text-gray-800 mb-4">Agregar Noticia</h2>

        <form id="formNoticia" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="noticiaId">

            {{-- Título --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" id="titulo"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2"
                    placeholder="Título de la noticia" required>
            </div>


            {{-- Imagen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Imagen (opcional)</label>
                <input type="file" id="imagen" accept="image/*"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2">
                <img id="previewImagen" src="" alt="Vista previa"
                    class="mt-3 w-32 h-32 object-cover rounded-lg hidden">
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="btnCancelar"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">Cancelar</button>
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = 'http://127.0.0.1:8000/api/noticias';
    const token = localStorage.getItem('token');
    const modal = document.getElementById('modalNoticia');
    const modalTitulo = document.getElementById('modalTitulo');
    const btnAgregar = document.getElementById('btnAgregar');
    const btnCancelar = document.getElementById('btnCancelar');
    const form = document.getElementById('formNoticia');
    const noticiasContainer = document.getElementById('noticiasContainer');
    const preview = document.getElementById('previewImagen');
    let modo = 'agregar';

    // --- Funciones auxiliares ---
    function abrirModal(titulo, data = null) {

        modal.classList.remove('hidden');
        modalTitulo.textContent = titulo;
        document.getElementById('titulo').value = data ? data.titulo : '';
        document.getElementById('noticiaId').value = data ? data.id : '';
        preview.src = data && data.imagen ? data.imagen : '';
        preview.classList.toggle('hidden', !data || !data.imagen);
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        form.reset();
        preview.classList.add('hidden');
    }

    // Vista previa de imagen
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

    // --- Cargar noticias (GET) ---
    async function cargarNoticias() {
        try {
            const res = await fetch(`${API_BASE}`);
            const data = await res.json();
            noticiasContainer.innerHTML = '';
            

            data.data.forEach(noticia => {
                const card = document.createElement('div');
                console.log(noticia.imagen);
                card.className =
                    'bg-white border-l-8 border-orange-500 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between rounded-lg shadow-lg';
                card.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <img src="${noticia.imagen ?? 'https://via.placeholder.com/80x80?text=No+Img'}" class="w-16 h-16 rounded-full object-cover shadow-sm" alt="img">
                        <div>
                            <p class="text-gray-800 text-base leading-relaxed font-semibold">${noticia.titulo}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-orange-500" title="Editar" onclick='editarNoticia(${JSON.stringify(noticia)})'>✏️</button>
                        <button class="text-gray-500 hover:text-red-600" title="Eliminar" onclick='eliminarNoticia(${noticia.id})'>🗑️</button>
                    </div>
                `;
                noticiasContainer.appendChild(card);
            });
        } catch (err) {
            console.error('Error cargando noticias', err);
        }
    }

    // --- Crear noticia (POST) ---
    async function agregarNoticia(formData) {
        const res = await fetch(`${API_BASE}/agregar`, {
            method: 'POST',
            headers: {
    'Authorization': `Bearer ${token}` // 👈 Envía el token aquí
  },
            body: formData
        });
        return res.json();
    }

    // --- Actualizar noticia (PUT) ---
    async function actualizarNoticia(formData) {
    formData.append('_method', 'PUT');
    const res = await fetch(`${API_BASE}/actualizar`, {
        method: 'POST', // 👈 OJO: debe ser POST
        headers: {
    'Authorization': `Bearer ${token}` // 👈 Envía el token aquí
  },
        body: formData
    });
    return res.json();
}

    // --- Eliminar noticia (DELETE) ---
    async function eliminarNoticia(id) {
      console.log(id)
        const confirm = await Swal.fire({
            title: '¿Eliminar noticia?',
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
                Swal.fire('Eliminado', 'La noticia fue eliminada.', 'success');
                cargarNoticias();
            } else {
                Swal.fire('Error', 'No se pudo eliminar.', 'error');
            }
        }
    }

    // --- EVENTOS ---
    btnAgregar.addEventListener('click', () => {
        modo = 'agregar';
        abrirModal('Agregar Noticia');
    });

    btnCancelar.addEventListener('click', cerrarModal);

    form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData();
    const id = document.getElementById('noticiaId').value;

    formData.append('titulo', document.getElementById('titulo').value);
    formData.append('id', id);

    const imagen = document.getElementById('imagen').files[0];
    if (imagen) {
        formData.append('imagen', imagen);
    }

    try {
        if (modo === 'agregar') {
            await agregarNoticia(formData);
            Swal.fire('Éxito', 'Noticia agregada correctamente', 'success');
        } else {
            await actualizarNoticia(formData);
            Swal.fire('Actualizado', 'Noticia actualizada correctamente', 'success');
        }

        cerrarModal();
        cargarNoticias();
    } catch (error) {
        Swal.fire('Error', 'Hubo un problema al guardar.', 'error');
    }
});


    // Función global para editar
    window.editarNoticia = (noticia) => {
        modo = 'editar';
        abrirModal('Editar Noticia', noticia);
    };
    window.eliminarNoticia = eliminarNoticia;

    // Inicializar
    cargarNoticias();
});
</script>
@endsection

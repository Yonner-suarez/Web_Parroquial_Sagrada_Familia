@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 mt-56">

    {{-- Botón Agregar --}}
    <div class="text-right mb-6">
        <button id="btnAgregar"
            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out">
            Agregar Usuario
        </button>
    </div>

    {{-- Contenedor de usuarios --}}
    <div id="usuariosContainer" class="space-y-4">
        {{-- Usuarios dinámicos --}}
    </div>
</div>

{{-- ✅ MODAL (Agregar Usuario) --}}
<div id="modalUsuario" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-lg relative">

        <h2 id="modalTitulo" class="text-2xl font-bold text-gray-800 mb-4">Agregar Usuario</h2>

        <form id="formUsuario" class="space-y-4">
            <input type="hidden" id="usuarioId">

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" id="nombre"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2"
                    placeholder="Nombre del usuario" required>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2"
                    placeholder="Email del usuario" required>
            </div>
            {{-- Rol --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select id="rol"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2"
                    required>
                    <option value="">Selecciona un rol</option>
                    <option value="administrador">Administrador</option>
                    <option value="visitante">Usuario</option>
                </select>
            </div>

            
            {{-- Password --}}
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" id="password"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2 pr-10"
                    placeholder="Contraseña" required>
                <button type="button" id="togglePassword"
                    class="absolute right-2 top-9 text-gray-500 hover:text-gray-700">
                    👁️
                </button>
            </div>

            {{-- Confirmar Password --}}
            <div class="relative mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2 pr-10"
                    placeholder="Confirma la contraseña" required>
                <button type="button" id="togglePasswordConfirm"
                    class="absolute right-2 top-9 text-gray-500 hover:text-gray-700">
                    👁️
                </button>
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
  // Mostrar / Ocultar contraseña
const passwordInput = document.getElementById('password');
const passwordConfirmInput = document.getElementById('password_confirmation');
const togglePassword = document.getElementById('togglePassword');
const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

togglePassword.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
});

togglePasswordConfirm.addEventListener('click', () => {
    const type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
    passwordConfirmInput.type = type;
});

document.addEventListener('DOMContentLoaded', () => {
    const API_BASE = 'http://127.0.0.1:8000/api/usuarios';
    const token = localStorage.getItem('token');
    const modal = document.getElementById('modalUsuario');
    const modalTitulo = document.getElementById('modalTitulo');
    const btnAgregar = document.getElementById('btnAgregar');
    const btnCancelar = document.getElementById('btnCancelar');
    const form = document.getElementById('formUsuario');
    const usuariosContainer = document.getElementById('usuariosContainer');

    function abrirModal(titulo, data = null) {
        modal.classList.remove('hidden');
        modalTitulo.textContent = titulo;
        document.getElementById('nombre').value = data ? data.nombre : '';
        document.getElementById('email').value = data ? data.correo : '';
        document.getElementById('usuarioId').value = data ? data.id : '';
        document.getElementById('rol').value = data ? data.rol : '';
        document.getElementById('password').value = data ? data.password : '';
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        form.reset();
    }

    // --- Cargar usuarios (GET) ---
    async function cargarUsuarios() {
        try {
            const res = await fetch(API_BASE, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await res.json();
            usuariosContainer.innerHTML = '';

            data.data.forEach(usuario => {
                const card = document.createElement('div');
                card.className =
                    'bg-white border-l-8 border-orange-500 p-4 flex justify-between items-center rounded-lg shadow-lg';
                card.innerHTML = `
                    <div>
                        <p class="text-gray-800 font-semibold">${usuario.nombre}</p>
                        <p class="text-gray-500 text-sm">${usuario.correo}</p>
                    </div>
                    <div>
                        <button class="text-gray-500 hover:text-red-600" title="Eliminar" onclick='eliminarUsuario(${usuario.id})'>🗑️</button>
                    </div>
                `;
                usuariosContainer.appendChild(card);
            });
        } catch (err) {
            console.error('Error cargando usuarios', err);
        }
    }

    // --- Agregar usuario (POST) ---
    async function agregarUsuario(formData) {
        const res = await fetch(`${API_BASE}/agregar`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` },
            body: formData
        });
        return res.json();
    }

    // --- Eliminar usuario (DELETE) ---
    async function eliminarUsuario(id) {
        const confirm = await Swal.fire({
            title: '¿Eliminar usuario?',
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
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            });

            if (res.ok) {
                Swal.fire('Eliminado', 'El usuario fue eliminado.', 'success');
                cargarUsuarios();
            } else {
                Swal.fire('Error', 'No se pudo eliminar.', 'error');
            }
        }
    }

    // --- EVENTOS ---
    btnAgregar.addEventListener('click', () => abrirModal('Agregar Usuario'));
    btnCancelar.addEventListener('click', cerrarModal);

    form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const rol = document.getElementById('rol').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    // Validar que las contraseñas coincidan
    if (password !== password_confirmation) {
        Swal.fire('Error', 'Las contraseñas no coinciden', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('email', email);
    formData.append('rol', rol);
    formData.append('password', password);

        try {
            await agregarUsuario(formData);
            Swal.fire('Éxito', 'Usuario agregado correctamente', 'success');
            cerrarModal();
            cargarUsuarios();
        } catch (error) {
            Swal.fire('Error', 'Hubo un problema al guardar.', 'error');
        }
    });

    window.eliminarUsuario = eliminarUsuario;
    // Inicializar
    cargarUsuarios();
});
</script>
@endsection

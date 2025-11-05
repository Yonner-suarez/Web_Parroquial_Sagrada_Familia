
<div class="contenedor_nv">
  
  <img class="lgo" src="/Assets/logo.png" alt="logo">
  
  <nav class="navbar navbar-expand-lg mt-4 w-100">  <!-- ✅ añadimos w-100 -->
    <ul class="nav rounded-pill bg-white shadow px-4 py-3 mx-auto">
      <li class="nav-item mx-3">
        <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('index') ? 'active' : '' }}" 
           href="{{ route('index') }}">Inicio</a>
      </li>
      <li class="nav-item mx-3">
        <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('noticias') ? 'active' : '' }}" 
           href="{{ route('noticias') }}">Noticias</a>
      </li>
      <li class="nav-item mx-3">
        <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('eventos') ? 'active' : '' }}" 
           href="{{ route('eventos') }}">Eventos</a>
      </li>
      <li class="nav-item mx-3">
        <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('horarios') ? 'active' : '' }}" 
           href="{{ route('horarios') }}">Horarios</a>
      </li>
      <li class="nav-item mx-3">
        <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('contacto') ? 'active' : '' }}" 
           href="{{ route('contacto') }}">Contacto</a>
      </li>
    </ul> 

  </nav>
</div>



<!-- Modal Login -->
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-xl shadow-2xl w-96 p-6 relative">
    <!-- Cerrar modal -->
    <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
      &times;
    </button>

    <img class="lgo" src="/Assets/logo.png" alt="logo">
    <h2 class="text-2xl font-bold mb-4 text-center">Hola Administrador</h2>

    <form id="loginForm" class="space-y-4">
      <div>
        <input type="text" id="username" name="username" placeholder="Ingresa tu usuario"
               class="w-full p-3 border rounded-full focus:ring-2 focus:ring-orange-500 focus:outline-none">
      </div>

      <div class="relative">
        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña"
        class="w-full p-3 border rounded-full focus:ring-2 focus:ring-orange-500 focus:outline-none pr-10">
          <!-- Icono de ojo -->
          <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
              👁️
          </button>
      </div>

      <button type="submit" 
              class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-full shadow-xl transition transform hover:scale-105">
        Ingresar
      </button>
    </form>
  </div>
</div>


<style>
.navbar {
  margin-top: 2rem; /* margen superior visible */
}

.nav {   
  width: 80%;                /* ancho del contenedor */
  margin: 0 auto;            /* centrado horizontal */
  border-radius: 50px;
  background-color: white;
  backdrop-filter: blur(4px);
  transition: all 0.3s ease-in-out;
  display: flex;
  flex-direction: row;
  justify-content: space-around;
  align-items: center;
  padding: 1rem 2rem;        /* altura/padding aumentada */
  box-sizing: border-box;
  list-style: none;
}

/* Enlaces sin subrayado ni color raro */
.nav-item a {
  color: #000 !important;
  font-size: 1.1rem;
  text-decoration: none !important;
  transition: color 0.3s, transform 0.3s;
  font-weight: bold;
}

.nav-item a:hover {
  color: #ff6600 !important;
  transform: scale(1.05);
}

.nav-item .active {
  color: #ff6600 !important;
}
.lgo {
  width: 80px;
  display: block;         /* Para que respete el centrado con margin */
  margin: 0 auto;         /* Centra horizontalmente */
}
.titulo-ruta {
  color: black;
  font-size: 1.8rem;
  font-weight: bold;
}
</style>


<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ---- Toggle contraseña ----
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', (e) => {
            e.preventDefault(); // evita efectos colaterales
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            togglePassword.textContent = isPassword ? '🙈' : '👁️';
            passwordInput.focus();
        });
    }

    // ---- Modal login ----
    const logo = document.querySelector('.lgo');
    const modal = document.getElementById('loginModal');
    const closeBtn = document.getElementById('closeModal');
    const form = document.getElementById('loginForm');

    // Mostrar/Ocultar modal
    const token = localStorage.getItem('token');
    if (token) {
        // Sesión activa: evitar click en logo
        logo.style.cursor = 'not-allowed';
        logo.addEventListener('click', (e) => {
            e.stopImmediatePropagation();
            Swal.fire({
                icon: 'info',
                title: 'Sesión activa',
                text: 'Ya has iniciado sesión.',
                confirmButtonColor: '#f97316'
            }).then(() => {
                window.location.href = '/administracion/Gestionar_Noticias';
            });
        });
    } else {
        logo.addEventListener('click', () => modal.classList.remove('hidden'));
    }

    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.add('hidden');
    });

    // ---- Manejo del formulario ----
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const user = document.getElementById('username').value.trim();
            const pass = passwordInput.value.trim();

            if (!user || !pass) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos incompletos',
                    text: 'Por favor ingresa usuario y contraseña.',
                    confirmButtonColor: '#f97316'
                });
                return;
            }

            try {
                const response = await fetch('http://127.0.0.1:8000/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: user, password: pass })
                });

                const data = await response.json();

                if (response.ok && data.success && data.token) {
                    localStorage.setItem('token', data.token);

                    Swal.fire({
                        icon: 'success',
                        title: 'Inicio de sesión correcto',
                        text: `Bienvenido, ${user}!`,
                        confirmButtonColor: '#f97316'
                    }).then(() => {
                        window.location.href = '/administracion/Gestionar_Noticias';
                    });

                    modal.classList.add('hidden');
                    form.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en el login',
                        text: data.message || 'Usuario o contraseña incorrectos',
                        confirmButtonColor: '#f97316'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar al servidor.',
                    confirmButtonColor: '#f97316'
                });
            }
        });
    }
});
</script>

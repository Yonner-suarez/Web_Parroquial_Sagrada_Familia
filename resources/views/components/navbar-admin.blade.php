<div class="contenedor_nv fixed top-0 left-0 w-full z-50">
    <img class="lgo" src="/Assets/logo.png" alt="logo">

    <nav class="navbar navbar-expand-lg mt-4 w-100">
        <ul class="nav rounded-pill bg-white shadow px-4 py-3 mx-auto">
            <li class="nav-item mx-3">
                <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('administracion.gestionar-noticias') ? 'active' : '' }}" 
                  href="{{ route('administracion.gestionar-noticias') }}">Gestionar Noticias</a>
            </li>
            <li class="nav-item mx-3">
                <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('administracion.gestionar-eventos') ? 'active' : '' }}" 
                  href="{{ route('administracion.gestionar-eventos') }}">Gestionar Eventos</a>
            </li>
            <li class="nav-item mx-3">
                <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('administracion.gestionar-horarios') ? 'active' : '' }}" 
                  href="{{ route('administracion.gestionar-horarios') }}">Gestionar Horarios</a>
            </li>
             <li class="nav-item mx-3">
                <a class="nav-link fw-semibold text-decoration-none {{ request()->routeIs('administracion.gestionar-usuarios') ? 'active' : '' }}" 
                  href="{{ route('administracion.gestionar-usuarios') }}">Gestionar Usuarios</a>
            </li>
                        <!-- Botón Salir -->
            <li class="nav-item mx-3">
                <a class="nav-link fw-semibold text-decoration-none" href="#" id="logoutBtn" title="Salir">
                    <!-- Ícono de salir (Bootstrap Icons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M6 3a1 1 0 0 1 1-1h5.5a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-.5.5H7a1 1 0 0 1-1-1v-1h1v1h5.5v-11H7v1H6V3z"/>
                      <path fill-rule="evenodd" d="M11.146 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 8 7.438 10.854a.5.5 0 0 0 .708.708l3-3z"/>
                    </svg>
                    Salir
                </a>
            </li>
        </ul> 
    </nav>
</div>

<style>
.navbar {
    margin-top: 2rem;
}

.nav {   
    width: 80%;
    margin: 0 auto;
    border-radius: 50px;
    background-color: white;
    backdrop-filter: blur(4px);
    transition: all 0.3s ease-in-out;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    padding: 1rem 2rem;
    box-sizing: border-box;
    list-style: none;
}

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
    display: block;
    margin: 0 auto;
}

.titulo-ruta {
    color: black;
    font-size: 1.8rem;
    font-weight: bold;
}
</style>

<script>
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        // Borra el token del localStorage
        localStorage.removeItem('token');
        // Redirige al /
        window.location.href = '/';
    });
</script>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Parroquia Sagrada Familia')</title>
    <link rel="icon" href="Assets/logo.png" type="image/png">
    <link rel="preload" href="{{ asset('Assets/bodyBack.png') }}" as="image">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
        height: 100%;
        margin: 0;
        font-family: 'Nunito', sans-serif;
        color: #636b6f;

        /* Fondo */
        background-image: url("/Assets/bodyBack.png");
        background-size: cover;        /* Cubre toda la pantalla sin distorsión */
        background-position: center;   /* Centra la imagen */
        background-attachment: fixed;  /* Permite el efecto al hacer scroll */
        background-repeat: no-repeat;  /* Evita que se repita */
    }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

      
        
    </style>
    @stack('styles')
    
   

</head>
<body>
    @if(request()->is('administracion*'))
        @include('components.navbar-admin')
    @else
        @include('components.navbar')
    @endif

    @yield('content')
    @yield('scripts')
</body>

</html>

@extends('layouts.app')

@section('title', 'Parroquia Sagrada Familia')
@section('content')

<main class="relative **min-h-[70vh]** overflow-hidden">
    
    <div class="absolute inset-0 bg-yellow-50 opacity-50 z-0"></div>

    <div class="relative z-10 container mx-auto flex items-center **min-h-[70vh]** py-10 px-4">
        
        <div class="w-full lg:w-3/5 xl:w-1/2 p-4 flex justify-center">
            <div class="shadow-2xl rounded-lg overflow-hidden max-w-full">
                <img src="{{ asset('Assets/index.png') }}" 
                     class="w-full h-auto object-cover" 
                     alt="Altar de la Parroquia">
            </div>
        </div>

        <div class="w-full lg:w-2/5 xl:w-1/2 p-4 flex justify-end">
            <div class="text-gray-800 text-right max-w-lg">
                <p class="text-xl md:text-3xl font-semibold mb-6 leading-relaxed">
                    Somos una Parroquia de la Arquidiócesis de Tunja,<br>
                    presente en el barrio la Fuente para servir a la comunidad.
                </p>
                <a href="{{ route('contacto') }}" 
                    class="inline-block **bg-yellow-500 hover:bg-yellow-600** text-gray-900 font-bold py-3 px-6 rounded-lg shadow-xl transition duration-300 transform hover:scale-105">
                    Conócenos
                </a>
            </div>
        </div>
        
    </div>
</main>

---

<div class="container mx-auto py-10 px-4 text-white">
    <h2 class="text-3xl font-bold mb-4">Próximos Eventos</h2>
</div>
@endsection
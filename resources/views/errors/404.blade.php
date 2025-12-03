<x-layout :show-header="false" :show-footer="false">
<div class="flex flex-col items-center justify-center h-screen text-center text-gray-700 dark:text-gray-200">
    <img src="{{ asset('images/404.png') }}" alt="404 Error" class="w-lg h-lg mb-6 rounded-4xl">
    <p class="text-xl mt-4">"Hey, it's okay. People make mistakes."</p>

    <a href="{{ url('/') }}" 
       class="mt-6 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
        Voltar para Home
    </a>
</div>
</x-layout>
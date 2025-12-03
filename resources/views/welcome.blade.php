<x-layout>
    <x-slot class="dark:bg-gray-900">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- Coluna Principal (Posts) - Estrutura Blade/Laravel -->
            <div class="lg:col-span-2 space-y-10">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 dark:border-gray-700">Posts Recentes</h1>

                <!-- @foreach ($posts as $post) -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.01] dark:bg-gray-800 dark:shadow-gray-700/50">
                    
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-3 hover:text-indigo-600 cursor-pointer dark:text-white dark:hover:text-indigo-400">
                        {{ $post['title'] }}
                    </h2>
                    
                    <p class="text-gray-500 dark:text-gray-400 mb-4 text-sm">
                        <span class="font-medium mr-4">Views: {{ $post['views'] }}</span>
                        <span class="font-medium mr-4">User ID: {{ $post['userId'] }}</span>
                        <span class="font-medium text-green-600">👍 {{ $post['reactions']['likes'] }}</span>
                        <span class="font-medium text-red-600">👎 {{ $post['reactions']['dislikes'] }}</span>
                    </p>

                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
                        {{ $post->body ?? "His mother had always taught him not to ever think of himself as better than others. He'd tried to live by this motto..." }}
                    </p>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        <!-- @foreach ($post['tags'] as $tag) -->
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full hover:bg-indigo-200 transition duration-150 cursor-pointer dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">
                            {{ $tag ?? 'history' }}
                        </span>
                        <!-- @endforeach -->
                    </div>

                    <!-- Link para o Post Completo -->
                    <a href="#" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition duration-150 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Leia o Artigo Completo
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
                <!-- @endforeach -->

            </div>

            <!-- Coluna da Barra Lateral -->
            <aside class="lg:sticky lg:top-20 space-y-8 max-h-screen lg:max-h-[calc(100vh-2rem)] overflow-y-auto">
                <!-- Card de Pesquisa - Cor: Vermelho -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-800 dark:shadow-gray-700/50">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700 dark:text-gray-300 dark:border-gray-700">Pesquisar</h3>
                    <div class="flex">
                        <input type="text" placeholder="Buscar no blog..." class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <button class="bg-red-600 text-white px-4 rounded-r-lg hover:bg-red-700 transition duration-150 dark:bg-red-700 dark:hover:bg-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Card de Categorias - Cor: Indigo -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-800 dark:shadow-gray-700/50">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700 dark:text-gray-300 dark:border-gray-700">Categorias</h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li><a href="#" class="hover:text-indigo-600 transition duration-150 dark:hover:text-indigo-400">Tecnologia (15)</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition duration-150 dark:hover:text-indigo-400">Viagem (8)</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition duration-150 dark:hover:text-indigo-400">Culinária (12)</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition duration-150 dark:hover:text-indigo-400">História (5)</a></li>
                    </ul>
                </div>

                <!-- Card de Tags Populares - Cores: Vermelho, Índigo, Roxo -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-800 dark:shadow-gray-700/50">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700 dark:text-gray-300 dark:border-gray-700">Tags Populares</h3>
                    <div class="flex flex-wrap gap-2">
                        <!-- Red - Vermelho (Dark: Mais escuro) -->
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full cursor-pointer hover:bg-red-200 transition duration-150 dark:bg-red-700 dark:text-red-100 dark:hover:bg-red-600">#crime</span>
                        <!-- Indigo - Índigo -->
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full cursor-pointer hover:bg-indigo-200 transition duration-150 dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">#american</span>
                        <!-- Purple - Roxo -->
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-medium rounded-full cursor-pointer hover:bg-purple-200 transition duration-150 dark:bg-purple-700 dark:text-purple-100 dark:hover:bg-purple-600">#history</span>
                        <!-- Gray/Secondary -->
                        <span class="px-3 py-1 bg-gray-200 text-gray-700 text-sm font-medium rounded-full cursor-pointer hover:bg-gray-300 transition duration-150 dark:bg-gray-600 dark:text-gray-100 dark:hover:bg-gray-500">#code</span>
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full cursor-pointer hover:bg-red-200 transition duration-150 dark:bg-red-700 dark:text-red-100 dark:hover:bg-red-600">#life</span>
                    </div>
                </div>
            </aside>
        </div>
        <div class="mt-10 h-40 justify-center flex flex-col items-center">
            {{ $posts->links() }}
        </div>
    </x-slot>
</x-layout>

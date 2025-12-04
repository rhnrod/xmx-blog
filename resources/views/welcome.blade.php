<x-layout :showHeader="true" :showFooter="true">
    <x-slot class="dark:bg-gray-900">
        <!-- Main Container: 1 coluna em mobile, 3 colunas em desktop -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-10">

            <!-- Coluna Principal (Posts) -->
            <!-- Ocupa 1 coluna em mobile e 2 colunas em desktop -->
            <div class="lg:col-span-2 space-y-8 md:space-y-10 order-2 lg:order-1">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Posts Recentes</h1>

                <!-- @foreach ($posts as $post) -->
                <article class="bg-white p-6 md:p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.01] dark:bg-gray-800 dark:shadow-gray-700/50">
                    
                    <h2 class="mb-3">
                        <a href={{ route('post.show', ['id' => $post['id']]) }} class="text-2xl md:text-3xl font-extrabold text-gray-900 hover:text-indigo-600 cursor-pointer dark:text-white dark:hover:text-indigo-400">
                        {{ $post['title'] }}
                        </a>
                    </h2>
                    
                    <!-- Meta info (Autor) -->
                    <p class="text-gray-500 dark:text-gray-400 flex mb-4 text-sm items-center">
                        <a href={{ route('user.show', ['id' => $post['user']['id']]) }} class="hover:underline">
                            <span class="font-medium mr-12">{{ $post['user']['firstName']  }} {{ $post['user']['lastName'] }}</span>
                        </a>
                    </p>

                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6 line-clamp-3">
                        {{ $post->body ?? "His mother had always taught him not to ever think of himself as better than others. He'd tried to live by this motto..." }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2">
                            <!-- @foreach ($post['tags'] as $tag) -->
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full hover:bg-indigo-200 transition duration-150 cursor-pointer dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">
                                {{ $tag ?? 'history' }}
                            </span>
                            <!-- @endforeach -->
                        </div>
                        
                        <!-- Reações e Views -->
                        <div class="text-gray-500 dark:text-gray-400 flex text-sm items-center mt-3 sm:mt-0">
                            <span class="font-medium mr-4 hidden sm:inline-block">Views: {{ $post['views'] }}</span>
                            <!-- Substitua as classes internas para melhor visualização em mobile -->
                            <x-partials.like :postId="$post['id']">
                                <x-slot>
                                    {{ $post['reactions']['likes'] ?? $post['likes'] }}
                                </x-slot>
                            </x-partials.like>
                            <x-partials.dislike :postId="$post['id']">
                                <x-slot>
                                {{ $post['reactions']['dislikes'] ?? $post['dislikes'] }}
                                </x-slot>
                            </x-partials.dislike>
                            <x-partials.comment :postId="$post['id']">
                                <x-slot>
                                    {{ count($post['comments'])}}
                                </x-slot>
                            </x-partials.comment>
                        </div>
                    </div>
                </article>
                <!-- @endforeach -->

            </div>

            <!-- Coluna da Barra Lateral -->
            <!-- Ocupa 1 coluna em mobile e 1 coluna em desktop, mas vem antes no DOM (order-1) -->
            <aside class="space-y-6 md:space-y-8 order-1 lg:order-2 lg:sticky lg:top-20 lg:max-h-[calc(100vh-4rem)] overflow-y-auto">
                
                <!-- Formulário de Busca -->
                <form action="{{ url('/') }}" method="GET" class="bg-white p-4 rounded-xl shadow-md dark:bg-gray-800 dark:shadow-gray-700/50">
                    <div class="flex">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Buscar no blog..." 
                            value="{{ $search ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-l-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <button type="submit" class="bg-indigo-600 text-white px-4 rounded-r-lg hover:bg-indigo-700 transition duration-150 dark:bg-indigo-700 dark:hover:bg-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
                
                <!-- Card de Categorias - Cor: Red (mantendo o que você usou) -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-800 dark:shadow-gray-700/50">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700 dark:text-gray-300 dark:border-gray-700">Categorias</h3>
                    <!-- Alterado space-y-3 para space-y-1.5 para diminuir o espaçamento entre itens -->
                    <ul class="space-y-1.5 text-gray-600 dark:text-gray-400">
                        <!-- Itera sobre as tags e suas contagens passadas pelo Controller -->
                        @forelse ($tagCounts as $tag => $count)
                        <li>
                            <!-- Adiciona classe 'bg-red-50 dark:bg-gray-700' para destacar a linha da tag -->
                            <a
                            href="{{ url('/') }}?tag={{ urlencode($tag) }}"
                            class="hover:text-red-600 transition duration-150 dark:hover:text-red-400 capitalize flex justify-between items-center px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-gray-700"
                            >
                            <span class="truncate">{{ $tag }}</span>
                            <span class="font-semibold text-xs ml-2 py-0.5 px-2 bg-red-100 text-red-700 rounded-full dark:bg-red-900 dark:text-red-300">{{ $count }}</span>
                            </a>
                        </li>
                        @empty
                            <li class="p-2">Nenhuma categoria encontrada.</li>
                        @endforelse
                    </ul>
                </div>
            </aside>
        </div>
        
        <!-- Paginação -->
        <div class="mt-10 mb-8 justify-center flex flex-col items-center">
            {{ $posts->links() }}
        </div>
    </x-slot>
</x-layout>
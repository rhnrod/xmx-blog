<x-layout :showHeader="true" :showFooter="true">
    <x-slot class="dark:bg-gray-900">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- Coluna Principal (Posts) - Estrutura Blade/Laravel -->
            <div class="lg:col-span-2 space-y-10">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 dark:border-gray-700">Posts de {{ $user['firstName'] }} {{ $user['lastName'] }}</h1>

                <!-- @foreach ($posts as $post) -->
                <div class="bg-slate-200 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.01] dark:bg-gray-800 dark:shadow-gray-700/50">
                    
                    <h2>
                        <a href={{ route('post.show', ['id' => $post['id']]) }} class="text-3xl font-extrabold text-gray-900 mb-3 hover:text-indigo-600 cursor-pointer dark:text-white dark:hover:text-indigo-400">
                        {{ $post['title'] }}
                        </a>
                    </h2>
                    
                    <p class="text-gray-500 dark:text-gray-400 flex mb-4 text-sm items-center justify-between">
                        <a href={{ route('user.show', ['id' => $post['user']['id']]) }} class="hover:underline">
                            <span class="font-medium mr-12">{{ $post['user']['firstName']  }} {{ $post['user']['lastName'] }}</span>
                        </a>
                    </p>

                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
                        {{ $post->body ?? "His mother had always taught him not to ever think of himself as better than others. He'd tried to live by this motto..." }}
                    </p>
                    
                    <div class="flex items-end-safe justify-between">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <!-- @foreach ($post['tags'] as $tag) -->
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full hover:bg-indigo-200 transition duration-150 cursor-pointer dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">
                                {{ $tag ?? 'history' }}
                            </span>
                            <!-- @endforeach -->
                        </div>
                        <div class="text-gray-500 dark:text-gray-400 flex text-sm items-center">
                            <span class="font-medium mr-4">Views: {{ $post['views'] }}</span>
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
                </div>
                <!-- @endforeach -->

            </div>

            <!-- Coluna da Barra Lateral -->
            <aside class="lg:sticky lg:top-20 space-y-8 max-h-screen lg:max-h-[calc(100vh-2rem)] overflow-y-auto">
                <form action="{{ url('/') }}" method="GET" class="focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Buscar no blog..." 
                            value="{{ $search ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-l-lg  dark:text-white"
                        >
                        <button type="submit" class="bg-red-600 text-white px-4 rounded-r-lg hover:bg-red-700 transition duration-150 dark:bg-red-700 dark:hover:bg-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
                <!-- Card de Categorias - Cor: Indigo -->
                <div class="bg-slate-200 p-6 rounded-xl shadow-md dark:bg-gray-800 dark:shadow-gray-700/50">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700 dark:text-gray-300 dark:border-gray-700">Categorias</h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <!-- Itera sobre as tags e suas contagens passadas pelo Controller -->
                        @forelse ($tagCounts as $tag => $count)
                        <li>
                            <!-- Ao clicar, busca a tag. Usamos o parâmetro 'tag' para que o Controller filtre por tags exatas. -->
                            <a
                            href="{{ url('/') }}?tag={{ urlencode($tag) }}"
                            class="hover:text-red-600 transition duration-150 dark:hover:text-red-400 capitalize flex justify-between"
                            >
                            <span class="truncate">{{ $tag }}</span>
                            <span class="font-semibold text-xs ml-2 py-0.5 px-2 bg-red-100 text-red-700 rounded-full dark:bg-red-900 dark:text-red-300">{{ $count }}</span>
                            </a>
                        </li>
                        @empty
                            <li>Nenhuma categoria encontrada.</li>
                        @endforelse
                    </ul>
                </div>
            </aside>
        </div>
        <div class="mt-10 h-40 justify-center flex flex-col items-center">
            {{ $posts->links() }}
        </div>
    </x-slot>
</x-layout>


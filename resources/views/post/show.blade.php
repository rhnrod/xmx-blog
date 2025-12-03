<x-layout>
    <x-slot class="dark:bg-gray-900">
         <article class="bg-white p-6 sm:p-10 lg:p-12 rounded-2xl shadow-xl dark:bg-gray-800 dark:shadow-gray-700/50">

            <a href="{{ url()->previous() }}" class="inline-block mb-6 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 transition duration-150">
                ← Voltar
            </a>

            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4 leading-tight dark:text-white">
                {{ $post['title'] }}
            </h1>

            <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500 dark:text-gray-400 mb-6 border-b pb-4 dark:border-gray-700">
                <span class="font-medium mr-8">{{ $user['firstName'] }} {{ $user['lastName'] }}</span>
                <span class="font-medium">Views: {{ $post['views'] ?? 0 }}</span>
                
                <!-- Reações -->
                <span class="font-bold text-green-600 flex items-center">
                  👍 {{ $post['reactions']['likes'] ?? 0 }}
                </span>
                <span class="font-bold text-red-600 flex items-center">
                    👎 {{ $post['reactions']['dislikes'] ?? 0}}
                </span>
            </div>

            <div class="post-body text-gray-700 dark:text-gray-300 text-lg">
                <p>
                    {{ $post['body'] }}
                </p>
            </div>
            
            <!-- TAGS (Rodapé do Post) -->
            <div class="mt-8 pt-4 border-t dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Tags:</h3>
                <div class="flex flex-wrap gap-2">
                    <!-- @foreach ($post['tags'] as $tag) -->
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full hover:bg-indigo-200 transition duration-150 cursor-pointer dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">
                        {{ $tag }}
                    </span>
                    <!-- @endforeach -->
                </div>
            </div>
        </article>
        <div class="mt-12 pt-8 border-t dark:border-gray-700">
            <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">
                Comentários ({{ count($comments ?? []) }})
            </h3>
        
            {{-- Lista de Comentários --}}
            <div class="space-y-6">
                @foreach ($comments as $comment)
                    <div class="p-4 bg-gray-100 rounded-xl flex gap-x-4 dark:bg-gray-700">
                        {{-- Cabeçalho --}}
                        <div>
                            <img src={{ $user['image'] }} class="w-12 h-12 rounded-full" alt="">
                        </div>
                        <div class="flex-1 justify-between">

                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $comment['user']['fullName'] }}
                            </span>
                        
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                👍 Likes: {{ $comment['likes'] }}
                            </span>
                        </div>
                        
                        {{-- Corpo do comentário --}}
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ $comment['body'] ?? 'Este é um comentário de exemplo.' }}
                        </p>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-slot>
</x-layout>

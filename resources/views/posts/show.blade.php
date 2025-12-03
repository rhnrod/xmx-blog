<x-layout>
    <x-slot class="dark:bg-gray-900">
         <article class="bg-white p-6 sm:p-10 lg:p-12 rounded-2xl shadow-xl dark:bg-gray-800 dark:shadow-gray-700/50">

            <a href="{{ url()->previous() }}" class="inline-block mb-6 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 transition duration-150">
                ← Voltar
            </a>

            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4 leading-tight dark:text-white">
                {{ $post['title'] ?? 'His mother had always taught him' }}
            </h1>

            <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500 dark:text-gray-400 mb-6 border-b pb-4 dark:border-gray-700">
                <span class="font-medium">User ID: {{ $post->userId ?? 121 }}</span>
                <span class="font-medium">Views: {{ $post->views ?? 305 }}</span>
                
                <!-- Reações -->
                <span class="font-bold text-green-600 flex items-center">
                  👍 {{ $post->reactions->likes ?? 192 }}
                </span>
                <span class="font-bold text-red-600 flex items-center">
                    👎 {{ $post->reactions->dislikes ?? 25 }}
                </span>
            </div>

            <div class="post-body text-gray-700 dark:text-gray-300 text-lg">
                <p>
                    {{ $post['body'] ?? "His mother had always taught him not to ever think of himself as better than others. He'd tried to live by this motto. He never looked down on those who were less fortunate or who had less money than him. But the stupidity of the group of people he was talking to made him change his mind." }}
                </p>
            </div>
            
            <!-- TAGS (Rodapé do Post) -->
            <div class="mt-8 pt-4 border-t dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Tags:</h3>
                <div class="flex flex-wrap gap-2">
                    <!-- @foreach ($post['tags'] as $tag) -->
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full hover:bg-indigo-200 transition duration-150 cursor-pointer dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">
                        {{ $tag ?? 'history' }}
                    </span>
                    <!-- @endforeach -->
                </div>
            </div>

        </article>
    </x-slot>
</x-layout>

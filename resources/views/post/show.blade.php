<x-layout>
    <x-slot class="dark:bg-gray-900">
         <article class="bg-slate-200 p-6 sm:p-10 lg:p-12 rounded-2xl shadow-xl dark:bg-gray-800 dark:shadow-gray-700/50">

            <a href="{{ url()->previous() }}" class="inline-block mb-6 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 transition duration-150">
                ← Voltar
            </a>

            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4 leading-tight dark:text-white">
                {{ $post['title'] }}
            </h1>

            <div class="flex items-center space-x-6 text-sm text-gray-500 dark:text-gray-400 mb-6 border-b pb-4 dark:border-gray-700">
                <a href="{{ route('user.show', ['id' => $user['id']]) }}" class="hover:underline">
                    <span class="font-medium mr-8">{{ $user['firstName'] }} {{ $user['lastName'] }}</span>
                </a>
                <span class="font-medium">Views: {{ $post['views'] ?? 0 }}</span>
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
            <div class="space-y-6" id="comments-section">
                @foreach ($comments as $comment)
                    <div class="p-4 bg-gray-100 rounded-xl flex gap-x-4 dark:bg-gray-700">
                        {{-- Cabeçalho --}}
                        <div>
                           <img src={{ $comment->user->image }} class="w-12 h-12 rounded-full" alt="">
                        </div>
                        <div class="flex-1 justify-between">

                            <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900 dark:text-white">
                                    <a href={{ route('user.show', ['id' => $comment->user->id]) }} class="hover:underline">
                                        {{ $comment->user->fullName }}
                                    </a>
                            </span>
                        
                            <span class="text-xs flex text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M235.5 102.8C256.3 68 300.5 54 338 71.6L345.2 75.4C380 96.3 394 140.5 376.4 178L376.4 178L362.3 208L472 208L479.4 208.4C515.7 212.1 544 242.8 544 280C544 293.2 540.4 305.4 534.2 316C540.3 326.6 543.9 338.8 544 352C544 370.3 537.1 386.8 526 399.5C527.3 404.8 528 410.3 528 416C528 441.1 515.1 463 495.8 475.9C493.9 511.4 466.4 540.1 431.4 543.6L424 544L319.9 544C301.9 544 284 540.6 267.3 534.1L260.2 531.1L259.5 530.8L252.9 527.6L252.2 527.3L240 520.8C227.7 514.3 216.7 506.1 207.1 496.7C203 523.6 179.8 544.1 151.8 544.1L119.8 544.1C88.9 544.1 63.8 519 63.8 488.1L64 264C64 233.1 89.1 208 120 208L152 208C162.8 208 172.9 211.1 181.5 216.5L231.6 110L232.2 108.8L234.9 103.8L235.5 102.9zM120 256C115.6 256 112 259.6 112 264L112 488C112 492.4 115.6 496 120 496L152 496C156.4 496 160 492.4 160 488L160 264C160 259.6 156.4 256 152 256L120 256zM317.6 115C302.8 108.1 285.3 113.4 276.9 127L274.7 131L217.9 251.9C214.4 259.4 212.4 267.4 211.9 275.6L211.8 279.8L211.8 392.7L212 400.6C214.4 433.3 233.4 462.7 262.7 478.3L274.2 484.4L280.5 487.5C292.9 493.1 306.3 496 319.9 496L424 496L426.4 495.9C438.5 494.7 448 484.4 448 472L447.8 469.4C447.7 468.5 447.6 467.7 447.4 466.8C444.7 454.7 451.7 442.6 463.4 438.8C473.1 435.7 480 426.6 480 416C480 411.7 478.9 407.8 476.9 404.2C470.6 393.1 474.1 379 484.9 372.2C491.7 367.9 496.1 360.4 496.1 352C496.1 344.9 493 338.5 487.9 334C482.7 329.4 479.7 322.9 479.7 316C479.7 309.1 482.7 302.6 487.9 298C493 293.5 496.1 287.1 496.1 280L496 277.6C494.9 266.3 485.9 257.3 474.6 256.2L472.2 256.1L324.7 256.1C316.5 256.1 308.9 251.9 304.5 245C300.1 238.1 299.5 229.3 303 221.9L333 157.6C340 142.6 334.4 124.9 320.5 116.6L317.6 115z"/></svg>
                                {{ $comment->likes }}
                            </span>
                        </div>
                        
                        {{-- Corpo do comentário --}}
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ $comment->body ?? 'Este é um comentário de exemplo.' }}
                        </p>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-slot>
</x-layout>

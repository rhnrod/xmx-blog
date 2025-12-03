<x-layout :showHeader="true" :showFooter="true">
    <x-slot class="dark:bg-gray-900">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- Coluna Principal (Posts) - Estrutura Blade/Laravel -->
            <div class="lg:col-span-2 space-y-10">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-2 dark:border-gray-700">Posts Recentes</h1>

                <!-- @foreach ($posts as $post) -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.01] dark:bg-gray-800 dark:shadow-gray-700/50">
                    
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
                    
                    <div class="flex items-center justify-between">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <!-- @foreach ($post['tags'] as $tag) -->
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full hover:bg-indigo-200 transition duration-150 cursor-pointer dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">
                                {{ $tag ?? 'history' }}
                            </span>
                            <!-- @endforeach -->
                        </div>
                        <div class="text-gray-500 dark:text-gray-400 flex text-sm items-center">
                            <span class="font-medium mr-4">Views: {{ $post['views'] }}</span>
                            <span class="font-medium hover:text-green-700 hover:dark:text-green-200 cursor-pointer inline-flex items-center justify-center h-4 mr-2">
                                <svg class="w-5 h-5 mr-1 inline" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M235.5 102.8C256.3 68 300.5 54 338 71.6L345.2 75.4C380 96.3 394 140.5 376.4 178L376.4 178L362.3 208L472 208L479.4 208.4C515.7 212.1 544 242.8 544 280C544 293.2 540.4 305.4 534.2 316C540.3 326.6 543.9 338.8 544 352C544 370.3 537.1 386.8 526 399.5C527.3 404.8 528 410.3 528 416C528 441.1 515.1 463 495.8 475.9C493.9 511.4 466.4 540.1 431.4 543.6L424 544L319.9 544C301.9 544 284 540.6 267.3 534.1L260.2 531.1L259.5 530.8L252.9 527.6L252.2 527.3L240 520.8C227.7 514.3 216.7 506.1 207.1 496.7C203 523.6 179.8 544.1 151.8 544.1L119.8 544.1C88.9 544.1 63.8 519 63.8 488.1L64 264C64 233.1 89.1 208 120 208L152 208C162.8 208 172.9 211.1 181.5 216.5L231.6 110L232.2 108.8L234.9 103.8L235.5 102.9zM120 256C115.6 256 112 259.6 112 264L112 488C112 492.4 115.6 496 120 496L152 496C156.4 496 160 492.4 160 488L160 264C160 259.6 156.4 256 152 256L120 256zM317.6 115C302.8 108.1 285.3 113.4 276.9 127L274.7 131L217.9 251.9C214.4 259.4 212.4 267.4 211.9 275.6L211.8 279.8L211.8 392.7L212 400.6C214.4 433.3 233.4 462.7 262.7 478.3L274.2 484.4L280.5 487.5C292.9 493.1 306.3 496 319.9 496L424 496L426.4 495.9C438.5 494.7 448 484.4 448 472L447.8 469.4C447.7 468.5 447.6 467.7 447.4 466.8C444.7 454.7 451.7 442.6 463.4 438.8C473.1 435.7 480 426.6 480 416C480 411.7 478.9 407.8 476.9 404.2C470.6 393.1 474.1 379 484.9 372.2C491.7 367.9 496.1 360.4 496.1 352C496.1 344.9 493 338.5 487.9 334C482.7 329.4 479.7 322.9 479.7 316C479.7 309.1 482.7 302.6 487.9 298C493 293.5 496.1 287.1 496.1 280L496 277.6C494.9 266.3 485.9 257.3 474.6 256.2L472.2 256.1L324.7 256.1C316.5 256.1 308.9 251.9 304.5 245C300.1 238.1 299.5 229.3 303 221.9L333 157.6C340 142.6 334.4 124.9 320.5 116.6L317.6 115z"/></svg>
                                    {{ $post['reactions']['likes'] ?? $post['likes'] }}
                            </span>
                            <span class="font-medium cursor-pointer hover:text-red-700 hover:dark:text-red-200 inline-flex items-center justify-center h-4 mr-2">
                                <svg class="w-5 h-5 mr-1 inline" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M424 96L431.4 96.4C466.4 100 493.9 128.6 495.8 164.1C513.6 175.9 525.9 195.5 527.8 218L528 224C528 229.7 527.3 235.2 526 240.5C536.2 252 542.8 266.8 543.8 283.2L544 288C544 301.2 540.4 313.4 534.2 324C539.1 332.4 542.4 341.9 543.5 352L543.9 360C543.9 397.3 515.6 427.9 479.3 431.6L471.9 432L362.2 432L376.3 462L379.4 469.6C391.9 505.3 377.6 545.1 345.2 564.6L338 568.5C300.5 586.1 256.3 572.1 235.4 537.3L234.8 536.4L232.1 531.4L231.5 530.2L201.4 466.2C192 484 173.4 496.1 151.9 496.1L119.9 496.1C89 496.1 63.9 471 63.9 440.1L64 216C64 185.1 89.1 160 120 160L152 160C164.4 160 175.9 164.1 185.2 171C198.4 149.6 217.2 131.6 240.2 119.4L252.4 112.9L253.1 112.6L259.7 109.4L260.4 109.1L267.5 106.1C284.2 99.5 302 96.2 320.1 96.2L424 96zM319.9 144C307.9 144 296 146.3 284.8 150.6L280.1 152.6L274.8 155.2L274.8 155.2L262.6 161.7C233.4 177.2 214.3 206.6 211.9 239.3L211.7 247.3L211.7 360.2L211.8 364.3C212.3 372.5 214.3 380.5 217.8 388L274.6 508.9L276.7 512.7C285.1 526.4 302.7 531.8 317.5 524.9L320.4 523.3C333.4 515.5 339.1 499.6 334.1 485.3L332.9 482.3L302.7 418.1C299.2 410.7 299.8 402 304.2 395C308.6 388 316.2 383.9 324.4 383.9L471.9 383.9L474.3 383.8C485.6 382.7 494.6 373.7 495.7 362.4L495.8 359.9C495.8 352.8 492.7 346.4 487.6 341.9C482.4 337.3 479.4 330.8 479.4 323.9C479.4 317 482.4 310.5 487.6 305.9C492 302 495 296.6 495.6 290.6L495.8 287.9C495.8 279.5 491.4 272 484.6 267.7C473.9 260.8 470.4 246.8 476.6 235.7C478.1 233.1 479.1 230.1 479.5 227.1L479.7 223.9C479.7 213.3 472.8 204.3 463.1 201.1C451.4 197.3 444.4 185.2 447.1 173.1C447.3 172.2 447.4 171.3 447.5 170.5L447.7 167.9C447.7 155.5 438.2 145.3 426.1 144.1L424 144L319.9 144zM120 208C115.6 208 112 211.6 112 216L112 440C112 444.4 115.6 448 120 448L152 448C156.4 448 160 444.4 160 440L160 216C160 211.6 156.4 208 152 208L120 208z"/></svg>
                                {{ $post['reactions']['dislikes'] ?? $post['dislikes'] }}
                            </span>
                            <a href={{ route('post.show', ['id' => $post['id']]) . '#comments-section' }} class="font-medium hover:text-black hover:dark:text-white cursor-pointer inline-flex items-center justify-center h-4">
                                <svg class="w-5 h-5 mr-1 inline" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M115.9 448.9C83.3 408.6 64 358.4 64 304C64 171.5 178.6 64 320 64C461.4 64 576 171.5 576 304C576 436.5 461.4 544 320 544C283.5 544 248.8 536.8 217.4 524L101 573.9C97.3 575.5 93.5 576 89.5 576C75.4 576 64 564.6 64 550.5C64 546.2 65.1 542 67.1 538.3L115.9 448.9zM153.2 418.7C165.4 433.8 167.3 454.8 158 471.9L140 505L198.5 479.9C210.3 474.8 223.7 474.7 235.6 479.6C261.3 490.1 289.8 496 319.9 496C437.7 496 527.9 407.2 527.9 304C527.9 200.8 437.8 112 320 112C202.2 112 112 200.8 112 304C112 346.8 127.1 386.4 153.2 418.7z"/></svg>
                                {{ count($post['comments'])}}
                            </a>
                        </div>
                    </div>
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

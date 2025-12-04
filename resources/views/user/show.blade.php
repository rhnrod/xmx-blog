<x-layout>
    <x-slot>
        <div class="flex justify-center">

            
            
            <div class="user-card-container w-full space-y-8">
                <a href="{{ url()->previous() }}" class="inline-block mb-6 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 transition duration-150">
                    ← Voltar
                </a>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-6 border-b pb-3 dark:border-gray-700 text-center md:text-left">Detalhes do Perfil</h1>

                <div class="bg-slate-200 p-6 md:p-8 rounded-2xl shadow-xl dark:bg-gray-800 dark:shadow-gray-700/50">
                    <div class="flex flex-col gap-4 md:flex-row items-start md:items-center space-y-6 md:space-y-0 md:space-x-8">
                        
                        <img 
                            src={{ $user['image'] }}
                            alt="Foto de Perfil de {{ $user['firstName'] }} {{ $user['lastName'] }}" 
                            class="w-48 h-48 rounded-full object-cover shadow-lg border-4 border-primary-indigo-100 dark:border-primary-indigo-800 shrink-0 mx-auto md:mx-0"
                            onerror="this.onerror=null; this.src='https://placehold.co/96x96/4f46e5/ffffff?text=USER';"
                        >

                        <div class="grow w-full">
                            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-3 text-center md:text-left">
                                {{ $user['firstName'] }} {{ $user['lastName'] }}
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 ml-2">(ID: {{ $user['id'] }})</span>
                            </h2>

                            <!-- Detalhes de Contato e Pessoais (2 colunas em desktop) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm text-gray-700 dark:text-gray-300">
                                
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 6V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5"></path></svg>
                                    <span class="font-medium">E-mail:</span>
                                    <a href="mailto:{{ $user['email'] }}" class="hover:underline text-primary-indigo-600 dark:text-primary-indigo-400 truncate">{{ $user['email'] }}</a>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-12a2 2 0 01-2-2V5z"></path></svg>
                                    <span class="font-medium">Telefone:</span>
                                    <span>{{ $user['phone'] }}</span>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 18h4M8 21h4m0-4h4m-4-8h4m-4-4h4M3 10h18M3 14h18M3 18h18M5 6h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"></path></svg>
                                    <span class="font-medium">Nascimento:</span>
                                    <span>{{ \Carbon\Carbon::parse($user['birthDate'])->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <!-- Address Icon -->
                                <div class="flex items-start space-x-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <div class="flex flex-col">
                                        <span class="font-medium">Endereço:</span>
                                        <span>{{ $user['address']['address'] }} | {{ $user['address']['city'] }} | {{ $user['address']['state'] }}, {{ $user['address']['stateCode'] }} | {{ $user['address']['postalCode'] }}</span>
                                    </div>
                                </div>
                            </div>

                            @if(isset($posts) && count($posts) > 0)
                                <div class="bg-slate-300 dark:bg-gray-400 p-6 md:p-8 rounded-2xl shadow-xl border border-gray-50 dark:border-gray-700 mt-8">

                                    <h2 class="text-2xl font-bold text-gray-900 border-b pb-3 mb-5">
                                        Posts do Usuário ({{ $totalPosts }})
                                    </h2>

                                    <ul class="space-y-3">
                                        @foreach($posts->take(5) as $post)
                                            <li>
                                                <a href="{{ route('post.show', ['id' => $post['id']]) }}"
                                                    class="text-indigo-600 hover:underline text-lg font-medium">
                                                    {{ $post['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-5 text-right">
                                        <a href="{{ route('user.index', ['id' => $user['id']]) }}"
                                        class="text-gray-600 hover:text-gray-800 font-semibold">
                                            Ver todos os posts →
                                        </a>
                                    </div>

                                </div>
                            @else
                                <div class="mt-8 p-4 bg-yellow-100 dark:bg-yellow-200 rounded-lg">
                                    <p class="text-yellow-800 dark:text-yellow-900 text-center">
                                        Este usuário não possui posts.
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </x-slot>
</x-layout>
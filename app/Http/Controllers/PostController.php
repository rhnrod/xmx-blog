<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function posts()
    {
        $search = request()->get('search');
        $tagFilter = request()->get('tag'); // NOVO: Captura o parâmetro 'tag' para filtrar por categoria/tag
        
        $page   = request()->get('page', 1);
        $limit  = 30;
        $skip   = ($page - 1) * $limit;
        $searchIds = [];
        $url = "https://dummyjson.com/posts";

        // Se houver filtro de tag, tratamos isso como uma busca local (não API) e ignoramos a busca de texto geral na API para esta requisição.
        if ($tagFilter) {
            $search = null; // Prioriza o filtro de tag e desativa a busca de texto na API
        }

        if ($search) {
            $url = "https://dummyjson.com/posts/search";
            $queryParams = [
                'q'     => $search,
                'limit' => $limit,
                'skip'  => $skip,
            ];
        } else {
            $queryParams = [
                'limit'  => $limit,
                'skip'   => $skip,
                'sortBy' => 'id',
                'order'  => 'asc'
            ];
        }

        // 1. PRIMEIRA CHAMADA À API
        // Esta chamada ainda é necessária para sincronizar os dados paginados/iniciais ou o resultado da busca de texto.
        $response = Http::get($url, $queryParams)->json();
        
        $postsFromApi = $response['posts'] ?? [];
        $totalApi = $response['total'] ?? 0;
        $currentPostCount = count($postsFromApi);

        // 2. LÓGICA DE COMPENSAÇÃO/PREENCHIMENTO DE PÁGINA
        // A compensação só é necessária se for uma busca de texto genérica (via API)
        if ($search && $currentPostCount < $limit && ($skip + $currentPostCount) < $totalApi) {
            $needed = $limit - $currentPostCount;
            $nextSkip = $skip + $currentPostCount;

            // Segunda chamada para buscar a diferença
            $secondResponse = Http::get($url, [
                'q'     => $search,
                'limit' => $needed,
                'skip'  => $nextSkip,
            ])->json();

            // Adiciona os posts encontrados na segunda chamada
            $postsFromApi = array_merge($postsFromApi, $secondResponse['posts'] ?? []);
            // O totalApi permanece o mesmo, pois é o total de todos os resultados da busca.
        }

        // 3. SINCRONIZAÇÃO COM O BANCO DE DADOS LOCAL
        if (!empty($postsFromApi)) {
            foreach ($postsFromApi as $p) {

                if ($search) {
                    $searchIds[] = $p['id'];
                }

                // 3.1. Sincroniza o Usuário do Post
                $localUser = User::find($p['userId']);

                if (!$localUser || !$localUser->firstName) {
                    $apiUser = Http::get("https://dummyjson.com/users/{$p['userId']}")->json();

                    User::updateOrCreate(
                        ['id' => $apiUser['id']],
                        [
                            'firstName' => $apiUser['firstName'] ?? null,
                            'lastName'  => $apiUser['lastName']  ?? null,
                            'email'     => $apiUser['email']     ?? null,
                            'phone'     => $apiUser['phone']     ?? null,
                            'image'     => $apiUser['image']     ?? null,
                            'birth_date'=> $apiUser['birthDate'] ?? null,
                            'address'   => json_encode($apiUser['address'])
                        ]
                    );
                }
                
                // 3.2. Cria/Atualiza o Post
                $post = Post::updateOrCreate(
                    ['id' => $p['id']],
                    [
                        'title'   => $p['title'],
                        'body'    => $p['body'],
                        'tags'    => $p['tags'],
                        'user_id' => $p['userId'],
                    ]
                );

                if ($post->wasRecentlyCreated) {
                    $post->likes    = $p['reactions']['likes'] ?? 0;
                    $post->dislikes = $p['reactions']['dislikes'] ?? 0;
                    $post->views    = $p['views'] ?? 0;
                    $post->save();
                }

                // 3.3. Sincroniza Comentários (apenas se o post foi criado/atualizado com sucesso)
                if ($post && $post->id) { // Verificação de segurança adicional
                    $comments = Http::get("https://dummyjson.com/comments/post/{$p['id']}")->json()['comments'] ?? [];

                    foreach ($comments as $c) {

                        // Sincroniza Usuário do Comentário
                        $commentUser = Http::get("https://dummyjson.com/users/{$c['user']['id']}")->json();

                        User::updateOrCreate(
                            ['id' => $commentUser['id']],
                            [
                                'firstName' => $commentUser['firstName'] ?? null,
                                'lastName'  => $commentUser['lastName']  ?? null,
                                'email'     => $commentUser['email']     ?? null,
                                'phone'     => $commentUser['phone']     ?? null,
                                'image'     => $commentUser['image']     ?? null,
                                'birth_date'=> $commentUser['birthDate'] ?? null,
                                'address'   => json_encode($commentUser['address'])
                            ]
                        );

                        /** Salvar comentário */
                        Comment::updateOrCreate(
                            ['id' => $c['id']], // se existir não duplica
                            [
                                'body'    => $c['body'],
                                'likes'   => $c['likes'] ?? 0,
                                // Usar $post->id, garantindo que a referência é para o model recém-criado/atualizado
                                'post_id' => $post->id, 
                                'user_id' => $c['user']['id'],
                            ]
                        );
                    }
                }
            }
        }

        // 4. RECUPERAÇÃO DOS POSTS PAGINADOS E FILTRAGEM
        $postsQuery = Post::with(['user', 'comments.user']);

        if ($tagFilter) {
            // NOVO: Se for filtro por tag, usa whereJsonContains para buscar a tag exata
            // A tag é convertida para minúsculas para corresponder ao formato de contagem
            $tagValue = strtolower(trim($tagFilter));
            
            // whereJsonContains é usado para buscar um valor específico dentro de um array JSON em uma coluna.
            $postsQuery->whereJsonContains('tags', $tagValue);
            
            // Quando filtramos por tag, precisamos recalcular o total para paginação local
            $totalApi = $postsQuery->count();
            
        } elseif ($search) {
            // Se for busca de texto da API, usamos os IDs que a API nos devolveu (já limitados e preenchidos)
            $postsQuery->whereIn('id', $searchIds);
        }

        $posts = $postsQuery->orderBy('id', 'asc')
            ->skip($skip)
            ->take($limit)
            ->get();

        // 5. CRIAÇÃO DO PAGINATOR
        $paginator = new LengthAwarePaginator(
            $posts,
            $totalApi,
            $limit,
            $page,
            // Mantém os parâmetros 'search' ou 'tag' na URL de paginação
            ['path' => url('/'), 'query' => array_merge(request()->query(), ['tag' => $tagFilter, 'search' => $search])] 
        );
        
        // 6. CÁLCULO DAS CATEGORIAS (TAGS)
        // Puxamos todos os posts para obter a contagem completa das tags sincronizadas.
        $allPosts = Post::all(); 
        $tagCounts = [];

        foreach ($allPosts as $post) {
            // O campo 'tags' da API é uma array de strings, que é salvo como JSON string no DB.
            // Acessamos $post->tags diretamente, assumindo que o Casting está configurado no Model.
            $tags = $post->tags;

            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    $tag = strtolower(trim($tag));
                    $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
                }
            }
        }
        
        // Alterado para ordenar as tags por nome (chave) em ordem alfabética
        ksort($tagCounts);


        return view('welcome', [
            'posts' => $paginator, 
            'search' => $search,
            'tagCounts' => $tagCounts, // Dados das categorias/tags
            'tagFilter' => $tagFilter, // Passa o filtro ativo para a view (opcional para feedback ao usuário)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

/**
     * Display the specified resource.
     */
    public function showPost($id)
    {
        // 1. CHAMA A API PARA OBTER O POST E DEMAIS DADOS
        $apiPost = Http::get("https://dummyjson.com/posts/$id")->json();

        // Se o post não for encontrado na API, retorna 404 (ou outra lógica de erro)
        if (isset($apiPost['message']) && $apiPost['message'] === "Post with id $id not found") {
            abort(404, 'Post não encontrado na API externa.');
        }

        $commentsResponse = Http::get("https://dummyjson.com/comments/post/$id")->json();
        $apiComments = $commentsResponse['comments'] ?? [];
        $apiUser = Http::get("https://dummyjson.com/users/{$apiPost['userId']}?select=id,firstName,lastName,email,phone,image,birthDate,address")->json();

        // 2. SINCRONIZA USUÁRIO DO POST
        User::updateOrCreate(
            ['id' => $apiUser['id']],
            [
                'firstName' => $apiUser['firstName'] ?? null,
                'lastName'  => $apiUser['lastName']  ?? null,
                'email'     => $apiUser['email']     ?? null,
                'phone'     => $apiUser['phone']     ?? null,
                'image'     => $apiUser['image']     ?? null,
                'birth_date'=> $apiUser['birthDate'] ?? null,
                'address'   => json_encode($apiUser['address'] ?? null)
            ]
        );

        // 3. SINCRONIZA POST NO DB LOCAL (Preserva likes/dislikes/views existentes)
        $post = Post::updateOrCreate(
            ['id' => $apiPost['id']],
            [
                'title'   => $apiPost['title'],
                'body'    => $apiPost['body'],
                'tags'    => $apiPost['tags'],
                'user_id' => $apiPost['userId'],
            ]
        );

        // Se o post foi criado pela primeira vez, usa os dados de views/likes/dislikes da API
        if ($post->wasRecentlyCreated) {
            $post->likes    = $apiPost['reactions']['likes'] ?? 0;
            $post->dislikes = $apiPost['reactions']['dislikes'] ?? 0;
            $post->views    = $apiPost['views'] ?? 0;
            $post->save();
        }

        // 4. INCREMENTA AS VIEWS (apenas se não tiver contado views nesta sessão)
        $sessionViewKey = "post_viewed_$id";
        if (!session($sessionViewKey)) {
            // Incrementa as views no DB local
            $post->increment('views');
            // Marca o post como visto na sessão para evitar múltiplos increments
            session([$sessionViewKey => true]); 
        }

        // 5. SINCRONIZA COMENTÁRIOS E SEUS USUÁRIOS
        foreach ($apiComments as $c) {
            // Sincroniza Usuário do Comentário
            $commentUser = Http::get("https://dummyjson.com/users/{$c['user']['id']}?select=id,firstName,lastName,email,phone,image,birthDate,address")->json();

            User::updateOrCreate(
                ['id' => $commentUser['id']],
                [
                    'firstName' => $commentUser['firstName'] ?? null,
                    'lastName'  => $commentUser['lastName']  ?? null,
                    'email'     => $commentUser['email']     ?? null,
                    'phone'     => $commentUser['phone']     ?? null,
                    'image'     => $commentUser['image']     ?? null,
                    'birth_date'=> $commentUser['birthDate'] ?? null,
                    'address'   => json_encode($commentUser['address'] ?? null)
                ]
            );

            // Sincroniza Comentário
            Comment::updateOrCreate(
                ['id' => $c['id']], 
                [
                    'body'    => $c['body'],
                    'likes'   => $c['likes'] ?? 0,
                    'post_id' => $post->id, 
                    'user_id' => $c['user']['id'],
                ]
            );
        }

        // 6. RECUPERA DADOS PARA A VIEW (incluindo likes/dislikes/views atualizados do DB local)
        // Recarrega o post e seus comentários com relacionamentos
        $post = Post::with(['user', 'comments.user'])->findOrFail($id);
        
        // Prepara os dados do comentário para a view. 
        // Os comentários agora vêm do DB local, garantindo que o relacionamento `user` está carregado.
        $commentsForView = $post->comments;

        // O objeto 'user' para a view é o usuário do post (relation 'user' no $post)
        $userForView = $post->user; 
        
        // Passa o $post atualizado do DB local
        return view('post.show', [
            'post' => $post, 
            'comments' => $commentsForView, 
            'user' => $userForView
        ]);
    }

     public function like($id)
    {
        $post = Post::findOrFail($id);
        $sessionKey = "post_vote_$id";

        // Se já tiver dado like, remove
        if (session($sessionKey) === 'like') {
            $post->decrement('likes');
            session()->forget($sessionKey);
            return back();
        }

        // Se tinha dislike antes, remove antes de dar like
        if (session($sessionKey) === 'dislike') {
            $post->decrement('dislikes');
        }

        $post->increment('likes');
        session([$sessionKey => 'like']);

        return back();
    }

    public function dislike($id)
    {
        $post = Post::findOrFail($id);
        $sessionKey = "post_vote_$id";

        // Se já tiver dado dislike, remove
        if (session($sessionKey) === 'dislike') {
            $post->decrement('dislikes');
            session()->forget($sessionKey);
            return back();
        }

        // Se tinha like antes, remove antes de dar dislike
        if (session($sessionKey) === 'like') {
            $post->decrement('likes');
        }

        $post->increment('dislikes');
        session([$sessionKey => 'dislike']);

        return back();
    }
}

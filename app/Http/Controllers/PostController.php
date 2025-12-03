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
        $page   = request()->get('page', 1);
        $limit  = 30;
        $skip   = ($page - 1) * $limit;
        $searchIds = [];
        $url = "https://dummyjson.com/posts";

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
        $response = Http::get($url, $queryParams)->json();
        
        $postsFromApi = $response['posts'] ?? [];
        $totalApi = $response['total'] ?? 0;
        $currentPostCount = count($postsFromApi);

        // 2. LÓGICA DE COMPENSAÇÃO/PREENCHIMENTO DE PÁGINA
        // Se a busca retornar menos que o limite E houver mais posts disponíveis no total, 
        // tentamos buscar o restante na próxima página para preencher esta.
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

        // 4. RECUPERAÇÃO DOS POSTS PAGINADOS
        $postsQuery = Post::with(['user', 'comments.user']);

        if ($search) {
            // Se for busca, usamos os IDs que a API nos devolveu (já limitados e preenchidos)
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
            ['path' => url('/'), 'query' => request()->query()]
        );

        return view('welcome', [
            'posts' => $paginator, 
            'search' => $search
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
        $post = Http::get("https://dummyjson.com/posts/$id")->json();
        $commentsResponse = Http::get("https://dummyjson.com/comments/post/$id")->json();
        $comments = $commentsResponse['comments'];
        $user = Http::get("https://dummyjson.com/users/{$post['userId']}?select=id,firstName,lastName,email,phone,image,birthDate,address")->json();
        
        foreach ($comments as &$comment) {
            $avatar = Http::get("https://dummyjson.com/users/{$comment['user']['id']}?select=image")->json();
            $comment['avatar'] = $avatar['image'];
        }

        return view('post.show', compact('post', 'comments', 'user'));
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

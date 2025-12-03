<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index($id)
    {
        $page  = request()->get('page', 1);
        $limit = 1;
        $skip  = ($page - 1) * $limit;

        // 1) Buscar/ criar usuário
        $user = User::find($id) ?? User::create(Http::get("https://dummyjson.com/users/$id")->json());

        // 2) Pegar total da API (chamada leve) — garante que $totalApi exista sempre
        $apiMeta = Http::get("https://dummyjson.com/users/$id/posts", [
            'limit' => 1,
            'skip'  => 0
        ])->json();

        $totalApi = $apiMeta['total'] ?? Post::where('user_id', $id)->count();

        // 3) Só popular o DB se não houver posts locais
        if (! $user->posts()->exists()) {
            $api = Http::get("https://dummyjson.com/users/$id/posts", [
                'limit' => $limit,
                'skip'  => $skip
            ])->json();

            foreach ($api['posts'] as $post) {
                Post::updateOrCreate(
                    ['id' => $post['id']],
                    [
                        'title'    => $post['title'],
                        'body'     => $post['body'],
                        'tags'     => $post['tags'] ?? [],
                        'likes'    => $post['reactions']['likes'] ?? $post['likes'] ?? 0,
                        'dislikes' => $post['reactions']['dislikes'] ?? $post['dislikes'] ?? 0,
                        'views'    => $post['views'] ?? 0,
                        'user_id'  => $id
                    ]
                );
            }
        }

        // 4) Buscar do banco agora com paginação
        $postsDB = Post::where('user_id', $id)
            ->skip($skip)
            ->take($limit)
            ->get();

        // 5) Criar paginator usando o total da API (ou fallback)
        $paginator = new LengthAwarePaginator(
            $postsDB,
            $totalApi,
            $limit,
            $page,
            ['path' => url("/user/$id/posts"), 'query' => request()->query()]
        );

        return view('user.index', [
            'posts' => $paginator,
            'user'  => $user,
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
    public function show(string $id)
    {
        /** Buscar dados do usuário */
        $user = Http::get("https://dummyjson.com/users/$id?select=id,firstName,lastName,email,phone,image,birthDate,address")
                    ->json();

        /** Buscar TODOS os posts desse usuário */
        $postsResponse = Http::get("https://dummyjson.com/posts/user/$id")->json();

        /** Total de posts */
        $totalPosts = $postsResponse['total'] ?? 0;

        /** Últimos 5 posts */
        $latestPosts = collect($postsResponse['posts'])->sortByDesc('id')->take(5);

        return view('user.show', [
            'user'        => $user,
            'posts'       => $latestPosts,   // usados no card
            'totalPosts'  => $totalPosts,    // para exibir no título
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

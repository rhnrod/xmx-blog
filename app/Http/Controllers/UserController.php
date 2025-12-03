<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $page = request()->get('page', 1);
        $limit = 10;
        $skip = ($page - 1) * $limit;

        /** -------------------------
         * 1) Buscar usuário no banco
         * ------------------------- */
        $user = User::find($id);

        if (! $user) {
            $apiUser = Http::get("https://dummyjson.com/users/$id")->json();
            $user = User::create($apiUser);
        }

        /** ---------------------------------------------------------
         * 2) Carregar Posts do banco respeitando a mesma paginação
         * --------------------------------------------------------- */
        $postsDB = Post::where('user_id', $id)
            ->skip($skip)
            ->take($limit)
            ->get();

        $totalDB = Post::where('user_id', $id)->count();

        /** ----------------------------------------------------------------
         * 3) Se faltam posts no banco → buscar da API, salvar e atualizar
         * ---------------------------------------------------------------- */
        if ($postsDB->count() < $limit) {

            $api = Http::get("https://dummyjson.com/users/$id/posts", [
                'limit' => $limit,
                'skip' => $skip,
            ])->json();

            foreach ($api['posts'] as $post) {
                Post::updateOrCreate(
                    ['id' => $post['id']],  // evita duplicar
                    [
                        'title'    => $post['title'],
                        'body'     => $post['body'],
                        'tags'     => $post['tags'] ?? [],      // JSON
                        'likes'    => $post['reactions']['likes'] ?? $p['likes'] ?? 0,
                        'dislikes' => $post['reactions']['dislikes'] ?? $p['dislikes'] ?? 0,
                        'views'    => $post['views'] ?? 0,
                        'user_id'  => $id
                    ]
                );
            }

            // Carregar novamente do banco já atualizado
            $postsDB = Post::where('user_id', $id)
                ->skip($skip)
                ->take($limit)
                ->get();

            $totalDB = Post::where('user_id', $id)->count();
        }

        /** -------------------------------
         * 4) Criar paginator manualmente
         * ------------------------------- */
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $postsDB,
            $totalDB,  // total vindo do banco agora
            $limit,
            $page,
            [
                'path' => url("/user/$id/posts"),
                'query' => request()->query(),
            ]
        );

        return view('user.index', [
            'posts' => $paginator,
            'user' => $user,
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
        $users = Http::get("https://dummyjson.com/users/$id?select=id,firstName,lastName,email,phone,image,birthDate,address")->json();

        return view('user.show', ['user' => $users]);
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

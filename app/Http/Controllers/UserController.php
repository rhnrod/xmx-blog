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
        $page = request()->get('page', 1);
        $limit = 10;
        $skip = ($page - 1) * $limit;

        $user = User::find($id) ?? User::create(Http::get("https://dummyjson.com/users/$id")->json());

        /** 2) Buscar total da API apenas 1 vez e salvar para referência futura */
        if (!$user->posts()->exists()) {

            $api = Http::get("https://dummyjson.com/users/$id/posts?limit=200")->json();

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

        /** 3) Buscar do banco agora com paginação */
        $postsDB = Post::where('user_id', $id)
            ->skip($skip)
            ->take($limit)
            ->get();

        $totalDB = Post::where('user_id', $id)->count();

        /** 4) Criar paginator */
        $paginator = new LengthAwarePaginator(
            $postsDB,
            $totalDB,
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

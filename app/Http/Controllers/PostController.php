<?php

namespace App\Http\Controllers;

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
        $page = request()->get('page', 1);

        $limit = 30; 
        $skip = ($page - 1) * $limit;

        // Chamada à API
        $response = Http::get("https://dummyjson.com/posts", [
            'limit' => $limit,
            'skip'  => $skip,
        ])->json();

        $posts = $response['posts'];
        $total = $response['total']; 

        foreach ($posts as &$post) {
            $post['user'] = Http::get("https://dummyjson.com/users/{$post['userId']}?select=id,firstName,lastName,email,phone,image,birthDate,address")->json();
        }

        // Criar paginação igual ao Laravel
        $paginator = new LengthAwarePaginator(
            $posts,
            $total, 
            $limit,  
            $page,    
            [
                'path' => url('/'), // mantém rota /
                'query' => request()->query(), // mantém parâmetros da URL
            ]
        );

        return view('welcome', [
            'posts' => $paginator,
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

        return view('post.show', compact('post', 'comments', 'user', 'avatar'));
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

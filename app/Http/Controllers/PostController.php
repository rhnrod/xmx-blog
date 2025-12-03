<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function posts()
    {
        $page  = request()->get('page', 1);
        $limit = 30;
        $skip  = ($page - 1) * $limit;

        $response = Http::get("https://dummyjson.com/posts", [
            'limit' => $limit,
            'skip'  => $skip
        ])->json();

        $totalApi = $response['total'];

        foreach ($response['posts'] as $p) {

            $localUser = User::find($p['userId']);

            if (!$localUser || !$localUser->firstName || !$localUser->lastName) {

                $apiUser = Http::get("https://dummyjson.com/users/{$p['userId']}")->json();

                User::updateOrCreate(
                    ['id' => $apiUser['id']],
                    [
                        'firstName' => $apiUser['firstName'] ?? $localUser->firstName ?? null,
                        'lastName'  => $apiUser['lastName']  ?? $localUser->lastName  ?? null,
                        'email'     => $apiUser['email']     ?? $localUser->email     ?? null,
                        'phone'     => $apiUser['phone']     ?? $localUser->phone     ?? null,
                        'image'     => $apiUser['image']     ?? $localUser->image     ?? null,
                        'birth_date'=> $apiUser['birthDate'] ?? $localUser->birth_date ?? null,
                        'address'   => json_encode($apiUser['address'] ?? $localUser->address),
                    ]
                );
            }

            Post::updateOrCreate(
                ['id' => $p['id']],
                [
                    'title'    => $p['title'],
                    'body'     => $p['body'],
                    'tags'     => $p['tags'],
                    'likes'    => $p['reactions']['likes'] ?? 0,
                    'dislikes' => $p['reactions']['dislikes'] ?? 0,
                    'views'    => $p['views'] ?? 0,
                    'user_id'  => $p['userId'],
                ]
            );
        }

        /** 2) Puxar só o que precisa do banco */
        $posts = Post::with('user')
            ->skip($skip)
            ->take($limit)
            ->get();

        /** 3) Criar paginação usando TOTAL da API, não do banco local */
        $paginator = new LengthAwarePaginator(
            $posts,
            $totalApi,
            $limit,
            $page,
            ['path' => url('/'), 'query' => request()->query()]
        );

        return view('welcome', ['posts' => $paginator]);
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

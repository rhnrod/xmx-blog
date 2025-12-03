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

            $comments = Http::get("https://dummyjson.com/comments/post/{$p['id']}")->json()['comments'];

            foreach ($comments as $c) {

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
                        'post_id' => $p['id'],
                        'user_id' => $c['user']['id'],
                    ]
                );
            }
        }

        $posts = Post::with(['user', 'comments.user'])
            ->skip($skip)
            ->take($limit)
            ->get();

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

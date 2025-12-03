<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $page = request()->get('page', 1);

        $limit = 30; 
        $skip = ($page - 1) * $limit;

        // Chamada à API
        $response = Http::get("https://dummyjson.com/users/$id/posts", [
            'limit' => $limit,
            'skip'  => $skip,
        ])->json();

        $posts = $response['posts'];
        $total = $response['total']; 

        foreach ($posts as &$post) {
            $post['user'] = Http::get("https://dummyjson.com/users/$id?select=id,firstName,lastName,email,phone,image,birthDate,address")->json();
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

        return view('user.index', [
            'posts' => $paginator,
            'post' => $post['user']
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

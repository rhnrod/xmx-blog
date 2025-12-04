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
     * @param int $id O ID do usuário.
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        // 1. Capturar parâmetros de busca e filtro
        $search = request()->get('search');
        $tagFilter = request()->get('tag');
        // Padroniza o valor da tag para a busca local (minúsculas e sem espaços)
        $tagValue = $tagFilter ? strtolower(trim($tagFilter)) : null; 

        $page = request()->get('page', 1);
        $limit = 10;
        $skip = ($page - 1) * $limit;

        // 2. Buscar/ criar usuário
        $user = User::find($id) ?? User::create(Http::get("https://dummyjson.com/users/$id")->json());

        /** 3) Sincronização: Buscar todos os posts da API apenas 1 vez e salvar para referência futura */
        // Isso garante que a busca por tag e o contador funcionem corretamente no local.
        if (!$user->posts()->exists()) {
            // Busca um limite alto (200) para cobrir a maioria dos casos
            $api = Http::get("https://dummyjson.com/users/$id/posts?limit=200")->json();

            foreach ($api['posts'] as $post) {
                Post::updateOrCreate(
                    ['id' => $post['id']],
                    [
                        'title'     => $post['title'],
                        'body'      => $post['body'],
                        // As tags são salvas como JSON no DB
                        'tags'      => $post['tags'] ?? [], 
                        'likes'     => $post['reactions']['likes'] ?? $post['likes'] ?? 0,
                        'dislikes'  => $post['reactions']['dislikes'] ?? $post['dislikes'] ?? 0,
                        'views'     => $post['views'] ?? 0,
                        'user_id'   => $id
                    ]
                );
            }
        }

        // 4. Construção da Consulta ao Banco de Dados (com filtros)
        $postsQuery = Post::where('user_id', $id);

        if ($tagFilter) {
            // Filtragem por tag: usa whereJsonContains para buscar no array 'tags'
            $postsQuery->whereJsonContains('tags', $tagValue);
        } elseif ($search) {
            // Busca de texto local (em title e body)
            $postsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                      ->orWhere('body', 'like', "%$search%");
            });
        }
        
        // 5. Contagem Total após Filtros
        // Usa o count do Query Builder após a aplicação dos filtros (se houver)
        $totalDB = $postsQuery->count(); 

        // 6. Buscar os posts com paginação
        $postsDB = $postsQuery
            ->skip($skip)
            ->take($limit)
            // Se não houver busca, ordena por id (assumindo que posts são ordenados por id na API)
            ->orderBy('id', 'asc') 
            ->get();

        /** 7. Cálculo das Categorias (TAGS) */
        // Puxa todos os posts *deste usuário* (sem filtros) para obter a contagem completa das tags.
        $allUserPosts = Post::where('user_id', $id)->get();
        $tagCounts = [];

        foreach ($allUserPosts as $post) {
            // Assumindo que o model Post tem casting para array no campo 'tags'
            $tags = $post->tags; 

            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    $tag = strtolower(trim($tag));
                    $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
                }
            }
        }
        
        ksort($tagCounts); // Ordena as tags por nome

        /** 8. Criar paginator */
        $paginator = new LengthAwarePaginator(
            $postsDB,
            $totalDB, // Total já reflete os filtros (se aplicados)
            $limit,
            $page,
            // Mantém os parâmetros 'search' e 'tag' na URL de paginação
            ['path' => url("/user/$id/posts"), 'query' => request()->query()]
        );

        // 9. Retornar a view com todos os dados necessários
        return view('user.index', [
            'posts' => $paginator,
            'user'  => $user,
            'search' => $search,
            'tagCounts' => $tagCounts,
            'tagFilter' => $tagFilter,
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
    // 1. Buscar detalhes do usuário
    $userResponse = Http::get("https://dummyjson.com/users/$id?select=id,firstName,lastName,email,phone,image,birthDate,address")->json();

    // 2. Buscar posts do usuário (Apenas os primeiros 5)
    // Usamos a API diretamente para manter a simplicidade na tela de visualização
    $postsResponse = Http::get("https://dummyjson.com/users/$id/posts?limit=5")->json();
    
    // Total de posts (para o cabeçalho "Posts do Usuário (Total)")
    $totalPosts = $postsResponse['total'] ?? 0;
    
    // Os posts limitados
    $limitedPosts = $postsResponse['posts'] ?? [];
    
    // 3. Retornar a view com 'user', 'posts' e 'totalPosts'
    return view('user.show', [
        'user' => $userResponse,
        // Passa a lista limitada de posts (não o paginator, apenas o array de posts)
        'posts' => $limitedPosts, 
        'totalPosts' => $totalPosts,
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
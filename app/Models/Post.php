<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'tags',
        'likes',
        'dislikes',
        'views',
        'user_id'
    ];

    // Transformar JSON em array automaticamente
    protected $casts = [
        'tags' => 'array',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

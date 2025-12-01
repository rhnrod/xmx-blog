<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'firstName',
        'lastName',
        'email',
        'phone',
        'image',
        'birthDate',
        'address',
    ];

    protected $casts = [
        'address' => 'array',
        'birthDate' => 'date',
    ];
}

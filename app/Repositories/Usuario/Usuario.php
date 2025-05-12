<?php

namespace App\Repositories\Usuario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const LOGIN = 'login';
    public const NOME = 'nome';
    public const SENHA = 'senha';
    public const EMAIL = 'email';

    protected $table = 'usuario';

    public $timestamps = false;

    protected $fillable = [
        self::NOME,
        self::SENHA,
        self::LOGIN,
        self::EMAIL
    ];
}

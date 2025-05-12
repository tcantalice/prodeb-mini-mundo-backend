<?php


namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, Authenticatable
{
    use SoftDeletes;

    public const SENHA = 'senha';
    public const ID = 'id';
    public const LOGIN = 'login';

    protected $table = 'usuario';

    protected $fillable = [
        self::SENHA,
        self::LOGIN,
    ];

    public function getJWTIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthIdentifierName()
    {
        return self::LOGIN;
    }

    public function getAuthPassword()
    {
        return $this->getAttribute($this->getAuthPasswordName());
    }

    public function getRememberToken()
    {
        return '';
    }

    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    public function setRememberToken($value)
    {
        // No implementation needed for this example
    }

    public function getRememberTokenName()
    {
        return '';
    }

    public function getAuthPasswordName()
    {
        return self::SENHA;
    }
}

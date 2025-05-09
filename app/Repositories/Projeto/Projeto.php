<?php

namespace App\Repositories\Projeto;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projeto extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const NOME = 'nome';
    public const DESCRICAO = 'descricao';
    public const ATIVO = 'ativo';
    public const ORCAMENTO_DISPONIVEL = 'orcamento_disponivel';
    public const CRIADO_EM = 'criado_em';
    public const CRIADOR_ID = 'criador_id';

    protected $table = 'projeto';

    public $timestamps = false;

    protected $keyType = 'string';

    protected $primaryKey = 'id';
}

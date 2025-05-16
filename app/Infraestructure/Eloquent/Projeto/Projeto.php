<?php

namespace App\Infraestructure\Eloquent\Projeto;

use App\Infraestructure\Eloquent\Usuario\Usuario;
use Domain\Projeto\CriadorProjeto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projeto extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const UUID = 'uuid';
    public const NOME = 'nome';
    public const DESCRICAO = 'descricao';
    public const ATIVO = 'ativo';
    public const ORCAMENTO_DISPONIVEL = 'orcamento_disponivel';
    public const CRIADO_EM = 'criado_em';
    public const CRIADOR_ID = 'usuario_criador_id';

    public const DOMAIN_REF = self::UUID;
    public const DATABASE_KEY = self::ID;

    protected $table = 'projeto';

    public $timestamps = false;

    protected $primaryKey = self::ID;

    public $fillable = [
        self::NOME,
        self::DESCRICAO,
        self::ATIVO,
        self::ORCAMENTO_DISPONIVEL,
        self::CRIADO_EM,
        self::CRIADOR_ID,
    ];

    protected $casts = [
        self::ATIVO => 'boolean',
        self::CRIADO_EM => 'datetime',
    ];

    public $with = [
        'relationCriador'
    ];

    public function relationCriador()
    {
        return $this->belongsTo(Usuario::class, self::CRIADOR_ID);
    }

    public function setCriador(string $id): void
    {
        $this->relationCriador()->associate(Usuario::where(Usuario::LOGIN, $id)->first());
    }

    public function getCriador(): CriadorProjeto
    {
        return new CriadorProjeto(
            $this->relationCriador->getAttribute(Usuario::LOGIN),
            $this->relationCriador->getAttribute(Usuario::NOME)
        );
    }

    public function toEntity(): \Domain\Projeto\Projeto
    {
        $result = new \Domain\Projeto\Projeto(
            $this->getAttribute(self::NOME),
            $this->getAttribute(self::ATIVO),
            $this->getCriador(),
            $this->getAttribute(self::CRIADO_EM),
            \Domain\Projeto\IdProjeto::restore($this->getAttribute(self::DOMAIN_REF))
        );

        $result->setDescricao($this->getAttribute(self::DESCRICAO));
        $result->setOrcamento($this->getAttribute(self::ORCAMENTO_DISPONIVEL));

        return $result;
    }
}

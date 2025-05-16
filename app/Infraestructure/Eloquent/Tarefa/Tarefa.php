<?php

namespace App\Infraestructure\Eloquent\Tarefa;

use App\Infraestructure\Eloquent\Projeto\Projeto;
use App\Infraestructure\Eloquent\Usuario\Usuario;
use Domain\Tarefa\CriadorTarefa;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    public const ID = 'id';
    public const UUID = 'uuid';
    public const DESCRICAO = 'descricao';
    public const DATA_HORA_INICIO = 'data_hora_inicio';
    public const DATA_HORA_FIM = 'data_hora_fim';
    public const CRIADOR_ID = 'usuario_criador_id';
    public const PROJETO_ID = 'projeto_id';
    public const CRIADO_EM = 'criado_em';

    protected $table = 'tarefa';

    public $timestamps = false;

    protected $keyType = 'int';

    public $incrementing = true;

    protected $primaryKey = self::ID;

    public $fillable = [
        self::DESCRICAO,
        self::DATA_HORA_INICIO,
        self::DATA_HORA_FIM,
        self::CRIADO_EM
    ];

    protected $casts = [
        self::DATA_HORA_INICIO => 'datetime',
        self::DATA_HORA_FIM => 'datetime',
        self::CRIADO_EM => 'datetime'
    ];

    public $with = [
        implode(',', 'relationCriador', Usuario::ID, Usuario::LOGIN, Usuario::NOME),
        implode(',', 'relationProjeto:', Projeto::ID)
    ];

    public function relationCriador()
    {
        return $this->belongsTo(Usuario::class, self::CRIADOR_ID);
    }

    public function relationProjeto()
    {
        return $this->belongsTo(Projeto::class, Projeto::ID);
    }

    public function setCriador(string $id)
    {
        $this->relationCriador()->associate(Usuario::where(Usuario::LOGIN, $id)->first());
    }

    public function setProjeto(string $id)
    {
        $this->relationProjeto()->associate(Projeto::where(Projeto::ID, $id)->first());
    }

    public function getCriador(): CriadorTarefa
    {
        return new CriadorTarefa(
            $this->relationCriador->getAttribute(Usuario::ID),
            $this->relationCriador->getAttribute(Usuario::NOME)
        );
    }

    public function getProjetoRef(): string
    {
        return $this->relationProjeto->getAttribute(Projeto::ID);
    }
}

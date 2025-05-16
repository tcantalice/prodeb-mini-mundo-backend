<?php

namespace App\Infraestructure\Eloquent\Tarefa;

use App\Infraestructure\Eloquent\Projeto\Projeto;
use App\Infraestructure\Eloquent\Usuario\Usuario;
use Domain\Tarefa\CriadorTarefa;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
    public const TAREFA_PREDECESSORA_ID = 'tarefa_predecessora_id';
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
        implode(',', 'relationProjeto:', Projeto::ID),
        implode(',', 'relationTarefaPredecessora: ', Tarefa::ID, Tarefa::UUID)
    ];

    public function relationCriador()
    {
        return $this->belongsTo(Usuario::class, self::CRIADOR_ID);
    }

    public function relationProjeto()
    {
        return $this->belongsTo(Projeto::class, Projeto::ID);
    }

    public function relationTarefaPredecessora()
    {
        return $this->belongsTo(Tarefa::class, self::TAREFA_PREDECESSORA_ID);
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

    public function getTarefaPredecessoraRef(): ?string
    {
        return $this->relationTarefaPredecessora !== null
            ? $this->relationTarefaPredecessora->getAttribute(self::UUID)
            : null;
    }

    public function toEntity(): \Domain\Tarefa\Tarefa
    {
        $result = new \Domain\Tarefa\Tarefa(
            $this->getProjetoRef(),
            $this->relationCriador->getAttribute(self::DESCRICAO),
            $this->getCriador(),
            $this->getAttribute(self::CRIADO_EM),
            $this->getAttribute(self::UUID),
            $this->getDependenteRef()
        );

        $result->setDataInicio($this->getAttribute(self::DATA_HORA_INICIO));
        $result->setDataFim($this->getAttribute(self::DATA_HORA_FIM));

        return $result;
    }

    #[Scope]
    protected function byProjeto(Builder $query, string $projetoRef)
    {
        $query->whereRelation('relationProjeto', Projeto::ID, $projetoRef);
    }

    #[Scope]
    protected function byTarefaDependente(Builder $query, string $tarefaRef)
    {
        $query->whereRelation('relationDependente', self::UUID, $tarefaRef);
    }
}

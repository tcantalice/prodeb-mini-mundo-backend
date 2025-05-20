<?php

namespace App\Infraestructure\Eloquent\Tarefa;

use App\Infraestructure\Eloquent\Projeto\Projeto;
use App\Infraestructure\Eloquent\Usuario\Usuario;
use Domain\Tarefa\CriadorTarefa;
use Domain\Tarefa\IdTarefa;
use Domain\Tarefa\TarefaPredecessora;
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

    public const DOMAIN_REF = self::UUID;
    public const DATABASE_KEY = self::ID;

    protected $table = 'tarefa';

    public $timestamps = false;

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

    protected $with = [
        'relationCriador:'.Usuario::ID.','.Usuario::LOGIN.','.Usuario::NOME,
        'relationProjeto:'.Projeto::DATABASE_KEY.','.Projeto::DOMAIN_REF,
        'relationTarefaPredecessora:'.Tarefa::DATABASE_KEY.','.Tarefa::DOMAIN_REF.','.Tarefa::DATA_HORA_FIM
    ];

    public function relationCriador()
    {
        return $this->belongsTo(Usuario::class, self::CRIADOR_ID);
    }

    public function relationProjeto()
    {
        return $this->belongsTo(Projeto::class, self::PROJETO_ID);
    }

    public function relationTarefaPredecessora()
    {
        return $this->belongsTo(self::class, self::TAREFA_PREDECESSORA_ID);
    }

    public function setCriador(string $id)
    {
        $this->relationCriador()->associate(Usuario::where(Usuario::LOGIN, $id)->first());
    }

    public function setProjeto(string $id)
    {
        $this->relationProjeto()->associate(Projeto::where(Projeto::DOMAIN_REF, $id)->first());
    }

    public function setTarefaPredecessora(string $id)
    {
        $this->relationTarefaPredecessora()
            ->associate(self::where(self::DOMAIN_REF, $id)->first());
    }

    protected function getCriador(): CriadorTarefa
    {
        return new CriadorTarefa(
            $this->relationCriador->getAttribute(Usuario::ID),
            $this->relationCriador->getAttribute(Usuario::NOME)
        );
    }

    protected function getTarefaPredecessora(): ?TarefaPredecessora
    {
        return $this->relationTarefaPredecessora !== null
            ? new TarefaPredecessora(
                $this->relationTarefaPredecessora->getAttribute(Tarefa::DOMAIN_REF),
                $this->relationTarefaPredecessora->getAttribute(Tarefa::DATA_HORA_FIM)
            ) : null;
    }

    public function getProjetoRef(): string
    {
        return $this->relationProjeto->getAttribute(Projeto::DOMAIN_REF);
    }

    public function toEntity(): \Domain\Tarefa\Tarefa
    {
        $result = new \Domain\Tarefa\Tarefa(
            $this->getProjetoRef(),
            $this->getAttribute(self::DESCRICAO),
            $this->getCriador(),
            $this->getAttribute(self::CRIADO_EM),
            IdTarefa::restore($this->getAttribute(self::UUID)),
            $this->getTarefaPredecessora()
        );

        $result->setDataInicio($this->getAttribute(self::DATA_HORA_INICIO));
        $result->setDataFim($this->getAttribute(self::DATA_HORA_FIM));

        return $result;
    }

    #[Scope]
    protected function byProjeto(Builder $query, string $projetoRef)
    {
        $query->whereRelation('relationProjeto', Projeto::DOMAIN_REF, $projetoRef);
    }

    #[Scope]
    protected function byTarefaPredecessora(Builder $query, string $tarefaRef)
    {
        $query->whereRelation('relationTarefaPredecessora', self::DOMAIN_REF, $tarefaRef);
    }
}

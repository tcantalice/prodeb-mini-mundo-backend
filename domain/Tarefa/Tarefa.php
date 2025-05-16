<?php

namespace Domain\Tarefa;

use DateTime;
use DateTimeInterface;
use Domain\Tarefa\Exceptions\TarefaJaFinalizadaException;
use Domain\Tarefa\Exceptions\TarefaJaIniciadaException;

class Tarefa
{
    private ?IdTarefa $id;
    private ?DateTimeInterface $iniciadoEm;
    private ?DateTimeInterface $finalizadoEm;
    private ?TarefaDependenteList $tarefasDependentes;

    private ?TarefaPredecessora $dependeDe;

    public function __construct(
        private string $projetoRef,
        private string $descricao,
        private CriadorTarefa $criadoPor,
        private DateTimeInterface $criadoEm,
        ?IdTarefa $id,
        ?TarefaPredecessora $dependeDe = null,
    ) {
        $this->id = $id === null ? IdTarefa::generate() : $id;
        $this->dependeDe = $dependeDe;

        $this->iniciadoEm = null;
        $this->finalizadoEm = null;
        $this->tarefasDependentes = null;
    }

    public function setDataInicio(DateTimeInterface $data)
    {
        if ($this->iniciadoEm !== null) {
            throw new TarefaJaIniciadaException($this);
        }

        $this->iniciadoEm = $data;
    }

    public function setDataFim(DateTimeInterface $data)
    {
        if ($this->finalizadoEm !== null) {
            throw new TarefaJaFinalizadaException($this);
        }

        $this->finalizadoEm = $data;
    }

    public function setTarefasDependentes(TarefaDependenteList $tarefas)
    {
        $this->tarefasDependentes = $tarefas;
    }

    public function getID(): IdTarefa
    {
        return $this->id;
    }

    public function iniciar()
    {
        $this->setDataInicio(new DateTime());
    }

    public function finalizar()
    {
        $this->setDataFim(new DateTime());
    }

    public function isConcluida(): bool
    {
        return $this->finalizadoEm !== null;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getProjetoRef(): string
    {
        return $this->projetoRef;
    }

    public function iniciadoEm(): ?DateTimeInterface
    {
        return $this->iniciadoEm;
    }

    public function finalizadoEm(): ?DateTimeInterface
    {
        return $this->finalizadoEm;
    }

    public function tarefasDependentes(): ?TarefaDependenteList
    {
        return $this->tarefasDependentes;
    }

    public function dependeDe(): ?TarefaPredecessora
    {
        return $this->dependeDe;
    }

    public function criadoEm(): DateTimeInterface
    {
        return $this->criadoEm;
    }

    public function criadoPor(): CriadorTarefa
    {
        return $this->criadoPor;
    }
}

<?php

namespace Domain\Tarefa;

use DateTime;
use DateTimeInterface;
use Domain\Tarefa\Exceptions\DependenciaNaoConcluidaException;
use Domain\Tarefa\Exceptions\TarefaJaFinalizadaException;
use Domain\Tarefa\Exceptions\TarefaJaIniciadaException;
use Domain\Tarefa\Exceptions\TarefaNaoIniciadaException;

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

    public function setDataInicio(?DateTimeInterface $data)
    {
        if ($this->iniciadoEm !== null) {
            throw new TarefaJaIniciadaException($this);
        }

        if ($data !== null) {
            $this->iniciadoEm = $data;
        }
    }

    public function setDataFim(?DateTimeInterface $data)
    {
        if ($this->finalizadoEm !== null) {
            throw new TarefaJaFinalizadaException($this);
        }

        if ($data !== null) {
            $this->finalizadoEm = $data;
        }
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
        if ($this->hasDependencia() && !$this->dependeDe()->isConcluida()) {
            throw new DependenciaNaoConcluidaException($this);
        }

        $this->setDataInicio(new DateTime());
    }

    public function finalizar()
    {
        if (!$this->isIniciada()) {
            throw new TarefaNaoIniciadaException($this);
        }

        $this->setDataFim(new DateTime());
    }

    public function isIniciada(): bool
    {
        return $this->iniciadoEm !== null;
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

    public function hasDependencia(): bool
    {
        return $this->dependeDe !== null;
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $other->getID()->equals($this->getID());
    }

    public function addDependencia(Tarefa $tarefa)
    {
        if ($this->equals($tarefa)) {
            throw new \Exception('Uma tarefa não pode ser dependente dela mesma');
        }

        if ($this->hasDependencia()) {
            throw new \Exception('A tarefa já possui dependência');
        }

        $this->dependeDe = new TarefaPredecessora(
            $tarefa->getID(),
            $tarefa->finalizadoEm()
        );
    }

    public function removeDependencia()
    {
        $this->dependeDe = null;
    }
}

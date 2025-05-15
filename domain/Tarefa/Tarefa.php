<?php

namespace Domain\Tarefa;

use DateTime;
use DateTimeInterface;

class Tarefa
{
    private ?IdTarefa $id;
    private ?DateTimeInterface $iniciadoEm;
    private ?DateTimeInterface $finalizadoEm;
    private ?TarefaDependenteList $tarefasDependentes;

    private ?IdTarefa $dependenciaRef;

    public function __construct(
        private string $projetoRef,
        private string $descricao,
        private string $criadoPor,
        private DateTimeInterface $criadoEm,
        ?IdTarefa $id,
        ?IdTarefa $dependenciaRef = null,
    )
    {
        $this->id = $id === null ? IdTarefa::generate() : $id;
        $this->dependenciaRef = $dependenciaRef;

        $this->iniciadoEm = null;
        $this->finalizadoEm = null;
        $this->tarefasDependentes = null;
    }

    public function setDataInicio(DateTimeInterface $data)
    {
        if ($this->iniciadoEm !== null) {
            //
        }

        $this->iniciadoEm = $data;
    }

    public function setDataFim(DateTimeInterface $data)
    {
        if ($this->finalizadoEm !== null) {
            //
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

    public function getDependenciaRef(): ?IdTarefa
    {
        return $this->dependenciaRef;
    }

}

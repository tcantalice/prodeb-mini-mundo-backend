<?php

namespace Domain\Tarefa;

use DateTime;
use DateTimeInterface;

class Tarefa
{
    private ?DateTimeInterface $iniciadoEm;
    private ?DateTimeInterface $finalizadoEm;

    public function __construct(
        private String $projetoRef,
        private String $descricao,
        private ?String $tarefaDependente = null
    )
    {
        $this->iniciadoEm = null;
        $this->finalizadoEm = null;
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

    public function getDescricao(): String
    {
        return $this->descricao;
    }

    public function getProjetoRef(): String
    {
        return $this->projetoRef;
    }

    public function iniciadoEm(): ?DateTimeInterface
    {
        return $this->iniciadoEm;
    }

    public function finalizadoEm():? DateTimeInterface
    {
        return $this->finalizadoEm;
    }
}

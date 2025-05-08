<?php

namespace Domain\Projeto;

use DateTimeInterface;

class Projeto
{
    private String $descricao;
    private float $orcamento;

    public function __construct(
        private String $nome,
        private bool $ativo,
        private String $criadoPor,
        private DateTimeInterface $criadoEm,
        private IdProjeto $id
    ) {

    }

    public function getID(): IdProjeto
    {
        return $this->id;
    }

    public function getNome(): String
    {
        return $this->nome;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function getDescricao(): String
    {
        return $this->descricao;
    }

    public function getOrcamento(): float
    {
        return $this->orcamento;
    }
}

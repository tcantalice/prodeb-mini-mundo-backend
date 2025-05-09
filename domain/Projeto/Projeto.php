<?php

namespace Domain\Projeto;

use DateTimeInterface;

class Projeto
{
    private IdProjeto $id;
    private ?String $descricao;
    private ?float $orcamento;

    public function __construct(
        private String $nome,
        private bool $ativo,
        private CriadorProjeto $criadoPor,
        private DateTimeInterface $criadoEm,
        ?IdProjeto $id
    ) {
        $this->id = ($id === null) ? IdProjeto::generate() : $id;
        $this->descricao = null;
        $this->orcamento = null;
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

    public function getDescricao(): ?String
    {
        return $this->descricao;
    }

    public function getOrcamento(): ?float
    {
        return $this->orcamento;
    }

    public function criadoEm(): DateTimeInterface
    {
        return $this->criadoEm;
    }

    public function criadoPor(): CriadorProjeto
    {
        return $this->criadoPor;
    }

    public function setNome(String $nome): void
    {
        $this->nome = $nome;
    }

    public function setDescricao(?String $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function setOrcamento(?float $orcamento): void
    {
        // TODO: Implementar validação de orçamento
        $this->orcamento = $orcamento;
    }

    public function ativar()
    {
        $this->ativo = true;
    }

    public function inativar()
    {
        $this->ativo = false;
    }
}

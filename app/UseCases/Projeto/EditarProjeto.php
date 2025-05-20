<?php

namespace App\UseCases\Projeto;

use Domain\Projeto\Contracts\ProjetoRepository;
use Domain\Projeto\Exceptions\ProjetoNaoEncontradoException;
use Psr\Log\LoggerInterface;

class EditarProjeto
{
    public function __construct(
        private LoggerInterface $logger,
        private ProjetoRepository $projetoRepo
    ) {
        //
    }

    public function execute(EditarProjetoInput $input)
    {
        $projeto = $this->projetoRepo->find($input->id);

        if ($projeto === null) {
            throw new ProjetoNaoEncontradoException($input->id);
        }

        if ($input->nome !== null) {
            $projeto->setNome($input->nome);
        }

        if ($input->descricao !== null) {
            $projeto->setDescricao($input->descricao);
        }

        if ($input->orcamento !== null) {
            $projeto->setOrcamento($input->orcamento);
        }

        if ($input->ativo !== null) {
            $input->ativo ? $projeto->ativar() : $projeto->inativar();
        }

        try {
            $this->projetoRepo->save($projeto);
        } catch (\Throwable $th) {
            throw new \Exception('Erro ao editar o projeto: ' . $th->getMessage());
        }
    }
}

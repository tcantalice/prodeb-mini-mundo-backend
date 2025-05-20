<?php

namespace App\UseCases\Projeto;

use Domain\Projeto\Contracts\ProjetoRepository;
use Domain\Projeto\CriadorProjeto;
use Domain\Projeto\Exceptions\NomeJaExisteException;
use Domain\Projeto\Projeto;
use Psr\Log\LoggerInterface;

class CriarProjeto
{
    public function __construct(
        private LoggerInterface $logger,
        private ProjetoRepository $projetoRepo
    ) {
        //
    }

    public function execute(CriarProjetoInput $input)
    {
        if ($this->projetoRepo->existsByNome($input->nome)) {
            throw new NomeJaExisteException();
        }

        $projeto = new Projeto(
            $input->nome,
            true,
            new CriadorProjeto($input->refUsuarioCriador, ''),
            date_create(),
            null
        );

        if ($input->descricao !== null) {
            $projeto->setDescricao($input->descricao);
        }

        if ($input->orcamento !== null) {
            $projeto->setOrcamento($input->orcamento);
        }

        try {
            $this->projetoRepo->save($projeto);
        } catch(\Throwable $th) {
            $this->logger->error('Um erro inesperado ocorreu: ' . $th->getMessage());
        }
    }
}

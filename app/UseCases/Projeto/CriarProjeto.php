<?php

namespace App\UseCases\Projeto;

use DateTimeInterface;
use Domain\Projeto\Contracts\ProjetoRepository;
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

    public function execute(CriarProjetoDTO $input)
    {
        if ($this->projetoRepo->existsByNome($input->nome)) {
            throw new NomeJaExisteException();
        }

        $projeto = new Projeto(
            $input->nome,
            true,
            '', // TODO: Adicionar referência ao usuário autenticado
            date_create(),
            null
        );

        try {
            $this->projetoRepo->save($projeto);
        } catch(\Throwable $th) {
            $this->logger->error('Um erro inesperado ocorreu');
        }
    }
}

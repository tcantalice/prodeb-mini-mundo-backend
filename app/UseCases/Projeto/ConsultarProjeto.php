<?php

namespace App\UseCases\Projeto;

use Domain\Projeto\Contracts\ProjetoRepository;
use Psr\Log\LoggerInterface;

class ConsultarProjeto
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ProjetoRepository $projetoRepo
    ) {
        //
    }

    public function execute(string $id): ProjetoDTO
    {
        $this->logger->info('Consultando projeto com ID: ' . $id);

        $projeto = $this->projetoRepo->find($id);

        if ($projeto === null) {
            throw new \Domain\Projeto\Exceptions\ProjetoNaoEncontradoException($id);
        }

        return new ProjetoDTO(
            $projeto->getID()->valor,
            $projeto->getNome(),
            $projeto->getDescricao(),
            $projeto->getOrcamento(),
            $projeto->isAtivo(),
            new CriadorProjetoDTO(
                $projeto->criadoPor()->getRef(),
                $projeto->criadoPor()->getNome()
            ),
            $projeto->criadoEm(),
        );
    }
}

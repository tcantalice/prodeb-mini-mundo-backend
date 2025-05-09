<?php

namespace App\UseCases\Projeto;

use Domain\Projeto\Contracts\ProjetoRepository;
use Domain\Projeto\Projeto;

class ListarProjetos
{
    public function __construct(private readonly ProjetoRepository $repository)
    {
    }

    public function execute(): array
    {
        $result = $this->repository->findAll();

        return array_map(function (Projeto $entity) {
            return new ProjetoDTO(
                $entity->getID()->valor,
                $entity->getNome(),
                $entity->getDescricao(),
                $entity->getOrcamento(),
                $entity->isAtivo(),
                new CriadorProjetoDTO(
                    $entity->criadoPor()->getRef(),
                    $entity->criadoPor()->getNome(),
                ),
                $entity->criadoEm()
            );
        }, $result);
    }
}

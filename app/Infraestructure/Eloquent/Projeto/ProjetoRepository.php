<?php

namespace App\Infraestructure\Eloquent\Projeto;

use App\Infraestructure\Eloquent\Projeto\Projeto as Model;
use Domain\Projeto\Projeto;
use Domain\Projeto\Contracts\ProjetoRepository as Contract;
use Domain\Projeto\ProjetoFilter;
use Psr\Log\LoggerInterface;

class ProjetoRepository implements Contract
{
    public function __construct(private LoggerInterface $logger)
    {
        //
    }

    public function save(Projeto $projeto): void
    {
        if ($projeto->getID()->isNovo()) {
            $model = new Model([
                Model::NOME => $projeto->getNome(),
                Model::DESCRICAO => $projeto->getDescricao(),
                Model::ATIVO => $projeto->isAtivo(),
                Model::ORCAMENTO_DISPONIVEL => $projeto->getOrcamento(),
                Model::CRIADO_EM => $projeto->criadoEm(),
            ]);

            $model->setAttribute(Model::UUID, $projeto->getID()->valor);
            $model->setCriador($projeto->criadoPor()->getRef());
        } else {
            $model = Model::find($projeto->getID()->valor);

            $model->setAttribute(Model::NOME, $projeto->getNome());
            $model->setAttribute(Model::DESCRICAO, $projeto->getDescricao());
            $model->setAttribute(Model::ORCAMENTO_DISPONIVEL, $projeto->getOrcamento());
            $model->setAttribute(Model::ATIVO, $projeto->isAtivo());
        }

        try {
            $model->save();
        } catch(\Throwable $th) {
            $this->logger->error(
                "Ocorreu uma falha durante a execução da operação no banco de dados: {$th->getMessage()}"
            );
        }
    }

    public function find(string $id): ?Projeto
    {
        /**
         * @var Model $queryResult
         */
        $queryResult = Model::where(Model::DOMAIN_REF, $id)->first();

        return $queryResult !== null ? $queryResult->toEntity() : null;
    }

    public function findByNome(string $nome): ?Projeto
    {
        /**
         * @var Model $queryResult
         */
        $queryResult = Model::where(Model::NOME, $nome)->first();

        return $queryResult !== null ? $queryResult->toEntity() : null;
    }

    public function existsByNome(string $nome): bool
    {
        return Model::where(Model::NOME, $nome)->exists();
    }

    public function remove(Projeto $projeto): void
    {
        try {
            Model::where(Model::ID, $projeto->getID()->valor)->delete();
        } catch(\Throwable $th) {
            $this->logger->error(
                "Ocorreu uma falha durante a execução da operação no banco de dados: {$th->getMessage()}"
            );

            throw $th;
        }
    }

    public function findAll(?ProjetoFilter $filtro = null): array
    {
        return Model::all()->map(function ($item) {
            return $item->toEntity();
        })->toArray();
    }
}

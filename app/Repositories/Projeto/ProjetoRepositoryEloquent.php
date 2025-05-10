<?php

namespace App\Repositories\Projeto;

use App\Repositories\Projeto\Projeto as Model;
use Domain\Projeto\IdProjeto;
use Domain\Projeto\Projeto;
use Domain\Projeto\Contracts\ProjetoRepository as Contract;
use Domain\Projeto\CriadorProjeto;
use Domain\Projeto\ProjetoFilter;
use Psr\Log\LoggerInterface;

class ProjetoRepositoryEloquent implements Contract
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

            $model->setAttribute(Model::ID, $projeto->getID()->valor);
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
        $queryResult = Model::find($id);

        return $queryResult !== null ? new Projeto(
            $queryResult->getAttribute(Model::NOME),
            $queryResult->getAttribute(Model::ATIVO),
            new CriadorProjeto('', ''),
            $queryResult->getAttribute(Model::CRIADO_EM),
            IdProjeto::restore($queryResult->getKey())
        ) : null;
    }

    public function findByNome(string $nome): ?Projeto
    {
        /**
         * @var Model $queryResult
         */
        $queryResult = Model::where(Model::NOME, $nome)->first();

        return $queryResult !== null ? new Projeto(
            $queryResult->getAttribute(Model::NOME),
            $queryResult->getAttribute(Model::ATIVO),
            new CriadorProjeto('', ''),
            $queryResult->getAttribute(Model::CRIADO_EM),
            IdProjeto::restore($queryResult->getKey())
        ) : null;
    }

    public function existsByNome(string $nome): bool
    {
        // TODO: Checar regra quanto ao nível de semelhança
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
            $projeto = new Projeto(
                $item->getAttribute(Model::NOME),
                $item->getAttribute(Model::ATIVO),
                new CriadorProjeto('', ''),
                $item->getAttribute(Model::CRIADO_EM),
                IdProjeto::restore($item->getKey())
            );

            $projeto->setDescricao($item->getAttribute(Model::DESCRICAO));
            $projeto->setOrcamento($item->getAttribute(Model::ORCAMENTO_DISPONIVEL));

            return $projeto;
        })->toArray();
    }
}

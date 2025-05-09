<?php

namespace App\Repositories\Projeto;

use App\Repositories\Projeto\Projeto as Model;
use Domain\Projeto\IdProjeto;
use Domain\Projeto\Projeto;
use Domain\Projeto\Contracts\ProjetoRepository as Contract;
use Domain\Projeto\Exceptions\ProjetoNaoEncontradoException;
use Psr\Log\LoggerInterface;

class ProjetoRepositoryEloquent implements Contract
{
    public function __construct(private LoggerInterface $logger)
    {
        //
    }

    public function save(Projeto $projeto): void
    {
        // TODO: Implementar esquema de update ou insert
        $model = new Model([
            Model::ID => $projeto->getID()->valor,
            Model::NOME => $projeto->getNome(),
            Model::DESCRICAO => $projeto->getDescricao(),
            Model::ATIVO => $projeto->isAtivo(),
            Model::ORCAMENTO_DISPONIVEL => $projeto->getOrcamento(),
            Model::CRIADO_EM => $projeto->criadoEm(),
        ]);

        try {
            $model->save();
        } catch(\Throwable $th) {
            $this->logger->error(
                "Ocorreu uma falha durante a execução da operação no banco de dados: {$th->getMessage()}"
            );
        }
    }

    public function find(string $id): Projeto
    {
        $queryResult = Model::find($id);

        if ($queryResult === null) {
            throw new ProjetoNaoEncontradoException();
        }

        return new Projeto(
            $queryResult->getAttribute(Model::NOME),
            $queryResult->getAttribute(Model::ATIVO),
            '',
            $queryResult->getAttribute(Model::CRIADO_EM),
            new IdProjeto($queryResult->getKey())
        );
    }

    public function findByNome(string $nome): Projeto
    {
        $queryResult = Model::where(Model::NOME, $nome)->first();

        if ($queryResult === null) {
            throw new ProjetoNaoEncontradoException();
        }

        return new Projeto(
            $queryResult->getAttribute(Model::NOME),
            $queryResult->getAttribute(Model::ATIVO),
            '',
            $queryResult->getAttribute(Model::CRIADO_EM),
            new IdProjeto($queryResult->getKey())
        );
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
}

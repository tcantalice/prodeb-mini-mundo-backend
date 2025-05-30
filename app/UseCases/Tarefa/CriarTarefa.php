<?php

namespace App\UseCases\Tarefa;

use App\Exceptions\FalhaInternaException;
use Domain\Tarefa\Contracts\TarefaRepository;
use Domain\Tarefa\CriadorTarefa;
use Domain\Tarefa\Exceptions\TarefaNaoEncontradaException;
use Domain\Tarefa\Tarefa;
use Domain\Tarefa\TarefaPredecessora;
use Psr\Log\LoggerInterface;

class CriarTarefa
{
    public function __construct(
        private LoggerInterface $logger,
        private TarefaRepository $tarefaRepository
    ) {
        //
    }

    public function execute(CriarTarefaInput $input): TarefaOutput
    {
        $tarefaPredecessora = null;

        if ($input->refTarefaPredecessora) {
            $tarefaPredecessoraEntity = $this->tarefaRepository->find($input->refTarefaPredecessora)
                ?? throw new TarefaNaoEncontradaException($input->refTarefaPredecessora);

            $tarefaPredecessora = new TarefaPredecessora(
                $tarefaPredecessoraEntity->getID(),
                $tarefaPredecessoraEntity->finalizadoEm()
            );
        }

        $tarefa = new Tarefa(
            $input->refProjeto,
            $input->descricao,
            new CriadorTarefa($input->refUsuarioCriador, ''),
            date_create(),
            null,
            $tarefaPredecessora
        );

        try {
            $this->tarefaRepository->save($tarefa);
        } catch(\Throwable $th) {
            $this->logger->error($th->getMessage(), [
                "projeto" => $input->refProjeto,
                "use_case" => self::class
            ]);

            throw new FalhaInternaException($th);
        }

        return new TarefaOutput(
            $tarefa->getID()->valor,
            $tarefa->getDescricao(),
            $tarefa->getProjetoRef(),
            $tarefa->criadoEm(),
            $tarefa->iniciadoEm(),
            $tarefa->finalizadoEm(),
            $tarefa->dependeDe()
        );
    }
}

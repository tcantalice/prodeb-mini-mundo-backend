<?php

namespace App\UseCases\Tarefa;

use Domain\Projeto\Contracts\ProjetoRepository;
use Domain\Tarefa\Contracts\TarefaRepository;
use Domain\Tarefa\CriadorTarefa;
use Domain\Tarefa\Exceptions\TarefaNaoEncontradaException;
use Domain\Tarefa\IdTarefa;
use Domain\Tarefa\Tarefa;
use Domain\Tarefa\TarefaPredecessora;
use Domain\Usuario\Contracts\UsuarioRepository;
use Psr\Log\LoggerInterface;

class CriarTarefa
{
    public function __construct(
        private LoggerInterface $logger,
        private TarefaRepository $tarefaRepository
    ) {
        //
    }

    public function execute(CriarTarefaDTO $input)
    {
        $tarefaPredecessora = null;

        if ($input->refTarefaPredecessora !== null) {
            $tarefaPredecessoraEntity = $this->tarefaRepository->find($input->refTarefaPredecessora);

            if ($tarefaPredecessoraEntity === null) {
                throw new TarefaNaoEncontradaException($input->refTarefaPredecessora);
            }

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
            $this->logger->error("Um erro inesperado ocorreu: {$th->getMessage()}");
            // TODO Lançar exceção mais específica
        }
    }
}

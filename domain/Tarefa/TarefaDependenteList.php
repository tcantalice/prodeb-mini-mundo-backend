<?php

namespace Domain\Tarefa;

use InvalidArgumentException;

class TarefaDependenteList
{

    private readonly IdTarefa $dependenciaRef;

    /**
     * @var Tarefa[]
     */
    private readonly array $tarefas;

    public function __construct(IdTarefa $dependenciaRef, array $tarefas)
    {
        $this->dependenciaRef = $dependenciaRef;
        $this->tarefas = [];

        foreach ($tarefas as $tarefa) {
            if (!$tarefa instanceof Tarefa) {
                throw new InvalidArgumentException(
                    'Elementos no array $tarefas devem ser instâncias de Tarefa'
                );
            }

            if ($tarefa->hasDependencia() && !$tarefa->dependeDe()->getRef()->equals($dependenciaRef)) {
                throw new InvalidArgumentException(
                    "A tarefa {$tarefa->getID()} não depende de {$dependenciaRef}"
                );
            }

            $this->tarefas[] = $tarefa;
        }
    }

    /**
     * @return Tarefa[]
     */
    public function getTarefas(): array
    {
        return $this->tarefas;
    }

    public function isEmpty()
    {
        return empty($this->getTarefas());
    }
}

<?php

namespace App\Action\Task;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ToggleTaskHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(ToggleTask $command): Task
    {
        $task = $this->taskRepository->find($command->getTaskId());

        if (null === $task) {
            throw new ResourceNotFoundException(sprintf(
                'Task with id "%s" not found',
                $command->getTaskId()->toString()
            ));
        }

        $task->toggle($command->isDone());
        $this->entityManager->flush();

        return $task;
    }
}
<?php


namespace App\Action\Task;


use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class DeleteTaskHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;

    public function __construct(EntityManagerInterface $entityManager, TaskRepository $taskRepository)
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(DeleteTask $command): void
    {
        $task = $this->taskRepository->find($command->getTaskId());

        if (null === $task) {
            throw new ResourceNotFoundException('Task not found');
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }
}
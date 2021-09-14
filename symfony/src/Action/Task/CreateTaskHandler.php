<?php

namespace App\Action\Task;

use App\Entity\Task;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CreateTaskHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateTask $command): Task
    {
        $user = $this->userRepository->find($command->getUserId());

        if (null === $user) {
            throw new ResourceNotFoundException(sprintf(
                'User with id "%s" not found',
                $command->getUserId()->toString()
            ));
        }

        $task = new Task($user, $command->getDescription(), $command->isDone());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}
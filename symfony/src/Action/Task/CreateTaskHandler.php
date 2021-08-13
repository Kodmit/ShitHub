<?php

namespace App\Action\Task;

use App\Entity\Daily;
use App\Entity\Task;
use App\Repository\DailyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CreateTaskHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private DailyRepository $dailyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        DailyRepository $dailyRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->dailyRepository = $dailyRepository;
    }

    public function __invoke(CreateTask $command): Task
    {
        $daily = $this->dailyRepository->find($command->getDailyId());

        if (null === $daily) {
            throw new ResourceNotFoundException(sprintf(
                'Daily with id "%s" not found',
                $command->getUserId()->toString()
            ));
        }

        $user = $this->userRepository->find($command->getUserId());

        if (null === $user) {
            throw new ResourceNotFoundException(sprintf(
                'User with id "%s" not found',
                $command->getUserId()->toString()
            ));
        }

        $task = new Task($daily, $user, $command->getDescription(), $command->isDone());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}
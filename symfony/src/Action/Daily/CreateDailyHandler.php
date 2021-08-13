<?php

namespace App\Action\Daily;

use App\Entity\Daily;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CreateDailyHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateDaily $command): Daily
    {
        $user = $this->userRepository->find($command->getUserId());

        if (null === $user) {
            throw new ResourceNotFoundException(sprintf(
                'User with id "%s" not found',
                $command->getUserId()->toString()
            ));
        }

        $daily = new Daily($user, $command->getDescription());

        $this->entityManager->persist($daily);
        $this->entityManager->flush();

        return $daily;
    }
}
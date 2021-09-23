<?php

namespace App\Action\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class DeleteUserHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function __invoke(DeleteUser $command): void
    {
        $user = $this->userRepository->find($command->getUserId());

        if (null === $user) {
            throw new ResourceNotFoundException('User not found');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
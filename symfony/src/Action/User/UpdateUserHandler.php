<?php

namespace App\Action\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UpdateUserHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdateUser $command): User
    {
        $user = $this->userRepository->find($command->getUserId());

        if (null === $user) {
            throw new ResourceNotFoundException('User not found');
        }

        $user->update($command->getUsername());
        $this->entityManager->flush();

        return $user;
    }
}
<?php

namespace App\Controller;

use App\Action\User\CreateUser;
use App\Action\User\DeleteUser;
use App\Action\User\UpdateUser;
use App\Repository\UserRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private MessageBusInterface $commandBus;

    public function __construct(UserRepository $userRepository, MessageBusInterface $commandBus)
    {
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function getUsers(): JsonResponse
    {
        return $this->json(
            $this->userRepository->findAll(),
            JsonResponse::HTTP_OK,
            [],
            ['groups' => 'user']
        );
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function updateUser(Request $request, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $envelope = $this->commandBus->dispatch(new UpdateUser(
            $id,
            $data['username']
        ));

        $user = $envelope->last(HandledStamp::class)->getResult();

        return $this->json(
            $user,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => 'user']
        );
    }

    /**
     * @Route("/users/{id}", methods={"GET"})
     */
    public function getUsersById(string $id): JsonResponse
    {
        if (false === Uuid::isValid($id)) {
            return new JsonResponse('Invalid UUID', JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->find($id);

        if (null === $user) {
            return new JsonResponse('User not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json(
            $user,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => 'user']
        );
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function deleteUser(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteUser($id));

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $envelope = $this->commandBus->dispatch(new CreateUser(
            $data['username'],
            $data['password']
        ));

        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);

        return $this->json(
            $handledStamp->getResult(),
            JsonResponse::HTTP_CREATED,
            [],
            ['groups' => 'user']
        );
    }
}

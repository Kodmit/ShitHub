<?php

namespace App\Controller;

use App\Action\Task\CreateTask;
use App\Action\Task\DeleteTask;
use App\Action\Task\ToggleTask;
use App\Action\Task\UpdateTask;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private TaskRepository $taskRepository;
    private MessageBusInterface $commandBus;

    public function __construct(TaskRepository $taskRepository, MessageBusInterface $commandBus)
    {
        $this->taskRepository = $taskRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/tasks", methods={"GET"})
     */
    public function getTasks(Request $request): JsonResponse
    {
        $done = $request->query->get('done', false);
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        if (null !== $startDate) {
            try {
                $startDate = new DateTimeImmutable($startDate);
            } catch (\Exception $e) {
                return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        if (null !== $endDate) {
            try {
                $endDate = new DateTimeImmutable($endDate);
            } catch (\Exception $e) {
                return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        $tasks = $this->taskRepository->getTasksAndFilter($done, $startDate, $endDate);

        return $this->json(
            $tasks,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['task', 'user']]
        );
    }

    /**
     * @Route("/users/{userId}/tasks", methods={"GET"})
     */
    public function getTasksForUser(string $userId): JsonResponse
    {
        if (false === Uuid::isValid($userId)) {
            return new JsonResponse('Invalid UUID');
        }

        return $this->json(
            $this->taskRepository->findBy(['user' => $userId], ['createdAt' => 'DESC']),
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['task', 'user']]
        );
    }

    /**
     * @Route("/tasks", methods={"POST"})
     */
    public function createTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $envelope = $this->commandBus->dispatch(new CreateTask(
            $data['userId'],
            $data['description'],
            $data['done'],
        ));

        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);

        return $this->json(
            $handledStamp->getResult(),
            JsonResponse::HTTP_CREATED,
            [],
            ['groups' => ['task', 'user']]
        );
    }

    /**
     * @Route("/tasks/{id}", methods={"PATCH"})
     */
    public function patchTask(Request $request, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (true === isset($data['done'])) {
            $envelope = $this->commandBus->dispatch(new ToggleTask(
                $id,
                $data['done']
            ));

            /** @var HandledStamp $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);
        } else {
            return new JsonResponse('Unknown parameters', JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $handledStamp->getResult(),
            JsonResponse::HTTP_CREATED,
            [],
            ['groups' => ['task', 'user']]
        );
    }

    /**
     * @Route("/tasks/{taskId}", methods={"PUT"})
     */
    public function updateTask(string $taskId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $envelope = $this->commandBus->dispatch(new UpdateTask(
            $taskId,
            $data['description']
        ));

        $task = $envelope->last(HandledStamp::class)->getResult();

        return $this->json(
            $task,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['task', 'user']]
        );
    }

    /**
     * @Route("/tasks/{taskId}", methods={"DELETE"})
     */
    public function deleteTask(string $taskId): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteTask($taskId));

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

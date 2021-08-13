<?php

namespace App\Controller;

use App\Action\Daily\CreateDaily;
use App\Action\User\CreateUser;
use App\Repository\DailyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DailyController extends AbstractController
{
    private DailyRepository $dailyRepository;
    private MessageBusInterface $commandBus;

    public function __construct(DailyRepository $dailyRepository, MessageBusInterface $commandBus)
    {
        $this->dailyRepository = $dailyRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/dailys", methods={"GET"})
     */
    public function getDaily(): JsonResponse
    {
        return $this->json(
            $this->dailyRepository->findAll(),
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['daily', 'user']]
        );
    }

    /**
     * @Route("/dailys", methods={"POST"})
     */
    public function createDaily(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $envelope = $this->commandBus->dispatch(new CreateDaily(
            $data['userId'],
            $data['description']
        ));

        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);

        return $this->json(
            $handledStamp->getResult(),
            JsonResponse::HTTP_CREATED,
            [],
            ['groups' => ['daily', 'user']]
        );
    }
}

<?php

namespace App\Controller;

use App\Action\Github\GetAccessTokenFromCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class GithubController extends AbstractController
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/o-auth/access_token", methods={"POST"})
     */
    public function getAccessTokenFromCode(Request $request): JsonResponse
    {
        $code = $request->query->get('code');

        $res = $this->commandBus->dispatch(new GetAccessTokenFromCode($code));

        return $this->json($res->last(HandledStamp::class)->getResult());
    }
}
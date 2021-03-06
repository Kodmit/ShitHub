<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HandlerFailedException) {
            throw $exception->getPrevious();
        }

        switch(true) {
            case $exception instanceof UnauthorizedHttpException: $httpCode = Response::HTTP_UNAUTHORIZED;break;
            case $exception instanceof \DomainException: $httpCode = Response::HTTP_BAD_REQUEST;break;
            case $exception instanceof ResourceNotFoundException: $httpCode = Response::HTTP_NOT_FOUND;break;
        }

        if ($exception instanceof ValidationFailedException) {
            $errors = [];
            foreach ($exception->getViolations() as $violation) {
                $parameters = [];
                foreach ($violation->getParameters() as $key => $parameter) {
                    $parameters[trim($key, '{} ')] = trim($parameter, '\\"');
                }
                $errors[] = [
                    'property' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                    'code' => $violation->getCode(),
                    'parameters' => $parameters
                ];
            }
            $event->setResponse(new JsonResponse([
                'errors' => $errors,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ], Response::HTTP_BAD_REQUEST));
            return;
        }

        $event->setResponse(new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ], $httpCode));
    }
}
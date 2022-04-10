<?php

namespace App\EventListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ExceptionHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleGenericException', 0]
            ],
        ];
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $exception  = $event->getThrowable();
        $message    = $exception->getMessage();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        if($exception instanceof TooManyRequestsHttpException) {
            $message = 'Limite de requisições atingido';
        }

        $response = new JsonResponse([
            'message' => $message,
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            // 'trace' => $exception->getTrace(),
        ]);

        $response->setStatusCode($statusCode);

        $event->setResponse($response);
    }
}

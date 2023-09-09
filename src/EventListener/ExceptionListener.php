<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ExceptionListener
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
//        $exception = $event->getThrowable();
//
//        if ($exception instanceof AuthenticationException) {
//            $url = $this->urlGenerator->generate('app_login');
//            $response = new RedirectResponse($url);
//
//            $event->setResponse($response);
//        }
//
//        if ($exception instanceof AccessDeniedException) {
//            $response = new RedirectResponse($this->urlGenerator->generate('app_forbidden'));
//            $event->setResponse($response);
//        }


        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Si la route est 'app_verify_email', ne faites rien.
        if ($request->attributes->get('_route') === 'app_verify_email') {
            return;
        }

        if ($exception instanceof AuthenticationException) {
            $url = $this->urlGenerator->generate('app_login');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }

        if ($exception instanceof AccessDeniedException) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_forbidden'));
            $event->setResponse($response);
        }

    }
}
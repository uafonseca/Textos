<?php

namespace App\EventListener;

use App\AppEvents;
use App\Event\VisitEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class KernelRequestListener implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private AuthorizationCheckerInterface $authorizationChecker;
    private SessionInterface $session;
    private RouterInterface $router;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        SessionInterface $session,
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
        $this->router = $router;
        $this->dispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::FINISH_REQUEST => 'onKernelFinishRequest',
        ];
    }
    public function onKernelFinishRequest(FinishRequestEvent $event){
        $validRoutes = [
            'book_show',
        ];

        if (in_array($event->getRequest()->get('_route'), $validRoutes)){
            $routeParams = $event->getRequest()->get('_route_params');
            $user = $this->tokenStorage->getToken()->getUser();

            $this->dispatcher->dispatch(new VisitEvent($user, $routeParams), AppEvents::COURSE_VISITED);
        }
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (! $this->isUserLoggedIn()) {
            return;
        }

        $sessionId = $this->session->getId();
        $user = $this->tokenStorage->getToken()->getUser();

        // If the sessionId and the sessionId in database are equal: this is the latest connected user
        if ($sessionId === $user->getSessionId()) {
            return;
        }

        $this->session->getFlashBag()->add('error', 'You have been logged out, because another person logged in with your credentials.');
        $redirectUrl = $this->router->generate('app_logout');
        $response = new RedirectResponse($redirectUrl);

        $event->setResponse($response);
    }

    protected function isUserLoggedIn()
    {
        try {
            return $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY');
        } catch (AuthenticationCredentialsNotFoundException $exception) {
            // Ignoring this exception.
        }

        return false;
    }
}

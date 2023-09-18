<?php

namespace App\Middleware;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;  


class RouteBlockingMiddleware implements EventSubscriberInterface
{
    private $blockedRoutes;

    public function __construct(array $blockedRoutes)
    {
        $this->blockedRoutes = $blockedRoutes;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();
        $session = $request->getSession();
        $loggedIn = $session->get('user_logged_in');
        if ($this->shouldBlockRoute($pathInfo) && $loggedIn !== true) {
            $event->setResponse($this->redirect('/'));
        } 
    }

    private function shouldBlockRoute($path)
    {
        return in_array($path, $this->blockedRoutes);
    }

    private function redirect($url)
    {
        return new RedirectResponse($url);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}

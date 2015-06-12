<?php

namespace miniShopBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CheckOutExceptionListener
{
    private $router;
    private $container;
    private $session;

    public function __construct($router, $container, $session)
    {
        $this->router = $router;
        $this->container = $container;
        $this->session = $session;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $route = 'check_out';
        $shoppingCartRoute = "show_shopping_cart";
        $container = $this->container;
        $routeName = $container->get('request')->get('_route');
        if ($routeName == $route) {
            if (!$this->session->has('shoppingCart')) {
                $url = $this->router->generate($shoppingCartRoute);
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }
}

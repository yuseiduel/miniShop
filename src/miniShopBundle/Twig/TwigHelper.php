<?php

namespace miniShopBundle\Twig;


class TwigHelper extends \Twig_Extension
{
    private $session;
    private $miniShopService;

    public function __construct($session, $miniShopService)
    {
        $this->session = $session;
        $this->miniShopService = $miniShopService;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('cart', array($this, 'cartFilter')),
        );
    }

    public function cartFilter($cartName)
    {
        if ($this->session->has('shoppingCart')) {
            $shoppingCart = $this->session->get('shoppingCart');
            $priceAndNumber = $this->miniShopService->countTotalPriceAndItems($shoppingCart);
            $cartName = $cartName . "(" . $priceAndNumber['totalNumber'] . " products cost " . $priceAndNumber['totalPrice'] . ")";

            return $cartName;
        } else {
            return $cartName . "(empty)";
        }
    }

    public function getName()
    {
        return 'app_extension';
    }
} 
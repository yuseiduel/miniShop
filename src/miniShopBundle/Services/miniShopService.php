<?php


namespace miniShopBundle\Services;


use Doctrine\ORM\EntityManager;
use miniShopBundle\Entity\OrderedProducts;

class miniShopService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function generateProductsList($shoppingCart)
    {
        $cartProducts = array();
        $productsRepository = $this->em->getRepository("miniShopBundle:Products");
        foreach ($shoppingCart as $cartItem) {
            $product = $productsRepository->find($cartItem['productId']);
            $cartProducts[] = array(
                'id' => $cartItem['productId'],
                'name' => $product->getName(),
                'size' => $product->getSize(),
                'number' => $cartItem['number'],
                'price' => $cartItem['number']*$product->getPrice()
            );
        }

        return $cartProducts;
    }

    public function countTotalPriceAndItems($shoppingCart)
    {
        $totalPrice = 0;
        $totalNumber = 0;
        $productsRepository = $this->em->getRepository("miniShopBundle:Products");
        foreach ($shoppingCart as $cartItem) {
            $product = $productsRepository->find($cartItem['productId']);
            $totalPrice += $product->getPrice()*$cartItem['number'];
            $totalNumber += $cartItem['number'];
        }

        return array('totalPrice' => $totalPrice, 'totalNumber' => $totalNumber);
    }

    public function saveOrderedProducts($shoppingCart, $order)
    {
        $productsRepository = $this->em->getRepository("miniShopBundle:Products");
        foreach ($shoppingCart as $cartItem) {
            $orderedProduct = new OrderedProducts();
            $orderedProduct->setOrders($order);
            $orderedProduct->setProducts($productsRepository->find($cartItem['productId']));
            $orderedProduct->setNumber($cartItem['number']);
            $this->em->persist($orderedProduct);
            $this->em->flush();
        }
    }
} 
<?php

namespace miniShopBundle\Controller;

use miniShopBundle\Entity\Orders;
use miniShopBundle\Form\OrdersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;

class DefaultController extends Controller
{
    public function productsAction()
    {
        $productsRepository = $this->getDoctrine()->getRepository("miniShopBundle:Products");

        $products = $productsRepository->findAll();

        return $this->render("miniShopBundle::products.html.twig", array("products" => $products));
    }

    public function filteredProductsBySizeAction($size)
    {
        $productsRepository = $this->getDoctrine()->getRepository("miniShopBundle:Products");

        $products = $productsRepository->findFilteredProductsBySize($size);

        return $this->render("miniShopBundle::products.html.twig", array("products" => $products));
    }

    public function addToCartAction($productId)
    {
        $session = $this->get("session");

        if ($session->has('shoppingCart')){
            $shoppingCart = $session->get('shoppingCart');
            $i = 0;
            foreach ($shoppingCart as $key => $cartItem) {
                if ($cartItem['productId'] === $productId){
                    $shoppingCart[$key]['number']++;
                    $i = 1;
                }
            }
            if ($i === 0) {
                $shoppingCart[] = array('productId' => $productId, 'number' => 1);
            }
        } else {
            $shoppingCart = array();
            $shoppingCart[] = array('productId' => $productId, 'number' => 1);
        }
        $session->set('shoppingCart', $shoppingCart);

        $miniShopService = $this->get("mini_shop_service");
        $priceAndNumber = $miniShopService->countTotalPriceAndItems($shoppingCart);
        $response = "Shopping Cart (" . $priceAndNumber['totalNumber'] . " products cost " . $priceAndNumber['totalPrice'] . ")";

        return new Response(json_encode($response));
    }

    public function shoppingCartAction()
    {
        $session = $this->get("session");

        if ($session->has('shoppingCart')) {
            $shoppingCart = $session->get('shoppingCart');
            $miniShopService = $this->get('mini_shop_service');
            $cartProducts = $miniShopService->generateProductsList($shoppingCart);

            return $this->render("miniShopBundle::shopping_cart.html.twig", array('cartProducts' => $cartProducts));
        } else {
            return $this->render('miniShopBundle::empty_shopping_cart.html.twig');
        }
    }

    public function checkOutAction(Request $request)
    {
        $order = new Orders();

        $em = $this->getDoctrine()->getManager();

        $session = $this->get("session");
        $shoppingCart = $session->get('shoppingCart');
        $miniShopService = $this->get("mini_shop_service");
        $priceAndNumber = $miniShopService->countTotalPriceAndItems($shoppingCart);

        $form = $this->createForm(new OrdersType(), $order);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $order = $form->getData();
            $order->setPrice($priceAndNumber['totalPrice']);
            $em->persist($order);
            $em->flush();

            $miniShopService->saveOrderedProducts($shoppingCart, $order);

            $session->remove('shoppingCart');

            return $this->redirectToRoute('products');
        }

        $form = $this->createForm(new OrdersType(), $order);

        return $this->render('miniShopBundle::check_out.html.twig', array('form' => $form->createView()));
    }

    public function checkOrdersAction()
    {
        $ordersRepository = $this->getDoctrine()->getRepository("miniShopBundle:Orders");

        $orders = $ordersRepository->findAll();

        return $this->render("miniShopBundle::orders.html.twig", array("orders" => $orders));
    }

    public function checkOrderAction($orderId)
    {
        $orderedProductsRepository = $this->getDoctrine()->getRepository("miniShopBundle:OrderedProducts");

        $orderedProducts = $orderedProductsRepository->findOrderedProductsByOrder($orderId);

        return $this->render("miniShopBundle::order.html.twig", array("orderedProducts" => $orderedProducts));
    }
}

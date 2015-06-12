<?php

namespace miniShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderedProducts
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="miniShopBundle\Entity\OrderedProductsRepository")
 */
class OrderedProducts
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity = "Orders", inversedBy = "orderedProducts")
     * @ORM\JoinColumn(name="orders", referencedColumnName="id", nullable = false)
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity = "Products", inversedBy = "orderedProducts")
     * @ORM\JoinColumn(name="products", referencedColumnName="id", nullable = false)
     */
    private $products;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}

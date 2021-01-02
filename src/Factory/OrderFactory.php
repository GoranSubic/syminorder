<?php

namespace App\Factory;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\ProductRepository;

/**
 * Class OrderFactory
 * @package App\Factory
 */
class OrderFactory
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Creates an order.
     *
     * @return Order
     */
    public function create(): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $order;
    }

    /**
     * Creates an order.
     *
     * @return Order
     */
    public function createOrder($products, $cartAt): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        foreach ($products as $product) {
            $prodRepo = $this->productRepository->findOneBy(['id' => $product['id']]);
            $item = $this->createItem($prodRepo, $product['ammount']);
            $order->addItem($item);
        }

        return $order;
    }

    /**
     * Creates an item for a product.
     *
     * @param Product $product
     *
     * @return OrderItem
     */
    public function createItem(Product $product, int $qnt = 1): OrderItem
    {
        $item = new OrderItem();
        $item->setProduct($product);
        $item->setQuantity($qnt);

        return $item;
    }
}

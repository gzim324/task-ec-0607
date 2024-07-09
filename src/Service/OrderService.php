<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Exception\OrderMissingInformationException;
use App\Exception\ProductNotFoundException;
use App\Exception\ProductOutOfStockException;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param $content
     * @return void
     * @throws OrderMissingInformationException
     */
    public function validateOrderContent($content): void
    {
        if (empty($content->fullname) || empty($content->email) || empty($content->phone)) {
            throw new OrderMissingInformationException();
        }
    }

    /**
     * @param $content
     * @return Order
     */
    public function createOrder($content): Order
    {
        $order = (new Order())
            ->setFullname($content->fullname)
            ->setEmail($content->email)
            ->setPhone($content->phone);
        $this->entityManager->persist($order);

        return $order;
    }

    /**
     * @param array $products
     * @param Order $order
     * @return array
     * @throws ProductNotFoundException
     * @throws ProductOutOfStockException
     */
    public function processOrderProducts(array $products, Order $order): void
    {
        foreach ($products as $productData) {
            $product = $this->getProduct($productData->productId);

            $this->validateProductStock($product, $productData->quantity);

            $this->createOrderProduct($product, $order, $productData->quantity);

            $this->updateProductStock($product, $productData->quantity);
        }
    }

    /**
     * @param int $productId
     * @return Product
     * @throws ProductNotFoundException
     */
    public function getProduct(int $productId): Product
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return void
     * @throws ProductOutOfStockException
     */
    public function validateProductStock(Product $product, int $quantity): void
    {
        if ($product->getAvailableUnits() < $quantity) {
            throw new ProductOutOfStockException();
        }
    }

    /**
     * @param Product $product
     * @param Order $order
     * @param int $quantity
     * @return void
     */
    public function createOrderProduct(Product $product, Order $order, int $quantity): void
    {
        $orderProduct = (new OrderProduct())
            ->setProduct($product)
            ->setOrder($order)
            ->setQuantity($quantity);
        $this->entityManager->persist($orderProduct);
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    public function updateProductStock(Product $product, int $quantity): void
    {
        $product->setAvailableUnits($product->getAvailableUnits() - $quantity);
    }
}

<?php

namespace App\Service\OrderCalculation;

use App\Entity\Order;

class ProductTotalCalculator implements OrderCalculatorInterface
{
    /**
     * @param Order $order
     * @return float
     */
    public function calculate(Order $order): float
    {
        $total = 0.0;

        foreach ($order->getOrderProducts() as $orderProduct) {
            $total += $orderProduct->getProduct()->getPrice() * $orderProduct->getQuantity();
        }

        return $total;
    }
}

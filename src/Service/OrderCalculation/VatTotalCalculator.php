<?php

namespace App\Service\OrderCalculation;

use App\Entity\Order;

class VatTotalCalculator implements OrderCalculatorInterface
{
    private const VAT_RATE = 0.23;

    /**
     * @param Order $order
     * @return float
     */
    public function calculate(Order $order): float
    {
        $totalVat = 0.0;

        foreach ($order->getOrderProducts() as $orderProduct) {
            $totalVat += $orderProduct->getProduct()->getPrice() * $orderProduct->getQuantity() * self::VAT_RATE;
        }

        return $totalVat;
    }
}

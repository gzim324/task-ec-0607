<?php

namespace App\Service\OrderCalculation;

use App\Entity\Order;

class TotalCalculator implements OrderCalculatorInterface
{
    public function __construct(
        private readonly ProductTotalCalculator $productTotalCalculator,
        private readonly VatTotalCalculator $vatTotalCalculator
    ) {
    }

    /**
     * @param Order $order
     * @return float
     */
    public function calculate(Order $order): float
    {
        $productTotal = $this->productTotalCalculator->calculate($order);
        $vatTotal = $this->vatTotalCalculator->calculate($order);

        return $productTotal + $vatTotal;
    }
}

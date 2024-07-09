<?php

namespace App\Service\OrderCalculation;

use App\Entity\Order;

interface OrderCalculatorInterface
{
    /**
     * @param Order $order
     * @return float
     */
    public function calculate(Order $order): float;
}

<?php

namespace App\Service;

use App\Entity\Order;

class OrderCalculationService
{
    public function __construct(
        private iterable $calculators
    ) {
    }

    /**
     * @param Order $order
     * @return array
     */
    public function calculate(Order $order): array
    {
        $result = [];

        foreach ($this->calculators as $calculator) {
            $result[get_class($calculator)] = $calculator->calculate($order);
        }

        return $result;
    }
}

<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseService
{
    public function __construct(
        private readonly OrderProductRepository $orderProductRepository,
    ) {
    }

    public const RESPONSE_FULLNAME = 'fullname';
    public const RESPONSE_PRODUCTS = 'products';
    public const RESPONSE_CUSTOM_ID = 'customId';
    public const RESPONSE_QUANTITY = 'quantity';

    public function createResponse(Order $order): JsonResponse
    {
        $response = [
            self::RESPONSE_FULLNAME => $order->getFullname(),
            self::RESPONSE_PRODUCTS => []
        ];

        foreach ($this->orderProductRepository->findByOrder($order) as $orderProduct) {
            $response[self::RESPONSE_PRODUCTS][] = [
                self::RESPONSE_CUSTOM_ID => $orderProduct->getProduct()->getCustomId(),
                self::RESPONSE_QUANTITY => $orderProduct->getQuantity(),
            ];
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

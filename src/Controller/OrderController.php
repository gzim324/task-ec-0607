<?php

namespace App\Controller;

use App\Entity\Order;
use App\Exception\OrderMissingInformationException;
use App\Service\OrderCalculationService;
use App\Service\OrderService;
use App\Service\ResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Exception\ProductNotFoundException;
use App\Exception\ProductOutOfStockException;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly EntityManagerInterface $entityManager,
        private readonly ResponseService $responseService,
        private readonly OrderCalculationService $orderCalculationService,
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws OrderMissingInformationException
     * @throws ProductNotFoundException
     * @throws ProductOutOfStockException
     */
    #[Route('/order', name: 'new_order', methods: ['POST'])]
    public function newOrder(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent());

        $this->orderService->validateOrderContent($content);

        $order = $this->orderService->createOrder($content);

        $this->orderService->processOrderProducts($content->products, $order);

        $this->entityManager->flush();

        return $this->responseService->createResponse($order);
    }

    /**
     * @param int $orderId
     * @return JsonResponse
     */
    #[Route('/order/{orderId}', name: 'get_order_details')]
    public function getOrderDetails(int $orderId): JsonResponse
    {
        return $this->responseService->createResponse(
            $this->entityManager->getRepository(Order::class)->find($orderId)
        );
    }

    #[Route('/test/collector', name: 'test_collector')]
    public function testCollector(): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find(1);
        $calculationResults = $this->orderCalculationService->calculate($order);

        return new JsonResponse($calculationResults, Response::HTTP_OK);
    }
}

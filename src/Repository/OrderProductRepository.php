<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderProduct>
 */
class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    public function findByOrder(Order $order): array
    {
        return $this->createQueryBuilder('op')
            ->andWhere('op.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult();
    }
}

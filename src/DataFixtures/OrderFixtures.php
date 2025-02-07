<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Generator\SequentialOrderNumberGenerator;
use Decimal\Decimal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

class OrderFixtures extends Fixture
{
    public function __construct(
        private readonly SequentialOrderNumberGenerator $sequentialOrderNumberGenerator,
        private readonly ClockInterface $clock,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $order = new Order(
            number: $this->sequentialOrderNumberGenerator->generate(),
            createdAt: $this->clock->now(),
            title: 'First order',
            currency: 'CZK',
        );

        $shirt = new OrderItem(
            order: $order,
            title: 'Shirt XL',
            unitPrice: new Decimal('469'),
            quantity: new Decimal('2'),
        );

        $shoes = new OrderItem(
            order: $order,
            title: 'Shoe XL',
            unitPrice: new Decimal('1539'),
            quantity: new Decimal('1'),
        );

        $order->addOrderItem($shirt);
        $order->addOrderItem($shoes);
        $manager->persist($order);

        $manager->flush();
    }
}

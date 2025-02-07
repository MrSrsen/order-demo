<?php

namespace App\Tests\Unit;

use App\Entity\Order;
use App\Entity\OrderItem;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testEmptyOrder(): void
    {
        $order = new Order(
            number: '001',
            createdAt: new \DateTimeImmutable('2025-02-07 15:12:06'),
            title: 'Test',
            currency: 'CZK',
        );

        $this->assertEquals('0.00', $order->getTotal()->toString());
    }

    public function testOrderWithOneItem(): void
    {
        $order = new Order(
            number: '001',
            createdAt: new \DateTimeImmutable('2025-02-07 15:12:06'),
            title: 'Test',
            currency: 'CZK',
        );

        new OrderItem(
            order: $order,
            title: 'Shirt',
            unitPrice: new Decimal('100'),
            quantity: new Decimal('2'),
        );

        $this->assertEquals('200', $order->getTotal()->toString());
    }

    public function testOrderWithTwoItems(): void
    {
        $order = new Order(
            number: '001',
            createdAt: new \DateTimeImmutable('2025-02-07 15:12:06'),
            title: 'Test',
            currency: 'CZK',
        );

        new OrderItem(
            order: $order,
            title: 'Shirt',
            unitPrice: new Decimal('100'),
            quantity: new Decimal('2'),
        );

        new OrderItem(
            order: $order,
            title: 'Socks',
            unitPrice: new Decimal('100'),
            quantity: new Decimal('30'),
        );

        $this->assertEquals('3200', $order->getTotal()->toString());
    }

    public function testUpdatingOrder(): void
    {
        $order = new Order(
            number: '001',
            createdAt: new \DateTimeImmutable('2025-02-07 15:12:06'),
            title: 'Test',
            currency: 'CZK',
        );
        $this->assertEquals('0.00', $order->getTotal()->toString());

        $shirt = new OrderItem(
            order: $order,
            title: 'Shirt',
            unitPrice: new Decimal('100'),
            quantity: new Decimal('2'),
        );
        $this->assertEquals('200', $order->getTotal()->toString());

        $gloves = new OrderItem(
            order: $order,
            title: 'Gloves',
            unitPrice: new Decimal('300'),
            quantity: new Decimal('1'),
        );
        $this->assertEquals('500', $order->getTotal()->toString());

        $gloves->setQuantity(new Decimal('2'));
        $this->assertEquals('800', $order->getTotal()->toString());

        $order->removeOrderItem($gloves);
        $this->assertEquals('200', $order->getTotal()->toString());

        $order->removeOrderItem($shirt);
        $this->assertEquals('0', $order->getTotal()->toString());
    }
}

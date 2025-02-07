<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Decimal\Decimal;

#[\Doctrine\ORM\Mapping\Entity(OrderItemRepository::class)]
class OrderItem
{
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
    #[\Doctrine\ORM\Mapping\GeneratedValue]
    private ?int $id = null;

    #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 255, nullable: false)]
    private string $title;

    #[\Doctrine\ORM\Mapping\Column(type: 'decimal', length: 255, precision: 13, scale: 2, nullable: false)]
    private string $unitPrice;

    #[\Doctrine\ORM\Mapping\Column(type: 'decimal', length: 255, precision: 13, scale: 2, nullable: false)]
    private string $quantity;

    #[\Doctrine\ORM\Mapping\Column(type: 'decimal', length: 255, precision: 13, scale: 2, nullable: false)]
    private string $total = '0.00';

    #[\Doctrine\ORM\Mapping\ManyToOne(targetEntity: Order::class, inversedBy: 'orderItems')]
    private Order $order;

    public function __construct(Order $order, string $title, Decimal $unitPrice, Decimal $quantity)
    {
        $this->order = $order;
        $this->order->addOrderItem($this);

        $this->title = $title;
        $this->unitPrice = $unitPrice->toString();
        $this->quantity = $quantity->toString();

        $this->recalculate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getUnitPrice(): Decimal
    {
        return new Decimal($this->unitPrice);
    }

    public function setUnitPrice(Decimal $unitPrice): void
    {
        $this->unitPrice = $unitPrice->toString();

        $this->recalculate();
    }

    public function getQuantity(): Decimal
    {
        return new Decimal($this->quantity);
    }

    public function setQuantity(Decimal $quantity): void
    {
        $this->quantity = $quantity->toString();

        $this->recalculate();
    }

    public function getTotal(): Decimal
    {
        return new Decimal($this->total);
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    private function recalculate(): void
    {
        $this->total = $this->getUnitPrice()
            ->mul($this->getQuantity())
            ->toString();

        $this->order->recalculate();
    }
}

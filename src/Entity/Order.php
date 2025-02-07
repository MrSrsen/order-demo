<?php

namespace App\Entity;

use App\Enum\OrderStateEnum;
use App\Repository\OrderRepository;
use Decimal\Decimal;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[\Doctrine\ORM\Mapping\Entity(OrderRepository::class)]
#[\Doctrine\ORM\Mapping\Table(name: '`order`')]
class Order
{
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
    #[\Doctrine\ORM\Mapping\GeneratedValue]
    private ?int $id = null;

    #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 32, unique: true, nullable: false)]
    private string $number;

    #[\Doctrine\ORM\Mapping\Column(type: 'date_immutable', nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 255, nullable: false)]
    private string $title;

    #[\Doctrine\ORM\Mapping\Column(type: 'decimal', length: 255, precision: 13, scale: 2, nullable: false)]
    private string $total = '0.00';

    #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 3, nullable: false)]
    private string $currency;

    #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 16, nullable: false)]
    private string $state = OrderStateEnum::New->value;

    /** @var Collection<array-key, OrderItem> */
    #[\Doctrine\ORM\Mapping\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist'])]
    private Collection $orderItems;

    public function __construct(string $number, \DateTimeImmutable $createdAt, string $title, string $currency)
    {
        $this->number = $number;
        $this->createdAt = $createdAt;
        $this->title = $title;
        $this->currency = $currency;

        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @internal For tests only */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTotal(): Decimal
    {
        return new Decimal($this->total);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getState(): OrderStateEnum
    {
        return OrderStateEnum::from($this->state);
    }

    public function setState(OrderStateEnum $state): static
    {
        $this->state = $state->value;

        return $this;
    }

    /** @return Collection<array-key, OrderItem> */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $this->recalculate();
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);

            $this->recalculate();
        }

        return $this;
    }

    public function recalculate(): void
    {
        $sum = new Decimal(0);
        foreach ($this->orderItems as $orderItem) {
            $sum = $sum->add($orderItem->getTotal());
        }

        $this->total = $sum->toString();
    }
}

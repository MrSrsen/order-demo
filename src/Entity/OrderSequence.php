<?php

namespace App\Entity;

use App\Repository\OrderSequenceRepository;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: OrderSequenceRepository::class)]
class OrderSequence
{
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
    #[\Doctrine\ORM\Mapping\GeneratedValue]
    private ?int $id = null;

    /** @var int<0, max> */
    #[\Doctrine\ORM\Mapping\Column(name: '`index`', type: 'integer', nullable: true)]
    protected int $index = 0;

    /** @var int<1, max> */
    #[\Doctrine\ORM\Mapping\Column(type: 'integer', nullable: true)]
    #[\Doctrine\ORM\Mapping\Version]
    protected int $version = 1;

    public function getIndex(): int
    {
        return $this->index;
    }

    public function incrementIndex(): void
    {
        ++$this->index;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    /** @param int<1, max> $version */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }
}

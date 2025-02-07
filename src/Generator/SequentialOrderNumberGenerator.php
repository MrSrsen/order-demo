<?php

namespace App\Generator;

use App\Entity\Order;
use App\Entity\OrderSequence;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\PessimisticLockException;

readonly class SequentialOrderNumberGenerator
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected int $startNumber = 1,
        protected int $numberLength = 9,
    ) {
    }

    /**
     * @throws OptimisticLockException
     * @throws PessimisticLockException
     */
    public function generate(): string
    {
        $sequence = $this->getSequence();

        $this->entityManager->lock($sequence, LockMode::OPTIMISTIC, $sequence->getVersion());

        $number = $this->generateNumber($sequence->getIndex());
        $sequence->incrementIndex();

        return $number;
    }

    protected function generateNumber(int $index): string
    {
        $number = $this->startNumber + $index;

        return \str_pad((string) $number, $this->numberLength, '0', \STR_PAD_LEFT);
    }

    protected function getSequence(): OrderSequence
    {
        $sequence = $this->entityManager->getRepository(OrderSequence::class)->findOneBy([]);

        if (null !== $sequence) {
            return $sequence;
        }

        $sequence = new OrderSequence();
        $this->entityManager->persist($sequence);

        return $sequence;
    }

}

<?php

/*
 * This file is part of the Advisor application.
 * (c) entiry.tech s.r.o. (Karel Mladý, Martin Sršeň)
 */

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\Loader\PurgerLoader;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait FixturesTrait
{
    protected static function executeFixtures(ContainerInterface $container, array $fixtures): void
    {
        /** @var PurgerLoader $fixtureLoader */
        $fixtureLoader = $container->get('fidry_alice_data_fixtures.loader.doctrine');

        foreach ($fixtures as $index => $fixtureAddress) {
            $fixtures[$index] = __DIR__.'/fixtures/'.$fixtureAddress;
        }

        $fixtureLoader->load($fixtures, [], [], PurgeMode::createTruncateMode());

        /*
         * This is important!! Without this the doctrine state will be broken and application will have unpredictable
         * behaviour. Purge your cache kids.
         */
        $container->get(EntityManagerInterface::class)->clear();
    }
}

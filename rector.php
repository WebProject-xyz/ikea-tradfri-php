<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromSymfonySerializerRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/wiki',
    ])
    ->withPhpSets(
        php83: true,
    )
    ->withSets([
        Rector\Symfony\Set\SymfonySetList::SYMFONY_72,
        Rector\Symfony\Set\SymfonySetList::SYMFONY_CODE_QUALITY,
        Rector\Symfony\Set\SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,

        Rector\Doctrine\Set\DoctrineSetList::DOCTRINE_CODE_QUALITY,
        Rector\Doctrine\Set\DoctrineSetList::DOCTRINE_COLLECTION_22,
    ])
    ->withAttributesSets(all: true)
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        ReturnTypeFromSymfonySerializerRector::class,
    ]);

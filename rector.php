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

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/wiki',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(
        php82: true,
    )
    ->withSets([
        Rector\Symfony\Set\SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        Rector\Symfony\Set\SymfonySetList::SYMFONY_64,
        Rector\Symfony\Set\SymfonySetList::SYMFONY_CODE_QUALITY,
        Rector\Symfony\Set\SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,

        Rector\Doctrine\Set\DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);

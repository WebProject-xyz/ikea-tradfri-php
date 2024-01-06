<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @template T of AbstractCollection
 */
interface MapperInterface
{
    /**
     * @phpstan-param T $collection
     *
     * @phpstan-return T
     */
    public function map(
        ServiceInterface $service,
        array $dataItems,
        AbstractCollection $collection,
    ): AbstractCollection;

    public function isValidData($device): bool;
}

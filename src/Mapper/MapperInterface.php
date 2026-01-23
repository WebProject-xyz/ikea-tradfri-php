<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use IKEA\Tradfri\Group\DeviceGroup;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @template TDevice of DeviceInterface|DeviceGroup
 * @template TInput of GroupDto|DeviceDto
 * @template TOutput of AbstractCollection<TDevice>
 */
interface MapperInterface
{
    /**
     * @phpstan-param TOutput $collection
     * @phpstan-param iterable<TInput> $dataItems
     *
     * @phpstan-return TOutput
     */
    public function map(
        ServiceInterface $service,
        iterable $dataItems,
        AbstractCollection $collection,
    ): AbstractCollection;
}

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

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Service\ServiceInterface;

abstract class AdapterAbstract implements AdapterInterface
{
    public function __construct(
        protected DeviceData $_deviceDataMapper,
        protected GroupData $_groupDataMapper,
    ) {
    }

    abstract public function getDeviceCollection(ServiceInterface $service): Devices;

    abstract public function getGroupCollection(ServiceInterface $service): Groups;
}

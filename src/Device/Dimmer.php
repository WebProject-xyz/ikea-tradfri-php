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

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Command\Coap\Keys;

final class Dimmer extends Device
{
    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct(int $deviceId)
    {
        parent::__construct($deviceId, Keys::ATTR_DEVICE_INFO_TYPE_DIMMER);
    }
}

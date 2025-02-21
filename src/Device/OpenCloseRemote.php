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

final class OpenCloseRemote extends Device
{
    public function __construct(int $deviceId)
    {
        parent::__construct($deviceId, \IKEA\Tradfri\Command\Coap\Keys::ATTR_DEVICE_INFO_TYPE_OPEN_CLOSE_REMOTE);
    }
}

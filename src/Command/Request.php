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

namespace IKEA\Tradfri\Command;

enum Request: string
{
    case RootDevices = Coap\Keys::ROOT_DEVICES;
    case RootGroups  = Coap\Keys::ROOT_GROUPS;
    case RootGateway = Coap\Keys::ROOT_GATEWAY;

    public function withTargetId(int|string $deviceId): string
    {
        return $this->value . '/' . $deviceId;
    }
}

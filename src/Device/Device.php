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

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Traits\ProvidesId;
use IKEA\Tradfri\Traits\ProvidesManufacturer;
use IKEA\Tradfri\Traits\ProvidesName;
use IKEA\Tradfri\Traits\ProvidesService;
use IKEA\Tradfri\Traits\ProvidesType;
use IKEA\Tradfri\Traits\ProvidesVersion;
use IKEA\Tradfri\Values\DeviceType;
use Webmozart\Assert\Assert;

abstract class Device implements \JsonSerializable, DeviceInterface
{
    use ProvidesId;
    use ProvidesManufacturer;
    use ProvidesName;
    use ProvidesService;
    use ProvidesType;
    use ProvidesVersion;

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct(int $deviceId, string $deviceType)
    {
        $this->type       = DeviceType::tryFromType(deviceTypeValue: $deviceType, allowUnknown: true);
        $this->deviceType = $deviceType;
        $this->setId($deviceId);
    }

    /**
     * @return array<int, array<string, mixed>>|array<string, mixed>
     */
    final public function jsonSerialize(): array
    {
        $data = [];

        foreach (\get_class_methods(static::class) as $method) {
            if ('getService' !== $method && \str_starts_with($method, 'get')) {
                $key        = \mb_strtolower(\mb_substr($method, 3));
                Assert::stringNotEmpty($key);
                $data[(string) $key] = $this->{$method}();
            }
        }

        return $data;
    }
}

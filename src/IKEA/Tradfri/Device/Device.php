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

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Traits\ProvidesId;
use IKEA\Tradfri\Traits\ProvidesManufacturer;
use IKEA\Tradfri\Traits\ProvidesName;
use IKEA\Tradfri\Traits\ProvidesService;
use IKEA\Tradfri\Traits\ProvidesType;
use IKEA\Tradfri\Traits\ProvidesVersion;

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
    public function __construct(int $deviceId, string $type)
    {
        $this->setType($type);
        $this->setId($deviceId);
    }

    /**
     * @deprecated
     */
    final public function isLightBulb(): bool
    {
        return (new Type())->isLightBulb($this->getType());
    }

    final public function jsonSerialize(): array
    {
        $data = [];

        foreach (\get_class_methods(static::class) as $method) {
            if ('getService' !== $method && \str_starts_with($method, 'get')) {
                $key        = \mb_strtolower(\mb_substr($method, 3));
                $data[$key] = $this->{$method}();
            }
        }

        return $data;
    }
}

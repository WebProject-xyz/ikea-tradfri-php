<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Traits\ProvidesId;
use IKEA\Tradfri\Traits\ProvidesManufacturer;
use IKEA\Tradfri\Traits\ProvidesName;
use IKEA\Tradfri\Traits\ProvidesService;
use IKEA\Tradfri\Traits\ProvidesType;
use IKEA\Tradfri\Traits\ProvidesVersion;
use JsonSerializable;

/**
 * Class Device.
 */
abstract class Device implements JsonSerializable, DeviceInterface
{
    use ProvidesId;
    use ProvidesName;
    use ProvidesManufacturer;
    use ProvidesVersion;
    use ProvidesType;
    use ProvidesService;

    /**
     * LightBulb constructor.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct(int $deviceId, string $type)
    {
        $this->setType($type);
        $this->setId($deviceId);
    }

    /**
     * Is this a lightblub.
     *
     * @deprecated
     */
    public function isLightBulb(): bool
    {
        return (new Type())->isLightBulb($this->getType());
    }

    public function jsonSerialize(): array
    {
        $data = [];

        foreach (\get_class_methods(static::class) as $method) {
            if ('getService' !== $method && 0 === \strpos($method, 'get')) {
                $key = \strtolower(\substr($method, 3));
                $data[$key] = $this->$method();
            }
        }

        return $data;
    }
}

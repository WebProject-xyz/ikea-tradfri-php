<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device\Helper;

use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;

/**
 * Class Type.
 */
class Type
{
    public const BLUB = 'TRADFRI bulb';
    public const BLUB_CLASS = LightBulb::class;
    public const MOTION_SENSOR = 'TRADFRI motion sensor';
    public const MOTION_SENSOR_CLASS = MotionSensor::class;
    public const REMOTE = 'TRADFRI remote control';
    public const REMOTE_CLASS = Remote::class;
    public const DIMMER = 'TRADFRI dimmer';
    public const DIMMER_CLASS = Dimmer::class;
    public const DRIVER = 'TRADFRI Driver ';
    public const DRIVER_CLASS = Driver::class;

    /**
     * Check if given type attribute is from a light blub.
     */
    public function isLightBulb(string $typeAttribute): bool
    {
        return 0 === \strpos($typeAttribute, self::BLUB);
    }

    /**
     * Check if given type attribute is from a remote control.
     */
    public function isRemote(string $typeAttribute): bool
    {
        return 0 === \strpos($typeAttribute, self::REMOTE);
    }

    /**
     * Check if given type attribute is from a dimmer.
     */
    public function isDimmer(string $typeAttribute): bool
    {
        return 0 === \strpos($typeAttribute, self::DIMMER);
    }

    /**
     * Check if given type attribute is from a driver.
     */
    public function isDriver(string $typeAttribute): bool
    {
        return 0 === \strpos($typeAttribute, self::DRIVER);
    }

    /**
     * Check if given type attribute is from a MotionSensor.
     */
    public function isMotionSensor(string $typeAttribute): bool
    {
        return 0 === \strpos($typeAttribute, self::MOTION_SENSOR);
    }

    /**
     * Check if given type attribute can be processed.
     */
    public function isKnownDeviceType(string $typeAttribute): bool
    {
        foreach (\get_class_methods($this) as $method) {
            if (__FUNCTION__ !== $method && $this->$method($typeAttribute)) {
                return true;
            }
        }

        return false;
    }
}

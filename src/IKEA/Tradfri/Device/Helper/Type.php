<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device\Helper;

use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Floalt;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\OpenCloseRemote;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\Repeater;
use IKEA\Tradfri\Device\RollerBlind;

/**
 * Class Type.
 */
class Type
{
    public const BLUB                    = 'TRADFRI bulb';
    public const BLUB_CLASS              = LightBulb::class;
    public const MOTION_SENSOR           = 'TRADFRI motion sensor';
    public const MOTION_SENSOR_CLASS     = MotionSensor::class;
    public const REMOTE                  = 'TRADFRI remote control';
    public const REMOTE_CLASS            = Remote::class;
    public const DIMMER                  = 'TRADFRI dimmer';
    public const DIMMER_CLASS            = Dimmer::class;
    public const DRIVER                  = 'TRADFRI Driver ';
    public const DRIVER_CLASS            = Driver::class;
    public const FLOALT                  = 'FLOALT panel ';
    public const FLOALT_CLASS            = Floalt::class;
    public const REPEATER                = 'TRADFRI Signal Repeater';
    public const REPEATER_CLASS          = Repeater::class;
    public const REMOTE_OPEN_CLOSE       = 'TRADFRI open/close remote';
    public const REMOTE_OPEN_CLOSE_CLASS = OpenCloseRemote::class;
    public const ROLLER_BLIND            = 'FYRTUR block-out roller blind';
    public const ROLLER_BLIND_CLASS      = RollerBlind::class;

    /**
     * Check if given type attribute is from a light blub.
     */
    public function isLightBulb(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::BLUB);
    }

    /**
     * Check if given type attribute is from a remote control.
     */
    public function isRemote(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::REMOTE);
    }

    /**
     * Check if given type attribute is from a dimmer.
     */
    public function isDimmer(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::DIMMER);
    }

    /**
     * Check if given type attribute is from a driver.
     */
    public function isDriver(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::DRIVER);
    }

    /**
     * Check if given type attribute is from a MotionSensor.
     */
    public function isMotionSensor(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::MOTION_SENSOR);
    }

    /**
     * Check if given type attribute is from a floalt panel.
     */
    public function isFloalt(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::FLOALT);
    }

    /**
     * Check if given type attribute is from a repeater.
     */
    public function isRepeater(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::REPEATER);
    }

    /**
     * Check if given type attribute is from a open/close remote.
     */
    public function isOpenCloseRemote(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::REMOTE_OPEN_CLOSE);
    }

    /**
     * Check if given type attribute is from a roller blind.
     */
    public function isRollerBlind(string $typeAttribute): bool
    {
        return 0 === strpos($typeAttribute, self::ROLLER_BLIND);
    }

    /**
     * Check if given type attribute can be processed.
     */
    public function isKnownDeviceType(string $typeAttribute): bool
    {
        foreach (get_class_methods($this) as $method) {
            if (__FUNCTION__ !== $method && $this->$method($typeAttribute)) {
                return true;
            }
        }

        return false;
    }
}

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

namespace IKEA\Tradfri\Device\Helper;

use IKEA\Tradfri\Device\ControlOutlet;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Driver;
use IKEA\Tradfri\Device\Floalt;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\OpenCloseRemote;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\Repeater;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Exception\RuntimeException;

/**
 * todo: enum.
 */
final class Type
{
    final public const BLUB                    = 'TRADFRI bulb';
    final public const BLUB_CLASS              = LightBulb::class;
    final public const MOTION_SENSOR           = 'TRADFRI motion sensor';
    final public const MOTION_SENSOR_CLASS     = MotionSensor::class;
    final public const REMOTE                  = 'TRADFRI remote control';
    final public const REMOTE_CLASS            = Remote::class;
    final public const DIMMER                  = 'TRADFRI dimmer';
    final public const DIMMER_CLASS            = Dimmer::class;
    final public const DRIVER                  = 'TRADFRI Driver ';
    final public const DRIVER_CLASS            = Driver::class;
    final public const FLOALT                  = 'FLOALT panel ';
    final public const FLOALT_CLASS            = Floalt::class;
    final public const REPEATER                = 'TRADFRI Signal Repeater';
    final public const REPEATER_CLASS          = Repeater::class;
    final public const REMOTE_OPEN_CLOSE       = 'TRADFRI open/close remote';
    final public const REMOTE_OPEN_CLOSE_CLASS = OpenCloseRemote::class;
    final public const ROLLER_BLIND            = 'FYRTUR block-out roller blind';
    final public const ROLLER_BLIND_CLASS      = RollerBlind::class;
    final public const CONTROL_OUTLET          = 'TRADFRI control outlet';
    final public const CONTROL_OUTLET_CLASS    = ControlOutlet::class;

    /**
     * Check if given type attribute is from a light blub.
     */
    public function isLightBulb(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::BLUB);
    }

    /**
     * Check if given type attribute is from a remote control.
     */
    public function isRemote(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::REMOTE);
    }

    /**
     * Check if given type attribute is from a dimmer.
     */
    public function isDimmer(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::DIMMER);
    }

    /**
     * Check if given type attribute is from a driver.
     */
    public function isDriver(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::DRIVER);
    }

    /**
     * Check if given type attribute is from a MotionSensor.
     */
    public function isMotionSensor(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::MOTION_SENSOR);
    }

    public function isControlOutlet(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::CONTROL_OUTLET);
    }

    /**
     * Check if given type attribute is from a floalt panel.
     */
    public function isFloalt(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::FLOALT);
    }

    /**
     * Check if given type attribute is from a repeater.
     */
    public function isRepeater(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::REPEATER);
    }

    /**
     * Check if given type attribute is from a open/close remote.
     */
    public function isOpenCloseRemote(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::REMOTE_OPEN_CLOSE);
    }

    /**
     * Check if given type attribute is from a roller blind.
     */
    public function isRollerBlind(string $typeAttribute): bool
    {
        return \str_starts_with($typeAttribute, self::ROLLER_BLIND);
    }

    /**
     * Check if given type attribute can be processed.
     */
    public function isUnknownDeviceType(string $typeAttribute): bool
    {
        foreach (\get_class_methods($this) as $method) {
            if (__FUNCTION__ === $method) {
                continue;
            }

            if ('buildFrom' === $method) {
                continue;
            }

            if (!$this->{$method}($typeAttribute)) {
                continue;
            }

            return false;
        }

        return true;
    }

    public function buildFrom(string $typeAttribute, int $deviceId, bool $buildUnknownDevice = true): Device
    {
        foreach (\get_class_methods($this) as $method) {
            if (!\str_starts_with($method, 'is')) {
                continue;
            }

            if ($this->{$method}($typeAttribute)) {
                $modelClass = \str_replace('is', '', $method);

                if ('isUnknownDeviceType' === $method && $buildUnknownDevice) {
                    $modelClass = 'Unknown';
                }

                $fqdnClassName = '\\IKEA\\Tradfri\\Device\\' . $modelClass;
                if (!\class_exists($fqdnClassName)) {
                    continue;
                }

                return new $fqdnClassName($deviceId, $typeAttribute);
            }
        }

        throw new RuntimeException('Unable to detect device type: ' . $typeAttribute);
    }
}

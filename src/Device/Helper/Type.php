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

namespace IKEA\Tradfri\Device\Helper;

use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Driver;
use IKEA\Tradfri\Device\Floalt;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\Repeater;

/**
 * todo: enum.
 */
final class Type
{
    final public const string BLUB                    = 'TRADFRI bulb';
    final public const string MOTION_SENSOR           = 'TRADFRI motion sensor';
    final public const string REMOTE                  = 'TRADFRI remote control';
    final public const string DIMMER                  = 'TRADFRI dimmer';
    final public const string DRIVER                  = 'TRADFRI Driver ';
    final public const string FLOALT                  = 'FLOALT panel ';
    final public const string REPEATER                = 'TRADFRI Signal Repeater';
    final public const string REMOTE_OPEN_CLOSE       = 'TRADFRI open/close remote';
    final public const string ROLLER_BLIND            = 'FYRTUR block-out roller blind';
    final public const string CONTROL_OUTLET          = 'TRADFRI control outlet';

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
}

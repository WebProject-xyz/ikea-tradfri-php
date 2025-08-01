<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Values;

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
use IKEA\Tradfri\Device\UnknownDevice;
use Webmozart\Assert\Assert;

enum DeviceType: string
{
    case BLUB = 'TRADFRI bulb';
    case MOTION_SENSOR = 'TRADFRI motion sensor';
    case REMOTE = 'TRADFRI remote control';
    case DIMMER = 'TRADFRI dimmer';
    case DRIVER = 'TRADFRI Driver ';
    case FLOALT = 'FLOALT panel ';
    case REPEATER = 'TRADFRI Signal Repeater';
    case REMOTE_OPEN_CLOSE = 'TRADFRI open/close remote';
    case ROLLER_BLIND = 'FYRTUR block-out roller blind';
    case CONTROL_OUTLET = 'TRADFRI control outlet';
    case UNKNOWN = 'XXXX';

    /**
     * @var array<string, class-string<Device>>
     */
    final public const array CLASS_MAP = [
        self::BLUB->name               => LightBulb::class,
        self::MOTION_SENSOR->name      => MotionSensor::class,
        self::REMOTE->name             => Remote::class,
        self::DIMMER->name             => Dimmer::class,
        self::DRIVER->name             => Driver::class,
        self::FLOALT->name             => Floalt::class,
        self::REPEATER->name           => Repeater::class,
        self::REMOTE_OPEN_CLOSE->name  => OpenCloseRemote::class,
        self::ROLLER_BLIND->name       => RollerBlind::class,
        self::CONTROL_OUTLET->name     => ControlOutlet::class,
        self::UNKNOWN->name            => UnknownDevice::class,
    ];

    /**
     * @phpstan-return ($allowUnknown is true ? DeviceType : null|DeviceType)
     */
    public static function tryFromType(string $deviceTypeValue, bool $allowUnknown = false): ?DeviceType
    {
        foreach (self::cases() as $case) {
            if (\str_starts_with(haystack: $deviceTypeValue, needle: $case->value)) {
                return $case;
            }
        }

        return $allowUnknown ? self::UNKNOWN : null;
    }

    /**
     * @phpstan-return ($allowUnknown is true ? Device : no-return|Device)
     */
    public static function initModel(string $deviceTypeValue, int $id, bool $allowUnknown = true): Device
    {
        $className = self::tryFromType($deviceTypeValue, $allowUnknown)
            ?->getClassName();

        if (
            null === $className
            || false === \class_exists($className)
        ) {
            if (false === $allowUnknown) {
                throw new \IKEA\Tradfri\Exception\InvalidTypeException(\sprintf('cannot find device class by type: "%s"', $deviceTypeValue));
            }
        }

        $class = new ($className)($id, $deviceTypeValue);
        Assert::isInstanceOf($class, Device::class);

        return $class;
    }

    /**
     * @phpstan-return class-string<Device>
     */
    public function getClassName(): string
    {
        return self::CLASS_MAP[$this->name];
    }
}

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

namespace IKEA\Tradfri\Values;

enum CoapDeviceAttribute: string
{
    case Id                           = 'ATTR_ID';
    case Name                         = 'ATTR_NAME';
    case DeviceInfo                   = 'ATTR_DEVICE_INFO';
    case DeviceModelNumber            = 'ATTR_DEVICE_MODEL_NUMBER';
    case DeviceFirmwareVersion        = 'ATTR_DEVICE_FIRMWARE_VERSION';
    case DeviceManufacturer           = 'ATTR_DEVICE_MANUFACTURER';
    case LightControl                 = 'ATTR_LIGHT_CONTROL';
    case DeviceState                  = 'ATTR_DEVICE_STATE';
    case LightDimmer                  = 'ATTR_LIGHT_DIMMER';
    case AttrLightColorHex            = 'ATTR_LIGHT_COLOR_HEX';

    /**
     * @phpstan-var array<string, numeric-string>
     */
    private const array VALUES        = [
        self::Id->value                    => '9003',
        self::Name->value                  => '9001',
        self::DeviceInfo->value            => '3',
        self::DeviceModelNumber->value     => '1',
        self::DeviceFirmwareVersion->value => '3',
        self::DeviceManufacturer->value    => '0',
        self::LightControl->value          => '3311', // array
        self::DeviceState->value           => '5850', // 0 / 1
        self::LightDimmer->value           => '5851', // Dimmer, not following spec: 0..255
        self::AttrLightColorHex->value     => '5706', // string representing a value in hex
    ];

    /**
     * @phpstan-return numeric-string
     */
    public function getAttribute(): string
    {
        return self::VALUES[$this->value];
    }

    /**
     * @return array<non-empty-string, non-empty-string>
     */
    public static function getAttributeReplacePatterns(): array
    {
        $map = [];
        foreach (self::cases() as $coapAttribute) {
            $key   = '"' . $coapAttribute->value . '"';
            $value = '#"' . $coapAttribute->getAttribute() . '"#';

            $map[$key] = $value;
        }

        return $map;
    }
}

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

namespace IKEA\Tradfri\Values;

enum CoapDeviceGroupAttribute: string
{
    case Id                  = 'ATTR_ID';
    case Name                = 'ATTR_NAME';
    case CreatedAt           = 'ATTR_CREATED_AT';
    case GroupMembers        = 'ATTR_GROUP_MEMBERS';
    case DeviceState         = 'ATTR_DEVICE_STATE';
    case LightDimmer         = 'ATTR_LIGHT_DIMMER';
    case Mood                = 'ATTR_MOOD';
    case GroupLights         = 'ATTR_GROUP_LIGHTS';

    /**
     * @phpstan-var array<string, numeric-string>
     */
    private const array VALUES        = [
        self::Id->value                       => '9003',
        self::Name->value                     => '9001',
        self::CreatedAt->value                => '9002',
        self::GroupMembers->value             => '9018',
        self::DeviceState->value              => '5850',
        self::LightDimmer->value              => '5851',
        self::Mood->value                     => '9039',
        self::GroupLights->value              => '15002',
    ];

    /**
     * @phpstan-return numeric-string
     */
    public function getAttribute(): string
    {
        return self::VALUES[$this->value];
    }

    /**
     * {
     *  '9001/ATTR_NAME':'Küche 2',
     *  '9002/ATTR_CREATED_AT':1547309682,
     *  '9003/ATTR_ID':177817,
     *  '5850/ATTR_DEVICE_STATE':0,
     *  '5851/ATTR_LIGHT_DIMMER':0,
     *  '9039/ATTR_MOOD':0,
     *  '9108/ATTR_?':0,
     *  '9018/MEMBERS':{
     *    '15002/ATTR_HS_LINK|ATTR_GROUP_LIGHTS':{
     *      '9003/ATTR_ID':[65588]
     *     }
     *   }
     *  }.
     *
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

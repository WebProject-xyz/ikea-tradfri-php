<?php

declare(strict_types=1);

namespace IKEA\Tests\Support\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use const JSON_THROW_ON_ERROR;
use IKEA\Tradfri\Command\Coap\Keys;
use function json_decode;
use function json_encode;

/**
 * Class Unit.
 */
class Unit extends \Codeception\Module
{
    public function getDevices(): array
    {
        return [
            'asd invalid',
            json_decode(
                json_encode([
                                Keys::ATTR_DEVICE_INFO_TYPE => 'invalid type error',
                            ]),
                null,
                512,
                JSON_THROW_ON_ERROR
            ),
            json_decode(json_encode([
                                        Keys::ATTR_ID          => 9999,
                                        Keys::ATTR_NAME        => 'invalid',
                                        Keys::ATTR_DEVICE_INFO => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => 'invalid type error',
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID                 => 1000,
                                        Keys::ATTR_NAME               => Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W,
                                        Keys::ATTR_DEVICE_INFO        => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W,
                                        ],
                                        Keys::ATTR_LIGHT_CONTROL => [
                                            0 => [
                                                Keys::ATTR_LIGHT_DIMMER => 22,
                                                Keys::ATTR_LIGHT_STATE  => 1,
                                            ],
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID                 => 2000,
                                        Keys::ATTR_NAME               => Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS,
                                        Keys::ATTR_DEVICE_INFO        => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS,
                                        ],
                                        Keys::ATTR_LIGHT_CONTROL => [
                                            0 => [
                                                Keys::ATTR_LIGHT_DIMMER => 22,
                                                Keys::ATTR_LIGHT_STATE  => 0,
                                            ],
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID          => 3000,
                                        Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_DIMMER,
                                        Keys::ATTR_DEVICE_INFO => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_DIMMER,
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID          => 4000,
                                        Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL,
                                        Keys::ATTR_DEVICE_INFO => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL,
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID          => 5000,
                                        Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                                        Keys::ATTR_DEVICE_INFO => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID          => 6000,
                                        Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND,
                                        Keys::ATTR_DEVICE_INFO => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND,
                                        ],
                                        Keys::ATTR_FYRTUR_CONTROL => [
                                            [
                                                Keys::ATTR_FYRTUR_STATE => 100,
                                            ],
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
        ];
    }

    public function getGroupDataCoapsResponse(): array
    {
        return [
            1000 => json_decode(json_encode([
                                                Keys::ATTR_NAME              => 'Group 1',
                                                Keys::ATTR_CREATED_AT        => 1_498_340_208,
                                                Keys::ATTR_ID                => 1000,
                                                Keys::ATTR_LIGHT_STATE       => 1,
                                                Keys::ATTR_LIGHT_DIMMER      => 38,
                                                Keys::ATTR_ALEXA_PAIR_STATUS => 220273,
                                                Keys::ATTR_GROUP_INFO        => (object) [
                                                    Keys::ATTR_GROUP_LIGHTS => (object) [
                                                        Keys::ATTR_ID => [
                                                            0 => 65540,
                                                            1 => 65547,
                                                            2 => 65545,
                                                            3 => 65548,
                                                            4 => 65549,
                                                        ],
                                                    ],
                                                ],
                                            ], JSON_THROW_ON_ERROR), null, 512, JSON_THROW_ON_ERROR),
            2000 => json_decode(json_encode([
                                                Keys::ATTR_NAME              => 'Group 2',
                                                Keys::ATTR_CREATED_AT        => 1_498_339_585,
                                                Keys::ATTR_ID                => 2000,
                                                Keys::ATTR_LIGHT_STATE       => 1,
                                                Keys::ATTR_LIGHT_DIMMER      => 0,
                                                Keys::ATTR_ALEXA_PAIR_STATUS => 214087,
                                                Keys::ATTR_GROUP_INFO        => (object) [
                                                    Keys::ATTR_GROUP_LIGHTS => (object) [
                                                        Keys::ATTR_ID => [
                                                            0 => 65536,
                                                            1 => 65537,
                                                            2 => 65538,
                                                            3 => 65539,
                                                            4 => 65542,
                                                            5 => 65543,
                                                        ],
                                                    ],
                                                ],
                                            ], JSON_THROW_ON_ERROR), null, 512, JSON_THROW_ON_ERROR),
            3000 => json_decode(json_encode([
                                                Keys::ATTR_NAME              => 'Group 3',
                                                Keys::ATTR_CREATED_AT        => 1_498_340_478,
                                                Keys::ATTR_ID                => 3000,
                                                Keys::ATTR_LIGHT_STATE       => 1,
                                                Keys::ATTR_LIGHT_DIMMER      => 0,
                                                Keys::ATTR_ALEXA_PAIR_STATUS => 222180,
                                                Keys::ATTR_GROUP_INFO        => (object) [
                                                    Keys::ATTR_GROUP_LIGHTS => (object) [
                                                        Keys::ATTR_ID => [
                                                            0 => 65544,
                                                            1 => 65546,
                                                        ],
                                                    ],
                                                ],
                                            ], JSON_THROW_ON_ERROR), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID          => 5000,
                                        Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                                        Keys::ATTR_DEVICE_INFO => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            'asd invalid',
            json_decode(json_encode([
                                        Keys::ATTR_DEVICE_INFO_TYPE => 'invalid type error',
                                    ]), null, 512, JSON_THROW_ON_ERROR),
            json_decode(json_encode([
                                        Keys::ATTR_ID          => 9999,
                                        Keys::ATTR_NAME        => 'invalid',
                                        Keys::ATTR_DEVICE_INFO => [
                                            Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                                            Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                                            Keys::ATTR_DEVICE_INFO_TYPE         => 'invalid type error',
                                        ],
                                    ]), null, 512, JSON_THROW_ON_ERROR),
        ];
    }
}

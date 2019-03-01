<?php
declare(strict_types=1);

namespace IKEA\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use IKEA\Tradfri\Command\Coap\Keys;

/**
 * Class Unit
 */
class Unit extends \Codeception\Module
{
    /**
     * @return array
     */
    public function getDevices(): array
    {
        return [
            'asd invalid',
            \json_decode(\json_encode([
                Keys::ATTR_DEVICE_INFO_TYPE => 'invalid type error',
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID          => 9999,
                Keys::ATTR_NAME        => 'invalid',
                Keys::ATTR_DEVICE_INFO => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => 'invalid type error',
                ],
            ])),
            \json_decode(\json_encode([
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
            ])),
            \json_decode(\json_encode([
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
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID                 => 2500,
                Keys::ATTR_NAME               => Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_CWS,
                Keys::ATTR_DEVICE_INFO        => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_CWS,
                ],
                Keys::ATTR_LIGHT_CONTROL => [
                    0 => [
                        Keys::ATTR_LIGHT_DIMMER => 22,
                        Keys::ATTR_LIGHT_STATE  => 0,
                    ],
                ],
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID          => 3000,
                Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_DIMMER,
                Keys::ATTR_DEVICE_INFO => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_DIMMER,
                ],
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID          => 4000,
                Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL,
                Keys::ATTR_DEVICE_INFO => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL,
                ],
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID          => 5000,
                Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                Keys::ATTR_DEVICE_INFO => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                ],
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID          => 6000,
                Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_OUTLET,
                Keys::ATTR_DEVICE_INFO => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_OUTLET,
                ],
            ])),
        ];
    }

    /**
     * @return array
     */
    public function getGroupDataCoapsResponse(): array
    {
        return [
            1000 => \json_decode(\json_encode([
                Keys::ATTR_NAME              => 'Group 1',
                Keys::ATTR_CREATED_AT        => 1498340208,
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
            ])),
            2000 => \json_decode(\json_encode([
                Keys::ATTR_NAME              => 'Group 2',
                Keys::ATTR_CREATED_AT        => 1498339585,
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
            ])),
            3000 => \json_decode(\json_encode([
                Keys::ATTR_NAME              => 'Group 3',
                Keys::ATTR_CREATED_AT        => 1498340478,
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
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID          => 5000,
                Keys::ATTR_NAME        => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                Keys::ATTR_DEVICE_INFO => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR,
                ],
            ])),
            'asd invalid',
            \json_decode(\json_encode([
                Keys::ATTR_DEVICE_INFO_TYPE => 'invalid type error',
            ])),
            \json_decode(\json_encode([
                Keys::ATTR_ID          => 9999,
                Keys::ATTR_NAME        => 'invalid',
                Keys::ATTR_DEVICE_INFO => [
                    Keys::ATTR_DEVICE_INFO_MANUFACTURER => 'UnitTestFactory',
                    Keys::ATTR_DEVICE_VERSION           => 'v1.33.7',
                    Keys::ATTR_DEVICE_INFO_TYPE         => 'invalid type error',
                ],
            ])),
        ];
    }
}

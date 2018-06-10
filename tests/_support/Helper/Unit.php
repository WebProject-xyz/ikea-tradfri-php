<?php
declare(strict_types=1);

namespace IKEA\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Helper\CoapCommandKeys;

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
                CoapCommandKeys::KEY_TYPE => 'invalid type error',
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID   => 9999,
                CoapCommandKeys::KEY_NAME => 'invalid',
                CoapCommandKeys::KEY_DATA => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => 'invalid type error',
                ],
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID          => 1000,
                CoapCommandKeys::KEY_NAME        => Device::TYPE_BLUB_E27_W,
                CoapCommandKeys::KEY_DATA        => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => Device::TYPE_BLUB_E27_W,
                ],
                CoapCommandKeys::KEY_DEVICE_DATA => [
                    0 => [
                        CoapCommandKeys::KEY_DIMMER => 22,
                        CoapCommandKeys::KEY_ONOFF  => 1,
                    ],
                ],
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID          => 2000,
                CoapCommandKeys::KEY_NAME        => Device::TYPE_BLUB_E27_WS,
                CoapCommandKeys::KEY_DATA        => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => Device::TYPE_BLUB_E27_WS,
                ],
                CoapCommandKeys::KEY_DEVICE_DATA => [
                    0 => [
                        CoapCommandKeys::KEY_DIMMER => 22,
                        CoapCommandKeys::KEY_ONOFF  => 0,
                    ],
                ],
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID   => 3000,
                CoapCommandKeys::KEY_NAME => Device::TYPE_DIMMER,
                CoapCommandKeys::KEY_DATA => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => Device::TYPE_DIMMER,
                ],
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID   => 4000,
                CoapCommandKeys::KEY_NAME => Device::TYPE_REMOTE_CONTROL,
                CoapCommandKeys::KEY_DATA => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => Device::TYPE_REMOTE_CONTROL,
                ],
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID   => 5000,
                CoapCommandKeys::KEY_NAME => Device::TYPE_MOTION_SENSOR,
                CoapCommandKeys::KEY_DATA => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => Device::TYPE_MOTION_SENSOR,
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
                CoapCommandKeys::KEY_NAME        => 'Group 1',
                CoapCommandKeys::KEY_TIME        => 1498340208,
                CoapCommandKeys::KEY_ID          => 1000,
                CoapCommandKeys::KEY_ONOFF       => 1,
                CoapCommandKeys::KEY_DIMMER      => 38,
                CoapCommandKeys::KEY_X           => 220273,
                CoapCommandKeys::KEY_GROUPS_DATA => (object) [
                    CoapCommandKeys::KEY_GET_LIGHTS => (object) [
                        CoapCommandKeys::KEY_ID => [
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
                CoapCommandKeys::KEY_NAME        => 'Group 2',
                CoapCommandKeys::KEY_TIME        => 1498339585,
                CoapCommandKeys::KEY_ID          => 2000,
                CoapCommandKeys::KEY_ONOFF       => 1,
                CoapCommandKeys::KEY_DIMMER      => 0,
                CoapCommandKeys::KEY_X           => 214087,
                CoapCommandKeys::KEY_GROUPS_DATA => (object) [
                    CoapCommandKeys::KEY_GET_LIGHTS => (object) [
                        CoapCommandKeys::KEY_ID => [
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
                CoapCommandKeys::KEY_NAME        => 'Group 3',
                CoapCommandKeys::KEY_TIME        => 1498340478,
                CoapCommandKeys::KEY_ID          => 3000,
                CoapCommandKeys::KEY_ONOFF       => 1,
                CoapCommandKeys::KEY_DIMMER      => 0,
                CoapCommandKeys::KEY_X           => 222180,
                CoapCommandKeys::KEY_GROUPS_DATA => (object) [
                    CoapCommandKeys::KEY_GET_LIGHTS => (object) [
                        CoapCommandKeys::KEY_ID => [
                            0 => 65544,
                            1 => 65546,
                        ],
                    ],
                ],
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID   => 5000,
                CoapCommandKeys::KEY_NAME => Device::TYPE_MOTION_SENSOR,
                CoapCommandKeys::KEY_DATA => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => Device::TYPE_MOTION_SENSOR,
                ],
            ])),
            'asd invalid',
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_TYPE => 'invalid type error',
            ])),
            \json_decode(\json_encode([
                CoapCommandKeys::KEY_ID   => 9999,
                CoapCommandKeys::KEY_NAME => 'invalid',
                CoapCommandKeys::KEY_DATA => [
                    CoapCommandKeys::KEY_MANUFACTURER => 'UnitTestFactory',
                    CoapCommandKeys::KEY_VERSION      => 'v1.33.7',
                    CoapCommandKeys::KEY_TYPE         => 'invalid type error',
                ],
            ])),
        ];
    }
}

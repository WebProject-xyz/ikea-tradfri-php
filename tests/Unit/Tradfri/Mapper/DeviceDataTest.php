<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Mapper;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\ControlOutlet;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Service\ServiceInterface;
use Mockery;

/**
 * Class DeviceDataTest.
 *
 * @method createMock($originalClassName)
 */
final class DeviceDataTest extends UnitTest
{
    protected \IKEA\Tests\Support\UnitTester $tester;

    public function testICanMapDataToCollectionWithNoError(): void
    {
        // Arrange
        $serviceMock = Mockery::mock(ServiceInterface::class);

        $mapper = new DeviceData();
        // Act
        $result = $mapper->map(
            $serviceMock,
            $this->tester->getDevicesDTOs(),
            new Devices(),
        );
        // Assert
        self::assertCount(8, $result);

        $device1 = $result->get(1000);
        self::assertInstanceOf(LightBulb::class, $device1);
        self::assertInstanceOf(\IKEA\Tradfri\Device\Feature\SwitchableInterface::class, $device1);
        self::assertSame(1000, $device1->getId());
        self::assertFalse($device1->isOn());
        self::assertSame('Off', $device1->getReadableState());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_CWS_PAL_600_LM, $device1->getName());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_CWS_PAL_600_LM, $device1->getType());
        self::assertSame('IKEA of Sweden', $device1->getManufacturer());
        self::assertSame('2.3.093', $device1->getVersion());
        self::assertSame(100.0, $device1->getBrightness());
        self::assertSame('F1E0B5', $device1->getColor());

        $device2 = $result->get(2000);
        self::assertInstanceOf(ControlOutlet::class, $device2);
        self::assertSame(2000, $device2->getId());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_CONTROL_OUTLET, $device2->getName());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_CONTROL_OUTLET, $device2->getType());
        self::assertSame('IKEA of Sweden', $device2->getManufacturer());
        self::assertSame('2.3.089', $device2->getVersion());

        $device3 = $result->get(6000);
        self::assertInstanceOf(Dimmer::class, $device3);
        self::assertSame(6000, $device3->getId());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, $device3->getName());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, $device3->getType());
        self::assertSame('IKEA of Sweden', $device3->getManufacturer());
        self::assertSame('24.4.5', $device3->getVersion());

        $device4 = $result->get(3000);
        self::assertInstanceOf(Remote::class, $device4);
        self::assertSame(3000, $device4->getId());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, $device4->getName());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, $device4->getType());
        self::assertSame('IKEA of Sweden', $device4->getManufacturer());
        self::assertSame('24.4.5', $device4->getVersion());

        $device5 = $result->get(7000);
        self::assertInstanceOf(RollerBlind::class, $device5);
        self::assertSame(7000, $device5->getId());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND, $device5->getName());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND, $device5->getType());
        self::assertSame('IKEA of Sweden', $device5->getManufacturer());
        self::assertSame('24.4.5', $device5->getVersion());

        $device6 = $result->get(5000);
        self::assertInstanceOf(MotionSensor::class, $device6);
        self::assertSame(5000, $device6->getId());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, $device6->getName());
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, $device6->getType());
        self::assertSame('IKEA of Sweden', $device6->getManufacturer());
        self::assertSame('24.4.5', $device6->getVersion());
        self::assertSame(
            [
                'id'           => 5000,
                'manufacturer' => 'IKEA of Sweden',
                'name'         => 'TRADFRI motion sensor',
                'type'         => 'TRADFRI motion sensor',
                'typeenum'     => \IKEA\Tradfri\Values\DeviceType::MOTION_SENSOR,
                'version'      => '24.4.5',
            ],
            $device6->jsonSerialize(),
        );

        self::assertCount(8, $result->getDevices());
        self::assertCount(3, $result->filterLightBulbs());
        self::assertCount(3, $result->filterLightBulbs()->sortByState());
        self::assertSame([
            1000 => [
                'brightness'    => 100.0,
                'color'         => 'F1E0B5',
                'id'            => 1000,
                'manufacturer'  => 'IKEA of Sweden',
                'name'          => 'TRADFRI bulb E27 CWS opal 600lm',
                'readablestate' => 'Off',
                'type'          => 'TRADFRI bulb E27 CWS opal 600lm',
                'typeenum'      => \IKEA\Tradfri\Values\DeviceType::BLUB,
                'version'       => '2.3.093',
            ],
            4001 => [
                'brightness'    => 100.0,
                'color'         => 'FF9834',
                'id'            => 4001,
                'manufacturer'  => 'IKEA of Sweden',
                'name'          => 'Wohnzimmer - Decke 1',
                'readablestate' => 'Off',
                'type'          => 'FLOALT panel 980lm',
                'typeenum'      => \IKEA\Tradfri\Values\DeviceType::FLOALT,
                'version'       => '2.3.095',
            ],
            4000 => [
                'brightness'    => 100.0,
                'color'         => 'FF9834',
                'id'            => 4000,
                'manufacturer'  => 'IKEA of Sweden',
                'name'          => 'Wohnzimmer - Fenster 1',
                'readablestate' => 'Off',
                'type'          => 'TRADFRI bulb E27 WS opal 980lm',
                'typeenum'      => \IKEA\Tradfri\Values\DeviceType::BLUB,
                'version'       => '2.3.095',
            ],
        ], $result->filterLightBulbs()->sortByState()->jsonSerialize());
    }

    public function testMapRollerBlindWithAttributes(): void
    {
        // Arrange
        $serviceMock = Mockery::mock(ServiceInterface::class);
        $mapper      = new DeviceData();

        $blindControl = new \IKEA\Tradfri\Dto\CoapResponse\BlindControlDto(12); // instance
        $deviceInfo   = new \IKEA\Tradfri\Dto\CoapResponse\DeviceInfoDto('IKEA', Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND, '1.0');
        $deviceDto    = new DeviceDto(
            123,
            'Blind',
            $deviceInfo,
            null,
            [$blindControl],
        );

        // Act
        $result = $mapper->map(
            $serviceMock,
            [$deviceDto],
            new Devices(),
        );

        // Assert
        $blind = $result->get(123);
        self::assertInstanceOf(RollerBlind::class, $blind);
        self::assertSame(12, $blind->getDarkenedState()); // Check if mapped
    }
}

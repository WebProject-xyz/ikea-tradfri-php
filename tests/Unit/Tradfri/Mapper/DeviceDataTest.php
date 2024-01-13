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

namespace IKEA\Tests\Unit\Tradfri\Mapper;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\ControlOutlet;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class DeviceDataTest.
 *
 * @method createMock($originalClassName)
 */
final class DeviceDataTest extends UnitTest
{
    protected \IKEA\Tests\Support\UnitTester $tester;

    public function testMapDataErrorNoData(): void
    {
        // Arrange
        $serviceMock = $this->createMock(ServiceInterface::class);
        $devices     = [];

        $mapper = new DeviceData();
        // Act
        $result = $mapper->map($serviceMock, $devices);
        // Assert
        $this->assertTrue($result->isEmpty());
    }

    public function testICanMapDataToCollectionWithNoError(): void
    {
        // Arrange
        $serviceMock = $this->createMock(ServiceInterface::class);

        $mapper = new DeviceData();
        // Act
        $result = $mapper->map($serviceMock, $this->tester->getDevicesDTOs());
        // Assert
        $this->assertFalse($result->isEmpty());
        $this->assertCount(7, $result);

        $device1 = $result->get(1000);
        $this->assertInstanceOf(LightBulb::class, $device1);
        $this->assertInstanceOf(\IKEA\Tradfri\Device\SwitchableInterface::class, $device1);
        $this->assertSame(1000, $device1->getId());
        $this->assertFalse($device1->isOn());
        $this->assertSame('Off', $device1->getReadableState());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_CWS_PAL_600_LM, $device1->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_CWS_PAL_600_LM, $device1->getType());
        $this->assertSame('IKEA of Sweden', $device1->getManufacturer());
        $this->assertSame('2.3.093', $device1->getVersion());
        $this->assertSame(100.0, $device1->getBrightness());
        $this->assertSame('F1E0B5', $device1->getColor());

        $device2 = $result->get(2000);
        $this->assertInstanceOf(ControlOutlet::class, $device2, $device2::class);
        $this->assertSame(2000, $device2->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_CONTROL_OUTLET, $device2->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_CONTROL_OUTLET, $device2->getType());
        $this->assertSame('IKEA of Sweden', $device2->getManufacturer());
        $this->assertSame('2.3.089', $device2->getVersion());

        $device3 = $result->get(6000);
        $this->assertInstanceOf(Dimmer::class, $device3);
        $this->assertFalse($device3->isLightBulb());
        $this->assertSame(6000, $device3->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, $device3->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, $device3->getType());
        $this->assertSame('IKEA of Sweden', $device3->getManufacturer());
        $this->assertSame('24.4.5', $device3->getVersion());

        $device4 = $result->get(3000);
        $this->assertInstanceOf(Remote::class, $device4);
        $this->assertFalse($device4->isLightBulb());
        $this->assertSame(3000, $device4->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, $device4->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, $device4->getType());
        $this->assertSame('IKEA of Sweden', $device4->getManufacturer());
        $this->assertSame('24.4.5', $device4->getVersion());

        $device5 = $result->get(7000);
        $this->assertInstanceOf(RollerBlind::class, $device5);
        $this->assertFalse($device5->isLightBulb());
        $this->assertSame(7000, $device5->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND, $device5->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND, $device5->getType());
        $this->assertSame('IKEA of Sweden', $device5->getManufacturer());
        $this->assertSame('24.4.5', $device5->getVersion());

        $device6 = $result->get(5000);
        $this->assertInstanceOf(MotionSensor::class, $device6);
        $this->assertFalse($device6->isLightBulb());
        $this->assertSame(5000, $device6->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, $device6->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, $device6->getType());
        $this->assertSame('IKEA of Sweden', $device6->getManufacturer());
        $this->assertSame('24.4.5', $device6->getVersion());

        $this->assertCount(7, $result->getDevices());
        $this->assertCount(2, $result->getLightBulbs());
    }
}

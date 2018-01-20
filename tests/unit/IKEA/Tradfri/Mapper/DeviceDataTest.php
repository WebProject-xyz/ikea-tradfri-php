<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Mapper;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class DeviceDataTest
 *
 * @method createMock($originalClassName)
 */
class DeviceDataTest extends UnitTest
{
    /**
     * @var \IKEA\Tests\UnitTester
     */
    protected $tester;

    public function testMapDataErrorNoData()
    {
        $this->expectExceptionMessage('no data');
        $this->expectException(RuntimeException::class);
        // Arrange
        $serviceMock = $this->createMock(ServiceInterface::class);
        $devices     = [];

        $mapper = new DeviceData();
        // Act
        $result = $mapper->map($serviceMock, $devices);
        // Assert
    }

    public function testICanMapDataToCollectionWithNoError()
    {
        // Arrange
        $serviceMock = $this->createMock(ServiceInterface::class);

        $mapper = new DeviceData();
        // Act
        $result = $mapper->map($serviceMock, $this->tester->getDevices());
        // Assert
        $this->tester->assertInstanceOf(Devices::class, $result);
        $this->tester->assertFalse($result->isEmpty());
        $this->tester->assertSame(5, $result->count());

        $device1 = $result->get(1000);
        $this->tester->assertInstanceOf(Lightbulb::class, $device1);
        $this->tester->assertTrue($device1->isLightbulb());
        $this->tester->assertSame(1000, $device1->getId());
        $this->tester->assertTrue($device1->isOn());
        $this->tester->assertSame('On', $device1->getState());
        $this->tester->assertSame(Device::TYPE_BLUB_E27_W, $device1->getName());
        $this->tester->assertSame(Device::TYPE_BLUB_E27_W, $device1->getType());
        $this->tester->assertSame('UnitTestFactory', $device1->getManufacturer());
        $this->tester->assertSame('v1.33.7', $device1->getVersion());
        $this->tester->assertSame(9.0, $device1->getBrightness());

        $device2 = $result->get(2000);
        $this->tester->assertInstanceOf(Lightbulb::class, $device2);
        $this->tester->assertTrue($device2->isLightbulb());
        $this->tester->assertSame(2000, $device2->getId());
        $this->tester->assertFalse($device2->isOn());
        $this->tester->assertSame('Off', $device2->getState());
        $this->tester->assertSame(Device::TYPE_BLUB_E27_WS, $device2->getName());
        $this->tester->assertSame(Device::TYPE_BLUB_E27_WS, $device2->getType());
        $this->tester->assertSame('UnitTestFactory', $device2->getManufacturer());
        $this->tester->assertSame('v1.33.7', $device2->getVersion());
        $this->tester->assertSame(9.0, $device2->getBrightness());

        $device3 = $result->get(3000);
        $this->tester->assertInstanceOf(Dimmer::class, $device3);
        $this->tester->assertFalse($device3->isLightbulb());
        $this->tester->assertSame(3000, $device3->getId());
        $this->tester->assertSame(Device::TYPE_DIMMER, $device3->getName());
        $this->tester->assertSame(Device::TYPE_DIMMER, $device3->getType());
        $this->tester->assertSame('UnitTestFactory', $device3->getManufacturer());
        $this->tester->assertSame('v1.33.7', $device3->getVersion());

        $device4 = $result->get(4000);
        $this->tester->assertInstanceOf(Remote::class, $device4);
        $this->tester->assertFalse($device4->isLightbulb());
        $this->tester->assertSame(4000, $device4->getId());
        $this->tester->assertSame(Device::TYPE_REMOTE_CONTROL, $device4->getName());
        $this->tester->assertSame(Device::TYPE_REMOTE_CONTROL, $device4->getType());
        $this->tester->assertSame('UnitTestFactory', $device4->getManufacturer());
        $this->tester->assertSame('v1.33.7', $device4->getVersion());

        $device5 = $result->get(5000);
        $this->tester->assertInstanceOf(MotionSensor::class, $device5);
        $this->tester->assertFalse($device5->isLightbulb());
        $this->tester->assertSame(5000, $device5->getId());
        $this->tester->assertSame(Device::TYPE_MOTION_SENSOR, $device5->getName());
        $this->tester->assertSame(Device::TYPE_MOTION_SENSOR, $device5->getType());
        $this->tester->assertSame('UnitTestFactory', $device5->getManufacturer());
        $this->tester->assertSame('v1.33.7', $device5->getVersion());

        $lights = $result->getLightbulbs();
        $this->tester->assertSame(2, $lights->count());
    }
}

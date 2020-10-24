<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Mapper;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\LightBulb;
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
        $devices = [];

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
        $this->assertInstanceOf(Devices::class, $result);
        $this->assertFalse($result->isEmpty());
        $this->assertSame(6, $result->count());

        $device1 = $result->get(1000);
        $this->assertInstanceOf(LightBulb::class, $device1);
        $this->assertTrue($device1->isLightbulb());
        $this->assertSame(1000, $device1->getId());
        $this->assertTrue($device1->isOn());
        $this->assertSame('On', $device1->getReadableState());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, $device1->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, $device1->getType());
        $this->assertSame('UnitTestFactory', $device1->getManufacturer());
        $this->assertSame('v1.33.7', $device1->getVersion());
        $this->assertSame(9.0, $device1->getBrightness());

        $device2 = $result->get(2000);
        $this->assertInstanceOf(LightBulb::class, $device2);
        $this->assertTrue($device2->isLightbulb());
        $this->assertSame(2000, $device2->getId());
        $this->assertFalse($device2->isOn());
        $this->assertSame('Off', $device2->getReadableState());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, $device2->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, $device2->getType());
        $this->assertSame('UnitTestFactory', $device2->getManufacturer());
        $this->assertSame('v1.33.7', $device2->getVersion());
        $this->assertSame(9.0, $device2->getBrightness());

        $device3 = $result->get(3000);
        $this->assertInstanceOf(Dimmer::class, $device3);
        $this->assertFalse($device3->isLightbulb());
        $this->assertSame(3000, $device3->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, $device3->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, $device3->getType());
        $this->assertSame('UnitTestFactory', $device3->getManufacturer());
        $this->assertSame('v1.33.7', $device3->getVersion());

        $device4 = $result->get(4000);
        $this->assertInstanceOf(Remote::class, $device4);
        $this->assertFalse($device4->isLightbulb());
        $this->assertSame(4000, $device4->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, $device4->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, $device4->getType());
        $this->assertSame('UnitTestFactory', $device4->getManufacturer());
        $this->assertSame('v1.33.7', $device4->getVersion());

        $device5 = $result->get(5000);
        $this->assertInstanceOf(MotionSensor::class, $device5);
        $this->assertFalse($device5->isLightbulb());
        $this->assertSame(5000, $device5->getId());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, $device5->getName());
        $this->assertSame(Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, $device5->getType());
        $this->assertSame('UnitTestFactory', $device5->getManufacturer());
        $this->assertSame('v1.33.7', $device5->getVersion());

        $this->assertCount(6, $result->getDevices());
        $this->assertCount(2, $result->getLightbulbs());
    }
}

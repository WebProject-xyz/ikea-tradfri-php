<?php

declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Device;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\DeviceInterface;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class DeviceTest.
 *
 * @method createMock($originalClassName)
 */
abstract class DeviceTester extends UnitTest
{
    /**
     * @var int
     */
    protected $_id = 1;

    abstract public function testGetAnInstance(): void;

    public function testICanNotGetService(): void
    {
        // Arrange
        $this->expectException('RuntimeException');
        $lamp = $this->getModel();

        // Act
        $lamp->getService();

        // Assert
    }

    /**
     * @return Device
     */
    abstract protected function getModel(): DeviceInterface;

    public function testICanGetService(): void
    {
        // Arrange
        $device = $this->getModel();

        $serviceMock = $this->createMock(ServiceInterface::class);
        // Act
        $device->setService($serviceMock);
        $service = $device->getService();

        // Assert
        $this->assertInstanceOf(ServiceInterface::class, $service);
    }

    public function testGetSetName(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setName('UnitTest');
        // Act
        $result = $lamp->getName();

        // Assert
        $this->assertSame('UnitTest', $result);
    }

    public function testGetSetManufacturer(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setManufacturer('UnitTest');
        // Act
        $result = $lamp->getManufacturer();

        // Assert
        $this->assertSame('UnitTest', $result);
    }

    public function testGetSetId(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setId(2);
        // Act
        $result = $lamp->getId();

        // Assert
        $this->assertSame(2, $result);
    }

    public function testGetSetVersion(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setVersion('V123');
        // Act
        $result = $lamp->getVersion();

        // Assert
        $this->assertSame('V123', $result);
    }
}

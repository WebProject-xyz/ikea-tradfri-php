<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Device;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class DeviceTest
 *
 * @method createMock($originalClassName)
 */
abstract class DeviceTester extends UnitTest
{
    /**
     * @var int
     */
    protected $id = 1;

    abstract public function testGetAnInstance();

    public function testICanNotGetService()
    {
        // Arrange
        $this->expectException('TypeError');
        $lamp = $this->_getModel();

        // Act
        $lamp->getService();

        // Assert
    }

    /**
     * @return Device
     */
    abstract protected function _getModel();

    public function testICanGetService()
    {
        // Arrange
        $device = $this->_getModel();

        $serviceMock = $this->createMock(ServiceInterface::class);
        // Act
        $device->setService($serviceMock);
        $service = $device->getService();

        // Assert
        $this->assertInstanceOf(ServiceInterface::class, $service);
    }

    public function testGetSetName()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setName('UnitTest');
        // Act
        $result = $lamp->getName();

        // Assert
        $this->assertSame('UnitTest', $result);
    }

    public function testGetSetManufacturer()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setManufacturer('UnitTest');
        // Act
        $result = $lamp->getManufacturer();

        // Assert
        $this->assertSame('UnitTest', $result);
    }

    public function testGetSetId()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setId(2);
        // Act
        $result = $lamp->getId();

        // Assert
        $this->assertSame(2, $result);
    }

    public function testGetSetVersion()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setVersion('V123');
        // Act
        $result = $lamp->getVersion();

        // Assert
        $this->assertSame('V123', $result);
    }

    public function testSetTypeError()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('unknown type');

        $this->_getModel()->setType('asdf');
    }
}

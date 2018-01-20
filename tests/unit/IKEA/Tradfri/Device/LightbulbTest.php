<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Device;

use IKEA\Tradfri\Client\Client;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class LightbulbTest
 */
class LightbulbTest extends \IKEA\Tests\Tradfri\Device\DeviceTester
{
    public function testGetAnInstance()
    {
        // Arrange
        // Act
        $lamp = $this->_getModel();
        // Assert
        $this->assertInstanceOf(Lightbulb::class, $lamp);
    }

    /**
     * @return \IKEA\Tradfri\Device\Lightbulb
     */
    protected function _getModel(): Lightbulb
    {
        $lamp = new Lightbulb($this->id, Device::TYPE_BLUB_E27_W);

        return $lamp;
    }

    public function testSetType()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setType(Device::TYPE_BLUB_E27_W);
        // Act
        $result = $lamp->getType();

        // Assert
        $this->assertSame(Device::TYPE_BLUB_E27_W, $result);
    }

    public function testIsLightbulb()
    {
        // Arrange
        $lamp = $this->_getModel();
        // Act
        // Assert
        $this->assertTrue($lamp->isLightbulb());
    }

    public function testGetBrightnessButNotSet()
    {
        // Arrange
        $lamp = $this->_getModel();

        // Act
        $result = $lamp->getBrightness();

        // Assert
        $this->assertSame(0.0, $result);
    }

    public function testGetBrightness()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setBrightness((int) \round(30 * 2.54));
        // Act
        $result = $lamp->getBrightness();

        // Assert
        $this->assertSame(30.0, $result);
    }

    public function testSetBrightnessToLow()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setBrightness(-12);
        // Act
        $result = $lamp->getBrightness();

        // Assert
        $this->assertSame(0.0, $result);
    }

    public function testSetTypeE27WS()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setType(Device::TYPE_BLUB_E27_WS);
        // Act
        $result = $lamp->getType();

        // Assert
        $this->assertSame(Device::TYPE_BLUB_E27_WS, $result);
    }

    public function testSetTypeGU10()
    {
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setType(Device::TYPE_BLUB_GU10);
        // Act
        $result = $lamp->getType();

        // Assert
        $this->assertSame(Device::TYPE_BLUB_GU10, $result);
    }

    public function testStates()
    {
        // Arrange
        $lamp = $this->_getModel();
        $this->assertFalse($lamp->isOn());

        // Act
        $lamp->setState(true);

        // Assert
        $this->assertTrue($lamp->isOn());
        $this->assertSame('On', $lamp->getState());
    }

    public function testICanSwitchOn()
    {
        // Arrange
        $lamp = $this->_getModel();

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOn')->andReturn(true);

        $service = new Api($client);

        $lamp->setService($service);
        $this->assertFalse($lamp->isOn());

        // Act
        $result = $lamp->switchOn();

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($lamp->isOn());
        $this->assertSame('On', $lamp->getState());

        // Act
        $result = $lamp->switchOn();
        // Assert
        $this->assertTrue($result);
        $this->assertTrue($lamp->isOn());
        $this->assertSame('On', $lamp->getState());
    }

    public function testICanSwitchOnFails()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('switch ON failed');
        // Arrange
        $lamp = $this->_getModel();

        /** @var ServiceInterface $service */
        $service = \Mockery::mock(Api::class);
        $service->shouldReceive('switchOn')->andReturn(false);

        $lamp->setService($service);
        $this->assertFalse($lamp->isOn());

        // Act
        $result = $lamp->switchOn();

        // Assert
        $this->assertFalse($result);
        $this->assertFalse($lamp->isOn());
        $this->assertSame('Off', $lamp->getState());
    }

    public function testICanSwitchOff()
    {
        // Arrange
        $lamp = $this->_getModel();
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOff')->andReturn(true);

        $service = new Api($client);

        $lamp->setService($service);
        $this->assertFalse($lamp->isOn());

        // Act
        $result = $lamp->switchOff();

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($lamp->isOn());
        $this->assertSame('Off', $lamp->getState());

        // Act
        $result = $lamp->switchOff();

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($lamp->isOn());
        $this->assertSame('Off', $lamp->getState());
    }

    public function testICanSwitchOffFails()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('unable to change state of lightbulb: 1');
        // Arrange
        $lamp = $this->_getModel();
        $lamp->setState(true);

        /** var Client $client */
        $client = \Mockery::mock(Client::class);
        $client
            ->shouldReceive('lightOff')
            ->andThrow(
                new RuntimeException('unable to change state of lightbulb: 1')
            );

        $service = new Api($client);

        $lamp->setService($service);
        $this->assertTrue($lamp->isOn());

        // Act
        $result = $lamp->switchOff();

        // Assert
        $this->assertFalse($result);
        $this->assertTrue($lamp->isOn());
        $this->assertSame('On', $lamp->getState());
    }

    public function testICanSwitchOffReturnedFalse()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('switch OFF failed');

        // Arrange
        $lamp    = $this->_getModel();
        $service = \Mockery::mock(Api::class);
        $service->shouldReceive('off')->andReturn(false);

        $lamp->setService($service);
        $this->assertFalse($lamp->isOn());

        $lamp->setState(true);
        $this->assertTrue($lamp->isOn());

        // Act
        $result = $lamp->switchOff();

        // Assert
        $this->assertFalse($result);
        $this->assertTrue($lamp->isOn());
        $this->assertSame('On', $lamp->getState());

        // Act 2
        $result = $lamp->switchOff();

        // Assert 2
        $this->assertFalse($result);
        $this->assertTrue($lamp->isOn());
        $this->assertSame('On', $lamp->getState());
    }
}

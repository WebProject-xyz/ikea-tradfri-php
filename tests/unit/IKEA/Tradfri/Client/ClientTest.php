<?php
declare(strict_types=1);

namespace IKEA\Tests\IKEA\Tradfri\Client;

use IKEA\Tradfri\Adapter\AdapterInterface;
use IKEA\Tradfri\Client\Client;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Service\ServiceInterface;

class ClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \IKEA\Tests\UnitTester
     */
    protected $tester;

    public function testICanGetAnInstanceOfClient()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        // Act
        $client = new Client($adapter);
        // Assert
        $this->tester->assertInstanceOf(Client::class, $client);
    }

    public function testICanGetDevicesFromClient()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('getDeviceCollection')->andReturn(new Devices());

        $client = new Client($adapter);
        // Act
        $devices = $client->getDevices(\Mockery::mock(ServiceInterface::class));
        // Assert
        $this->tester->assertInstanceOf(Devices::class, $devices);
        $this->tester->assertTrue($devices->isEmpty());
        $this->tester->assertCount(0, $devices->toArray());
    }

    public function testICanGetGroupFromClient()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('getGroupCollection')->andReturn(new Groups());

        $client = new Client($adapter);
        // Act
        $groups = $client->getGroups(\Mockery::mock(ServiceInterface::class));
        // Assert
        $this->tester->assertInstanceOf(Groups::class, $groups);
        $this->tester->assertTrue($groups->isEmpty());
        $this->tester->assertCount(0, $groups->toArray());
    }

    public function testICanTurnLightOn()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('changeLightState')->andReturn(true);

        $client = new Client($adapter);
        $light = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);
        // Act
        $result = $client->lightOn($light);
        // Assert
        $this->tester->assertTrue($result);
    }

    public function testICanTurnLightOff()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('changeLightState')->andReturn(true);

        $client = new Client($adapter);
        $light = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);
        // Act
        $result = $client->lightOff($light);
        // Assert
        $this->tester->assertTrue($result);
    }

    public function testICanTurnGroupOn()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('changeGroupState')->andReturn(true);

        $client = new Client($adapter);
        $group = new Light(1, \Mockery::mock(ServiceInterface::class));
        // Act
        $result = $client->groupOn($group);
        // Assert
        $this->tester->assertTrue($result);
    }

    public function testICanTurnGroupOff()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('changeGroupState')->andReturn(true);

        $client = new Client($adapter);
        $group = new Light(1, \Mockery::mock(ServiceInterface::class));
        // Act
        $result = $client->groupOff($group);
        // Assert
        $this->tester->assertTrue($result);
    }

    public function testICanDimLight()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('setLightBrightness')->andReturn(true);

        $client = new Client($adapter);
        $light = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);
        // Act
        $result = $client->dimLight($light, 50);
        // Assert
        $this->tester->assertTrue($result);
    }

    public function testICanDimGroup()
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->shouldReceive('setGroupBrightness')->andReturn(true);

        $client = new Client($adapter);
        $group = new Light(1, \Mockery::mock(ServiceInterface::class));
        // Act
        $result = $client->dimGroup($group, 50);
        // Assert
        $this->tester->assertTrue($result);
    }
}

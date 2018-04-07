<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Service;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Client\Client;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Collection\Lightbulbs;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\Light as Group;
use IKEA\Tradfri\Service\Api;

/**
 * Class ApiTest
 */
class ApiTest extends UnitTest
{
    public function testIGotAnInstanceOfApiService()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        // Act
        $service = new Api($client);
        // Assert
        $this->assertInstanceOf(Api::class, $service);
    }

    public function testICanGetDevicesCollectionFromService()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('getDevices')->andReturn(new Devices());
        $service = new Api($client);

        // Act
        $result = $service->getDevices();

        // Assert
        $this->assertInstanceOf(Devices::class, $result);
    }

    public function testICanGetLightblubsCollectionFromService()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('getDevices')->andReturn(new Devices());

        $service = new Api($client);

        // Act
        $result = $service->getLights();

        // Assert
        $this->assertInstanceOf(Devices::class, $result);
        $this->assertInstanceOf(Lightbulbs::class, $result);
    }

    public function testICanSwitchLightOff()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOff')->andReturn(true);

        $service = new Api($client);

        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_WS);
        $lightbulb->setState(true);

        $this->assertTrue($lightbulb->isOn());
        // Act
        $result = $service->off($lightbulb);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotSwitchLightOff()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOff')->andReturn(false);

        $service = new Api($client);

        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_WS);
        $lightbulb->setState(true);

        $this->assertTrue($lightbulb->isOn());
        // Act
        $result = $service->off($lightbulb);
    }

    public function testICanNotSwitchLightOffBecauseItIsOff()
    {
        // Arrange
        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_WS);
        $lightbulb->setState(false);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOff')->andReturn(true);
        $service = new Api($client);

        $this->assertFalse($lightbulb->isOn());

        // Act
        $result = $service->off($lightbulb);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchLightOn()
    {
        // Arrange
        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_WS);
        $lightbulb->setState(false);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOn')->andReturn(true);
        $service = new Api($client);

        $this->assertFalse($lightbulb->isOn());

        // Act
        $result = $service->switchOn($lightbulb);

        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotSwitchLightOn()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('unable to change state of lightbulb: 1');

        // Arrange
        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_WS);
        $lightbulb->setState(false);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOn')->andThrow(new RuntimeException('unable to change state of lightbulb: 1'));
        $service = new Api($client);

        $this->assertFalse($lightbulb->isOn());

        // Act
        $result = $service->switchOn($lightbulb);
    }

    public function testICanNotSwitchLightOnBecauseItIsOn()
    {
        // Arrange
        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_WS);
        $lightbulb->setState(true);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOn')->andReturn(true);
        $service = new Api($client);

        $this->assertTrue($lightbulb->isOn());

        // Act
        $result = $service->switchOn($lightbulb);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchAllLightsOff()
    {
        // Arrange
        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_WS);
        $lightbulb->setState(true);

        $lightbulbs = new Lightbulbs();
        $lightbulbs->addDevice(clone $lightbulb);
        $lightbulbs->addDevice((clone $lightbulb)->setId(2));

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('lightOff')->andReturn(true);
        $service = new Api($client);

        // Act
        $result = $service->allLightsOff($lightbulbs);

        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotSwitchGroupOnBecauseItIsOn()
    {
        // Arrange

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('groupOn')->andReturn(true);
        $service = new Api($client);

        $group = new Group(1, $service);
        $group->setState(true);
        $group->getDevices()->addDevice((new Lightbulb(2, Lightbulb::TYPE_BLUB_E27_W))
            ->setState(true)
            ->setName('test')
        );

        $this->assertTrue($group->isOn());

        // Act
        $result = $service->switchOn($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchGroupOn()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('groupOn')->andReturn(true);
        $service = new Api($client);

        $group = new Group(1, $service);
        $group->getDevices()->addDevice((new Lightbulb(2, Lightbulb::TYPE_BLUB_E27_W))
            ->setState(false)
            ->setName('test'));

        $this->assertFalse($group->isOn());

        // Act
        $result = $service->switchOn($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchGroupOff()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('groupOff')->andReturn(true);
        $service = new Api($client);
        $group = new Group(1, $service);

        $group->getDevices()->addDevice((new Lightbulb(2, Lightbulb::TYPE_BLUB_E27_W))
            ->setState(true)
            ->setName('test')
        );

        $this->assertTrue($group->isOn());

        // Act
        $result = $service->off($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchADimmerOn()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: TRADFRI dimmer');
        // Arrange
        $dimmer = new Dimmer(1);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $service = new Api($client);
        // Act
        $result = $service->switchOn($dimmer);
    }

    public function testICanSwitchADimmerOff()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: TRADFRI dimmer');
        // Arrange
        $dimmer = new Dimmer(1);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $service = new Api($client);
        // Act
        $result = $service->off($dimmer);
    }

    public function testICanSwitchARemoteOn()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: TRADFRI remote control');
        // Arrange
        $remote = new Remote(1);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $service = new Api($client);
        // Act
        $result = $service->switchOn($remote);
    }

    public function testICanSwitchARemoteOff()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: TRADFRI remote control');
        // Arrange
        $remote = new Remote(1);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $service = new Api($client);
        // Act
        $result = $service->off($remote);
    }

    public function testICanDimAGroup()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('dimGroup')->andReturn(true);

        $service = new Api($client);
        $group = new Group(1, $service);
        // Act
        $result = $service->dim($group, 20);
        // Assert
        $this->assertTrue($result);
    }
    public function testICanDimALight()
    {
        // Arrange
        $lightbulb = new Lightbulb(1, Lightbulb::TYPE_BLUB_E27_W);

        // @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('dimLight')->andReturn(true);

        $service = new Api($client);
        // Act
        $result = $service->dim($lightbulb, 20);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotDimADimmer()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: TRADFRI dimmer');
        // Arrange
        $dimmer = new Dimmer(1);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $service = new Api($client);
        // Act
        $result = $service->dim($dimmer, 20);
    }

    public function testICanNotDimARemote()
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: TRADFRI remote control');
        // Arrange
        $remote = new Remote(1);

        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $service = new Api($client);
        // Act
        $result = $service->dim($remote, 20);
    }

    public function testICanGetGroupsFromService()
    {
        // Arrange
        /** @var Client $client */
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('getGroups')->andReturn(new Groups());
        $service = new Api($client);

        // Act
        $groups = $service->getGroups();

        // Assert
        $this->assertInstanceOf(Groups::class, $groups);
        $this->assertTrue($groups->isEmpty());
        $this->assertCount(0, $groups);
    }
}

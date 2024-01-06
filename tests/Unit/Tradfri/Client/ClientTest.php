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

namespace IKEA\Tests\Unit\Tradfri\Client;

use IKEA\Tradfri\Adapter\AdapterInterface;
use IKEA\Tradfri\Client\Client;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Service\ServiceInterface;

final class ClientTest extends \Codeception\Test\Unit
{
    protected \IKEA\Tests\Support\UnitTester $tester;

    public function testICanGetAnInstanceOfClient(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        // Act
        $client = new Client($adapter);
        // Assert
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testICanGetDevicesFromClient(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('getDeviceCollection')->andReturn(new Devices());

        $client = new Client($adapter);
        // Act
        $devices = $client->getDevices(\Mockery::mock(ServiceInterface::class));
        // Assert
        $this->assertInstanceOf(Devices::class, $devices);
        $this->assertTrue($devices->isEmpty());
        $this->assertCount(0, $devices->toArray());
    }

    public function testICanGetGroupFromClient(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('getGroupCollection')->andReturn(new Groups());

        $client = new Client($adapter);
        // Act
        $groups = $client->getGroups(\Mockery::mock(ServiceInterface::class));
        // Assert
        $this->assertInstanceOf(Groups::class, $groups);
        $this->assertTrue($groups->isEmpty());
        $this->assertCount(0, $groups->toArray());
    }

    public function testICanTurnLightOn(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('changeLightState')->andReturn(true);

        $client = new Client($adapter);
        $light  = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);
        // Act
        $result = $client->lightOn($light);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanTurnLightOff(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('changeLightState')->andReturn(true);

        $client = new Client($adapter);
        $light  = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);
        // Act
        $result = $client->lightOff($light);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanTurnGroupOn(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('changeGroupState')->andReturn(true);

        $client = new Client($adapter);
        $group  = new Light(1, \Mockery::mock(ServiceInterface::class));
        // Act
        $result = $client->groupOn($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanTurnGroupOff(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('changeGroupState')->andReturn(true);

        $client = new Client($adapter);
        $group  = new Light(1, \Mockery::mock(ServiceInterface::class));
        // Act
        $result = $client->groupOff($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanDimLight(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('setLightBrightness')->andReturn(true);

        $client = new Client($adapter);
        $light  = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);
        // Act
        $result = $client->dimLight($light, 50);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanDimGroup(): void
    {
        // Arrange
        $adapter = \Mockery::mock(AdapterInterface::class);
        $adapter->expects('setGroupBrightness')->andReturn(true);

        $client = new Client($adapter);
        $group  = new Light(1, \Mockery::mock(ServiceInterface::class));
        // Act
        $result = $client->dimGroup($group, 50);
        // Assert
        $this->assertTrue($result);
    }
}

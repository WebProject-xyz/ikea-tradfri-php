<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Group;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Group\DeviceGroup;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class LightTest.
 */
final class DeviceGroupTest extends UnitTest
{
    public function testICanInitGroupOfLights(?ServiceInterface $service = null): DeviceGroup
    {
        // Arrange
        $service ??= \Mockery::mock(ServiceInterface::class);

        // Act
        $group = new DeviceGroup(1, $service);
        $group->setName('$name');

        // Assert
        $this->assertInstanceOf(DeviceGroup::class, $group);

        return $group;
    }

    public function testICanGetDeviceIdsFromGroup(): void
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $deviceIds = $group->getDeviceIds();

        // Assert
        $this->assertCount(0, $deviceIds);
    }

    public function testICanSetDevicesCollectionToGroup(): void
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $group->setDevices(new Devices());

        $result = $group->getDevices();
        $resultLights = $group->getLights();
        // Assert
        $this->assertCount(0, $result);
        $this->assertCount(0, $resultLights);
    }

    public function testEnumTypeError(): void
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $this->expectExceptionMessage('not implemented for groups');

        $group->getTypeEnum();
    }

    public function testICanSwitchOnGroup(): void
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);

        $group = $this->testICanInitGroupOfLights($service);

        $service->expects('on')->with($group)->andReturnTrue();
        // Act
        $group->setDevices(new Devices());

        // Assert
        $this->assertTrue($group->switchOn());
    }

    public function testICanSwitchOffGroup(): void
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);

        $group = $this->testICanInitGroupOfLights($service);

        $service->expects('off')->twice()->with($group)->andReturnTrue();
        // Act
        $group->setDevices(new Devices());

        // Assert
        $this->assertTrue($group->switchOff());
        $this->assertFalse($group->off()->isOn());
        $this->assertSame('GROUP: $name', $group->getType());
    }

    public function testICanDimOffGroup(): void
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);

        $group = $this->testICanInitGroupOfLights($service);

        $service->expects('dim')->with($group, 42)->andReturnTrue();
        // Act
        $group->setDevices(new Devices());

        // Assert
        $this->assertSame(0.0, $group->getBrightness());

        $group->dim(42);
        $this->assertSame(42.0, $group->getBrightness());
    }

    public function testJsonSerialize(): void
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);
        $group = $this->testICanInitGroupOfLights($service);
        $light = \Mockery::mock(\IKEA\Tradfri\Device\Feature\DeviceInterface::class);
        $light->shouldReceive('getId')->andReturn(100);
        $light->shouldReceive('jsonSerialize')->andReturn(['id' => 100]);

        $devices = new Devices();
        $devices->add($light);
        $group->setDevices($devices);

        // Act
        $json = (string) \json_encode($group);

        // Assert
        $this->assertJsonStringEqualsJsonString('{"100":{"id":100}}', $json);
    }

    public function testIsOnWithActiveLights(): void
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);
        $group = $this->testICanInitGroupOfLights($service);

        /** @var \IKEA\Tradfri\Device\LightBulb|\Mockery\MockInterface $light */
        $light = \Mockery::mock(\IKEA\Tradfri\Device\LightBulb::class);
        $light->shouldReceive('getId')->andReturn(100);
        $light->shouldReceive('getTypeEnum')->andReturn(\IKEA\Tradfri\Values\DeviceType::BLUB);
        $light->shouldReceive('isOn')->andReturnTrue();
        $light->shouldReceive('getName')->andReturn('Light 1');

        $devices = new Devices();
        $devices->add($light);
        $group->setDevices($devices);

        // Act
        // Assert
        $this->assertTrue($group->isOn());
        $this->assertFalse($group->isOff());
    }

    public function testServiceFailures(): void
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);
        $group = $this->testICanInitGroupOfLights($service);

        $service->expects('on')->with($group)->andReturnFalse();
        $service->expects('off')->with($group)->andReturnFalse();
        $service->expects('dim')->with($group, 50)->andReturnFalse();

        // Act & Assert
        $this->assertFalse($group->switchOn());
        $this->assertFalse($group->switchOff());
        $this->assertFalse($group->dim(50));
    }
}

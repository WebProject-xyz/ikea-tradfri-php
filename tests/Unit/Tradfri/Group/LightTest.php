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
final class LightTest extends UnitTest
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

        $result       = $group->getDevices();
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
}

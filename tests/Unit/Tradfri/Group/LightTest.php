<?php

declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Group;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Service\ServiceInterface;
use Mockery;

/**
 * Class LightTest.
 */
class LightTest extends UnitTest
{
    public function testICanInitGroupOfLights(ServiceInterface $service = null): Light
    {
        // Arrange
        $service ??= Mockery::mock(ServiceInterface::class);

        // Act
        $group = new Light(1, $service);

        // Assert
        $this->assertInstanceOf(Light::class, $group);

        return $group;
    }

    public function testICanGetDeviceIdsFromGroup(): void
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $deviceIds = $group->getDeviceIds();

        // Assert
        $this->assertIsArray($deviceIds);
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

    public function testICanSwitchOnGroup(): void
    {
        // Arrange
        $service = Mockery::mock(ServiceInterface::class);

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
        $service = Mockery::mock(ServiceInterface::class);

        $group = $this->testICanInitGroupOfLights($service);

        $service->expects('off')->twice()->with($group)->andReturnTrue();
        // Act
        $group->setDevices(new Devices());

        // Assert
        $this->assertTrue($group->switchOff());
        $this->assertFalse($group->off()->isOn());
    }

    public function testICanDimOffGroup(): void
    {
        // Arrange
        $service = Mockery::mock(ServiceInterface::class);

        $group = $this->testICanInitGroupOfLights($service);

        $service->expects('dim')->with($group, 42)->andReturnTrue();
        // Act
        $group->setDevices(new Devices());

        // Assert
        $this->assertSame(0.0, $group->getBrightness());
        $this->assertSame(42.0, $group->dim(42)->getBrightness());
    }
}

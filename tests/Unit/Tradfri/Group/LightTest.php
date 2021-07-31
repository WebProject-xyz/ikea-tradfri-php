<?php

declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Group;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\LightBulbs;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class LightTest.
 */
class LightTest extends UnitTest
{
    public function testICanInitGroupOfLights() : Light
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);

        // Act
        $group = new Light(1, $service);

        // Assert
        $this->assertInstanceOf(Light::class, $group);

        return $group;
    }

    public function testICanGetDeviceIdsFromGroup() : void
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $deviceIds = $group->getDeviceIds();

        // Assert
        $this->assertTrue(\is_array($deviceIds));
        $this->assertCount(0, $deviceIds);
    }

    public function testICanSetDevicesCollectionToGroup() : void
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $group->setDevices(new Devices());

        $result = $group->getDevices();
        $resultLights = $group->getLights();
        // Assert
        $this->assertInstanceOf(Devices::class, $result);
        $this->assertInstanceOf(LightBulbs::class, $resultLights);
    }
}

<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Group;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Lightbulbs;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class LightTest
 *
 * @package IKEA\Tests\Tradfri\Group
 */
class LightTest extends UnitTest
{
    public function testICanInitGroupOfLights()
    {
        // Arrange
        $service = \Mockery::mock(ServiceInterface::class);

        // Act
        $group = new Light(1, $service);

        // Assert
        $this->assertInstanceOf(Light::class, $group);

        return $group;
    }

    public function testICanGetDeviceIdsFromGroup()
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $deviceIds = $group->getDeviceIds();

        // Assert
        $this->assertTrue(\is_array($deviceIds));
        $this->assertCount(0, $deviceIds);
    }

    public function testICanSetDevicesCollectionToGroup()
    {
        // Arrange
        $group = $this->testICanInitGroupOfLights();

        // Act
        $group->setDevices(new Devices());

        $result = $group->getDevices();
        $resultLights = $group->getLights();
        // Assert
        $this->assertInstanceOf(Devices::class, $result);
        $this->assertInstanceOf(Lightbulbs::class, $resultLights);
    }
}

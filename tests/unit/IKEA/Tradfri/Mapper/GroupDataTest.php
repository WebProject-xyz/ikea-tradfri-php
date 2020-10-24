<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Mapper;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Group\Light as Group;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class DeviceDataTest
 */
class GroupDataTest extends UnitTest
{
    /**
     * @var \IKEA\Tests\UnitTester
     */
    protected $tester;

    public function testICanMapEmptyDataWithNoError(): void
    {
        // Arrange
        $serviceMock = \Mockery::mock(ServiceInterface::class);
        $devices = [];

        $mapper = new GroupData();
        // Act
        $result = $mapper->map($serviceMock, $devices);
        // Assert
        $this->tester->assertInstanceOf(\IKEA\Tradfri\Collection\Groups::class, $result);
    }

    public function testICanMapDataToCollectionWithNoError(): void
    {
        // Arrange
        $serviceMock = \Mockery::mock(ServiceInterface::class);

        $mapper = new GroupData();
        // Act
        $result = $mapper->map($serviceMock, $this->tester->getGroupDataCoapsResponse());
        // Assert
        $this->tester->assertInstanceOf(Groups::class, $result);
        $this->tester->assertFalse($result->isEmpty());
        $this->tester->assertSame(3, $result->count());

        /** @var Group $group1 */
        $group1 = $result->get(1000);
        $this->tester->assertInstanceOf(Group::class, $group1);
        $this->tester->assertSame(1000, $group1->getId());
        $this->tester->assertFalse($group1->isOn());
        $this->tester->assertSame('Group 1', $group1->getName());
        $this->tester->assertSame(38.0, $group1->getBrightness());

        /** @var Group $group1 */
        $group2 = $result->get(2000);
        $this->tester->assertInstanceOf(Group::class, $group2);
        $this->tester->assertSame(2000, $group2->getId());
        $this->tester->assertFalse($group2->isOn());
        $this->tester->assertSame('Group 2', $group2->getName());
        $this->tester->assertSame(0.0, $group2->getBrightness());

        /** @var Group $group3 */
        $group3 = $result->get(3000);
        $this->tester->assertInstanceOf(Group::class, $group3);
        $this->tester->assertSame(3000, $group3->getId());
        $this->tester->assertFalse($group3->isOn());
        $this->tester->assertSame('Group 3', $group3->getName());
        $this->tester->assertSame(0.0, $group3->getBrightness());
    }
}

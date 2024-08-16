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

namespace IKEA\Tests\Unit\Tradfri\Mapper;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use IKEA\Tradfri\Group\DeviceGroup as Group;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Util\JsonIntTypeNormalizer;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;

/**
 * Class DeviceDataTest.
 */
final class GroupDataTest extends UnitTest
{
    protected \IKEA\Tests\Support\UnitTester $tester;

    public function testICanMapEmptyDataWithNoError(): void
    {
        // Arrange
        $serviceMock = \Mockery::mock(ServiceInterface::class);
        $devices     = [];

        $mapper = new GroupData();
        $groups = new Groups();
        // Act
        $result = $mapper->map($serviceMock, $devices, $groups);
        // Assert
        $this->assertInstanceOf(Groups::class, $result);
        $this->assertSame($groups, $result);
    }

    public function testICanMapDataToCollectionWithNoError(): void
    {
        // Arrange
        $serviceMock = \Mockery::mock(ServiceInterface::class);

        $mapper = new GroupData();
        $groups = new Groups();
        // Act
        $groupsItems              =[];
        $jsonDeviceDataSerializer = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();
        foreach ($this->tester->getGroupDataCoapsResponse() as $item) {
            try {
                $groupsItems[] = $jsonDeviceDataSerializer->deserialize(
                    (new JsonIntTypeNormalizer())(
                        jsonString: \json_encode($item),
                        targetClass: GroupDto::class
                    ),
                    GroupDto::class,
                    $jsonDeviceDataSerializer::FORMAT,
                );
            } catch (MissingConstructorArgumentsException $exception) {
                \codecept_debug('VALID CASE: ' . $exception->getMessage());
                continue;
            }
        }

        $result = $mapper->map($serviceMock, $groupsItems, $groups);

        // Assert
        \Mockery::close();
        $this->assertInstanceOf(Groups::class, $result);
        $this->assertFalse($result->isEmpty());
        $this->assertCount(3, $result);

        $group1 = $result->get(1000);
        $this->assertInstanceOf(Group::class, $group1);
        $this->assertSame(1000, $group1->getId());
        $this->assertFalse($group1->isOn());
        $this->assertFalse($group1->isOff());
        $this->assertSame('Group 1', $group1->getName());
        $this->assertSame(38.0, $group1->getBrightness());

        $group2 = $result->get(2000);
        $this->assertInstanceOf(Group::class, $group2);
        $this->assertSame(2000, $group2->getId());
        $this->assertFalse($group2->isOn());
        $this->assertSame('Group 2', $group2->getName());
        $this->assertSame(0.0, $group2->getBrightness());

        $group3 = $result->get(3000);
        $this->assertInstanceOf(Group::class, $group3);
        $this->assertSame(3000, $group3->getId());
        $this->assertFalse($group3->isOn());
        $this->assertSame('Group 3', $group3->getName());
        $this->assertSame(0.0, $group3->getBrightness());
    }
}

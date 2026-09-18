<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl.
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
use Mockery;
use stdClass;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Webmozart\Assert\Assert;

/**
 * Class DeviceDataTest.
 */
final class GroupDataTest extends UnitTest
{
    protected \IKEA\Tests\Support\UnitTester $tester;

    public function testICanMapEmptyDataWithNoError(): void
    {
        // Arrange
        $serviceMock = Mockery::mock(ServiceInterface::class);
        $devices     = [];

        $mapper = new GroupData();
        $groups = new Groups();
        // Act
        $result = $mapper->map($serviceMock, $devices, $groups);
        // Assert
        self::assertInstanceOf(Groups::class, $result);
        self::assertSame($groups, $result);
    }

    public function testMapLogsWarningOnInvalidItem(): void
    {
        // Arrange
        $serviceMock = Mockery::mock(ServiceInterface::class);
        $loggerMock  = Mockery::mock(\Psr\Log\LoggerInterface::class);
        $loggerMock->shouldReceive('warning')->once();

        $mapper = new GroupData();
        $mapper->setLogger($loggerMock);
        $groups = new Groups();

        // Act
        /** @phpstan-ignore-next-line */
        $result = $mapper->map($serviceMock, [new stdClass()], $groups);

        // Assert
        self::assertInstanceOf(Groups::class, $result);
        self::assertTrue($result->isEmpty());
    }

    public function testICanMapDataToCollectionWithNoError(): void
    {
        // Arrange
        $serviceMock = Mockery::mock(ServiceInterface::class);

        $mapper = new GroupData();
        $groups = new Groups();
        // Act
        $groupsItems              = [];
        $jsonDeviceDataSerializer = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();
        foreach ($this->tester->getGroupDataCoapsResponse() as $item) {
            try {
                $groupsItems[] = $jsonDeviceDataSerializer->deserialize(
                    (new JsonIntTypeNormalizer())(
                        jsonString: json_encode($item, JSON_THROW_ON_ERROR),
                        targetClass: GroupDto::class
                    ),
                    GroupDto::class,
                    $jsonDeviceDataSerializer::FORMAT,
                );
            } catch (MissingConstructorArgumentsException $exception) {
                codecept_debug('VALID CASE: ' . $exception->getMessage());
                continue;
            }
        }

        Assert::allIsInstanceOf($groupsItems, GroupDto::class);
        $result = $mapper->map($serviceMock, $groupsItems, $groups);

        // Assert
        Mockery::close();
        self::assertInstanceOf(Groups::class, $result);
        self::assertFalse($result->isEmpty());
        self::assertCount(3, $result);

        $group1 = $result->get(1000);
        self::assertInstanceOf(Group::class, $group1);
        self::assertSame(1000, $group1->getId());
        self::assertFalse($group1->isOn());
        self::assertFalse($group1->isOff());
        self::assertSame('Group 1', $group1->getName());
        self::assertSame(38.0, $group1->getBrightness());

        $group2 = $result->get(2000);
        self::assertInstanceOf(Group::class, $group2);
        self::assertSame(2000, $group2->getId());
        self::assertFalse($group2->isOn());
        self::assertSame('Group 2', $group2->getName());
        self::assertSame(0.0, $group2->getBrightness());

        $group3 = $result->get(3000);
        self::assertInstanceOf(Group::class, $group3);
        self::assertSame(3000, $group3->getId());
        self::assertFalse($group3->isOn());
        self::assertSame('Group 3', $group3->getName());
        self::assertSame(0.0, $group3->getBrightness());
    }
}

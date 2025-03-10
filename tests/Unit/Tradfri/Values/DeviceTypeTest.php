<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Values;

use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Generator;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Values\DeviceType;

final class DeviceTypeTest extends Unit
{
    /**
     * @phpstan-return Generator<non-empty-string, array<non-empty-string|DeviceType|null|bool>>
     */
    public static function provideCases(): iterable
    {
        yield 'from type "invalidStringValue" with Unknown true' => ['invalidStringValue', DeviceType::UNKNOWN, true];
        yield 'from type "BLUB_E27_W" with Unknown true' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, DeviceType::BLUB, true];
        yield 'from type "BLUB_E27_WS" with Unknown true' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, DeviceType::BLUB, true];
        yield 'from type "BLUB_GU10_WS" with Unknown true' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, DeviceType::BLUB, true];
        yield 'from type "BLUB_GU10_W" with Unknown true' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W, DeviceType::BLUB, true];
        yield 'from type "MOTION_SENSOR" with Unknown true' => [Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, DeviceType::MOTION_SENSOR, true];
        yield 'from type "REMOTE_CONTROL" with Unknown true' => [Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, DeviceType::REMOTE, true];
        yield 'from type "DIMMER" with Unknown true' => [Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, DeviceType::DIMMER, true];

        yield 'from type "invalidStringValue" with Unknown false' => ['invalidStringValue', null, false];
        yield 'from type "BLUB_E27_W" with Unknown false' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, DeviceType::BLUB, false];
        yield 'from type "BLUB_E27_WS" with Unknown false' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, DeviceType::BLUB, false];
        yield 'from type "BLUB_GU10_WS" with Unknown false' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, DeviceType::BLUB, false];
        yield 'from type "BLUB_GU10_W" with Unknown false' => [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W, DeviceType::BLUB, false];
        yield 'from type "MOTION_SENSOR" with Unknown false' => [Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, DeviceType::MOTION_SENSOR, false];
        yield 'from type "REMOTE_CONTROL" with Unknown false' => [Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, DeviceType::REMOTE, false];
        yield 'from type "DIMMER" with Unknown false' => [Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, DeviceType::DIMMER, false];
    }

    #[DataProvider('provideCases')]
    public function testTryFromType(
        string $typeAttribute,
        ?DeviceType $expected,
        bool $allowUnknown,
    ): void {
        // Arrange
        // Act
        $actual = DeviceType::tryFromType(deviceTypeValue: $typeAttribute, allowUnknown: $allowUnknown);
        // Assert
        $this->assertSame(
            $expected,
            $actual,
            'Type failed: ' . $typeAttribute,
        );
    }

    #[DataProvider('provideCases')]
    public function testInitModel(
        string $typeAttribute,
        ?DeviceType $expected,
        bool $allowUnknown,
    ): void {
        // Arrange
        if (false === $allowUnknown && null === $expected) {
            $this->expectException(\IKEA\Tradfri\Exception\InvalidTypeException::class);
            $this->expectExceptionMessage(\sprintf('cannot find device class by type: "%s"', $typeAttribute));
        }

        // Act
        $actual = DeviceType::initModel(deviceTypeValue: $typeAttribute, id: 123, allowUnknown: $allowUnknown);
        // Assert
        if (null === $expected) {
            $this->assertNull($actual);
        } else {
            $this->assertInstanceOf(Device::class, $actual);
        }
    }
}

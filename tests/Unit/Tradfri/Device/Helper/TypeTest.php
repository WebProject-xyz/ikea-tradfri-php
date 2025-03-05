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

namespace IKEA\Tests\Unit\Tradfri\Device\Helper;

use Codeception\Attribute\DataProvider;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Values\DeviceType;

/**
 * Class TypeTest.
 */
final class TypeTest extends \Codeception\Test\Unit
{
    #[\Codeception\Attribute\DataProvider('provideIsLightBulbCases')]
    public function testIsLightBulb(
        string $typeAttribute,
        bool $assertTrue,
    ): void {
        // Arrange
        // Act
        $condition = \IKEA\Tradfri\Values\DeviceType::tryFromType($typeAttribute);
        // Assert
        if ($assertTrue) {
            $this->assertSame(
                DeviceType::BLUB,
                $condition,
                'Type: ' . $typeAttribute,
            );
        } else {
            $this->assertNotSame(
                DeviceType::BLUB,
                $condition,
                'Type: ' . $typeAttribute,
            );
        }
    }

    public static function provideIsLightBulbCases(): iterable
    {
        yield from [
            ['invalidStringValue', false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, true],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, true],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, true],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W, true],
            [Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, false],
        ];
    }

    #[\Codeception\Attribute\DataProvider('provideIsDimmerCases')]
    public function testIsDimmer(
        string $typeAttribute,
        bool $assertTrue,
    ): void {
        // Arrange
        $helper = new Type();

        // Act
        $condition = $helper->isDimmer($typeAttribute);
        // Assert
        if ($assertTrue) {
            $this->assertTrue(
                $condition,
                'Type: ' . $typeAttribute,
            );
        } else {
            $this->assertFalse(
                $condition,
                'Type: ' . $typeAttribute,
            );
        }
    }

    public static function provideIsDimmerCases(): iterable
    {
        yield from [
            ['invalidStringValue', false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, true],
        ];
    }

    #[\Codeception\Attribute\DataProvider('provideIsRemoteCases')]
    public function testIsRemote(
        string $typeAttribute,
        bool $assertTrue,
    ): void {
        // Arrange
        $helper = new Type();

        // Act
        $condition = $helper->isRemote($typeAttribute);
        // Assert
        if ($assertTrue) {
            $this->assertTrue(
                $condition,
                'Type: ' . $typeAttribute,
            );
        } else {
            $this->assertFalse(
                $condition,
                'Type: ' . $typeAttribute,
            );
        }
    }

    public static function provideIsRemoteCases(): iterable
    {
        yield from [
            ['invalidStringValue', false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, true],
            [Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, false],
        ];
    }

    #[\Codeception\Attribute\DataProvider('provideIsMotionSensorCases')]
    public function testIsMotionSensor(
        string $typeAttribute,
        bool $assertTrue,
    ): void {
        // Arrange
        $helper = new Type();

        // Act
        $condition = $helper->isMotionSensor($typeAttribute);
        // Assert
        if ($assertTrue) {
            $this->assertTrue(
                $condition,
                'Type: ' . $typeAttribute,
            );
        } else {
            $this->assertFalse(
                $condition,
                'Type: ' . $typeAttribute,
            );
        }
    }

    public static function provideIsMotionSensorCases(): iterable
    {
        yield from [
            ['invalidStringValue', false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, true],
            [Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, false],
        ];
    }

    #[DataProvider('provideKnownDeviceTypeCases')]
    public function testKnownDeviceType(
        string $typeAttribute,
        bool $assertTrue,
    ): void {
        // Arrange
        $helper = new Type();

        // Act
        $condition = $helper->isUnknownDeviceType($typeAttribute);
        // Assert
        if ($assertTrue) {
            $this->assertTrue(
                $condition,
                'Type: ' . $typeAttribute,
            );
        } else {
            $this->assertFalse(
                $condition,
                'Type: ' . $typeAttribute,
            );
        }
    }

    public static function provideKnownDeviceTypeCases(): iterable
    {
        yield from [
            ['invalidStringValue', true],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL, false],
            [Keys::ATTR_DEVICE_INFO_TYPE_DIMMER, false],
        ];
    }
}

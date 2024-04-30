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

namespace IKEA\Tests\Unit\Tradfri\Device\Helper;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\UnknownDevice;

/**
 * Class TypeTest.
 */
final class TypeTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider provideIsLightBulbCases
     */
    public function testIsLightBulb(
        string $typeAttribute,
        bool $assertTrue,
    ): void {
        // Arrange
        $helper = new Type();

        // Act
        $condition = $helper->isLightBulb($typeAttribute);
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

    /**
     * @dataProvider provideIsDimmerCases
     */
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

    /**
     * @dataProvider provideIsRemoteCases
     */
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

    /**
     * @dataProvider provideIsMotionSensorCases
     */
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

    /**
     * @dataProvider provideKnownDeviceTypeCases
     */
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

    public function testBuildFrom(): void
    {
        // Arrange
        $helper = new Type();

        // Act
        $model = $helper->buildFrom(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, 1);

        // Assert
        $this->assertNotNull($model);
    }

    public function testBuildFromUnknownClass(): void
    {
        // Arrange
        $helper = new Type();

        // Act
        $model = $helper->buildFrom('blubb', 1, true);

        // Assert
        $this->assertNotNull($model);
        $this->assertInstanceOf(UnknownDevice::class, $model);
    }

    public function testBuildFromNoUnknownClassAndSeeError(): void
    {
        $this->expectExceptionMessage('Unable to detect device type: blubb');
        // Arrange
        $helper = new Type();

        // Act
        $helper->buildFrom('blubb', 1, false);
    }
}

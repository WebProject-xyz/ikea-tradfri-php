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

namespace IKEA\Tests\Unit\Tradfri\Device;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\ServiceInterface as Api;
use IKEA\Tradfri\Values\DeviceType;
use Mockery;

use function assert;

final class RollerBlindTest extends DeviceTester
{
    public function testGetAnInstance(): void
    {
        // Arrange
        // Act
        $lamp = $this->getModel();
        // Assert
        self::assertInstanceOf(RollerBlind::class, $lamp);
        self::assertSame(DeviceType::ROLLER_BLIND, $lamp->getTypeEnum());
    }

    public function testSetType(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setType(Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND);
        // Act
        $result = $lamp->getType();

        // Assert
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND, $result);
        self::assertSame(DeviceType::ROLLER_BLIND, $lamp->getTypeEnum());
    }

    public function testGetBrightnessButNotSet(): void
    {
        // Arrange
        $rollerBlind = $this->getModel();

        // Act
        $result = $rollerBlind->getDarkenedState();

        // Assert
        self::assertSame(DeviceType::ROLLER_BLIND, $rollerBlind->getTypeEnum());
        self::assertSame(0, $result);
    }

    public function testGetBrightness(): void
    {
        // Arrange
        $rollerBlind = $this->getModel();
        $rollerBlind->setDarkenedState((int) 30);
        // Act
        $result = $rollerBlind->getDarkenedState();

        // Assert
        self::assertSame(30, $result);
    }

    public function testSetBrightnessToLow(): void
    {
        // Arrange
        $rollerBlind = $this->getModel();
        $rollerBlind->setDarkenedState(-12);
        // Act
        $result = $rollerBlind->getDarkenedState();

        // Assert
        self::assertSame(0, $result);
    }

    public function testStates(): void
    {
        // Arrange
        $rollerBlind = $this->getModel();
        self::assertTrue($rollerBlind->isFullyOpened());

        $service = Mockery::mock(Api::class);
        $service->expects()->setRollerBlindPosition($rollerBlind, 0)->once()->andReturn(true);
        $rollerBlind->setService($service);

        // Act
        $rollerBlind->setToPosition(0);

        // Assert
        self::assertTrue($rollerBlind->isFullyOpened());
    }

    public function testOpen(): void
    {
        // Arrange
        $rollerBlind = $this->getModel();
        self::assertTrue($rollerBlind->isFullyOpened());

        $service = Mockery::mock(Api::class);
        $service->expects()->setRollerBlindPosition($rollerBlind, 0)->once()->andReturn(true);
        $rollerBlind->setService($service);

        // Act
        $rollerBlind->open();

        // Assert
        self::assertTrue($rollerBlind->isFullyOpened());
    }

    public function testClose(): void
    {
        // Arrange
        $rollerBlind = $this->getModel();
        self::assertTrue($rollerBlind->isFullyOpened());

        $service = Mockery::mock(Api::class);
        $service->expects()->setRollerBlindPosition($rollerBlind, 100)->once()->andReturn(true);
        $rollerBlind->setService($service);

        // Act
        $rollerBlind->close();

        // Assert
        self::assertFalse($rollerBlind->isFullyOpened());
    }

    public function testICanSetPositions(): void
    {
        // Arrange
        $rollerBlind = $this->getModel();

        $service = Mockery::mock(Api::class);
        $service->expects('setRollerBlindPosition')->with($rollerBlind, 100)->once()->andReturnUsing(static function (object $model, int $state): bool {
            assert($model instanceof RollerBlind);
            $model->setDarkenedState($state);

            return true;
        });
        $service->expects('setRollerBlindPosition')->with($rollerBlind, 75)->once()->andReturnUsing(static function (object $model, int $state): bool {
            assert($model instanceof RollerBlind);
            $model->setDarkenedState($state);

            return true;
        });

        $rollerBlind->setService($service);
        self::assertFalse($rollerBlind->isFullyClosed());

        // Act
        $result = $rollerBlind->setToPosition(100);

        // Assert
        self::assertTrue($result);
        self::assertTrue($rollerBlind->isFullyClosed());
        self::assertFalse($rollerBlind->isFullyOpened());

        // Act
        $result = $rollerBlind->setToPosition(75);
        // Assert
        self::assertTrue($result);
        self::assertFalse($rollerBlind->isFullyOpened());
        self::assertFalse($rollerBlind->isFullyClosed());

        self::assertSame(75, $rollerBlind->getDarkenedState());
    }

    public function testICanSetPositionFails(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('set postion failed');
        // Arrange
        $lamp = $this->getModel();

        $service = Mockery::mock(Api::class);
        $service->expects('setRollerBlindPosition')->andThrow(new RuntimeException('set postion failed'));

        $lamp->setService($service);
        self::assertTrue($lamp->isFullyOpened());

        // Act
        $result = $lamp->setToPosition(100);

        // Assert
    }

    protected function getModel(): RollerBlind
    {
        return new RollerBlind($this->_id, Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND);
    }
}

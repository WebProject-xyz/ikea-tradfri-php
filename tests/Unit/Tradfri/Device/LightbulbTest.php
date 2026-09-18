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
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\ServiceInterface as Api;
use Mockery;

/**
 * Class LightBulbTest.
 */
final class LightbulbTest extends DeviceTester
{
    public function testGetAnInstance(): void
    {
        // Arrange
        // Act
        $lamp = $this->getModel();
        // Assert
        self::assertInstanceOf(LightBulb::class, $lamp);
    }

    public function testSetType(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setType(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);
        // Act
        $result = $lamp->getType();

        // Assert
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W, $result);
    }

    public function testGetBrightnessButNotSet(): void
    {
        // Arrange
        $lamp = $this->getModel();

        // Act
        $result = $lamp->getBrightness();

        // Assert
        self::assertSame(0.0, $result);
    }

    public function testGetBrightness(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setBrightness((int) round(30 * 2.54));
        // Act
        $result = $lamp->getBrightness();

        // Assert
        self::assertSame(30.0, $result);
    }

    public function testSetBrightnessToLow(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setBrightness(-12);
        // Act
        $result = $lamp->getBrightness();

        // Assert
        self::assertSame(0.0, $result);
    }

    public function testSetTypeE27WS(): void
    {
        // Arrange
        $lamp = $this->getModel(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        // Act
        $result = $lamp->getType();

        // Assert
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS, $result);
        self::assertTrue($lamp->isValidType(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS));
    }

    public function testSetTypeGU10(): void
    {
        // Arrange
        $lamp = $this->getModel(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS);
        // Act
        $result = $lamp->getType();

        // Assert
        self::assertSame(Keys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS, $result);
    }

    public function testStates(): void
    {
        // Arrange
        $lamp = $this->getModel();
        self::assertFalse($lamp->isOn());

        // Act
        $lamp->setState(true);

        // Assert
        self::assertTrue($lamp->isOn());
        self::assertFalse($lamp->isOff());
        self::assertSame('On', $lamp->getReadableState());
    }

    public function testICanSwitchOn(): void
    {
        // Arrange
        $lamp = $this->getModel();

        $service = Mockery::mock(Api::class);
        $service->expects()->on($lamp)->twice()->andReturn(true);

        $lamp->setService($service);
        self::assertFalse($lamp->isOn());

        // Act
        $result = $lamp->switchOn();

        // Assert
        self::assertTrue($result);
        self::assertTrue($lamp->isOn());
        self::assertSame('On', $lamp->getReadableState());

        // Act
        $result = $lamp->switchOn();
        // Assert
        self::assertTrue($result);
        self::assertTrue($lamp->isOn());
        self::assertSame('On', $lamp->getReadableState());
    }

    public function testICanSwitchOnFails(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('switch ON failed');
        // Arrange
        $lamp = $this->getModel();

        $service = Mockery::mock(Api::class);
        $service->expects('on')->andReturn(false);

        $lamp->setService($service);
        self::assertFalse($lamp->isOn());

        // Act
        $result = $lamp->switchOn();

        // Assert
        self::assertFalse($result);
        self::assertFalse($lamp->isOn());
        self::assertSame('Off', $lamp->getReadableState());
    }

    public function testICanSwitchOff(): void
    {
        // Arrange
        $lamp = $this->getModel();

        $service = Mockery::mock(Api::class);
        $service->expects()->off($lamp)->twice()->andReturn(true);

        $lamp->setService($service);
        self::assertFalse($lamp->isOn());

        // Act
        $result = $lamp->switchOff();

        // Assert
        self::assertTrue($result);
        self::assertFalse($lamp->isOn());
        self::assertSame('Off', $lamp->getReadableState());

        // Act
        $result = $lamp->switchOff();

        // Assert
        self::assertTrue($result);
        self::assertFalse($lamp->isOn());
        self::assertSame('Off', $lamp->getReadableState());
    }

    public function testICanSwitchDim(): void
    {
        // Arrange
        $lamp = $this->getModel();

        $service = Mockery::mock(Api::class);
        $service->expects()->dim($lamp, 10)->andReturn(true);
        $service->expects()->dim($lamp, 0)->andReturn(true);
        $service->expects()->off($lamp)->times(2)->andReturn(true);

        $lamp->setService($service);
        self::assertFalse($lamp->isOn());

        // Act
        $result = $lamp->switchOff();

        // Assert
        self::assertTrue($result);
        self::assertTrue($lamp->dim(10));
        self::assertSame('On', $lamp->getReadableState());
        self::assertSame(10.0, $lamp->getBrightness());

        // Act
        $result = $lamp->switchOff();

        // Assert
        self::assertTrue($result);
        self::assertTrue($lamp->dim(0));
        self::assertSame('Off', $lamp->getReadableState());
        self::assertSame(0.0, $lamp->getBrightness());
    }

    public function testICanSwitchDimFails(): void
    {
        // Arrange
        $lamp = $this->getModel();

        $service = Mockery::mock(Api::class);
        $service->expects()->dim($lamp, 10)->andReturn(false);

        $lamp->setService($service);
        self::assertFalse($lamp->isOn());

        // Act
        // Assert
        self::assertFalse($lamp->dim(10));
    }

    public function testICanSwitchOffFails(): void
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('unable to change state of lightBulb: 1');
        // Arrange
        $lamp = $this->getModel();
        $lamp->setState(true);

        $service = Mockery::mock(Api::class);
        $service->expects()->off($lamp)->andThrow(new RuntimeException('unable to change state of lightBulb: 1'));

        $lamp->setService($service);
        self::assertTrue($lamp->isOn());

        // Act
        $result = $lamp->switchOff();

        // Assert
        self::assertFalse($result);
        self::assertTrue($lamp->isOn());
        self::assertSame('On', $lamp->getReadableState());
    }

    public function testICanSwitchOffReturnedFalse(): void
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('switch OFF failed');

        // Arrange
        $lamp    = $this->getModel();
        $service = Mockery::mock(Api::class);
        $service->expects('off')->andReturn(false);

        $lamp->setService($service);
        self::assertFalse($lamp->isOn());

        $lamp->setState(true);
        self::assertTrue($lamp->isOn());

        // Act
        $result = $lamp->switchOff();

        // Assert
        self::assertFalse($result);
        self::assertTrue($lamp->isOn());
        self::assertSame('On', $lamp->getReadableState());

        // Act 2
        $result = $lamp->switchOff();

        // Assert 2
        self::assertFalse($result);
        self::assertTrue($lamp->isOn());
        self::assertSame('On', $lamp->getReadableState());
    }

    protected function getModel(string $deviceType = Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W): LightBulb
    {
        return new LightBulb($this->_id, $deviceType);
    }
}

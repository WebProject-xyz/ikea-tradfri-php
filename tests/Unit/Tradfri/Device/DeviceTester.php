<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Device;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class DeviceTest.
 *
 * @method createMock($originalClassName)
 */
abstract class DeviceTester extends UnitTest
{
    protected int $_id = 1;

    abstract public function testGetAnInstance(): void;

    final public function testICanNotGetService(): void
    {
        // Arrange
        $this->expectException('RuntimeException');
        $lamp = $this->getModel();

        // Act
        $lamp->getService();

        // Assert
    }

    final public function testICanGetService(): void
    {
        // Arrange
        $device = $this->getModel();

        $serviceMock = \Mockery::mock(ServiceInterface::class);
        // Act
        $device->setService($serviceMock);
        $service = $device->getService();

        // Assert
        $this->assertInstanceOf(ServiceInterface::class, $service);
    }

    final public function testGetSetName(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setName('UnitTest');
        // Act
        $result = $lamp->getName();

        // Assert
        $this->assertSame('UnitTest', $result);
    }

    final public function testGetSetManufacturer(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setManufacturer('UnitTest');
        // Act
        $result = $lamp->getManufacturer();

        // Assert
        $this->assertSame('UnitTest', $result);
    }

    final public function testGetSetId(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setId(2);
        // Act
        $result = $lamp->getId();

        // Assert
        $this->assertSame(2, $result);
    }

    final public function testGetSetVersion(): void
    {
        // Arrange
        $lamp = $this->getModel();
        $lamp->setVersion('V123');
        // Act
        $result = $lamp->getVersion();

        // Assert
        $this->assertSame('V123', $result);
    }

    /**
     * @return Device
     */
    abstract protected function getModel(): DeviceInterface;
}

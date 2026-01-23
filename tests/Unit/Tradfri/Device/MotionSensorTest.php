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

use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Values\DeviceType;

final class MotionSensorTest extends DeviceTester
{
    public function testGetAnInstance(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertInstanceOf(MotionSensor::class, $model);
        $this->assertSame(DeviceType::MOTION_SENSOR, $model->getTypeEnum());
    }

    public function testIsMotionSensor(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertSame(DeviceType::MOTION_SENSOR, $model->getTypeEnum());
    }

    protected function getModel(): MotionSensor
    {
        return new MotionSensor($this->_id);
    }
}

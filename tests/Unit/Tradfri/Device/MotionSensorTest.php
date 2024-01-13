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

namespace IKEA\Tests\Unit\Tradfri\Device;

use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\MotionSensor;

final class MotionSensorTest extends DeviceTester
{
    public function testGetAnInstance(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertInstanceOf(MotionSensor::class, $model);
    }

    public function testIsMotionSensor(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertTrue((new Type())->isMotionSensor($model->getType()));
    }

    protected function getModel(): MotionSensor
    {
        return new MotionSensor($this->_id);
    }
}

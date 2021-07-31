<?php

declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Device;

use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\MotionSensor;

class MotionSensorTest extends DeviceTester
{
    public function testGetAnInstance() : void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertInstanceOf(MotionSensor::class, $model);
    }

    public function testIsMotionSensor() : void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertTrue((new Type())->isMotionSensor($model->getType()));
    }

    protected function getModel() : MotionSensor
    {
        return new MotionSensor($this->_id);
    }
}

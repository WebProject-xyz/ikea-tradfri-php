<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Device;

use IKEA\Tradfri\Device\MotionSensor;

class MotionSensorTest extends DeviceTester
{
    public function testGetAnInstance()
    {
        // Arrange
        // Act
        $model = $this->_getModel();
        // Assert
        $this->assertInstanceOf(MotionSensor::class, $model);
    }

    public function testIsLightbulb()
    {
        // Arrange
        // Act
        $model = $this->_getModel();
        // Assert
        $this->assertFalse($model->isLightbulb());
    }

    /**
     * @return MotionSensor
     */
    protected function _getModel(): MotionSensor
    {
        return new MotionSensor($this->id);
    }
}

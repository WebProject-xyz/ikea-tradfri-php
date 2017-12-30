<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Device;

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;

class DimmerTest extends DeviceTester
{
    public function testGetAnInstance()
    {
        // Arrange
        // Act
        $model = $this->_getModel();
        // Assert
        $this->assertInstanceOf(Dimmer::class, $model);
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
     * @return Device
     */
    protected function _getModel(): Device
    {
        return new Dimmer($this->id, Device::TYPE_DIMMER);
    }
}

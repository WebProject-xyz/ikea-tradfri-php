<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Device;

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Remote;

class RemoteTest extends DeviceTester
{
    public function testGetAnInstance()
    {
        // Arrange
        // Act
        $model = $this->_getModel();
        // Assert
        $this->assertInstanceOf(Remote::class, $model);
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
     * @return Remote
     */
    protected function _getModel(): \IKEA\Tradfri\Device\Remote
    {
        return new Remote($this->id, Device::TYPE_REMOTE_CONTROL);
    }
}

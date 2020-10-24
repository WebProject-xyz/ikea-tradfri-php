<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Device;

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Helper\Type;

/**
 * Class DimmerTest
 */
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

    public function testIsDimmer()
    {
        // Arrange
        // Act
        $model = $this->_getModel();
        // Assert
        $this->assertTrue((new Type())->isDimmer($model->getType()));
    }

    /**
     * @return Device
     */
    protected function _getModel(): Device
    {
        return new Dimmer($this->_id);
    }
}

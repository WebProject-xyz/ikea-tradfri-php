<?php
declare(strict_types=1);

namespace IKEA\Tests\Tradfri\Device;

use IKEA\Tradfri\Device\Helper\Type;
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

    public function testIsRemote()
    {
        // Arrange
        // Act
        $model = $this->_getModel();
        // Assert
        $this->assertTrue((new Type())->isRemote($model->getType()));
    }

    /**
     * @return Remote
     */
    protected function _getModel(): Remote
    {
        return new Remote($this->_id);
    }
}

<?php
declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Device;

use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\Remote;

class RemoteTest extends DeviceTester
{
    public function testGetAnInstance(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertInstanceOf(Remote::class, $model);
    }

    public function testIsRemote(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertTrue((new Type())->isRemote($model->getType()));
    }

    /**
     * @return Remote
     */
    protected function getModel(): Remote
    {
        return new Remote($this->_id);
    }
}

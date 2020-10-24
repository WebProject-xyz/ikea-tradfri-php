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
        $model = $this->getModel();
        // Assert
        $this->assertInstanceOf(Dimmer::class, $model);
    }

    public function testIsDimmer(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertTrue((new Type())->isDimmer($model->getType()));
    }

    /**
     * @return Device
     */
    protected function getModel(): Device
    {
        return new Dimmer($this->_id);
    }
}

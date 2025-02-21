<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Device;

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Helper\Type;

/**
 * Class DimmerTest.
 */
final class DimmerTest extends DeviceTester
{
    public function testGetAnInstance(): void
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

    protected function getModel(): Device
    {
        return new Dimmer($this->_id);
    }
}

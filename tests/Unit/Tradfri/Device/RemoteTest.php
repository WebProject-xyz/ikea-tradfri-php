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

use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Values\DeviceType;

final class RemoteTest extends DeviceTester
{
    public function testGetAnInstance(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertInstanceOf(Remote::class, $model);
        $this->assertSame(DeviceType::REMOTE, $model->getTypeEnum());
    }

    public function testIsRemote(): void
    {
        // Arrange
        // Act
        $model = $this->getModel();
        // Assert
        $this->assertSame(DeviceType::REMOTE, $model->getTypeEnum());
    }

    protected function getModel(): Remote
    {
        return new Remote($this->_id);
    }
}

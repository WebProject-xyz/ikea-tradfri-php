<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Device;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Values\DeviceType;

final class DeviceJsonSerializeTest extends UnitTest
{
    public function testJsonSerializeKeepsDynamicGettersWithStableOrder(): void
    {
        $device = new class(123, 'TRADFRI bulb dynamic test') extends Device {
            public function getZeta(): string
            {
                return 'z-value';
            }

            public function getAlpha(): int
            {
                return 42;
            }
        };

        $device->setManufacturer('IKEA of Sweden');
        $device->setName('Dynamic Test Device');
        $device->setVersion('1.2.3');

        $this->assertSame(
            [
                'alpha'        => 42,
                'id'           => 123,
                'manufacturer' => 'IKEA of Sweden',
                'name'         => 'Dynamic Test Device',
                'type'         => 'TRADFRI bulb dynamic test',
                'typeenum'     => DeviceType::BLUB,
                'version'      => '1.2.3',
                'zeta'         => 'z-value',
            ],
            $device->jsonSerialize(),
        );
    }
}

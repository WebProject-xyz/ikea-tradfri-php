<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Util;

use Codeception\Test\Unit;
use IKEA\Tests\Support\UnitTester;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Util\JsonIntTypeNormalizer;

final class JsonNormalizerTest extends Unit
{
    protected UnitTester $tester;

    public function testAllKnownIntTypesAreReplacesWithUsefulStringValues(): void
    {
        // Arrange
        $rawJson = /** @lang JSON */ <<<'DEVICE_JSON'
{
    "9003": 5000,
    "9001": "TRADFRI motion sensor",
    "3": {
        "0": "UnitTestFactory",
        "3": "v1.33.7",
        "1": "TRADFRI motion sensor"
    }
}
DEVICE_JSON;
        $expectedJson = /** @lang JSON */ <<<'DEVICE_JSON'
{
    "ATTR_ID": 5000,
    "ATTR_NAME": "TRADFRI motion sensor",
    "ATTR_DEVICE_INFO": {
        "ATTR_DEVICE_MANUFACTURER": "UnitTestFactory",
        "ATTR_DEVICE_FIRMWARE_VERSION": "v1.33.7",
        "ATTR_DEVICE_MODEL_NUMBER": "TRADFRI motion sensor"
    }
}
DEVICE_JSON;

        $util = new JsonIntTypeNormalizer();

        // Act
        $normalizedJson = $util($rawJson, DeviceDto::class);

        // Assert
        $this->assertSame($expectedJson, $normalizedJson, 'no change detected');
    }
}

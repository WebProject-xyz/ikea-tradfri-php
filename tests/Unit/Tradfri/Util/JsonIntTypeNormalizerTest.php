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

use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Util\JsonIntTypeNormalizer;
use PHPUnit\Framework\TestCase;

final class JsonIntTypeNormalizerTest extends TestCase
{
    public function testFinalJsonIsNotChangedForSimpleDevice(): void
    {
        // Arrange
        $inputJson = /** @lang JSON */ <<<'JSON'
{
    "9003": 5000,
    "9001": "TRADFRI motion sensor",
    "3": {
        "0": "UnitTestFactory",
        "3": "v1.33.7",
        "1": "TRADFRI motion sensor"
    }
}
JSON;
        $deviceJson = /** @lang JSON */ <<<'DEVICE_JSON'
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

        $util  = new JsonIntTypeNormalizer();

        // Act
        $normalizedJson = $util($inputJson, DeviceDto::class);

        // Assert
        $this->assertSame($deviceJson, $normalizedJson);
    }

    public function testFinalJsonIsNotChangedForLights(): void
    {
        // Arrange
        $inputJson = /** @lang JSON */ <<<'JSON'
{
    "9003": 1000,
    "9001": "TRADFRI bulb E27 W opal 1000lm",
    "3": {
        "0": "UnitTestFactory",
        "3": "v1.33.7",
        "1": "TRADFRI bulb E27 W opal 1000lm"
    },
    "3311": [
        {
            "5851": 22,
            "5850": 1
        }
    ]
}
JSON;
        $deviceJson = /** @lang JSON */ <<<'DEVICE_JSON'
{
    "ATTR_ID": 1000,
    "ATTR_NAME": "TRADFRI bulb E27 W opal 1000lm",
    "ATTR_DEVICE_INFO": {
        "ATTR_DEVICE_MANUFACTURER": "UnitTestFactory",
        "ATTR_DEVICE_FIRMWARE_VERSION": "v1.33.7",
        "ATTR_DEVICE_MODEL_NUMBER": "TRADFRI bulb E27 W opal 1000lm"
    },
    "ATTR_LIGHT_CONTROL": [
        {
            "ATTR_LIGHT_DIMMER": 22,
            "ATTR_DEVICE_STATE": 1
        }
    ]
}
DEVICE_JSON;

        // Act
        $normalizedJson = (new JsonIntTypeNormalizer())($inputJson, DeviceDto::class);

        // Assert
        $this->assertSame($deviceJson, $normalizedJson);
    }
}

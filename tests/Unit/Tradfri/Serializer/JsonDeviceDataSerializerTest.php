<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Unit\Tradfri\Serializer;

use DateTimeImmutable;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\DeviceInfoDto;
use IKEA\Tradfri\Dto\CoapResponse\LightControlDto;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class JsonDeviceDataSerializerTest extends TestCase
{
    public function testSerializeAndDeserialize(): void
    {
        // Arrange
        $expectedJson = /** @lang JSON */ <<<'JSON'
            {
                "ATTR_ID": 12,
                "ATTR_NAME": "name",
                "ATTR_DEVICE_INFO": {
                    "ATTR_DEVICE_MANUFACTURER": "man",
                    "ATTR_DEVICE_MODEL_NUMBER": "type",
                    "ATTR_DEVICE_FIRMWARE_VERSION": "version"
                }
            }
            JSON;

        $serializer = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();

        $deviceData = new DeviceInfoDto('man', 'type', 'version');
        $deviceDto  = new DeviceDto(12, 'name', $deviceData);

        // Act
        $json = $serializer->serialize($deviceDto, $serializer::FORMAT);
        self::assertSame($expectedJson, $json);

        $backToObject = $serializer->deserialize($json, DeviceDto::class, $serializer::FORMAT);
        // Assert
        self::assertInstanceOf(DeviceDto::class, $backToObject);
        self::assertSame($backToObject->getName(), $deviceDto->getName());
        self::assertSame($backToObject->getId(), $deviceDto->getId());
        self::assertSame($backToObject->getDeviceInfo()->getVersion(), $deviceDto->getDeviceInfo()->getVersion());
        self::assertSame($backToObject->getDeviceInfo()->getType(), $deviceDto->getDeviceInfo()->getType());
        self::assertSame($backToObject->getDeviceInfo()->getManufacturer(), $deviceDto->getDeviceInfo()->getManufacturer());
        self::assertNull($backToObject->getLightControl());
    }

    public function testSerializeAndDeserializeWithLightControl(): void
    {
        // Arrange
        $expectedJson = /** @lang JSON */ <<<'JSON'
            {
                "ATTR_ID": 1000,
                "ATTR_NAME": "TRADFRI bulb E27 W opal 1000lm",
                "ATTR_DEVICE_INFO": {
                    "ATTR_DEVICE_MANUFACTURER": "UnitTestFactory",
                    "ATTR_DEVICE_MODEL_NUMBER": "TRADFRI bulb E27 W opal 1000lm",
                    "ATTR_DEVICE_FIRMWARE_VERSION": "v1.33.7"
                },
                "ATTR_LIGHT_CONTROL": {
                    "ATTR_DEVICE_STATE": 1,
                    "ATTR_LIGHT_DIMMER": 22
                }
            }
            JSON;

        $serializer = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();

        $lightControlDto = new LightControlDto(1, 22);
        $deviceData      = new DeviceInfoDto('UnitTestFactory', 'TRADFRI bulb E27 W opal 1000lm', 'v1.33.7');
        $deviceDto       = new DeviceDto(1000, 'TRADFRI bulb E27 W opal 1000lm', $deviceData, $lightControlDto);

        // Act
        $json = $serializer->serialize($deviceDto, $serializer::FORMAT);
        self::assertSame($expectedJson, $json);

        $backToObject = $serializer->deserialize($json, DeviceDto::class, $serializer::FORMAT);

        // Assert
        self::assertInstanceOf(DeviceDto::class, $backToObject);
        self::assertSame($backToObject->getName(), $deviceDto->getName());
        self::assertSame($backToObject->getId(), $deviceDto->getId());

        self::assertSame($backToObject->getDeviceInfo()->getVersion(), $deviceDto->getDeviceInfo()->getVersion());
        self::assertSame($backToObject->getDeviceInfo()->getType(), $deviceDto->getDeviceInfo()->getType());
        self::assertSame($backToObject->getDeviceInfo()->getManufacturer(), $deviceDto->getDeviceInfo()->getManufacturer());

        self::assertNotNull($backToObject->getLightControl(), 'failed to deserialize');
        self::assertNotNull($deviceDto->getLightControl(), 'failed to deserialize');
        self::assertSame($backToObject->getLightControl()->getBrightness(), $deviceDto->getLightControl()->getBrightness());
        self::assertSame($backToObject->getLightControl()->getColorHex(), $deviceDto->getLightControl()->getColorHex());
        self::assertSame($backToObject->getLightControl()->getState(), $deviceDto->getLightControl()->getState());
    }

    public function testSerializeAndDeserializeWithLightControlAndColor(): void
    {
        // Arrange
        $expectedJson = /** @lang JSON */ <<<'JSON'
            {
                "ATTR_ID": 1000,
                "ATTR_NAME": "TRADFRI bulb E27 W opal 1000lm",
                "ATTR_DEVICE_INFO": {
                    "ATTR_DEVICE_MANUFACTURER": "UnitTestFactory",
                    "ATTR_DEVICE_MODEL_NUMBER": "TRADFRI bulb E27 W opal 1000lm",
                    "ATTR_DEVICE_FIRMWARE_VERSION": "v1.33.7"
                },
                "ATTR_LIGHT_CONTROL": {
                    "ATTR_DEVICE_STATE": 1,
                    "ATTR_LIGHT_DIMMER": 22,
                    "ATTR_LIGHT_COLOR_HEX": "ff00ff"
                }
            }
            JSON;

        $serializer = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();

        $lightControlDto = new LightControlDto(1, 22, 'ff00ff');
        $deviceData      = new DeviceInfoDto('UnitTestFactory', 'TRADFRI bulb E27 W opal 1000lm', 'v1.33.7');
        $deviceDto       = new DeviceDto(1000, 'TRADFRI bulb E27 W opal 1000lm', $deviceData, $lightControlDto);

        // Act
        $json = $serializer->serialize($deviceDto, $serializer::FORMAT);
        self::assertSame($expectedJson, $json);

        $backToObject = $serializer->deserialize($json, DeviceDto::class, $serializer::FORMAT);

        // Assert
        self::assertInstanceOf(DeviceDto::class, $backToObject);
        self::assertSame($backToObject->getName(), $deviceDto->getName());
        self::assertSame($backToObject->getId(), $deviceDto->getId());

        self::assertSame($backToObject->getDeviceInfo()->getVersion(), $deviceDto->getDeviceInfo()->getVersion());
        self::assertSame($backToObject->getDeviceInfo()->getType(), $deviceDto->getDeviceInfo()->getType());
        self::assertSame($backToObject->getDeviceInfo()->getManufacturer(), $deviceDto->getDeviceInfo()->getManufacturer());

        self::assertNotNull($backToObject->getLightControl(), 'failed to deserialize');
        self::assertNotNull($deviceDto->getLightControl(), 'failed to deserialize');
        self::assertSame($backToObject->getLightControl()->getBrightness(), $deviceDto->getLightControl()->getBrightness());
        self::assertSame($backToObject->getLightControl()->getColorHex(), $deviceDto->getLightControl()->getColorHex());
        self::assertSame($backToObject->getLightControl()->getState(), $deviceDto->getLightControl()->getState());
    }

    public function testDeserializeThrowsExceptionOnInvalidData(): void
    {
        $serializer = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Deserialize should be an array or an instance of DeviceDto');

        $serializer->deserialize(
            '{"ATTR_DEVICE_MANUFACTURER":"a","ATTR_DEVICE_MODEL_NUMBER":"b","ATTR_DEVICE_FIRMWARE_VERSION":"c"}',
            DeviceInfoDto::class,
            'json',
        );
    }

    public function testSerializeAndDeserializeGroup(): void
    {
        // Arrange
        $expectedJson = /** @lang JSON */ <<<'JSON'
            {
                "ATTR_ID": 1000,
                "ATTR_NAME": "Group 1",
                "ATTR_CREATED_AT": "2019-01-01T00:00:00+00:00",
                "ATTR_GROUP_MEMBERS": [
                    65588
                ],
                "ATTR_DEVICE_STATE": true,
                "ATTR_LIGHT_DIMMER": 100,
                "ATTR_MOOD": 1
            }
            JSON;

        $serializer = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();
        $groupDto   = new \IKEA\Tradfri\Dto\CoapResponse\GroupDto(
            1000,
            'Group 1',
            new DateTimeImmutable('2019-01-01T00:00:00+00:00'),
            [65588],
            true,
            100.0,
            1,
        );

        // Act
        $json = $serializer->serialize($groupDto, $serializer::FORMAT);
        self::assertJsonStringEqualsJsonString($expectedJson, $json);

        $backToObject = $serializer->deserialize($json, \IKEA\Tradfri\Dto\CoapResponse\GroupDto::class, $serializer::FORMAT);

        // Assert
        self::assertInstanceOf(\IKEA\Tradfri\Dto\CoapResponse\GroupDto::class, $backToObject);
        self::assertSame($groupDto->getId(), $backToObject->getId());
        self::assertSame($groupDto->getName(), $backToObject->getName());
        self::assertSame($groupDto->getState(), $backToObject->getState());
        self::assertSame($groupDto->getDimmerLevel(), $backToObject->getDimmerLevel());
        self::assertSame($groupDto->getMood(), $backToObject->getMood());
        self::assertSame($groupDto->getMembers(), $backToObject->getMembers());
    }
}

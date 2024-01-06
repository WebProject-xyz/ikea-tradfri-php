<?php

declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Serializer;

use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\DeviceInfoDto;
use IKEA\Tradfri\Dto\CoapResponse\LightControlDto;
use PHPUnit\Framework\TestCase;

class JsonDeviceDataSerializerTest extends TestCase
{
    public function testSerializeAndDeserialize(): void
    {
        // Arrange
        $expectedJson = /** @lang JSON */ <<<JSON
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

        $serializer                 = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();

        $deviceData   = new DeviceInfoDto('man', 'type', 'version');
        $deviceDto    = new DeviceDto(12, 'name', $deviceData);

        // Act
        $json         = $serializer->serialize($deviceDto);
        $this->assertSame($expectedJson, $json);

        $backToObject = $serializer->deserialize($json);
        // Assert
        $this->assertSame($backToObject->getName(), $deviceDto->getName());
        $this->assertSame($backToObject->getId(), $deviceDto->getId());
        $this->assertSame($backToObject->getDeviceInfo()->getVersion(), $deviceDto->getDeviceInfo()->getVersion());
        $this->assertSame($backToObject->getDeviceInfo()->getType(), $deviceDto->getDeviceInfo()->getType());
        $this->assertSame($backToObject->getDeviceInfo()->getManufacturer(), $deviceDto->getDeviceInfo()->getManufacturer());
        $this->assertNull($backToObject->getLightControl());
    }

    public function testSerializeAndDeserializeWithLightControl(): void
    {
        // Arrange
        $expectedJson = /** @lang JSON */ <<<JSON
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

        $serializer      = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();

        $lightControlDto = new LightControlDto(1, 22);
        $deviceData      = new DeviceInfoDto('UnitTestFactory', 'TRADFRI bulb E27 W opal 1000lm', 'v1.33.7');
        $deviceDto       = new DeviceDto(1000, 'TRADFRI bulb E27 W opal 1000lm', $deviceData, $lightControlDto);

        // Act
        $json         = $serializer->serialize($deviceDto);
        $this->assertSame($expectedJson, $json);

        $backToObject = $serializer->deserialize($json);

        // Assert
        $this->assertSame($backToObject->getName(), $deviceDto->getName());
        $this->assertSame($backToObject->getId(), $deviceDto->getId());

        $this->assertSame($backToObject->getDeviceInfo()->getVersion(), $deviceDto->getDeviceInfo()->getVersion());
        $this->assertSame($backToObject->getDeviceInfo()->getType(), $deviceDto->getDeviceInfo()->getType());
        $this->assertSame($backToObject->getDeviceInfo()->getManufacturer(), $deviceDto->getDeviceInfo()->getManufacturer());

        $this->assertNotNull($backToObject->getLightControl(), 'failed to deserialize');
        $this->assertSame($backToObject->getLightControl()->getBrightness(), $deviceDto->getLightControl()->getBrightness());
        $this->assertSame($backToObject->getLightControl()->getColorHex(), $deviceDto->getLightControl()->getColorHex());
        $this->assertSame($backToObject->getLightControl()->getState(), $deviceDto->getLightControl()->getState());
    }

    public function testSerializeAndDeserializeWithLightControlAndColor(): void
    {
        // Arrange
        $expectedJson = /** @lang JSON */ <<<JSON
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

        $serializer      = new \IKEA\Tradfri\Serializer\JsonDeviceDataSerializer();

        $lightControlDto = new LightControlDto(1, 22, 'ff00ff');
        $deviceData      = new DeviceInfoDto('UnitTestFactory', 'TRADFRI bulb E27 W opal 1000lm', 'v1.33.7');
        $deviceDto       = new DeviceDto(1000, 'TRADFRI bulb E27 W opal 1000lm', $deviceData, $lightControlDto);

        // Act
        $json         = $serializer->serialize($deviceDto);
        $this->assertSame($expectedJson, $json);

        $backToObject = $serializer->deserialize($json);

        // Assert
        $this->assertSame($backToObject->getName(), $deviceDto->getName());
        $this->assertSame($backToObject->getId(), $deviceDto->getId());

        $this->assertSame($backToObject->getDeviceInfo()->getVersion(), $deviceDto->getDeviceInfo()->getVersion());
        $this->assertSame($backToObject->getDeviceInfo()->getType(), $deviceDto->getDeviceInfo()->getType());
        $this->assertSame($backToObject->getDeviceInfo()->getManufacturer(), $deviceDto->getDeviceInfo()->getManufacturer());

        $this->assertNotNull($backToObject->getLightControl(), 'failed to deserialize');
        $this->assertSame($backToObject->getLightControl()->getBrightness(), $deviceDto->getLightControl()->getBrightness());
        $this->assertSame($backToObject->getLightControl()->getColorHex(), $deviceDto->getLightControl()->getColorHex());
        $this->assertSame($backToObject->getLightControl()->getState(), $deviceDto->getLightControl()->getState());
    }
}

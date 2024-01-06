<?php

declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Adapter;

use IKEA\Tradfri\Adapter\Coap;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\DeviceInfoDto;
use IKEA\Tradfri\Dto\CoapResponse\LightControlDto;
use IKEA\Tradfri\Helper\CommandRunner as Runner;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use Mockery;
use PHPUnit\Framework\TestCase;
use function current;

class CoapTest extends TestCase
{
    public function testGetDevicesDataCanHandleEmptyDeviceIdsResponse(): void
    {
        // Arrange
        $runner = Mockery::mock(Runner::class);
        $runner->expects('execWithTimeout')
            ->andReturn(['[]']);

        $adapter = new Coap(
            new \IKEA\Tradfri\Command\Coaps('127.0.0.1', 'mocked-secret', 'mocked-api-key', 'mocked-user'),
            new DeviceData(),
            new GroupData(),
            $runner
        );

        // Act
        $deviceData = $adapter->getDevicesData();
        // Assert
        $this->assertSame([], $deviceData);
    }

    public function testGetDevicesDataWithRawJson(): void
    {
        // Arrange
        $deviceJson = /** @lang JSON */ <<<DEVICE_JSON
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

        $runner = Mockery::mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"', 1, true)
            ->andReturn(['[5000]']);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/5000"', 1, true)
            ->andReturn([$deviceJson]);

        $adapter = new Coap(
            new \IKEA\Tradfri\Command\Coaps('127.0.0.1', 'mocked-secret', 'mocked-api-key', 'mocked-user'),
            new DeviceData(),
            new GroupData(),
            $runner
        );

        $deviceData  = new DeviceInfoDto('UnitTestFactory', 'TRADFRI motion sensor', 'v1.33.7');
        $deviceDto   = new DeviceDto(5000, 'TRADFRI motion sensor', $deviceData, null);

        // Act
        $deviceData = $adapter->getDevicesData();
        // Assert
        $this->assertIsArray($deviceData);
        $this->assertCount(1, $deviceData);
        $device = current($deviceData);
        $this->assertInstanceOf(DeviceDto::class, $device);
        $this->assertSame($deviceDto->getId(), $device->getId());
        $this->assertSame($deviceDto->getName(), $device->getName());
        $this->assertSame($deviceDto->getDeviceInfo()->getManufacturer(), $device->getDeviceInfo()->getManufacturer());
        $this->assertSame($deviceDto->getDeviceInfo()->getType(), $device->getDeviceInfo()->getType());
        $this->assertSame($deviceDto->getDeviceInfo()->getVersion(), $device->getDeviceInfo()->getVersion());
    }

    public function testGetDevicesDataWithValidJson(): void
    {
        // Arrange
        $deviceJson = /** @lang JSON */ <<<DEVICE_JSON
{
    "ATTR_ID": 12,
    "ATTR_NAME": "name",
    "ATTR_DEVICE_INFO": {
        "ATTR_DEVICE_MANUFACTURER": "manufacturer",
        "ATTR_DEVICE_FIRMWARE_VERSION": "version",
        "ATTR_DEVICE_MODEL_NUMBER": "type"
    }
}
DEVICE_JSON;

        $runner = Mockery::mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"', 1, true)
            ->andReturn(['[5000]']);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/5000"', 1, true)
            ->andReturn([$deviceJson]);

        $adapter = new Coap(
            new \IKEA\Tradfri\Command\Coaps('127.0.0.1', 'mocked-secret', 'mocked-api-key', 'mocked-user'),
            new DeviceData(),
            new GroupData(),
            $runner
        );

        $deviceData  = new DeviceInfoDto('manufacturer', 'type', 'version');
        $deviceDto   = new DeviceDto(12, 'name', $deviceData, null);

        // Act
        $deviceData = $adapter->getDevicesData();
        // Assert
        $this->assertIsArray($deviceData);
        $this->assertCount(1, $deviceData);
        $device = current($deviceData);
        $this->assertInstanceOf(DeviceDto::class, $device);
        $this->assertSame($deviceDto->getId(), $device->getId());
        $this->assertSame($deviceDto->getName(), $device->getName());
        $this->assertSame($deviceDto->getDeviceInfo()->getManufacturer(), $device->getDeviceInfo()->getManufacturer());
        $this->assertSame($deviceDto->getDeviceInfo()->getType(), $device->getDeviceInfo()->getType());
        $this->assertSame($deviceDto->getDeviceInfo()->getVersion(), $device->getDeviceInfo()->getVersion());
    }

    public function testGetDevicesDataWithRawJsonMixedResponse(): void
    {
        // Arrange
        $sensorDeviceId   = 5000;
        $sensorDeviceJson = /** @lang JSON */ <<<SENSOR_DEVICE_JSON
{
    "9003": 5000,
    "9001": "TRADFRI motion sensor",
    "3": {
        "0": "UnitTestFactory",
        "3": "v1.33.7",
        "1": "TRADFRI motion sensor"
    }
}
SENSOR_DEVICE_JSON;

        $lightDeviceId   = 1000;
        $lightDeviceJson = /** @lang JSON */ <<<BULB_DEVICE_JSON
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
                "5850": 1,
                "5851": 22
            }
        ]
    }
BULB_DEVICE_JSON;

        $runner = Mockery::mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"', 1, true)
            ->andReturn(['[' . $sensorDeviceId . ', ' . $lightDeviceId . ']']);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/' . $sensorDeviceId . '"', 1, true)
            ->andReturn([$sensorDeviceJson]);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/' . $lightDeviceId . '"', 1, true)
            ->andReturn([$lightDeviceJson]);

        $adapter = new Coap(
            new \IKEA\Tradfri\Command\Coaps('127.0.0.1', 'mocked-secret', 'mocked-api-key', 'mocked-user'),
            new DeviceData(),
            new GroupData(),
            $runner
        );

        $sensorDeviceData  = new DeviceInfoDto('UnitTestFactory', 'TRADFRI motion sensor', 'v1.33.7');
        $sensorDeviceDto   = new DeviceDto($sensorDeviceId, 'TRADFRI motion sensor', $sensorDeviceData);

        $blubControlData  = new LightControlDto(1, 22, null);
        $blubDeviceData   = new DeviceInfoDto('UnitTestFactory', 'TRADFRI bulb E27 W opal 1000lm', 'v1.33.7');
        $blubDeviceDto    = new DeviceDto($lightDeviceId, 'TRADFRI bulb E27 W opal 1000lm', $blubDeviceData, $blubControlData);

        // Act
        $sensorDeviceData = $adapter->getDevicesData();
        // Assert
        $this->assertIsArray($sensorDeviceData);
        $this->assertCount(2, $sensorDeviceData);
        $this->assertArrayHasKey($sensorDeviceId, $sensorDeviceData);

        $device = $sensorDeviceData[$sensorDeviceId];
        $this->assertInstanceOf(DeviceDto::class, $device);
        $this->assertSame($sensorDeviceDto->getId(), $device->getId());
        $this->assertSame($sensorDeviceDto->getName(), $device->getName());
        $this->assertSame($sensorDeviceDto->getDeviceInfo()->getManufacturer(), $device->getDeviceInfo()->getManufacturer());
        $this->assertSame($sensorDeviceDto->getDeviceInfo()->getType(), $device->getDeviceInfo()->getType());
        $this->assertSame($sensorDeviceDto->getDeviceInfo()->getVersion(), $device->getDeviceInfo()->getVersion());
        $this->assertNull($device->getLightControl());

        $this->assertArrayHasKey($lightDeviceId, $sensorDeviceData);
        $lightDevice = $sensorDeviceData[$lightDeviceId];
        $this->assertInstanceOf(DeviceDto::class, $lightDevice);
        $this->assertSame($blubDeviceDto->getId(), $lightDevice->getId());
        $this->assertSame($blubDeviceDto->getName(), $lightDevice->getName());
        $this->assertSame($blubDeviceDto->getDeviceInfo()->getManufacturer(), $lightDevice->getDeviceInfo()->getManufacturer());
        $this->assertSame($blubDeviceDto->getDeviceInfo()->getType(), $lightDevice->getDeviceInfo()->getType());
        $this->assertSame($blubDeviceDto->getDeviceInfo()->getVersion(), $lightDevice->getDeviceInfo()->getVersion());
        $this->assertNotNull($lightDevice->getLightControl());

        $this->assertSame($blubDeviceDto->getLightControl()->getState(), $lightDevice->getLightControl()->getState());
        $this->assertSame($blubDeviceDto->getLightControl()->getBrightness(), $lightDevice->getLightControl()->getBrightness());
        $this->assertSame($blubDeviceDto->getLightControl()->getColorHex(), $lightDevice->getLightControl()->getColorHex());
    }
}

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

namespace IKEA\Tests\Unit\Tradfri\Adapter;

use IKEA\Tradfri\Adapter\CoapAdapter;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\UnknownDevice;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\DeviceInfoDto;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use IKEA\Tradfri\Dto\CoapResponse\LightControlDto;
use IKEA\Tradfri\Helper\CommandRunnerInterface as Runner;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Service\ServiceInterface;
use PHPUnit\Framework\TestCase;
use WMDE\PsrLogTestDoubles\LoggerSpy;

final class CoapTest extends TestCase
{
    public function testGetDevicesDataCanHandleEmptyDeviceIdsResponse(): void
    {
        // Arrange
        $runner = mock(Runner::class);
        $runner->expects('execWithTimeout')
            ->andReturn(['[]']);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $deviceData = $adapter->getDevicesData();
        // Assert
        $this->assertSame([], $deviceData);
    }

    public function testGetDevicesDataWithRawJson(): void
    {
        // Arrange
        $deviceJson = /** @lang JSON */ <<<'DEVICE_JSON'
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

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"', 1, true, true)
            ->andReturn(['[5000]']);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/5000"', 1, true, true)
            ->andReturn([$deviceJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        $deviceData  = new DeviceInfoDto('UnitTestFactory', 'TRADFRI motion sensor', 'v1.33.7');
        $deviceDto   = new DeviceDto(5000, 'TRADFRI motion sensor', $deviceData, null);

        // Act
        $deviceData = $adapter->getDevicesData();
        // Assert
        $this->assertIsArray($deviceData);
        $this->assertCount(1, $deviceData);
        $device = \current($deviceData);
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
        $deviceJson = /** @lang JSON */ <<<'DEVICE_JSON'
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

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"', 1, true, true)
            ->andReturn(['[5000]']);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/5000"', 1, true, true)
            ->andReturn([$deviceJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        $deviceData  = new DeviceInfoDto('manufacturer', 'type', 'version');
        $deviceDto   = new DeviceDto(12, 'name', $deviceData, null);

        // Act
        $deviceData = $adapter->getDevicesData();
        // Assert
        $this->assertIsArray($deviceData);
        $this->assertCount(1, $deviceData);
        $device = \current($deviceData);
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
        $sensorDeviceJson = /** @lang JSON */ <<<'SENSOR_DEVICE_JSON'
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
        $lightDeviceJson = /** @lang JSON */ <<<'BULB_DEVICE_JSON'
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

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"', 1, true, true)
            ->andReturn(['[' . $sensorDeviceId . ', ' . $lightDeviceId . ']']);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/' . $sensorDeviceId . '"', 1, true, true)
            ->andReturn([$sensorDeviceJson]);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/' . $lightDeviceId . '"', 1, true, true)
            ->andReturn([$lightDeviceJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
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

    public function testGetDevicesCollectionWithValidJson(): void
    {
        // Arrange
        $deviceJson = /** @lang JSON */ <<<'DEVICE_JSON'
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

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"', 1, true, true)
            ->andReturn(['[5000]']);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/5000"', 1, true, true)
            ->andReturn([$deviceJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        $deviceData  = new DeviceInfoDto('manufacturer', 'unknowntype', 'version');
        $deviceDto   = new DeviceDto(12, 'name', $deviceData, null);

        $service = mock(ServiceInterface::class);
        // Act
        $deviceCollection = $adapter->getDeviceCollection($service);
        // Assert
        $this->assertCount(1, $deviceCollection);
        $device = $deviceCollection->first();

        $this->assertInstanceOf(UnknownDevice::class, $device);
        $this->assertSame($deviceDto->getId(), $device->getId());
        $this->assertSame($deviceDto->getName(), $device->getName());
        $this->assertSame($deviceDto->getDeviceInfo()->getManufacturer(), $device->getManufacturer());
        $this->assertSame($deviceDto->getDeviceInfo()->getType(), $device->getType());
        $this->assertSame($deviceDto->getDeviceInfo()->getVersion(), $device->getVersion());
    }

    public function testGetType(): void
    {
        // Arrange
        $sensorDeviceId   = 5000;
        $sensorDeviceJson = /** @lang JSON */ <<<'SENSOR_DEVICE_JSON'
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

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/' . $sensorDeviceId . '"', 1, true, true)
            ->andReturn([$sensorDeviceJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $type = $adapter->getType($sensorDeviceId);

        // Assert
        $this->assertSame(\IKEA\Tradfri\Command\Coap\Keys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR, $type);
    }

    public function testGetGroupIds(): void
    {
        // Arrange
        $groupIdsJson = /** @lang JSON */ <<<'GROUPS_JSON'
[1234, 4321]
GROUPS_JSON;

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15004"', 1, true, true)
            ->andReturn([$groupIdsJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $groupIds = $adapter->getGroupIds();

        // Assert
        $this->assertCount(2, $groupIds);
        $this->assertContains(1234, $groupIds);
        $this->assertContains(4321, $groupIds);
    }

    public function testChangeLightState(): void
    {
        // Arrange
        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "3311": [{ "5850": 1 }] }\' "coaps://127.0.0.1:5684/15001/123"', 2, true, true)
            ->andReturn(['']);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $changeLightState = $adapter->changeLightState(123, true);

        // Assert
        $this->assertTrue($changeLightState);
    }

    public function testChangeGroupState(): void
    {
        // Arrange
        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "5850": 1 }\' "coaps://127.0.0.1:5684/15004/123"', 2, true)
            ->andReturn(['']);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $changeLightState = $adapter->changeGroupState(123, true);

        // Assert
        $this->assertTrue($changeLightState);
    }

    public function testSetLightBrightness(): void
    {
        // Arrange
        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "3311": [{ "5851": 59 }] }\' "coaps://127.0.0.1:5684/15001/123"', 2, true)
            ->andReturn(['']);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $changeLightState = $adapter->setLightBrightness(123, 23);

        // Assert
        $this->assertTrue($changeLightState);
    }

    public function testSetGroupBrightness(): void
    {
        // Arrange
        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "5851": 59 }\' "coaps://127.0.0.1:5684/15004/123"', 2, true)
            ->andReturn(['']);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $changeLightState = $adapter->setGroupBrightness(123, 23);

        // Assert
        $this->assertTrue($changeLightState);
    }

    public function testSetRollerBlindPosition(): void
    {
        // Arrange
        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "15015": [{ "5536": 23 }] }\' "coaps://127.0.0.1:5684/15001/123"', 2, true)
            ->andReturn(['']);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $changeLightState = $adapter->setRollerBlindPosition(123, 23);

        // Assert
        $this->assertTrue($changeLightState);
    }

    public function testGetManufacturer(): void
    {
        // Arrange
        $sensorDeviceId   = 5000;
        $sensorDeviceJson = /** @lang JSON */ <<<'SENSOR_DEVICE_JSON'
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

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/' . $sensorDeviceId . '"', 1, true, true)
            ->andReturn([$sensorDeviceJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $type = $adapter->getManufacturer($sensorDeviceId);

        // Assert
        $this->assertSame('UnitTestFactory', $type);
    }

    public function testGetGroupsData(): void
    {
        // Arrange
        $groupIdsJson = /** @lang JSON */ <<<'GROUPS_JSON'
[1234, 4321]
GROUPS_JSON;
        $group1Json = /** @lang JSON */ <<<'GROUP_JSON'
{
    "9001": "Group 1",
    "9002": 1498340478,
    "9003": 1234,
    "5850": 1,
    "5851": 0,
    "9093": 222180,
    "9018": {
        "15002": {
            "9003": [
                65544,
                65546
            ]
        }
    }
}
GROUP_JSON;
        $group2Json = /** @lang JSON */ <<<'GROUP2_JSON'
{
    "9001": "Group 2",
    "9002": 1498340478,
    "9003": 4321,
    "5850": 1,
    "5851": 0,
    "9093": 222180,
    "9018": {
        "15002": {
            "9003": [
                65544,
                65546
            ]
        }
    }
}
GROUP2_JSON;

        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15004"', 1, true, true)
            ->andReturn([$groupIdsJson]);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15004/1234"', 1, true, true)
            ->andReturn([$group1Json]);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15004/4321"', 1, true, true)
            ->andReturn([$group2Json]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        // Act
        $groupData = $adapter->getGroupsData();
        // Assert
        $this->assertIsArray($groupData);
        $this->assertCount(2, $groupData);
        $group = \current($groupData);

        $this->assertIsObject($group);
        $this->assertInstanceOf(GroupDto::class, $group);
        $this->assertSame(1234, $group->getId());
        $this->assertSame('Group 1', $group->getName());
    }

    public function testGetGroupCollection(): void
    {
        // Arrange
        $groupIdsJson = /** @lang JSON */ <<<'GROUPS_JSON'
[1234, 4321]
GROUPS_JSON;
        $group1Json = /** @lang JSON */ <<<'GROUP_JSON'
{
    "9001": "Group 1",
    "9002": 1498340478,
    "9003": 1234,
    "5850": 1,
    "5851": 0,
    "9093": 222180,
    "9018": {
        "15002": {
            "9003": [
                5000
            ]
        }
    }
}
GROUP_JSON;
        $group2Json = /** @lang JSON */ <<<'GROUP2_JSON'
{
    "9001": "Group 2",
    "9002": 1498340478,
    "9003": 4321,
    "5850": 1,
    "5851": 0,
    "9093": 222180,
    "9018": {
        "15002": {
            "9003": [
                5000
            ]
        }
    }
}
GROUP2_JSON;
        $deviceJson = /** @lang JSON */ <<<'DEVICE_JSON'
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
        $runner = mock(Runner::class);
        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15004"', 1, true, true)
            ->andReturn([$groupIdsJson]);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15004/1234"', 1, true, true)
            ->andReturn([$group1Json]);

        $runner
            ->expects('execWithTimeout')
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15004/4321"', 1, true, true)
            ->andReturn([$group2Json]);

        $runner
            ->expects('execWithTimeout')
            ->times(2)
            ->with('coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001/5000"', 1, true, true)
            ->andReturn([$deviceJson]);

        $adapter = new CoapAdapter(
            $this->getGatewayAuthConfigDto(),
            $this->buildCoapsCommandsWrapper(),
            new DeviceData(),
            new GroupData(),
            $runner,
        );

        $logger = new LoggerSpy();
        $adapter->setLogger($logger);

        $service = mock(\IKEA\Tradfri\Service\ServiceInterface::class);
        // Act
        $groupCollection = $adapter->getGroupCollection($service);

        // Assert
        \Mockery::close();
        $this->assertCount(0, $logger->getLogCalls());
        $this->assertCount(2, $groupCollection);
        $group = $groupCollection->first();

        $this->assertInstanceOf(\IKEA\Tradfri\Group\DeviceGroup::class, $group);
        $this->assertSame(1234, $group->getId());
        $this->assertSame('Group 1', $group->getName());
        $this->assertSame([5000], $group->getDeviceIds());
        $this->assertInstanceOf(MotionSensor::class, $group->getDevices()->first());
        $this->assertSame(5000, $group->getDevices()->first()->getId());
        $this->assertSame('TRADFRI motion sensor', $group->getDevices()->first()->getType());
        $this->assertSame('GROUP: Group 1', $group->getType());
        $this->assertSame(
            [
                5000 => [
                    'id'           => 5000,
                    'manufacturer' => 'UnitTestFactory',
                    'name'         => 'TRADFRI motion sensor',
                    'type'         => 'TRADFRI motion sensor',
                    'version'      => 'v1.33.7',
                ],
            ],
            $group->jsonSerialize(),
        );
    }

    private function buildCoapsCommandsWrapper(): \IKEA\Tradfri\Command\GatewayHelperCommands
    {
        return new \IKEA\Tradfri\Command\GatewayHelperCommands(
            $this->getGatewayAuthConfigDto(),
        );
    }

    private function getGatewayAuthConfigDto(): CoapGatewayAuthConfigDto
    {
        return new CoapGatewayAuthConfigDto(
            username: 'mocked-user',
            apiKey: 'mocked-api-key',
            gatewaySecret: 'mocked-secret',
            gatewayIp: '127.0.0.1',
        );
    }
}

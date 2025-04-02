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

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Command\Coap\Blinds\BlindsGetCurrentPositionCommand;
use IKEA\Tradfri\Command\Coap\Group\GroupDimmerCommand;
use IKEA\Tradfri\Command\Coap\Group\GroupSwitchStateCommand;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Command\Coap\Light\LightDimmerCommand;
use IKEA\Tradfri\Command\Coap\Light\LightSwitchStateCommand;
use IKEA\Tradfri\Command\GatewayHelperCommands;
use IKEA\Tradfri\Command\Get;
use IKEA\Tradfri\Command\Request;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Helper\CommandRunner;
use IKEA\Tradfri\Helper\CommandRunnerInterface;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Serializer\JsonDeviceDataSerializer;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Util\JsonIntTypeNormalizer;
use IKEA\Tradfri\Values\CoapHubResponseDataType;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @phpstan-import-type DeviceIdsType from AdapterInterface
 * @phpstan-import-type DeviceIdType from AdapterInterface
 */
final class CoapAdapter implements AdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    private const string COULD_NOT_SWITCH_STATE = 'Could not switch state';

    public function __construct(
        private readonly CoapGatewayAuthConfigDto $authConfig,
        private readonly GatewayHelperCommands $commands,
        private readonly DeviceData $deviceDataMapper,
        private readonly GroupData $groupDataMapper,
        private readonly CommandRunnerInterface $runner = new CommandRunner(),
        private readonly JsonDeviceDataSerializer $deviceSerializer = new JsonDeviceDataSerializer(),
    ) {
    }

    /**
     * @phpstan-param DeviceIdType $deviceId
     *
     * @throws \JsonException|RuntimeException
     */
    public function getType(int $deviceId): string
    {
        $data = $this->requestDataFromHub(
            requestType: Request::RootDevices,
            deviceId: $deviceId,
            returnRawData: false,
            returnType: CoapHubResponseDataType::Object,
        );

        if (\is_object($data)
            && \property_exists($data, Keys::ATTR_DEVICE_INFO)
            && \is_object($data->{Keys::ATTR_DEVICE_INFO})
            && \property_exists($data->{Keys::ATTR_DEVICE_INFO}, Keys::ATTR_DEVICE_MODEL_NUMBER)
        ) {
            $type = $data
                ->{Keys::ATTR_DEVICE_INFO}
                ->{Keys::ATTR_DEVICE_MODEL_NUMBER} ?? throw new RuntimeException('invalid coap response');
            Assert::stringNotEmpty($type);

            return $type;
        }

        throw new RuntimeException('invalid coap response');
    }

    /**
     * @phpstan-param DeviceIdType $deviceId
     *
     * @throws \JsonException|RuntimeException
     */
    public function getManufacturer(int $deviceId): string
    {
        $data = $this->requestDataFromHub(
            requestType: Request::RootDevices,
            deviceId: $deviceId,
            returnRawData: false,
            returnType: CoapHubResponseDataType::Object,
        );

        if (\is_object($data)
            && \property_exists($data, Keys::ATTR_DEVICE_INFO)
            && \is_object($data->{Keys::ATTR_DEVICE_INFO})
            && \property_exists($data->{Keys::ATTR_DEVICE_INFO}, Keys::ATTR_DEVICE_MANUFACTURER)
        ) {
            $manufacturer = $data->{Keys::ATTR_DEVICE_INFO}->{Keys::ATTR_DEVICE_MANUFACTURER};
            Assert::stringNotEmpty($manufacturer);

            return $manufacturer;
        }

        throw new RuntimeException('invalid hub response');
    }

    /**
     * @phpstan-param DeviceIdType $deviceId
     *
     * @throws RuntimeException
     */
    public function changeLightState(int $deviceId, bool $toState): bool
    {
        $command = new LightSwitchStateCommand($this->authConfig, $deviceId, $toState);

        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @phpstan-param DeviceIdType $groupId
     *
     * @throws RuntimeException
     */
    public function changeGroupState(int $groupId, bool $toState): bool
    {
        $command = new GroupSwitchStateCommand($this->authConfig, $groupId, $toState);

        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @phpstan-param DeviceIdType $lightId
     *
     * @throws RuntimeException
     */
    public function setLightBrightness(int $lightId, int $level): bool
    {
        $command = new LightDimmerCommand($this->authConfig, $lightId, $level);

        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * * @phpstan-param DeviceIdType $rollerBlindId
     */
    public function setRollerBlindPosition(int $rollerBlindId, int $level): bool
    {
        $command = new BlindsGetCurrentPositionCommand($this->authConfig, $rollerBlindId, $level);

        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @phpstan-param DeviceIdType $groupId
     *
     * @throws RuntimeException
     */
    public function setGroupBrightness(int $groupId, int $level): bool
    {
        $command = new GroupDimmerCommand($this->authConfig, $groupId, $level);

        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getDeviceCollection(ServiceInterface $service): Devices
    {
        return $this
            ->deviceDataMapper
            ->map($service, $this->getDevicesData(), new Devices());
    }

    /**
     * @phpstan-param DeviceIdsType|null $deviceIds
     *
     * @throws \JsonException|RuntimeException
     *
     * @return array<int, \IKEA\Tradfri\Dto\CoapResponse\DeviceDto>
     */
    public function getDevicesData(?array $deviceIds = null): array
    {
        if (null === $deviceIds) {
            $deviceIds = $this->getDeviceIds();
        }

        $array_key_last = \array_key_last($deviceIds);
        $deviceData     = [];
        foreach ($deviceIds as $index => $deviceId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $deviceData[$deviceId] = $this->getDeviceData((int) $deviceId);

            if ($array_key_last !== $index) {
                \usleep(50);
            }
        }

        return $deviceData;
    }

    /**
     * @throws \JsonException|RuntimeException
     *
     * @return DeviceIdsType
     */
    public function getDeviceIds(): array
    {
        $dataFromHub = $this->requestDataFromHub(
            requestType: Request::RootDevices,
            deviceId: null,
            returnRawData: false,
            returnType: CoapHubResponseDataType::ListInt,
        );
        Assert::isList($dataFromHub);
        Assert::allPositiveInteger($dataFromHub);

        return $dataFromHub;
    }

    /**
     * @phpstan-param DeviceIdType $deviceId
     *
     * @throws \JsonException|RuntimeException
     */
    public function getDeviceData(int $deviceId): DeviceDto
    {
        $jsonStringRaw = $this->requestDataFromHub(
            requestType: Request::RootDevices,
            deviceId: $deviceId,
            returnRawData: true,
            returnType: CoapHubResponseDataType::String,
        );

        Assert::stringNotEmpty($jsonStringRaw);
        $rawJson       = (new JsonIntTypeNormalizer())(
            $jsonStringRaw,
            DeviceDto::class
        );

        $deviceDto = $this->deviceSerializer->deserialize($rawJson, DeviceDto::class, $this->deviceSerializer::FORMAT);
        Assert::isInstanceOf($deviceDto, DeviceDto::class);

        return $deviceDto;
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getGroupCollection(ServiceInterface $service): Groups
    {
        $groups = $this
            ->groupDataMapper
            ->map($service, $this->getGroupsData(), new Groups());

        if (false === $groups->isEmpty()) {
            foreach ($groups->toArray() as $group) {
                $groupDevices = $this
                    ->deviceDataMapper
                    ->map(
                        $service,
                        $this->getDevicesData($group->getDeviceIds()),
                        new Devices(),
                    );
                $group->setDevices($groupDevices);
            }
        }

        return $groups;
    }

    /**
     * @throws \JsonException|RuntimeException
     *
     * @phpstan-return array<positive-int, GroupDto>
     */
    public function getGroupsData(): array
    {
        $groupData = [];
        foreach ($this->getGroupIds() as $groupId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $dataFromHub = $this->requestDataFromHub(
                requestType: Request::RootGroups,
                deviceId: (int) $groupId,
                returnRawData: true,
                returnType: CoapHubResponseDataType::String,
            );
            Assert::stringNotEmpty($dataFromHub);

            $rawJson = (new JsonIntTypeNormalizer())(
                jsonString: $dataFromHub,
                targetClass: GroupDto::class
            );

            $groupDto = $this->deviceSerializer->deserialize($rawJson, GroupDto::class, $this->deviceSerializer::FORMAT);
            Assert::isInstanceOf($groupDto, GroupDto::class);
            Assert::positiveInteger($groupId = $groupDto->getId());
            $groupData[$groupId] = $groupDto;
        }

        return $groupData;
    }

    /**
     * @throws \JsonException|RuntimeException
     *
     * @phpstan-return DeviceIdsType
     */
    public function getGroupIds(): array
    {
        $groupIds = $this->requestDataFromHub(
            requestType: Request::RootGroups,
            deviceId: null,
            returnRawData: false,
            returnType: CoapHubResponseDataType::ListInt,
        );

        Assert::isList($groupIds);
        Assert::allPositiveInteger($groupIds);

        return $groupIds;
    }

    /**
     * @throws \JsonException
     * @throws RuntimeException
     *
     * @phpstan-return ($returnType is CoapHubResponseDataType::String ? string : no-return)|($returnType is CoapHubResponseDataType::Array ? array<mixed> : no-return)|($returnType is CoapHubResponseDataType::Object ? object : no-return)|($returnType is CoapHubResponseDataType::ListInt ? list<positive-int> : no-return)
     */
    private function requestDataFromHub(
        Request|string $requestType,
        ?int $deviceId,
        bool $returnRawData,
        CoapHubResponseDataType $returnType,
    ): array|object|string {
        $requestCommand = new Get($this->authConfig);

        $dataRaw = $this->commands->parseResult(
            $requestCommand->run(runner: $this->runner, request: $requestType, deviceId: $deviceId, throw: true),
        ) ?: throw new RuntimeException('invalid hub response');

        if ($returnRawData) {
            $this->validateResponseType($returnType, $dataRaw);

            return $dataRaw;
        }

        $decodeData = $this->decodeData($dataRaw);

        $this->validateResponseType($returnType, $decodeData);

        return $decodeData;
    }

    /**
     * @throws \JsonException
     *
     * @phpstan-return array<int|string|mixed>|object|string
     */
    private function decodeData(string $dataRaw): array|object|string
    {
        $decoded = \json_decode($dataRaw, false, 512);
        if (null === $decoded) {
            $decoded = $dataRaw;
        }

        if (false === $decoded || (!\is_array($decoded) && !\is_object($decoded) && !\is_string($decoded))) {
            throw new RuntimeException('invalid decoded response');
        }

        return $decoded;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateResponseType(CoapHubResponseDataType $returnType, mixed $dataRaw): void
    {
        match ($returnType) {
            CoapHubResponseDataType::Array   => Assert::isArray($dataRaw),
            CoapHubResponseDataType::ListInt => Assert::allPositiveInteger($dataRaw),
            CoapHubResponseDataType::Object  => Assert::object($dataRaw),
            CoapHubResponseDataType::String  => Assert::stringNotEmpty($dataRaw),
        };
    }
}

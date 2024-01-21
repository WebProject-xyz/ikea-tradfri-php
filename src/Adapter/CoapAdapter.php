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
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Helper\CommandRunner;
use IKEA\Tradfri\Helper\CommandRunnerInterface;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Serializer\JsonDeviceDataSerializer;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Util\JsonIntTypeNormalizer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use const JSON_THROW_ON_ERROR;

final class CoapAdapter implements AdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    private const COULD_NOT_SWITCH_STATE = 'Could not switch state';

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
     * @throws \JsonException|RuntimeException
     */
    public function getType(int $deviceId): string
    {
        $data = $this->requestDataFromHub(Request::RootDevices, $deviceId);
        if (\is_object($data)
            && \property_exists($data, Keys::ATTR_DEVICE_INFO)
            && \property_exists($data->{Keys::ATTR_DEVICE_INFO}, Keys::ATTR_DEVICE_MODEL_NUMBER)
        ) {
            return $data
                ->{Keys::ATTR_DEVICE_INFO}
                ->{Keys::ATTR_DEVICE_MODEL_NUMBER};
        }

        throw new RuntimeException('invalid coap response');
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getManufacturer(int $deviceId): string
    {
        $data = $this->requestDataFromHub(Request::RootDevices, $deviceId);

        if (\is_object($data) && isset($data->{Keys::ATTR_DEVICE_INFO}->{'0'})) {
            return $data->{Keys::ATTR_DEVICE_INFO}->{'0'};
        }

        throw new RuntimeException('invalid hub response');
    }

    /**
     * @throws RuntimeException
     */
    public function changeLightState(int $deviceId, bool $toState): bool
    {
        // run command
        $command = new LightSwitchStateCommand($this->authConfig, $deviceId, $toState);

        // verify result
        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws RuntimeException
     */
    public function changeGroupState(int $groupId, bool $toState): bool
    {
        // run command
        $command = new GroupSwitchStateCommand($this->authConfig, $groupId, $toState);

        // verify result
        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws RuntimeException
     */
    public function setLightBrightness(int $lightId, int $level): bool
    {
        // run command
        $command = new LightDimmerCommand($this->authConfig, $lightId, $level);

        // verify result
        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    public function setRollerBlindPosition(int $rollerBlindId, int $level): bool
    {
        // run command
        $command = new BlindsGetCurrentPositionCommand($this->authConfig, $rollerBlindId, $level);

        // verify result
        return $command->run($this->runner)
            ?: throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws RuntimeException
     */
    public function setGroupBrightness(int $groupId, int $level): bool
    {
        // run command
        $command = new GroupDimmerCommand($this->authConfig, $groupId, $level);

        // verify result
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
            ->map($service, $this->getDevicesData());
    }

    /**
     * Get devices.
     *
     * @throws \JsonException|RuntimeException
     */
    public function getDevicesData(?array $deviceIds = null): array
    {
        if (null === $deviceIds) {
            $deviceIds = $this->getDeviceIds();
        }

        $deviceData = [];
        foreach ($deviceIds as $index => $deviceId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $deviceData[$deviceId] = $this->getDeviceData((int) $deviceId);

            if (\array_key_last($deviceIds) !== $index) {
                \usleep((int) COAP_GATEWAY_FLOOD_PROTECTION);
            }
        }

        return $deviceData;
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getDeviceIds(): array
    {
        return $this->requestDataFromHub(Request::RootDevices);
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getDeviceData(int $deviceId): DeviceDto
    {
        $jsonStringRaw = $this->requestDataFromHub(Request::RootDevices, $deviceId, true);
        $rawJson       = (new JsonIntTypeNormalizer())(
            $jsonStringRaw,
            DeviceDto::class
        );

        return $this->deviceSerializer->deserialize($rawJson, DeviceDto::class, $this->deviceSerializer::FORMAT);
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getGroupCollection(ServiceInterface $service): Groups
    {
        /** @var Groups $groups */
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
     */
    public function getGroupsData(): array
    {
        $groupData = [];
        foreach ($this->getGroupIds() as $groupId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $groupData[$groupId] = $this->requestDataFromHub(
                Request::RootGroups,
                (int) $groupId,
            );
        }

        return $groupData;
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getGroupIds(): array
    {
        return $this->requestDataFromHub(Request::RootGroups);
    }

    /**
     * @throws \JsonException
     * @throws RuntimeException
     */
    private function requestDataFromHub(Request|string $requestType, ?int $deviceId = null, bool $returnRawData = false): array|object|string
    {
        $requestCommand = new Get($this->authConfig);

        $dataRaw = $this->commands->parseResult(
            $requestCommand->run($this->runner, $requestType, $deviceId),
        ) ?: throw new RuntimeException('invalid hub response');

        if ($returnRawData) {
            return $dataRaw;
        }

        return $this->decodeData($dataRaw);
    }

    /**
     * @throws \JsonException
     */
    private function decodeData(string $dataRaw): array|object|string
    {
        $decoded = \json_decode($dataRaw, false, 512, JSON_THROW_ON_ERROR);
        if (null === $decoded) {
            $decoded = $dataRaw;
        }

        // todo: maybe full dto normalize
        return $decoded;
    }
}

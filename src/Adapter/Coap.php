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
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Command\Coaps;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Helper\CommandRunner;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Serializer\JsonDeviceDataSerializer;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Util\JsonIntTypeNormalizer;
use const JSON_THROW_ON_ERROR;

final class Coap implements AdapterInterface
{
    private const COULD_NOT_SWITCH_STATE = 'Could not switch state';

    public function __construct(
        protected Coaps $commands,
        private readonly DeviceData $deviceDataMapper,
        private readonly GroupData $groupDataMapper,
        private readonly CommandRunner $runner = new CommandRunner(),
        private readonly JsonDeviceDataSerializer $deviceSerializer = new JsonDeviceDataSerializer(),
    ) {
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getType(int $deviceId): string
    {
        $data = $this->requestDataFromHub(Keys::ROOT_DEVICES, $deviceId);
        if (\is_object($data)
            && \property_exists($data, Keys::ATTR_DEVICE_INFO)
            && \property_exists($data->{Keys::ATTR_DEVICE_INFO}, Keys::ATTR_DEVICE_INFO_TYPE)
        ) {
            return $data
                ->{Keys::ATTR_DEVICE_INFO}
                ->{Keys::ATTR_DEVICE_INFO_TYPE};
        }

        throw new RuntimeException('invalid coap response');
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getManufacturer(int $deviceId): string
    {
        $data = $this->requestDataFromHub(Keys::ROOT_DEVICES, $deviceId);

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
        $data = $this->runner
            ->execWithTimeout(
                $this->commands->getLightSwitchCommand($deviceId, $toState),
                2,
                true,
                true,
            );

        // verify result
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws RuntimeException
     */
    public function changeGroupState(int $groupId, bool $toState): bool
    {
        // run command
        $data = $this->runner
            ->execWithTimeout(
                $this->commands->getGroupSwitchCommand($groupId, $toState),
                2,
                true,
            );

        // verify result

        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws RuntimeException
     */
    public function setLightBrightness(int $lightId, int $level): bool
    {
        // run command
        $data = $this->runner->execWithTimeout(
            $this->commands->getLightDimmerCommand($lightId, $level),
            2,
            true,
        );

        // verify result
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    public function setRollerBlindPosition(int $rollerBlindId, int $level): bool
    {
        // run command
        $data = $this->runner->execWithTimeout(
            $this->commands->getRollerBlindDarkenedStateCommand($rollerBlindId, $level),
            2,
            true,
        );

        // verify result
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws RuntimeException
     */
    public function setGroupBrightness(int $groupId, int $level): bool
    {
        // run command
        $data = $this->runner->execWithTimeout(
            $this->commands->getGroupDimmerCommand($groupId, $level),
            2,
            true,
        );

        // verify result
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
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
        return $this->requestDataFromHub(Keys::ROOT_DEVICES);
    }

    /**
     * @throws \JsonException|RuntimeException
     */
    public function getDeviceData(int $deviceId): DeviceDto
    {
        $jsonStringRaw = $this->requestDataFromHub(Keys::ROOT_DEVICES, $deviceId, true);
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
                Keys::ROOT_GROUPS,
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
        return $this->requestDataFromHub(Keys::ROOT_GROUPS);
    }

    /**
     * @throws \JsonException
     * @throws RuntimeException
     */
    private function requestDataFromHub(string $requestType, ?int $deviceId = null, bool $returnRawData = false): array|object|string
    {
        $command = $this->commands->getCoapsCommandGet($requestType);

        if (null !== $deviceId) {
            $command = \mb_substr($command, 0, -1) . '/' . $deviceId . '"';
        }

        $dataRaw = $this->commands->parseResult(
            $this->runner->execWithTimeout(
                $command,
                1,
                true,
            ),
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

        return $decoded;
    }

    private function _verifyResult(mixed $data): bool
    {
        return \is_array($data)
            && (4 === \count($data) || '' === ($data[0] ?? null));
    }
}

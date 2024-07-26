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
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @phpstan-type DeviceIdType = positive-int
 * @phpstan-type DeviceIdsType = array<DeviceIdType>
 */
interface AdapterInterface
{
    public const STATE_ON  = false;
    public const STATE_OFF = false;

    /**
     * @phpstan-param DeviceIdType $deviceId
     */
    public function getDeviceData(int $deviceId): DeviceDto;

    /**
     * @phpstan-return DeviceIdsType
     */
    public function getDeviceIds(): array;

    /**
     * @phpstan-return DeviceIdsType
     */
    public function getGroupIds(): array;

    /**
     * @phpstan-return GroupDto[]
     */
    public function getGroupsData(): array;

    /**
     * @phpstan-param DeviceIdsType|null $deviceIds
     */
    public function getDevicesData(?array $deviceIds = null): array;

    public function changeLightState(int $deviceId, bool $toState): bool;

    public function changeGroupState(int $groupId, bool $toState): bool;

    public function setLightBrightness(int $lightId, int $level): bool;

    public function setGroupBrightness(int $groupId, int $level): bool;

    public function setRollerBlindPosition(int $rollerBlindId, int $level): bool;

    /**
     * @phpstan-return Devices<DeviceInterface&\JsonSerializable>
     */
    public function getDeviceCollection(ServiceInterface $service): Devices;

    public function getGroupCollection(ServiceInterface $service): Groups;

    /**
     * @throws RuntimeException
     */
    public function getType(int $deviceId): string;

    public function getManufacturer(int $deviceId): string;
}

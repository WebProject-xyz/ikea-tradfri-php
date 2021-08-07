<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Service\ServiceInterface;
use stdClass;

interface AdapterInterface
{
    public function getDeviceData(int $deviceId): stdClass;

    public function getDeviceIds(): array;

    public function getGroupIds(): array;

    public function getGroupsData(): array;

    public function getDevicesData(array $deviceIds = null): array;

    public function changeLightState(int $deviceId, bool $toState): bool;

    public function changeGroupState(int $groupId, bool $toState): bool;

    public function setLightBrightness(int $lightId, int $level): bool;

    public function setGroupBrightness(int $groupId, int $level): bool;

    public function setRollerBlindPosition(int $rollerBlindId, int $level);

    public function getDeviceCollection(ServiceInterface $service): Devices;

    public function getGroupCollection(ServiceInterface $service): Groups;
}

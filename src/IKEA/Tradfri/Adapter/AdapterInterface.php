<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class Coap.
 */
interface AdapterInterface
{
    /**
     * Get device as object.
     *
     * @param int $deviceId
     *
     * @return \stdClass
     */
    public function getDeviceData(int $deviceId);

    /**
     * Get device ids as array.
     *
     * @return array
     */
    public function getDeviceIds(): array;

    /**
     * Get device ids as array.
     *
     * @return array
     */
    public function getGroupIds(): array;

    /**
     * Get groups as array.
     *
     * @return array
     */
    public function getGroupsData(): array;

    /**
     * Get devices as array.
     *
     * @param array|null $deviceIds
     *
     * @return array
     */
    public function getDevicesData(array $deviceIds = null): array;

    /**
     * Change State of given device.
     *
     * @param int  $deviceId
     * @param bool $toState
     *
     * @return bool
     */
    public function changeLightState(int $deviceId, bool $toState): bool;

    /**
     * Change State of given group.
     *
     * @param int  $groupId
     * @param bool $toState
     *
     * @return bool
     */
    public function changeGroupState(int $groupId, bool $toState): bool;

    /**
     * Set Light Brightness.
     *
     * @param int $lightId
     * @param int $level
     *
     * @return bool
     */
    public function setLightBrightness(int $lightId, int $level): bool;

    /**
     * Set Group Brightness.
     *
     * @param int $groupId
     * @param int $level
     *
     * @return bool
     */
    public function setGroupBrightness(int $groupId, int $level): bool;

    /**
     * Get a collection of Devices.
     *
     * @param ServiceInterface $service
     *
     * @return Devices
     */
    public function getDeviceCollection(ServiceInterface $service): Devices;

    /**
     * Get a collection of Groups.
     *
     * @param ServiceInterface $service
     *
     * @return Groups
     */
    public function getGroupCollection(ServiceInterface $service): Groups;
}

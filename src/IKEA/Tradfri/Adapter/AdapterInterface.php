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
     */
    public function getDeviceData(int $deviceId) : \stdClass;

    /**
     * Get device ids as array.
     */
    public function getDeviceIds() : array;

    /**
     * Get device ids as array.
     */
    public function getGroupIds() : array;

    /**
     * Get groups as array.
     */
    public function getGroupsData() : array;

    /**
     * Get devices as array.
     */
    public function getDevicesData(array $deviceIds = null) : array;

    /**
     * Change State of given device.
     */
    public function changeLightState(int $deviceId, bool $toState) : bool;

    /**
     * Change State of given group.
     */
    public function changeGroupState(int $groupId, bool $toState) : bool;

    /**
     * Set Light Brightness.
     */
    public function setLightBrightness(int $lightId, int $level) : bool;

    /**
     * Set Group Brightness.
     */
    public function setGroupBrightness(int $groupId, int $level) : bool;

    /**
     * Get a collection of Devices.
     */
    public function getDeviceCollection(ServiceInterface $service) : Devices;

    /**
     * Get a collection of Groups.
     */
    public function getGroupCollection(ServiceInterface $service) : Groups;
}

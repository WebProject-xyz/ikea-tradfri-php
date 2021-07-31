<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Service;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\LightBulbs;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\DeviceInterface;
use IKEA\Tradfri\Group\Light;

/**
 * Class Client.
 */
interface ServiceInterface
{
    /**
     * Get Collection of lights.
     */
    public function getLights() : LightBulbs;

    /**
     * Get devices from client.
     */
    public function getDevices() : Devices;

    /**
     * Switch all lights off.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function allLightsOff(LightBulbs $lightBulbsCollection) : bool;

    /**
     * Switch state of.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function on(DeviceInterface $device) : bool;

    /**
     * Switch state of.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function off(DeviceInterface $device) : bool;

    /**
     * Dom device.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function dim(DeviceInterface $device, int $level) : bool;
}

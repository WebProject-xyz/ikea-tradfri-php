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
     *
     * @return LightBulbs
     */
    public function getLights(): LightBulbs;

    /**
     * Get devices from client.
     *
     * @return Devices
     */
    public function getDevices(): Devices;

    /**
     * Switch all lights off.
     *
     * @param LightBulbs $lightBulbsCollection
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function allLightsOff(LightBulbs $lightBulbsCollection): bool;

    /**
     * Switch state of.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function on(DeviceInterface $device): bool;

    /**
     * Switch state of.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function off(DeviceInterface $device): bool;

    /**
     * Dom device.
     *
     * @param Device|Light $device
     * @param int          $level
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function dim(DeviceInterface $device, int $level): bool;
}

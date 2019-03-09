<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Service;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Lightbulbs;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Group\Light;

/**
 * Class Client.
 */
interface ServiceInterface
{
    /**
     * Get Collection of lights.
     *
     * @return Lightbulbs
     */
    public function getLights(): Lightbulbs;

    /**
     * Get devices from client.
     *
     * @return Devices
     */
    public function getDevices(): Devices;

    /**
     * Switch all lights off.
     *
     * @param Lightbulbs $lightbulbsCollection
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function allLightsOff(Lightbulbs $lightbulbsCollection): bool;

    /**
     * Switch state of.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function on($device): bool;

    /**
     * Switch state of.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function off($device): bool;

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
    public function dim($device, int $level): bool;
}

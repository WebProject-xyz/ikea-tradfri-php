<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Service;

use IKEA\Tradfri\Client\Client;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Collection\Lightbulbs;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Group;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\Light;

/**
 * Class Client.
 */
class Api implements ServiceInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Api constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get Collection of lights.
     *
     * @return Lightbulbs
     */
    public function getLights(): Lightbulbs
    {
        return $this->getDevices()->getLightbulbs();
    }

    /**
     * Get devices from client.
     *
     * @return Devices
     */
    public function getDevices(): Devices
    {
        return $this->client->getDevices($this);
    }

    /**
     * Get Collection of groups.
     *
     * @return Groups
     */
    public function getGroups(): Groups
    {
        return $this->client->getGroups($this);
    }

    /**
     * Switch all lights off.
     *
     * @param Lightbulbs $lightbulbsCollection
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function allLightsOff(Lightbulbs $lightbulbsCollection): bool
    {
        $service = $this;
        $lightbulbsCollection->forAll(function ($key, $lightbulb) use ($service) {
            /* @var Lightbulb $lightbulb */
            $service->off($lightbulb);
        });

        return true;
    }

    /**
     * Switch device on.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function on($device): bool
    {
        if ($device instanceof Light) {
            return $this->client->groupOn($device);
        }

        if ($device instanceof Lightbulb) {
            return $this->client->lightOn($device);
        }

        throw new RuntimeException('invalid device type: '.$device->getType());
    }

    /**
     * Switch device off.
     *
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function off($device): bool
    {
        if ($device instanceof Light) {
            return $this->client->groupOff($device);
        }

        if ($device instanceof Lightbulb) {
            return $this->client->lightOff($device);
        }

        throw new RuntimeException('invalid device type: '.$device->getType());
    }

    /**
     * Dim device or group.
     *
     * @param Device|Light $device
     * @param int          $level
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function dim($device, int $level): bool
    {
        if ($device instanceof Light) {
            return $this->client->dimGroup($device, $level);
        }

        if ($device instanceof Lightbulb) {
            return $this->client->dimLight($device, $level);
        }

        throw new RuntimeException('invalid device type: '.$device->getType());
    }
}

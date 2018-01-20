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
    const INVALID_DEVICE_TYPE = 'invalid device type: ';
    /**
     * @var Client
     */
    protected $_client;

    /**
     * Api constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->_client = $client;
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
        return $this->_client->getDevices($this);
    }

    /**
     * Get Collection of groups.
     *
     * @return Groups
     */
    public function getGroups(): Groups
    {
        return $this->_client->getGroups($this);
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
        $lightbulbsCollection->forAll(
            function ($lightbulbKey, $lightbulb) use ($service) {
                /* @var Lightbulb $lightbulb */
                if ($lightbulbKey === $lightbulb->getId()) {
                    // this is ok but who cars can't make var unused
                }
                $service->off($lightbulb);
            }
        );

        return true;
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
            return $this->_client->groupOff($device);
        }

        if ($device instanceof Lightbulb) {
            return $this->_client->lightOff($device);
        }

        throw new RuntimeException(
            self::INVALID_DEVICE_TYPE.$device->getType()
        );
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
    public function switchOn($device): bool
    {
        if ($device instanceof Light) {
            return $this->_client->groupOn($device);
        }

        if ($device instanceof Lightbulb) {
            return $this->_client->lightOn($device);
        }

        throw new RuntimeException(
            self::INVALID_DEVICE_TYPE.$device->getType()
        );
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
            return $this->_client->dimGroup($device, $level);
        }

        if ($device instanceof Lightbulb) {
            return $this->_client->dimLight($device, $level);
        }

        throw new RuntimeException(
            self::INVALID_DEVICE_TYPE.$device->getType()
        );
    }
}

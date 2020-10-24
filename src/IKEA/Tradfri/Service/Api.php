<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Service;

use IKEA\Tradfri\Client\Client;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Collection\LightBulbs;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\Light;

class Api implements ServiceInterface
{
    public const INVALID_DEVICE_TYPE = 'invalid device type: ';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getLights(): LightBulbs
    {
        return $this->getDevices()->getLightBulbs();
    }

    public function getDevices(): Devices
    {
        return $this->client->getDevices($this);
    }

    public function getGroups(): Groups
    {
        return $this->client->getGroups($this);
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function allLightsOff(LightBulbs $lightBulbsCollection): bool
    {
        $service = $this;
        $lightBulbsCollection->forAll(
            function ($lightBulbKey, $lightBulb) use ($service) {
                /** @var LightBulb $lightBulb */
                if ($lightBulbKey === $lightBulb->getId()) {
                    // this is ok but who cares can't make var unused
                }
                $service->off($lightBulb);

                return true;
            }
        );

        return true;
    }

    /**
     * @todo interface switchable
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function off($device): bool
    {
        // @todo interface for "switch" on
        if ($device instanceof Light) {
            return $this->client->groupOff($device);
        }

        if ($device instanceof LightBulb) {
            return $this->client->lightOff($device);
        }

        throw new RuntimeException(
            self::INVALID_DEVICE_TYPE.$device->getType()
        );
    }

    /**
     * @todo interface switchable
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function on($device): bool
    {
        if ($device instanceof Light) {
            return $this->client->groupOn($device);
        }

        if ($device instanceof LightBulb) {
            return $this->client->lightOn($device);
        }

        throw new RuntimeException(
            self::INVALID_DEVICE_TYPE.$device->getType()
        );
    }

    /**
     * @param Device|Light $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function dim(\IKEA\Tradfri\Device\DeviceInterface $device, int $level): bool
    {
        if ($device instanceof Light) {
            return $this->client->dimGroup($device, $level);
        }

        if ($device instanceof LightBulb) {
            return $this->client->dimLight($device, $level);
        }

        throw new RuntimeException(
            self::INVALID_DEVICE_TYPE.$device->getType()
        );
    }
}

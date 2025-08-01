<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Service;

use IKEA\Tradfri\Client\ClientInterface;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Collection\LightBulbs;
use IKEA\Tradfri\Device\Feature\BrightnessStateInterface;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\Feature\SwitchableInterface;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\DeviceGroup;

/**
 * @phpstan-import-type LevelType from ClientInterface
 */
final readonly class GatewayApiService implements ServiceInterface
{
    final public const string INVALID_DEVICE_TYPE = 'invalid device type: ';

    public function __construct(private ClientInterface $client)
    {
    }

    public function getLights(): LightBulbs
    {
        return $this->getDevices()->filterLightBulbs();
    }

    /**
     * @return Devices<DeviceInterface&\JsonSerializable>
     */
    public function getDevices(): Devices
    {
        return $this->client->getDevices($this);
    }

    public function getGroups(): Groups
    {
        return $this->client->getGroups($this);
    }

    /**
     * @throws RuntimeException
     */
    public function allLightsOff(LightBulbs $lightBulbsCollection): bool
    {
        $lightBulbsCollection->forAll(
            function (int $lightBulbKey, SwitchableInterface $lightBulb): bool {
                // if ($lightBulb->getId() === $lightBulbKey) {
                // this is ok but who cares can't make var unused
                // }

                $this->off($lightBulb);

                return true;
            },
        );

        return true;
    }

    /**
     * @throws RuntimeException
     */
    public function off(SwitchableInterface $device): bool
    {
        if ($device instanceof DeviceGroup) {
            return $this->client->groupOff($device);
        }

        if ($device instanceof LightBulb) {
            return $this->client->lightOff($device);
        }

        throw new RuntimeException(self::INVALID_DEVICE_TYPE.$device->getType());
    }

    /**
     * @throws RuntimeException
     */
    public function on(SwitchableInterface $device): bool
    {
        if ($device instanceof DeviceGroup) {
            return $this->client->groupOn($device);
        }

        if ($device instanceof LightBulb) {
            return $this->client->lightOn($device);
        }

        throw new RuntimeException(self::INVALID_DEVICE_TYPE.$device->getType());
    }

    /**
     * @phpstan-param LevelType $level
     *
     * @throws RuntimeException
     */
    public function dim(BrightnessStateInterface&DeviceInterface $device, int $level): bool
    {
        if ($device instanceof DeviceGroup) {
            return $this->client->dimGroup($device, $level);
        }

        if ($device instanceof LightBulb) {
            return $this->client->dimLight($device, $level);
        }

        throw new RuntimeException(self::INVALID_DEVICE_TYPE.$device->getType());
    }

    /**
     * @phpstan-param LevelType $level
     */
    public function setRollerBlindPosition(RollerBlind $device, int $level): bool
    {
        return $this->client->setRollerBlindPosition($device, $level);
    }
}

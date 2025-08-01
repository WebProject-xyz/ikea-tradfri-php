<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Client;

use IKEA\Tradfri\Adapter\AdapterInterface;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Group\DeviceGroup;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @phpstan-type LevelType = int<0,100>
 */
final readonly class Client implements ClientInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {
    }

    /**
     * @phpstan-return Devices<DeviceInterface&\JsonSerializable>
     */
    public function getDevices(ServiceInterface $service): Devices
    {
        return $this->adapter->getDeviceCollection($service);
    }

    public function getGroups(ServiceInterface $service): Groups
    {
        return $this->adapter->getGroupCollection($service);
    }

    public function lightOn(LightBulb $lightBulb): bool
    {
        $wasSet = $this->adapter->changeLightState($lightBulb->getId(), AdapterInterface::STATE_ON);
        if ($wasSet) {
            $lightBulb->setState(true);
        }

        return $wasSet;
    }

    public function lightOff(LightBulb $lightBulb): bool
    {
        $wasSet = $this->adapter->changeLightState($lightBulb->getId(), AdapterInterface::STATE_OFF);
        if ($wasSet) {
            $lightBulb->setState(false);
        }

        return $wasSet;
    }

    public function groupOn(DeviceGroup $group): bool
    {
        $wasSet = $this->adapter->changeGroupState($group->getId(), AdapterInterface::STATE_ON);
        if ($wasSet) {
            $group->setState(true);
        }

        return $wasSet;
    }

    public function groupOff(DeviceGroup $group): bool
    {
        $wasSet = $this->adapter->changeGroupState($group->getId(), AdapterInterface::STATE_OFF);
        if ($wasSet) {
            $group->setState(false);
        }

        return $wasSet;
    }

    /**
     * @phpstan-param LevelType $level
     */
    public function dimLight(LightBulb $lightBulb, int $level): bool
    {
        $wasSet = $this->adapter->setLightBrightness($lightBulb->getId(), $level);
        if ($wasSet) {
            $lightBulb->setBrightnessLevel($level);
        }

        return $wasSet;
    }

    /**
     * @phpstan-param LevelType $level
     */
    public function dimGroup(DeviceGroup $group, int $level): bool
    {
        $wasSet = $this->adapter->setGroupBrightness($group->getId(), $level);
        if ($wasSet) {
            $group->setBrightness($level);
        }

        return $wasSet;
    }

    /**
     * @phpstan-param LevelType $level
     */
    public function setRollerBlindPosition(RollerBlind $blind, int $level): bool
    {
        $wasSet = $this->adapter->setRollerBlindPosition($blind->getId(), $level);
        if ($wasSet) {
            $blind->setDarkenedState($level);
        }

        return $wasSet;
    }
}

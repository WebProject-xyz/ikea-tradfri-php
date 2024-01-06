<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Client;

use IKEA\Tradfri\Adapter\AdapterInterface;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Group\Light as Group;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @psalm-type LevelType = int<0,100>
 */
class Client
{
    public function __construct(protected AdapterInterface $adapter)
    {
    }

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
        return $this->adapter->changeLightState($lightBulb->getId(), AdapterInterface::STATE_ON);
    }

    public function lightOff(LightBulb $lightBulb): bool
    {
        return $this->adapter->changeLightState($lightBulb->getId(), AdapterInterface::STATE_OFF);
    }

    public function groupOn(Group $group): bool
    {
        return $this->adapter->changeGroupState($group->getId(), AdapterInterface::STATE_ON);
    }

    public function groupOff(Group $group): bool
    {
        return $this->adapter->changeGroupState($group->getId(), AdapterInterface::STATE_OFF);
    }

    /**
     * @psalm-param LevelType $level
     */
    public function dimLight(LightBulb $lightBulb, int $level): bool
    {
        return $this->adapter->setLightBrightness($lightBulb->getId(), $level);
    }

    /**
     * @psalm-param LevelType $level
     */
    public function dimGroup(Group $group, int $level): bool
    {
        return $this->adapter->setGroupBrightness($group->getId(), $level);
    }

    /**
     * @psalm-param LevelType $level
     */
    public function setRollerBlindPosition(RollerBlind $blind, int $level): bool
    {
        return $this->adapter->setRollerBlindPosition($blind->getId(), $level);
    }
}

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

class Client
{
    protected AdapterInterface $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getDevices(ServiceInterface $service): Devices
    {
        return $this->getAdapter()->getDeviceCollection($service);
    }

    private function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    public function getGroups(ServiceInterface $service): Groups
    {
        return $this->getAdapter()->getGroupCollection($service);
    }

    public function lightOn(LightBulb $lightBulb): bool
    {
        return $this->getAdapter()->changeLightState($lightBulb->getId(), true);
    }

    public function lightOff(LightBulb $lightBulb): bool
    {
        return $this->getAdapter()->changeLightState(
            $lightBulb->getId(),
            false
        );
    }

    public function groupOn(Group $group): bool
    {
        return $this->getAdapter()->changeGroupState(
            $group->getId(),
            true
        );
    }

    public function groupOff(Group $group): bool
    {
        return $this->getAdapter()->changeGroupState($group->getId(), false);
    }

    public function dimLight(LightBulb $lightBulb, int $level): bool
    {
        return $this->getAdapter()->setLightBrightness(
            $lightBulb->getId(),
            $level
        );
    }

    public function dimGroup(Group $group, int $level): bool
    {
        return $this->getAdapter()->setGroupBrightness($group->getId(), $level);
    }

    public function setRollerBlindPosition(RollerBlind $blind, int $level): bool
    {
        return $this->getAdapter()->setRollerBlindPosition($blind->getId(), $level);
    }
}

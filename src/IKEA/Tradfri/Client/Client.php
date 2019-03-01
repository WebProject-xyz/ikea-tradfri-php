<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Client;

use IKEA\Tradfri\Adapter\AdapterInterface;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Device\Feature\Switchable;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Group\Light as Group;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class Client.
 */
class Client
{
    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * Client constructor.
     *
     * @param AdapterInterface $adapter
     *
     * @internal param MapperInterface $deviceDataMapper
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Get devices.
     *
     * @param Api|ServiceInterface $service
     *
     * @return Devices
     */
    public function getDevices(ServiceInterface $service): Devices
    {
        return $this->getAdapter()->getDeviceCollection($service);
    }

    /**
     * Get Adapter.
     *
     * @return AdapterInterface
     */
    private function getAdapter(): AdapterInterface
    {
        return $this->_adapter;
    }

    /**
     * Get Groups.
     *
     * @param Api|ServiceInterface $service
     *
     * @return Groups
     */
    public function getGroups(ServiceInterface $service): Groups
    {
        return $this->getAdapter()->getGroupCollection($service);
    }

    /**
     * Switch device on.
     *
     * @deprecated This method is moved to the on method
     * @param Lightbulb $lightbulb
     *
     * @return bool
     */
    public function lightOn(Lightbulb $lightbulb): bool
    {
        return $this->on($lightbulb);
    }

    /**
     * Switch device off.
     *
     * @deprecated This method is moved to the off method
     * @param Lightbulb $lightbulb
     *
     * @return bool
     */
    public function lightOff(Lightbulb $lightbulb): bool
    {
        return $this->off($lightbulb);
    }

    /**
     * Switch device on.
     *
     * @param Switchable $switchable
     *
     * @return bool
     */
    public function on(Switchable $switchable): bool
    {
        return $this->getAdapter()->changeLightState($switchable->getId(), true);
    }

    /**
     * Switch device off.
     *
     * @param Switchable $switchable
     *
     * @return bool
     */
    public function off(Switchable $switchable): bool
    {
        return $this->getAdapter()->changeLightState($switchable->getId(), false);
    }

    /**
     * Switch group on.
     *
     * @param Group $group
     *
     * @return bool
     */
    public function groupOn(Group $group): bool
    {
        return $this->getAdapter()->changeGroupState($group->getId(), true);
    }

    /**
     * Switch group off.
     *
     * @param Group $group
     *
     * @return bool
     */
    public function groupOff(Group $group): bool
    {
        return $this->getAdapter()->changeGroupState($group->getId(), false);
    }

    /**
     * Din Light.
     *
     * @param Lightbulb $lightbulb
     * @param int $level
     *
     * @return bool
     */
    public function dimLight(Lightbulb $lightbulb, int $level): bool
    {
        return $this->getAdapter()->setLightBrightness(
            $lightbulb->getId(), $level
        );
    }

    /**
     * Dim Group.
     *
     * @param Group $group
     * @param int $level
     *
     * @return bool
     */
    public function dimGroup(Group $group, int $level): bool
    {
        return $this->getAdapter()->setGroupBrightness($group->getId(), $level);
    }
}

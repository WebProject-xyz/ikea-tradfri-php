<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Mapper\MapperInterface;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class AdapterAbstract.
 */
abstract class AdapterAbstract implements AdapterInterface
{
    /**
     * @var MapperInterface|DeviceData
     */
    protected $deviceDataMapper;

    /**
     * @var MapperInterface|GroupData
     */
    protected $groupDataMapper;

    public function __construct(MapperInterface $deviceDataMapper, MapperInterface $groupDataMapper)
    {
        $this->deviceDataMapper = $deviceDataMapper;
        $this->groupDataMapper = $groupDataMapper;
    }

    /**
     * Get a collection of devices.
     *
     * @param ServiceInterface $service
     *
     * @return Devices
     */
    abstract public function getDeviceCollection(ServiceInterface $service): Devices;

    /**
     * Get a collection of Groups.
     *
     * @param ServiceInterface $service
     *
     * @return Groups
     */
    abstract public function getGroupCollection(ServiceInterface $service): Groups;
}

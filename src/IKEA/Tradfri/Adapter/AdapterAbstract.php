<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Mapper\DeviceData;
use IKEA\Tradfri\Mapper\GroupData;
use IKEA\Tradfri\Mapper\MapperInterface;
use IKEA\Tradfri\Service\ServiceInterface;

abstract class AdapterAbstract implements AdapterInterface
{
    /**
     * @var DeviceData|MapperInterface
     */
    protected $_deviceDataMapper;

    /**
     * @var GroupData|MapperInterface
     */
    protected $_groupDataMapper;

    public function __construct(
        MapperInterface $deviceDataMapper,
        MapperInterface $groupDataMapper
    ) {
        $this->_deviceDataMapper = $deviceDataMapper;
        $this->_groupDataMapper  = $groupDataMapper;
    }

    abstract public function getDeviceCollection(ServiceInterface $service): Devices;

    abstract public function getGroupCollection(ServiceInterface $service): Groups;

    /**
     * @return DeviceData|MapperInterface
     */
    public function getDeviceDataMapper()
    {
        return $this->_deviceDataMapper;
    }

    /**
     * @return GroupData|MapperInterface
     */
    public function getGroupDataMapper()
    {
        return $this->_groupDataMapper;
    }
}

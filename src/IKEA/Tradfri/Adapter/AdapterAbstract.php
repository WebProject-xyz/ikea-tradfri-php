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
     * @var DeviceData|MapperInterface
     */
    protected $_deviceDataMapper;

    /**
     * @var GroupData|MapperInterface
     */
    protected $_groupDataMapper;

    /**
     * AdapterAbstract constructor.
     */
    public function __construct(
        MapperInterface $deviceDataMapper,
        MapperInterface $groupDataMapper
    ) {
        $this->_deviceDataMapper = $deviceDataMapper;
        $this->_groupDataMapper = $groupDataMapper;
    }

    /**
     * Get a collection of devices.
     */
    abstract public function getDeviceCollection(
        ServiceInterface $service
    ) : Devices;

    /**
     * Get a collection of Groups.
     */
    abstract public function getGroupCollection(
        ServiceInterface $service
    ) : Groups;

    /**
     * Get DeviceDataMapper.
     *
     * @return DeviceData|MapperInterface
     */
    public function getDeviceDataMapper()
    {
        return $this->_deviceDataMapper;
    }

    /**
     * Get GroupDataMapper.
     *
     * @return GroupData|MapperInterface
     */
    public function getGroupDataMapper()
    {
        return $this->_groupDataMapper;
    }
}

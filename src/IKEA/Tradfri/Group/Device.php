<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Group;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Device\DeviceInterface;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class Device.
 */
class Device implements DeviceInterface
{
    /**
     * @var int
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var Api|ServiceInterface
     */
    protected $_service;

    /**
     * @var Devices
     */
    protected $_devices;

    /**
     * @var array
     */
    protected $_deviceIds = [];

    /**
     * @var int
     */
    protected $_brightness;

    /**
     * @var bool
     */
    protected $_state;

    /**
     * Group constructor.
     *
     * @param int              $deviceId
     * @param ServiceInterface $service
     */
    public function __construct(int $deviceId, ServiceInterface $service)
    {
        $this->setId($deviceId);
        $this->setService($service);
    }

    /**
     * Set Service.
     *
     * @param Api|ServiceInterface $service
     *
     * @return Device
     */
    public function setService(ServiceInterface $service): self
    {
        $this->_service = $service;

        return $this;
    }

    /**
     * Get LightIds.
     *
     * @return array
     */
    public function getDeviceIds(): array
    {
        return $this->_deviceIds;
    }

    /**
     * Set device ids.
     *
     * @param array $ids
     *
     * @return $this
     */
    public function setDeviceIds(array $ids)
    {
        $this->_deviceIds = $ids;

        return $this;
    }

    /**
     * Get Devices.
     *
     * @return Devices
     */
    public function getDevices(): Devices
    {
        if (null === $this->_devices) {
            $this->_devices = new Devices();
        }

        return $this->_devices;
    }

    /**
     * Set Devices.
     *
     * @param Devices $devices
     *
     * @return $this
     */
    public function setDevices(Devices $devices): self
    {
        $this->_devices = $devices;

        return $this;
    }

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * Set Id.
     *
     * @param int $deviceId
     *
     * @return Device
     */
    public function setId($deviceId): self
    {
        $this->_id = $deviceId;

        return $this;
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Set Name.
     *
     * @param string $name
     *
     * @return Device
     */
    public function setName(string $name): self
    {
        $this->_name = $name;

        return $this;
    }

    /**
     * Set State of group.
     *
     * @param bool $state
     *
     * @return $this
     */
    public function setState(bool $state)
    {
        $this->_state = $state;

        return $this;
    }

    /**
     * Get Brightness.
     *
     * @return float
     */
    public function getBrightness(): float
    {
        return (float) $this->_brightness;
    }

    /**
     * @param int $level
     *
     * @return $this
     */
    public function setBrightness(int $level)
    {
        $this->_brightness = $level;

        return $this;
    }

    /**
     * Get State.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->_state;
    }
}

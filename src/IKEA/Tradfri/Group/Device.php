<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Group;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class Device.
 */
class Device
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Api|ServiceInterface
     */
    protected $service;

    /**
     * @var Devices
     */
    protected $devices;

    /**
     * @var array
     */
    protected $deviceIds = [];

    /**
     * @var int
     */
    protected $brightness;

    /**
     * @var bool
     */
    protected $state;

    /**
     * Group constructor.
     *
     * @param int              $id
     * @param ServiceInterface $service
     */
    public function __construct(int $id, ServiceInterface $service)
    {
        $this->setId($id);
        $this->setService($service);
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
        $this->deviceIds = $ids;

        return $this;
    }

    /**
     * Get LightIds.
     *
     * @return array
     */
    public function getDeviceIds(): array
    {
        return $this->deviceIds;
    }

    /**
     * Get Devices.
     *
     * @return Devices
     */
    public function getDevices(): Devices
    {
        if ($this->devices === null) {
            $this->devices = new Devices();
        }

        return $this->devices;
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
        $this->devices = $devices;

        return $this;
    }

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set Id.
     *
     * @param int $id
     *
     * @return Device
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
        $this->name = $name;

        return $this;
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
        $this->service = $service;

        return $this;
    }

    /**
     * @param int $level
     *
     * @return $this
     */
    public function setBrightness(int $level)
    {
        $this->brightness = $level;

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
        $this->state = $state;

        return $this;
    }

    /**
     * Get Brightness.
     *
     * @return float
     */
    public function getBrightness(): float
    {
        return (float) $this->brightness;
    }

    /**
     * Get State.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->state;
    }
}

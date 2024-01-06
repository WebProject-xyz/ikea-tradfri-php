<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Group;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Device\DeviceInterface;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @final
 */
class Device implements DeviceInterface
{
    protected int $_id;
    protected string $_name;
    protected Api|ServiceInterface $_service;
    protected Devices $_devices;
    protected array $_deviceIds = [];
    protected int $_brightness  = 0;
    protected bool $_state;

    public function __construct(int $deviceId, ServiceInterface $service)
    {
        $this->setId($deviceId);
        $this->setService($service);
    }

    public function setService(ServiceInterface $service): self
    {
        $this->_service = $service;

        return $this;
    }

    /**
     * @return list<int>
     */
    public function getDeviceIds(): array
    {
        return $this->_deviceIds;
    }

    /**
     * @param list<int> $ids
     */
    public function setDeviceIds(array $ids): self
    {
        $this->_deviceIds = $ids;

        return $this;
    }

    public function getDevices(): Devices
    {
        return $this->_devices ??= new Devices();
    }

    public function setDevices(Devices $devices): self
    {
        $this->_devices = $devices;

        return $this;
    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function setId(int|string $deviceId): self
    {
        $this->_id = (int) $deviceId;

        return $this;
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function setName(string $name): self
    {
        $this->_name = $name;

        return $this;
    }

    public function setState(bool $state): self
    {
        $this->_state = $state;

        return $this;
    }

    /**
     * @phpstan-return float
     */
    public function getBrightness(): float
    {
        return (float) $this->_brightness;
    }

    /**
     * @phpstan-param float|int<0, 100> $level
     */
    public function setBrightness(float|int $level): self
    {
        $this->_brightness = (int) \round($level);

        return $this;
    }

    public function isOn(): bool
    {
        return $this->_state;
    }

    /**
     * @return array<int, list<string>>
     */
    final public function jsonSerialize(): array
    {
        $data = [];
        foreach ($this->getDevices() as $device) {
            $data[$device->getId()] = $device->jsonSerialize();
        }

        return $data;
    }
}

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
use IKEA\Tradfri\Device\BooleanStateInterface;
use IKEA\Tradfri\Device\DeviceInterface;
use IKEA\Tradfri\Service\GatewayApiService;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Traits\ProvidesId;
use IKEA\Tradfri\Traits\ProvidesName;
use IKEA\Tradfri\Traits\ProvidesState;

/**
 * @final
 */
class DeviceGroup implements \JsonSerializable, BooleanStateInterface, DeviceInterface
{
    use ProvidesId;
    use ProvidesName;
    use ProvidesState;
    protected GatewayApiService|ServiceInterface $_service;
    protected Devices $_devices;
    protected array $_deviceIds = [];

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

    public function getType(): string
    {
        return 'GROUP: ' . $this->getName();
    }
}

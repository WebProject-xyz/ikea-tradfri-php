<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Group;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\LightBulbs;
use IKEA\Tradfri\Device\Feature\BooleanStateInterface;
use IKEA\Tradfri\Device\Feature\BrightnessStateInterface;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\Feature\SwitchableInterface;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Traits\ProvidesBrightness;
use IKEA\Tradfri\Traits\ProvidesId;
use IKEA\Tradfri\Traits\ProvidesName;
use IKEA\Tradfri\Traits\ProvidesState;
use IKEA\Tradfri\Values\DeviceType;

/**
 * @final
 */
class DeviceGroup implements \JsonSerializable, BooleanStateInterface, BrightnessStateInterface, DeviceInterface, SwitchableInterface
{
    use ProvidesId;
    use ProvidesName;
    use ProvidesState;
    use ProvidesBrightness;
    protected Devices $devices;
    protected array $deviceIds = [];

    public function __construct(
        int $deviceId,
        private ServiceInterface $service,
    ) {
        $this->setId($deviceId);
    }

    public function setService(ServiceInterface $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return list<int>
     */
    public function getDeviceIds(): array
    {
        return $this->deviceIds;
    }

    /**
     * @param list<int> $ids
     */
    public function setDeviceIds(array $ids): self
    {
        $this->deviceIds = $ids;

        return $this;
    }

    public function getDevices(): Devices
    {
        return $this->devices ??= new Devices();
    }

    public function setDevices(Devices $devices): self
    {
        $this->devices = $devices;

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

    public function isOn(): bool
    {
        if (false === $this->getLights()->isEmpty()) {
            return $this->getLights()->getActive()->count() > 0;
        }

        return false;
    }

    public function isOff(): bool
    {
        if (false === $this->getLights()->isEmpty()) {
            return 0 === $this->getLights()->getActive()->count();
        }

        return false;
    }

    public function getLights(): LightBulbs
    {
        return $this->getDevices()->filterLightBulbs();
    }

    public function switchOn(): bool
    {
        if ($this->service->on($this)) {
            $this->setState(true);

            return true;
        }

        return false;
    }

    public function off(): self
    {
        if ($this->service->off($this)) {
            $this->setState(false);
        }

        return $this;
    }

    /**
     * @phpstan-param int<0, 100> $levelInPercent
     */
    public function dim(int $levelInPercent): bool
    {
        if ($this->service->dim($this, $levelInPercent)) {
            $this->setBrightnessLevel((float) $levelInPercent);

            return true;
        }

        return false;
    }

    public function switchOff(): bool
    {
        if ($this->service->off($this)) {
            $this->setState(false);

            return true;
        }

        return false;
    }

    public function getTypeEnum(): DeviceType
    {
        throw new \LogicException('not implemented for groups');
    }
}

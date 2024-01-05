<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Exception\RuntimeException;
use JsonSerializable;

abstract class AbstractCollection extends ArrayCollection implements JsonSerializable
{
    public function addDevice(Device $newItem): self
    {
        $this->set($newItem->getId(), $newItem);

        return $this;
    }

    /**
     * @throws RuntimeException
     * @throws RuntimeException
     */
    public function find(Closure $closure): ?Device
    {
        foreach ($this->toArray() as $item) {
            if (true === $closure($item)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string>>
     */
    public function jsonSerialize(): array
    {
        $data = [];
        foreach ($this->toArray() as $device) {
            $data[$device->getId()] = $device->jsonSerialize();
        }

        return $data;
    }
}

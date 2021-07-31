<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Exception\RuntimeException;
use JsonSerializable;

/**
 * Class Devices.
 */
abstract class AbstractCollection extends ArrayCollection implements JsonSerializable
{
    /**
     * Add item to collection.
     *
     * @return $this
     */
    public function addDevice(Device $newItem): self
    {
        $this->set($newItem->getId(), $newItem);

        return $this;
    }

    /**
     * Find item by closure.
     *
     * @throws RuntimeException
     * @throws \IKEA\Tradfri\Exception\RuntimeException
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
     * Specify data which should be serialized to JSON.
     *
     * @see  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->toArray() as $device) {
            $data[$device->getId()] = $device->jsonSerialize();
        }

        return $data;
    }
}

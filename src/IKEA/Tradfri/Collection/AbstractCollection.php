<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Exception\RuntimeException;
use JsonSerializable;

/**
 * Class Devices.
 */
abstract class AbstractCollection extends ArrayCollection
    implements JsonSerializable
{
    /**
     * Add item to collection.
     *
     * @param Device $newItem
     *
     * @return $this
     */
    public function addDevice(Device $newItem)
    {
        $this->set($newItem->getId(), $newItem);

        return $this;
    }

    /**
     * Find item by closure.
     *
     * @param $closure
     *
     * @throws RuntimeException
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Device|null|void
     */
    public function find($closure)
    {
        foreach ($this->toArray() as $item) {
            if (\is_callable($closure)) {
                if ($closure($item) === true) {
                    return $item;
                }
            } else {
                throw new RuntimeException('closure function not working');
            }
        }

        return;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->toArray() as $device) {
            /* @var Device $device */
            $data[$device->getId()] = $device->jsonSerialize();
        }

        return $data;
    }
}

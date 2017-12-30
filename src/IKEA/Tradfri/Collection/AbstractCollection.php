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
abstract class AbstractCollection extends ArrayCollection implements JsonSerializable
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
     * @return mixed
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

        return null;
    }
}

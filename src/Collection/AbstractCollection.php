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

namespace IKEA\Tradfri\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Feature\DeviceInterface;

/**
 * @template TDevice of DeviceInterface&\JsonSerializable
 *
 * @extends ArrayCollection<int, TDevice>
 */
abstract class AbstractCollection extends ArrayCollection implements \JsonSerializable
{
    final public function addDevice(Device $newItem): self
    {
        $this->set($newItem->getId(), $newItem);

        return $this;
    }

    /**
     * @return array<int, list<string>>
     */
    final public function jsonSerialize(): array
    {
        $data = [];
        foreach ($this->toArray() as $device) {
            $data[$device->getId()] = $device->jsonSerialize();
        }

        return $data;
    }
}

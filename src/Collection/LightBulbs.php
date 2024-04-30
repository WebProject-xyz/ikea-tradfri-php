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

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\DeviceInterface;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\SwitchableInterface;


/**
 * @method self createFrom(array $elements)
 *
 * @extends Devices<DeviceInterface&SwitchableInterface&\JsonSerializable>
 */
final class LightBulbs extends Devices
{
    public function sortByState(): self
    {
        $items = $this->toArray();

        \usort(
            $items,
            /** @phpstan-ignore-next-line */
            static fn (LightBulb $lightBulbOne, LightBulb $lightBulbTwo) => \strcmp(
                $lightBulbOne->getReadableState(),
                $lightBulbTwo->getReadableState(),
            ),
        );

        return $this->createFrom($items);
    }

    public function getActive(): self
    {
        $newItems = [];
        foreach ($this->toArray() as $key => $light) {
            if (!$light instanceof LightBulb) {
                continue;
            }

            if ($light->isOn()) {
                $newItems[$key] = $light;
            }
        }

        return $this->createFrom($newItems);
    }

    public function sortByName(): self
    {
        return $this->createFrom($this->namesAsKeys());
    }

    protected function namesAsKeys(): array
    {
        $elements = [];
        $this->forAll(
            static function ($deviceId, DeviceInterface $device) use (&$elements): bool {
                $elements[$device->getName() . '_' . $deviceId] = $device;

                return true;
            },
        );
        \ksort($elements, \SORT_NATURAL);

        return $elements;
    }
}

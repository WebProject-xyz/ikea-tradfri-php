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

use IKEA\Tradfri\Device\Feature\BooleanStateInterface;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\Feature\SwitchableInterface;

/**
 * @extends Devices<DeviceInterface&SwitchableInterface>
 */
final class LightBulbs extends Devices
{
    public function sortByState(): self
    {
        $items = $this->toArray();

        \usort(
            $items,
            static fn (BooleanStateInterface $lightBulbOne, BooleanStateInterface $lightBulbTwo) => \strcmp(
                $lightBulbOne->getReadableState(),
                $lightBulbTwo->getReadableState(),
            ),
        );

        return $this->createFrom($items);
    }

    public function getActive(): self
    {
        $newItems = \array_filter($this->toArray(), static function (\IKEA\Tradfri\Device\Feature\SwitchableInterface $light) {
            return $light->isOn();
        });

        return $this->createFrom($newItems);
    }

    public function sortByName(): self
    {
        return $this->createFrom($this->namesAsKeys());
    }

    /**
     * @phpstan-return array<non-empty-string|DeviceInterface>
     */
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

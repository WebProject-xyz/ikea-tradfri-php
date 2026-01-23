<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\Feature\SwitchableInterface;

/**
 * @extends Devices<SwitchableInterface>
 */
final class LightBulbs extends Devices
{
    public function sortByState(): self
    {
        [$on, $off] = $this
            ->partition(
                p: static fn (int|string $idOrName, SwitchableInterface $lightBulbOne): bool => $lightBulbOne->isOn(),
            );

        return new self($on->toArray() + $off->toArray());
    }

    public function getActive(): self
    {
        $newItems = \array_filter($this->toArray(), static fn (SwitchableInterface $light): bool => $light->isOn());

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

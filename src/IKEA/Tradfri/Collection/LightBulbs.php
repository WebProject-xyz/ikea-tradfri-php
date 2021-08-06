<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use const SORT_NATURAL;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\LightBulb;

/**
 * Class LightBulbs.
 */
class LightBulbs extends Devices
{
    /**
     * Get first light.
     */
    public function first(): ?LightBulb
    {
        return parent::first();
    }

    /**
     * Sort by blub state.
     *
     * @return $this
     */
    public function sortByState(): self
    {
        $items = $this->toArray();

        usort(
            $items,
            function (LightBulb $lightBulbOne, LightBulb $lightBulbTwo) {
                return strcmp(
                    $lightBulbOne->getReadableState(),
                    $lightBulbTwo->getReadableState()
                );
            }
        );

        return $this->createFrom($items);
    }

    /**
     * Get active lights.
     *
     * @return static
     */
    public function getActive(): self
    {
        $newItems = [];
        foreach ($this->toArray() as $key => $light) {
            /** @var LightBulb $light */
            if ($light->isOn()) {
                $newItems[$key] = $light;
            }
        }

        return $this->createFrom($newItems);
    }

    /**
     * Sort items by name.
     *
     * @return static
     */
    public function sortByName()
    {
        return $this->createFrom($this->namesAsKeys());
    }

    /**
     * Get an array with names as keys.
     */
    protected function namesAsKeys(): array
    {
        $elements = [];
        $this->forAll(
            function ($deviceId, Device $device) use (&$elements) {
                $elements[$device->getName() . '_' . $deviceId] = $device;

                return true;
            }
        );
        ksort($elements, SORT_NATURAL);

        return $elements;
    }
}

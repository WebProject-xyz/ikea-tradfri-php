<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Lightbulb;

/**
 * Class Lightbulbs.
 */
class Lightbulbs extends Devices
{
    /**
     * Get first light.
     *
     * @return null|Lightbulb
     */
    public function first()
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

        \usort(
            $items,
            function (Lightbulb $lightbulbOne, Lightbulb $lightbulbTwo) {
                return \strcmp(
                    $lightbulbOne->getState(),
                    $lightbulbTwo->getState()
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
            /** @var Lightbulb $light */
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
     *
     * @return array
     */
    protected function namesAsKeys(): array
    {
        $elements = [];
        $this->forAll(function ($key, Device $device) use (&$elements) {
            $elements[$device->getName()] = $device;

            return true;
        });
        \ksort($elements, \SORT_NATURAL);

        return $elements;
    }
}

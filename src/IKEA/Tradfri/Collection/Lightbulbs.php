<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Lightbulb;

/**
 * Class Lightbulbs.
 */
class Lightbulbs extends Devices
{
    /**
     * Get first light.
     *
     * @return Lightbulb|null
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
}

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
    public function sortByState()
    {
        $items = $this->toArray();

        usort($items, function (Lightbulb $a, Lightbulb $b) {
            return strcmp($a->getState(), $b->getState());
        });

        return $this->createFrom($items);
    }

    /**
     * Get active lights.
     *
     * @return static
     */
    public function getActive()
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
        // @TODO: Implement jsonSerialize() method.
    }
}

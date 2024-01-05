<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use const SORT_NATURAL;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\LightBulb;

/**
 * @method self createFrom(array $elements)
 */
class LightBulbs extends Devices
{
    public function first(): ?LightBulb
    {
        return parent::first();
    }

    public function sortByState(): self
    {
        $items = $this->toArray();

        usort(
            $items,
            static fn (LightBulb $lightBulbOne, LightBulb $lightBulbTwo) => strcmp(
                $lightBulbOne->getReadableState(),
                $lightBulbTwo->getReadableState()
            )
        );

        return $this->createFrom($items);
    }

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

    public function sortByName(): self
    {
        return $this->createFrom($this->namesAsKeys());
    }

    protected function namesAsKeys(): array
    {
        $elements = [];
        $this->forAll(
            static function ($deviceId, Device $device) use (&$elements) {
                $elements[$device->getName() . '_' . $deviceId] = $device;

                return true;
            }
        );
        ksort($elements, SORT_NATURAL);

        return $elements;
    }
}

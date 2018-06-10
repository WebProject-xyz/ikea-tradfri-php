<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;

/**
 * Class Devices.
 */
class Devices extends AbstractCollection
{
    /**
     * Get lightbulbs.
     *
     * @return Lightbulb[]|Lightbulbs
     */
    public function getLightbulbs(): Lightbulbs
    {
        $lightbulbs = new Lightbulbs();
        foreach ($this->getDevices() as $device) {
            if ($device->isLightbulb()) {
                $lightbulbs->addDevice($device);
            }
        }

        return $lightbulbs->sortByName();
    }

    /**
     * Get items.
     *
     * @return array|Dimmer[]|Lightbulb[]|MotionSensor[]|Remote[]
     */
    public function getDevices(): array
    {
        return $this->toArray();
    }
}

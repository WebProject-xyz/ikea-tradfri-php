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
     * @return Lightbulbs|Lightbulb[]
     */
    public function getLightbulbs(): Lightbulbs
    {
        $lightbulbs = new Lightbulbs();
        foreach ($this->getDevices() as $device) {
            if ($device->isLightbulb()) {
                $lightbulbs->addDevice($device);
            }
        }

        $lightbulbs = $lightbulbs->sortByName();

        return $lightbulbs;
    }

    /**
     * Get items.
     *
     * @return Dimmer[]|MotionSensor[]|Lightbulb[]|Remote[]|array
     */
    public function getDevices(): array
    {
        return $this->toArray();
    }
}

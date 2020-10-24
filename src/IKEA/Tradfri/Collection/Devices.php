<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;

/**
 * Class Devices.
 */
class Devices extends AbstractCollection
{
    /**
     * Get lightBulbs.
     *
     * @return LightBulb[]|Lightbulbs
     */
    public function getLightbulbs(): Lightbulbs
    {
        $lightBulbs = new Lightbulbs();
        $typeHelper = new Type();
        foreach ($this->getDevices() as $device) {
            if ($typeHelper->isLightbulb($device->getType())) {
                $lightBulbs->addDevice($device);
            }
        }

        return $lightBulbs->sortByName();
    }

    /**
     * Get items.
     *
     * @return array|Dimmer[]|LightBulb[]|MotionSensor[]|Remote[]
     */
    public function getDevices(): array
    {
        return $this->toArray();
    }
}

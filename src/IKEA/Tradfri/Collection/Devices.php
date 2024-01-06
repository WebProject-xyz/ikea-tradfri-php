<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Floalt;
use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\OpenCloseRemote;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\Repeater;
use IKEA\Tradfri\Device\RollerBlind;

/**
 * @extends AbstractCollection<string, Device>
 */
class Devices extends AbstractCollection
{
    /**
     * @return LightBulb[]|LightBulbs
     */
    public function getLightBulbs(): LightBulbs
    {
        $lightBulbs = new LightBulbs();
        $typeHelper = new Type();
        foreach ($this->getDevices() as $device) {
            if ($typeHelper->isLightBulb($device->getType())) {
                $lightBulbs->addDevice($device);
            }
            if ($typeHelper->isFloalt($device->getType())) {
                $lightBulbs->addDevice($device);
            }
        }

        return $lightBulbs->sortByName();
    }

    /**
     * @return array<Dimmer|LightBulb|MotionSensor|Remote|Floalt|RollerBlind|Repeater|OpenCloseRemote>
     */
    public function getDevices(): array
    {
        return $this->toArray();
    }
}

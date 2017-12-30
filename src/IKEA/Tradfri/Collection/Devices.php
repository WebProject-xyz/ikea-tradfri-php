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
     * @return Lightbulbs
     */
    public function getLightbulbs(): Lightbulbs
    {
        $lamps = new Lightbulbs();
        foreach ($this->getDevices() as $device) {
            if ($device->isLightbulb()) {
                $lamps->addDevice($device);
            }
        }

        return $lamps;
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

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // @TODO: Implement jsonSerialize() method.
    }
}

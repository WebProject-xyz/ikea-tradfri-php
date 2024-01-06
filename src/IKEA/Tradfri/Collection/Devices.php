<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\DeviceInterface;
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
 * @final
 *
 * @template TDevice of DeviceInterface
 *
 * @extends AbstractCollection<Dimmer|Floalt|LightBulb|MotionSensor|OpenCloseRemote|Remote|Repeater|RollerBlind|Device|TDevice>
 */
class Devices extends AbstractCollection
{
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
     * @return list<Device|Dimmer|Floalt|LightBulb|MotionSensor|OpenCloseRemote|Remote|Repeater|RollerBlind|TDevice>
     */
    public function getDevices(): array
    {
        return $this->toArray();
    }
}

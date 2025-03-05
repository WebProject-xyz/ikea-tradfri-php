<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\Feature\SwitchableInterface;
use IKEA\Tradfri\Values\DeviceType;

/**
 * @final
 *
 * @template TDevice of DeviceInterface&\JsonSerializable
 *
 * @extends AbstractCollection<TDevice>
 */
class Devices extends AbstractCollection
{
    public function filterLightBulbs(): LightBulbs
    {
        $lightBulbs = new LightBulbs();
        foreach ($this->toArray() as $device) {
            if (!$device instanceof SwitchableInterface) {
                continue;
            }

            if ($device->getTypeEnum() === DeviceType::BLUB) {
                $lightBulbs->addDevice($device);
            }

            if ($device->getTypeEnum() === DeviceType::FLOALT) {
                $lightBulbs->addDevice($device);
            }
        }

        return $lightBulbs->sortByName();
    }

    /**
     * @phpstan-return array<TDevice>
     */
    public function getDevices(): array
    {
        return $this->toArray();
    }
}

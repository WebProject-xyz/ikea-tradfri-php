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

namespace IKEA\Tradfri\Service;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\LightBulbs;
use IKEA\Tradfri\Device\Feature\BrightnessStateInterface;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\Feature\SwitchableInterface;
use IKEA\Tradfri\Exception\RuntimeException;

interface ServiceInterface
{
    public function getLights(): LightBulbs;

    public function getDevices(): Devices;

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function allLightsOff(LightBulbs $lightBulbsCollection): bool;

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function on(DeviceInterface&SwitchableInterface $device): bool;

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function off(DeviceInterface&SwitchableInterface $device): bool;

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function dim(BrightnessStateInterface&DeviceInterface $device, int $level): bool;

    /**
     * @throws RuntimeException
     */
    public function setRollerBlindPosition(DeviceInterface $device, int $level): bool;
}

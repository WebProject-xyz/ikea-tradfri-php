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

namespace IKEA\Tradfri\Client;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Group\Light as Group;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @psalm-type LevelType = int<0,100>
 */
interface ClientInterface
{
    public function getDevices(ServiceInterface $service): Devices;

    public function getGroups(ServiceInterface $service): Groups;

    public function lightOn(LightBulb $lightBulb): bool;

    public function lightOff(LightBulb $lightBulb): bool;

    public function groupOn(Group $group): bool;

    public function groupOff(Group $group): bool;

    /**
     * @psalm-param LevelType $level
     */
    public function dimLight(LightBulb $lightBulb, int $level): bool;

    /**
     * @psalm-param LevelType $level
     */
    public function dimGroup(Group $group, int $level): bool;

    /**
     * @psalm-param LevelType $level
     */
    public function setRollerBlindPosition(RollerBlind $blind, int $level): bool;
}

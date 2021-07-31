<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

interface SwitchableInterface
{
    public function switchOn() : bool;

    public function switchOff() : bool;
}

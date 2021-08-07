<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Command\Coap\Keys;

class Dimmer extends Device
{
    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct(int $deviceId)
    {
        parent::__construct($deviceId, Keys::ATTR_DEVICE_INFO_TYPE_DIMMER);
    }
}

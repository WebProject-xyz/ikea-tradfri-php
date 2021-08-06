<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

class OpenCloseRemote extends Device
{
    public function __construct(int $deviceId)
    {
        parent::__construct($deviceId, \IKEA\Tradfri\Command\Coap\Keys::ATTR_DEVICE_INFO_TYPE_OPEN_CLOSE_REMOTE);
    }
}

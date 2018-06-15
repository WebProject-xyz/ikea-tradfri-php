<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Command\Coap\Keys;

/**
 * Class Remote.
 */
class Remote extends Device
{
    /**
     * Remote constructor.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($deviceId)
    {
        parent::__construct(
            $deviceId,
            Keys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL
        );
    }
}

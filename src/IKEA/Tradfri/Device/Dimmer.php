<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

/**
 * Class Dimmer.
 */
class Dimmer extends Device
{
    /**
     * Dimmer constructor.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($deviceId)
    {
        parent::__construct($deviceId, self::TYPE_DIMMER);
    }
}

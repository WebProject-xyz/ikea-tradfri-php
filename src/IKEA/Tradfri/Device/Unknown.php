<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

/**
 * Class Unknown.
 */
class Unknown extends Device
{
    /**
     * Unknown device constructor.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($deviceId)
    {
        parent::__construct(
            $deviceId,
            self::TYPE_UNKNOWN
        );
    }
}

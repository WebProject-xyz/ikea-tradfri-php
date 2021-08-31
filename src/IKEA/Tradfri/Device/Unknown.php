<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

class Unknown extends Device
{
    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct(int $deviceId, string $type)
    {
        parent::__construct(
            $deviceId,
            'TRADFRI unknown' . $type
        );
    }
}

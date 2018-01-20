<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

/**
 * Class MotionSensor.
 */
class MotionSensor extends Device
{
    /**
     * MotionSensor constructor.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($deviceId)
    {
        parent::__construct($deviceId, self::TYPE_MOTION_SENSOR);
    }
}

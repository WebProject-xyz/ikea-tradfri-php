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
     * @param int $id
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($id)
    {
        parent::__construct($id, self::TYPE_MOTION_SENSOR);
    }
}

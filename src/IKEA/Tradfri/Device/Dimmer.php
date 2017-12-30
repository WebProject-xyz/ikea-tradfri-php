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
     * @param int $id
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($id)
    {
        parent::__construct($id, self::TYPE_DIMMER);
    }
}

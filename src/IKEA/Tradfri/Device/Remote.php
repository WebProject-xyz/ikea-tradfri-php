<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

/**
 * Class Remote.
 */
class Remote extends Device
{
    /**
     * Remote constructor.
     *
     * @param int $id
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($id)
    {
        parent::__construct($id, self::TYPE_REMOTE_CONTROL);
    }
}

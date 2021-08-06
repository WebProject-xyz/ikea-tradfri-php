<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Traits\ProvidesDarkenedState;

/**
 * Class Remote.
 */
class RollerBlind extends Device
{
    use ProvidesDarkenedState;

    /**
     * Close Roller Blind.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function close(): bool
    {
        return $this->setToPosition(100);
    }

    /**
     * Open Roller Blind.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function open(): bool
    {
        return $this->setToPosition(0);
    }

    /**
     * Set Roller Blind to specific position.
     *
     * @param $level
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function setToPosition($level): bool
    {
        return $this->getService()->setRollerBlindPosition($this, $level);
    }
}

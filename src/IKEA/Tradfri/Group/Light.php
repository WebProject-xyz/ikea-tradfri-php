<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Group;

use IKEA\Tradfri\Collection\Lightbulbs;
use IKEA\Tradfri\Device\SwitchableInterface;

/**
 * Class Light.
 */
class Light extends Device implements SwitchableInterface
{
    /**
     * Get State.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        if (false === $this->getLights()->isEmpty()) {
            return $this->getLights()->getActive()->count() > 0;
        }

        return false;
    }

    /**
     * Get Lights.
     *
     * @return Lightbulbs
     */
    public function getLights(): Lightbulbs
    {
        return $this->getDevices()->getLightbulbs();
    }

    /**
     * Switch group on.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return $this
     */
    public function switchOn(): bool
    {
        if ($this->_service->on($this)) {
            $this->setState(true);

            return true;
        }

        return false;
    }

    /**
     * Switch group off.
     *
     * @return $this
     */
    public function off(): self
    {
        if ($this->_service->off($this)) {
            $this->setState(false);
        }

        return $this;
    }

    /**
     * Dim group to level.
     *
     * @param int $level
     *
     * @return $this
     */
    public function dim(int $level): self
    {
        if ($this->_service->dim($this, $level)) {
            $this->setBrightness($level);
        }

        return $this;
    }

    public function switchOff(): bool
    {
        if ($this->_service->off($this)) {
            $this->setState(false);

            return true;
        }

        return false;
    }
}

<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Group;

use IKEA\Tradfri\Collection\Lightbulbs;

/**
 * Class Light.
 */
class Light extends Device
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
    public function switchOn(): self
    {
        if ($this->_service->switchOn($this)) {
            $this->setState(true);
        }

        return $this;
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
}

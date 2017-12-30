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
     * Get Lights.
     *
     * @return Lightbulbs
     */
    public function getLights(): Lightbulbs
    {
        return $this->getDevices()->getLightbulbs();
    }

    /**
     * Get State.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        if ($this->getLights()->isEmpty() === false) {
            return $this->getLights()->getActive()->count() > 0;
        }

        return false;
    }

    /**
     * Switch group on.
     *
     * @return $this
     */
    public function on(): self
    {
        if ($this->service->on($this)) {
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
        if ($this->service->off($this)) {
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
        if ($this->service->dim($this, $level)) {
            $this->setBrightness($level);
        }

        return $this;
    }
}

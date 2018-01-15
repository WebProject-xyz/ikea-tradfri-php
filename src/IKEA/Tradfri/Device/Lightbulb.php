<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

/**
 * Class Lamp.
 */
class Lightbulb extends Device
{
    /**
     * @var int
     */
    protected $brightness;

    /**
     * @var bool
     */
    protected $state = false;

    /**
     * Get Brightness.
     *
     * @return float
     */
    public function getBrightness(): float
    {
        return (float)$this->brightness;
    }

    /**
     * Set Brightness.
     *
     * @param int $brightness
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Lightbulb
     */
    public function setBrightness(int $brightness): self
    {
        if ($brightness < 0) {
            $this->brightness = 0;
        } else {
            $this->brightness = round($brightness / 2.54);
        }

        return $this;
    }

    /**
     * Switch off.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function off(): bool
    {
        if ($this->getService()->off($this) === true) {
            $this->setState(false);

            return true;
        }

        return false;
    }

    /**
     * Switch off.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function on(): bool
    {
        if ($this->getService()->switchOn($this) === true) {
            $this->setState(true);

            return true;
        }

        return false;
    }

    /**
     * Get state as string.
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->isOn() ? 'On' : 'Off';
    }

    /**
     * Set State.
     *
     * @param bool $state
     *
     * @return Lightbulb
     */
    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get State.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->state;
    }

    /**
     * Dim Light.
     *
     * @param int $level
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function dim(int $level)
    {
        $this->getService()->dim($this, $level);
    }
}

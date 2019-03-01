<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Device\Feature\Switchable;
use IKEA\Tradfri\Exception\RuntimeException;

/**
 * Class Lamp.
 */
class Lightbulb extends Device implements Switchable
{
    /**
     * @var float
     */
    protected $_brightness;

    /**
     * @var string
     */
    protected $_color = '';

    /**
     * @var bool
     */
    protected $_state = false;

    /**
     * Get Brightness.
     *
     * @return float
     */
    public function getBrightness(): float
    {
        return (float) $this->_brightness;
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
            $this->_brightness = 0;
        } else {
            $this->_brightness = \round($brightness / 2.54);
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
    public function switchOff(): bool
    {
        if (true === $this->getService()->off($this)) {
            $this->setState(false);

            return true;
        }

        throw new RuntimeException('switch OFF failed');
    }

    /**
     * Switch off.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function switchOn(): bool
    {
        if (true === $this->getService()->switchOn($this)) {
            $this->setState(true);

            return true;
        }

        throw new RuntimeException('switch ON failed');
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
     * @return static
     */
    public function setState(bool $state)
    {
        $this->_state = $state;

        return $this;
    }

    /**
     * Get State.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->_state;
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

    /**
     * Get Color.
     *
     * @return string
     */
    public function getColor(): string
    {
        return \strtoupper($this->_color);
    }

    /**
     * Set Color.
     *
     * @param string $color
     *
     * @return Lightbulb
     */
    public function setColor(string $color): self
    {
        $this->_color = $color;

        return $this;
    }
}

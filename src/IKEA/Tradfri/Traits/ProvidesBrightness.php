<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesBrightness.
 */
trait ProvidesBrightness
{
    /**
     * @var float
     */
    protected $_brightness;

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
     * @return static
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
}

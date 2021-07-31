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
     */
    public function getBrightness(): float
    {
        return (float) $this->_brightness;
    }

    /**
     * Set Brightness.
     *
     * @return static
     */
    public function setBrightness(int $brightness): self
    {
        if ($brightness < 0) {
            $brightness = 1;
        }

        $this->_brightness = \round($brightness / 2.54);

        return $this;
    }
}

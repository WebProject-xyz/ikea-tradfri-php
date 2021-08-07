<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesBrightness
{
    protected float $brightness = 0;

    public function getBrightness(): float
    {
        return $this->brightness;
    }

    public function setBrightness(int $brightness): self
    {
        if ($brightness < 0) {
            $brightness = 1;
        }

        $this->brightness = round($brightness / 2.54);

        return $this;
    }
}

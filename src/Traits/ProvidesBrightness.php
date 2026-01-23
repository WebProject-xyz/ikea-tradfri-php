<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Traits;

trait ProvidesBrightness
{
    protected float $brightness = 0;

    public function setBrightnessLevel(float|int $levelInPercent): void
    {
        $this->brightness = (int) \round($levelInPercent);
    }

    public function getBrightness(): float
    {
        return $this->brightness;
    }

    public function setBrightness(int $brightness): void
    {
        if (0 > $brightness) {
            $brightness = 1;
        }

        $this->brightness = \round($brightness / 2.54);
    }
}

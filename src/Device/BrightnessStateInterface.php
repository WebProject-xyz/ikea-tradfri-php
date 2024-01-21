<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Exception\RuntimeException;

interface BrightnessStateInterface
{
    /**
     * @throws RuntimeException
     */
    public function dim(int $levelInPercent): bool;

    public function setBrightnessLevel(float|int $levelInPercent): void;

    public function setBrightness(int $brightness): void;

    public function getBrightness(): float;
}

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

namespace IKEA\Tradfri\Device\Feature;

interface BooleanStateInterface extends DeviceInterface
{
    public function getReadableState(): string;

    public function setState(bool $state): static;

    public function isOn(): bool;

    public function isOff(): bool;
}

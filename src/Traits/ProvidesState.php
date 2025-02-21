<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Traits;

trait ProvidesState
{
    protected bool $state = false;

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getReadableState(): string
    {
        return $this->isOn() ? 'On' : 'Off';
    }

    public function isOn(): bool
    {
        return true === $this->state;
    }

    public function isOff(): bool
    {
        return false === $this->state;
    }
}

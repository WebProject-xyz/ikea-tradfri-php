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

trait ProvidesDarkenedState
{
    protected int $darkenedState = 0;

    public function setDarkenedState(int $state): self
    {
        $this->darkenedState = \max(0, $state);

        return $this;
    }

    public function getDarkenedState(): int
    {
        return $this->darkenedState;
    }

    public function isFullyOpened(): bool
    {
        return 0 === $this->darkenedState;
    }

    public function isFullyClosed(): bool
    {
        return 100 === $this->darkenedState;
    }
}

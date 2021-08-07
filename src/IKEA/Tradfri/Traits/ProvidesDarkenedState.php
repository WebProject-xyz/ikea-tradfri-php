<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesDarkenedState
{
    protected int $darkenedState = 0;

    public function setDarkenedState(int $state): self
    {
        $this->darkenedState = $state;

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

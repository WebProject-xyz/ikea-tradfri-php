<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesState.
 */
trait ProvidesDarkenedState
{
    protected int $darkenedState = 0;

    /**
     * Set State.
     *
     * @return static
     */
    public function setDarkenedState(int $state): self
    {
        $this->darkenedState = $state;

        return $this;
    }

    /**
     * Get state as string.
     */
    public function getDarkenedState(): int
    {
        return $this->darkenedState;
    }

    /**
     * Is fully open.
     */
    public function isFullyOpened(): bool
    {
        return 0 === $this->darkenedState;
    }

    /**
     * Is fully closed.
     */
    public function isFullyClosed(): bool
    {
        return 100 === $this->darkenedState;
    }
}

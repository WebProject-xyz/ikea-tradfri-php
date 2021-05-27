<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesState.
 */
trait ProvidesDarkenedState
{
    /**
     * @var int
     */
    protected int $_darkenedState = 0;

    /**
     * Set State.
     *
     * @param int $state
     *
     * @return static
     */
    public function setDarkenedState(int $state): self
    {
        $this->_darkenedState = $state;

        return $this;
    }

    /**
     * Get state as string.
     *
     * @return int
     */
    public function getDarkenedState(): int
    {
        return $this->_darkenedState;
    }

    /**
     * Is fully open.
     *
     * @return bool
     */
    public function isFullyOpened(): bool
    {
        return 0 === $this->_darkenedState;
    }

    /**
     * Is fully closed.
     *
     * @return bool
     */
    public function isFullyClosed(): bool
    {
        return 100 === $this->_darkenedState;
    }
}

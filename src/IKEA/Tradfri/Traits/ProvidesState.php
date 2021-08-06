<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesState.
 */
trait ProvidesState
{
    /**
     * @var bool
     */
    protected $_state = false;

    /**
     * Set State.
     *
     * @return static
     */
    public function setState(bool $state)
    {
        $this->_state = $state;

        return $this;
    }

    /**
     * Get state as string.
     */
    public function getReadableState(): string
    {
        return $this->isOn() ? 'On' : 'Off';
    }

    /**
     * Is on.
     */
    public function isOn(): bool
    {
        return true === $this->_state;
    }

    /**
     * Is off.
     */
    public function isOff(): bool
    {
        return false === $this->_state;
    }
}

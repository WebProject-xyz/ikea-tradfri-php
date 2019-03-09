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
     * @param bool $state
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
     *
     * @return string
     */
    public function getReadableState(): string
    {
        return $this->isOn() ? 'On' : 'Off';
    }

    /**
     * Is on.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        return true === $this->_state;
    }

    /**
     * Is off.
     *
     * @return bool
     */
    public function isOff(): bool
    {
        return false === $this->_state;
    }
}

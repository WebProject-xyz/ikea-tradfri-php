<?php

namespace IKEA\Tradfri\Device\Feature;

/**
 * Interface Switchable
 */
interface Switchable
{
    /**
     * Get state as string.
     *
     * @return string
     */
    public function getState(): string;

    /**
     * Set State.
     *
     * @param bool $state
     *
     * @return static
     */
    public function setState(bool $state);
}

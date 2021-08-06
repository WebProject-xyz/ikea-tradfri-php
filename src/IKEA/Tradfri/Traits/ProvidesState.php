<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesState
{
    protected bool $_state = false;

    public function setState(bool $state): self
    {
        $this->_state = $state;

        return $this;
    }

    public function getReadableState(): string
    {
        return $this->isOn() ? 'On' : 'Off';
    }

    public function isOn(): bool
    {
        return true === $this->_state;
    }

    public function isOff(): bool
    {
        return false === $this->_state;
    }
}

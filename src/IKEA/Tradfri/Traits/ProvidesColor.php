<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesColor
{
    protected string $_color = '';

    public function getColor(): string
    {
        return strtoupper($this->_color);
    }

    public function setColor(string $color): self
    {
        $this->_color = $color;

        return $this;
    }
}

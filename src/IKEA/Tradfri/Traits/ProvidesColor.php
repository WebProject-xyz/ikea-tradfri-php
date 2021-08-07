<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesColor
{
    protected string $color = '';

    public function getColor(): string
    {
        return strtoupper($this->color);
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}

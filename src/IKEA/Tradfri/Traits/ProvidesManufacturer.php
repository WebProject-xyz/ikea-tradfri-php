<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesManufacturer
{
    protected string $manufacturer;

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}

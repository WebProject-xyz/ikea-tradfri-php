<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesManufacturer
{
    protected string $_manufacturer;

    public function getManufacturer(): string
    {
        return $this->_manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->_manufacturer = $manufacturer;

        return $this;
    }
}

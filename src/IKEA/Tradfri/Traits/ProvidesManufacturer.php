<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesManufacturer.
 */
trait ProvidesManufacturer
{
    /**
     * @var string
     */
    protected $_manufacturer;

    /**
     * Get Manufacturer.
     */
    public function getManufacturer() : string
    {
        return $this->_manufacturer;
    }

    /**
     * Set Manufacturer.
     *
     * @return static
     */
    public function setManufacturer(string $manufacturer)
    {
        $this->_manufacturer = $manufacturer;

        return $this;
    }
}

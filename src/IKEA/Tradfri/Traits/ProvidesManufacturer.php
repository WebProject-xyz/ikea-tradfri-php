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
     *
     * @return string
     */
    public function getManufacturer(): string
    {
        return $this->_manufacturer;
    }

    /**
     * Set Manufacturer.
     *
     * @param string $manufacturer
     *
     * @return static
     */
    public function setManufacturer(string $manufacturer)
    {
        $this->_manufacturer = $manufacturer;

        return $this;
    }
}

<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesName.
 */
trait ProvidesName
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Set Name.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->_name = $name;

        return $this;
    }
}

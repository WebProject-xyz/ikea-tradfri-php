<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesColor.
 */
trait ProvidesColor
{
    /**
     * @var string
     */
    protected $_color = '';

    /**
     * Get Color.
     *
     * @return string
     */
    public function getColor(): string
    {
        return \strtoupper($this->_color);
    }

    /**
     * Set Color.
     *
     * @param string $color
     *
     * @return static
     */
    public function setColor(string $color): self
    {
        $this->_color = $color;

        return $this;
    }
}

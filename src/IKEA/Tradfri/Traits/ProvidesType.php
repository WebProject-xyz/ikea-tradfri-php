<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

use IKEA\Tradfri\Device\Helper\Type;

/**
 * Trait ProvidesType.
 */
trait ProvidesType
{
    /**
     * @var string
     */
    protected $_type;

    /**
     * Get Type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * Set Type.
     *
     * @param string $type
     *
     * @return static
     */
    public function setType(string $type)
    {
        $this->_type = $type;

        return $this;
    }

    /**
     * Validate Type.
     *
     * @param string $type
     *
     * @throws \IKEA\Tradfri\Exception\TypeException
     *
     * @return bool
     */
    public function isValidType(string $type): bool
    {
        return (new Type())->isKnownDeviceType($type);
    }
}

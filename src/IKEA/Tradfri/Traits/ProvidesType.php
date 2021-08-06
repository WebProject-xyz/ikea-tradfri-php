<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

use IKEA\Tradfri\Device\Helper\Type;

trait ProvidesType
{
    protected string $_type;

    public function getType(): string
    {
        return $this->_type;
    }

    public function setType(string $type): self
    {
        $this->_type = $type;

        return $this;
    }

    /**
     * @throws \IKEA\Tradfri\Exception\TypeException
     */
    public function isValidType(string $type): bool
    {
        return (new Type())->isKnownDeviceType($type);
    }
}

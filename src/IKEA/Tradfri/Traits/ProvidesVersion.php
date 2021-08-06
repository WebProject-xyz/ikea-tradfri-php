<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesVersion
{
    protected string $_version;

    /**
     * Get Version.
     */
    public function getVersion(): string
    {
        return $this->_version;
    }

    public function setVersion(string $name): self
    {
        $this->_version = $name;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesVersion
{
    protected string $version;

    /**
     * Get Version.
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $name): self
    {
        $this->version = $name;

        return $this;
    }
}

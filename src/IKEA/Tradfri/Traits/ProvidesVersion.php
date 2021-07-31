<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesVersion.
 */
trait ProvidesVersion
{
    /**
     * @var string
     */
    protected $_version;

    /**
     * Get Version.
     */
    public function getVersion(): string
    {
        return $this->_version;
    }

    /**
     * Set Version.
     *
     * @return static
     */
    public function setVersion(string $name)
    {
        $this->_version = $name;

        return $this;
    }
}

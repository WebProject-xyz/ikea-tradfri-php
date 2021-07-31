<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Trait ProvidesService.
 */
trait ProvidesService
{
    /**
     * @var ServiceInterface
     */
    protected $_service;

    /**
     * Get Service.
     */
    public function getService() : ServiceInterface
    {
        return $this->_service;
    }

    /**
     * Set Service.
     *
     * @return static
     */
    public function setService(ServiceInterface $service)
    {
        $this->_service = $service;

        return $this;
    }

    /**
     * Has Service.
     */
    public function hasService() : bool
    {
        return null !== $this->_service;
    }
}

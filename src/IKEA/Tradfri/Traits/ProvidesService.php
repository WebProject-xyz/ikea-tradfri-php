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
     *
     * @return ServiceInterface
     */
    public function getService(): ServiceInterface
    {
        return $this->_service;
    }

    /**
     * Set Service.
     *
     * @param ServiceInterface $service
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
     *
     * @return bool
     */
    public function hasService(): bool
    {
        return null !== $this->_service;
    }
}

<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

use IKEA\Tradfri\Service\ServiceInterface;
use RuntimeException;

trait ProvidesService
{
    protected ?ServiceInterface $_service = null;

    public function getService(): ServiceInterface
    {
        if (!$this->hasService()) {
            throw new RuntimeException('Service missing');
        }

        return $this->_service;
    }

    public function setService(ServiceInterface $service): self
    {
        $this->_service = $service;

        return $this;
    }

    public function hasService(): bool
    {
        return null !== $this->_service;
    }
}

<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

use IKEA\Tradfri\Service\ServiceInterface;
use RuntimeException;

trait ProvidesService
{
    protected ?ServiceInterface $service = null;

    public function getService(): ServiceInterface
    {
        if (!$this->hasService()) {
            throw new RuntimeException('Service missing');
        }

        return $this->service;
    }

    public function setService(ServiceInterface $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function hasService(): bool
    {
        return null !== $this->service;
    }
}

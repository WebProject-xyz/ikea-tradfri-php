<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Traits;

use IKEA\Tradfri\Service\ServiceInterface;

trait ProvidesService
{
    protected ?ServiceInterface $service = null;

    public function getService(): ServiceInterface
    {
        if (!$this->hasService()) {
            throw new \RuntimeException('Service missing');
        }

        return $this->service;
    }

    public function setService(ServiceInterface $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @phpstan-assert-if-true !null $this->service
     */
    public function hasService(): bool
    {
        return null !== $this->service;
    }
}

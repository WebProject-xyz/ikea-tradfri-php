<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Traits;

trait ProvidesVersion
{
    protected string $version;

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $name): static
    {
        $this->version = $name;

        return $this;
    }
}

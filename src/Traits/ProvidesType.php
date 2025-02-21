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

use IKEA\Tradfri\Device\Helper\Type;

trait ProvidesType
{
    protected string $type;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @throws \IKEA\Tradfri\Exception\TypeException
     */
    public function isValidType(string $type): bool
    {
        return false === (new Type())->isUnknownDeviceType($type);
    }
}

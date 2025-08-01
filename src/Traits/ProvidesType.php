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

use IKEA\Tradfri\Values\DeviceType;

trait ProvidesType
{
    protected DeviceType $type;
    protected string $deviceType;

    public function getTypeEnum(): DeviceType
    {
        return $this->type;
    }

    public function getType(): string
    {
        return $this->deviceType;
    }

    public function setType(DeviceType|string $type): static
    {
        if (\is_string($type)) {
            $type = DeviceType::tryFromType($type, true);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @throws \IKEA\Tradfri\Exception\TypeException
     */
    public function isValidType(string $type): bool
    {
        return null !== DeviceType::tryFromType($type, false);
    }
}

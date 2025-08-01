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

namespace IKEA\Tradfri\Device\Feature;

use IKEA\Tradfri\Values\DeviceType;

interface DeviceInterface extends \JsonSerializable
{
    public function getId(): int;

    public function getName(): string;

    public function getType(): string;

    public function getTypeEnum(): DeviceType;

    /**
     * @return array<int, array<string, float|int|string>>|array<string, float|int|string>
     */
    public function jsonSerialize(): array;
}

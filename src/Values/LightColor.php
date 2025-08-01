<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Values;

enum LightColor: string
{
    case Warm = 'warm';
    case Normal = 'normal';
    case Cold = 'cold';

    /**
     * @var array<string, list<non-empty-string>>
     */
    private const array TEMPERATURES = [
        self::Warm->name   => ['33135', '27211'],
        self::Normal->name => ['30140', '26909'],
        self::Cold->name   => ['24930', '24684'],
    ];

    /**
     * @phpstan-return array<non-empty-string>
     */
    public function getTemperatureValues(): array
    {
        return self::TEMPERATURES[$this->name];
    }
}

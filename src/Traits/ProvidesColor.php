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

namespace IKEA\Tradfri\Traits;

trait ProvidesColor
{
    protected string $color = '';

    public function getColor(): string
    {
        return \mb_strtoupper($this->color);
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }
}

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

namespace IKEA\Tradfri\Dto\CoapResponse;

use Symfony\Component\Serializer\Annotation\SerializedName;

final readonly class BlindControlDto
{
    public function __construct(
        #[SerializedName(serializedName: 'ATTR_BLIND_CURRENT_POSITION')]
        private int $state = 0,
    ) {
    }

    public function getState(): int
    {
        return $this->state;
    }
}

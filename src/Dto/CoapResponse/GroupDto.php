<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Dto\CoapResponse;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GroupDto
{
    public function __construct(
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_ID')]
        private int $id,
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_NAME')]
        private ?string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}

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

namespace IKEA\Tradfri\Dto\CoapResponse;

use IKEA\Tradfri\Values\CoapDeviceAttribute;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeviceDto
{
    public function __construct(
        /** @var positive-int */
        #[Assert\NotBlank()]
        #[Assert\Positive()]
        #[SerializedName(serializedName: 'ATTR_ID')]
        private int $id,
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_NAME')]
        private ?string $name,
        #[Assert\NotBlank()]
        #[Assert\Valid()]
        #[SerializedName(serializedName: 'ATTR_DEVICE_INFO')]
        private DeviceInfoDto $deviceInfo,
        #[Assert\Valid()]
        #[SerializedName(serializedName: 'ATTR_LIGHT_CONTROL')]
        private ?LightControlDto $lightControl = null,
        /** @var list<\IKEA\Tradfri\Dto\CoapResponse\BlindControlDto> */
        #[Assert\Valid()]
        #[SerializedName(serializedName: 'ATTR_START_BLINDS')]
        private ?array $blindControlDto = null,
    ) {
    }

    /**
     * @return positive-int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name ?? 'none';
    }

    public function getDeviceInfo(): DeviceInfoDto
    {
        return $this->deviceInfo;
    }

    public function getLightControl(): ?LightControlDto
    {
        return $this->lightControl;
    }

    /**
     * @phpstan-return list<\IKEA\Tradfri\Dto\CoapResponse\BlindControlDto>|null
     */
    public function getBlindControlDto(): ?array
    {
        return $this->blindControlDto;
    }

    /**
     * @phpstan-return array<non-empty-string, non-empty-string>
     */
    public static function getAttributeReplacePatterns(): array
    {
        return CoapDeviceAttribute::getAttributeReplacePatterns();
    }
}

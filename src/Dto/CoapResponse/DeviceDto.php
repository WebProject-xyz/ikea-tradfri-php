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
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeviceDto
{
    public const array ATTR_MAP = [
        // - root node
        '"ATTR_ID"'                      => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_ID . '"#',
        '"ATTR_NAME"'                    => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_NAME . '"#',
        // - device info = node
        '"ATTR_DEVICE_INFO"'             => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_DEVICE_INFO . '"#',
        '"ATTR_DEVICE_MODEL_NUMBER"'     => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_DEVICE_MODEL_NUMBER . '"#',
        '"ATTR_DEVICE_FIRMWARE_VERSION"' => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_DEVICE_FIRMWARE_VERSION . '"#',
        '"ATTR_DEVICE_MANUFACTURER"'     => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_DEVICE_MANUFACTURER . '"#',
        // - light control = node
        '"ATTR_LIGHT_CONTROL"'       => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_LIGHT_CONTROL . '"#',
        '"ATTR_DEVICE_STATE"'        => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_DEVICE_STATE . '"#',
        '"ATTR_LIGHT_DIMMER"'        => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_LIGHT_DIMMER . '"#',
        '"ATTR_LIGHT_COLOR_HEX"'     => '#"' . \IKEA\Tradfri\Dto\CoapApiResponseDto::ATTR_LIGHT_COLOR_HEX . '"#',
    ];

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

    public function getName(): ?string
    {
        return $this->name;
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
}

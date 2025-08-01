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

final readonly class DeviceInfoDto
{
    public function __construct(
        #[SerializedName(serializedName: 'ATTR_DEVICE_MANUFACTURER')]
        private string $manufacturer,
        #[SerializedName(serializedName: 'ATTR_DEVICE_MODEL_NUMBER')]
        private string $type,
        #[SerializedName(serializedName: 'ATTR_DEVICE_FIRMWARE_VERSION')]
        private string $version,
    ) {
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getType(): string
    {
        return $this->type;
    }
}

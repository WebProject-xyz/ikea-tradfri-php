<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Dto\CoapResponse;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class DeviceInfoDto
{
    public function __construct(
        /**
         * @SerializedName(serializedName="ATTR_DEVICE_MANUFACTURER")
         */
        private string $manufacturer,
        /**
         * @SerializedName(serializedName="ATTR_DEVICE_MODEL_NUMBER")
         */
        private string $type,
        /**
         * @SerializedName(serializedName="ATTR_DEVICE_FIRMWARE_VERSION")
         */
        private string $version
    ) {
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}

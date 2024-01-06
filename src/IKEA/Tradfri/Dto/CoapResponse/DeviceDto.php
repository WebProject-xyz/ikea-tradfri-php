<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Dto\CoapResponse;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class DeviceDto
{
    public const ATTR_MAP = [
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
        /**
         * @SerializedName(serializedName="ATTR_ID")
         *
         * @Assert\NotBlank
         */
        private int $id,
        /**
         * @SerializedName(serializedName="ATTR_NAME")
         *
         * @Assert\NotBlank
         */
        private ?string $name,
        /**
         * @SerializedName(serializedName="ATTR_DEVICE_INFO")
         *
         * @Assert\Valid()
         *
         * @Assert\NotBlank()
         */
        private DeviceInfoDto $deviceInfo,
        /**
         * @SerializedName(serializedName="ATTR_LIGHT_CONTROL")
         *
         * @Assert\Valid()
         */
        private readonly ?LightControlDto $lightControl = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDeviceInfo(): DeviceInfoDto
    {
        return $this->deviceInfo;
    }

    public function setDeviceInfo(DeviceInfoDto $deviceInfo): void
    {
        $this->deviceInfo = $deviceInfo;
    }

    public function getLightControl(): ?LightControlDto
    {
        return $this->lightControl;
    }
}

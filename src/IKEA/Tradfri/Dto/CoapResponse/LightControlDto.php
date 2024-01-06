<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Dto\CoapResponse;

use Symfony\Component\Serializer\Annotation\SerializedName;

final readonly class LightControlDto
{
    public function __construct(
        #[SerializedName(serializedName: 'ATTR_DEVICE_STATE')]
        private int $state = 0,
        #[SerializedName(serializedName: 'ATTR_LIGHT_DIMMER')]
        private int $brightness = 0,
        #[SerializedName(serializedName: 'ATTR_LIGHT_COLOR_HEX')]
        private ?string $colorHex = null
    ) {
    }

    public function getBrightness(): int
    {
        return $this->brightness;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getColorHex(): ?string
    {
        return $this->colorHex;
    }
}

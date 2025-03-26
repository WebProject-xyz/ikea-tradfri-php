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

namespace IKEA\Tradfri\Dto;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Values\LightColor;

final readonly class CoapGatewayRequestPayloadDto implements \Stringable
{
    private const string FORMAT_DEVICE      = '-e \'{ "%s": [{ "%s": %s }] }\'';
    private const string FORMAT_GROUP       = '-e \'{ "%s": %s }\'';
    private const string FORMAT_LIGHT_COLOR = '-e \'{ "' . Keys::ATTR_LIGHT_CONTROL . '": [{ "' . Keys::ATTR_LIGHT_COLOR_X . '": %s, "' . Keys::ATTR_LIGHT_COLOR_Y . '": %s }] }\'';

    public function __construct(
        private string $target,
        private string $endpoint,
        private float|int|string $value,
    ) {
    }

    public function __toString(): string
    {
        return \sprintf(
            self::FORMAT_DEVICE,
            $this->target,
            $this->endpoint,
            $this->value,
        );
    }

    public static function fromValues(string $target, string $endpoint, float|int|string $value): self
    {
        return new self($target, $endpoint, $value);
    }

    public function toGroupFormat(): string
    {
        return \sprintf(
            self::FORMAT_GROUP,
            $this->target,
            $this->value,
        );
    }

    public static function formatToLightTemperature(LightColor $color): string
    {
        return \sprintf(
            self::FORMAT_LIGHT_COLOR,
            ...$color->getTemperatureValues(),
        );
    }
}

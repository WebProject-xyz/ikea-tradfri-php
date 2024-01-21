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

namespace IKEA\Tradfri\Dto;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Exception\RuntimeException;

final class CoapGatewayRequestPayloadDto implements \Stringable
{
    public const COLOR_WARM             = 'warm';
    public const COLOR_NORMAL           = 'normal';
    public const COLOR_COLD             = 'cold';
    private const COLORS                = [
        self::COLOR_COLD,
        self::COLOR_NORMAL,
        self::COLOR_WARM,
    ];
    private const FORMAT_DEVICE      = '-e \'{ "%s": [{ "%s": %s }] }\'';
    private const FORMAT_GROUP       = '-e \'{ "%s": %s }\'';
    private const FORMAT_LIGHT_COLOR = '-e \'{ "' . Keys::ATTR_LIGHT_CONTROL . '": [{ "' . Keys::ATTR_LIGHT_COLOR_X . '": %s, "' . Keys::ATTR_LIGHT_COLOR_Y . '": %s }] }\'';

    public function __construct(
        private readonly string $target,
        private readonly string $endpoint,
        private readonly float|int|string $value,
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

    /**
     * @phpstan-param value-of<self::COLORS>|string $color
     *
     * @throws RuntimeException
     */
    public static function formatToLightTemperature(string $color): string
    {
        if (!\in_array($color, self::COLORS, true)) {
            throw new RuntimeException('unknown color');
        }

        $values = match ($color) {
            self::COLOR_WARM   => ['33135', '27211'],
            self::COLOR_NORMAL => ['30140', '26909'],
            self::COLOR_COLD   => ['24930', '24684'],
        };

        return \sprintf(
            self::FORMAT_LIGHT_COLOR,
            ...$values,
        );
    }
}

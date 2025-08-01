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

namespace IKEA\Tradfri\Util;

use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class JsonIntTypeNormalizer
{
    /**
     * @phpstan-param non-empty-string $jsonString
     * @phpstan-param class-string<DeviceDto|GroupDto>     $targetClass
     *
     * @phpstan-return non-empty-string|string
     */
    public function __invoke(string $jsonString, string $targetClass): string
    {
        $patternMap = self::extractPatterns($targetClass);

        return \preg_replace($patternMap, \array_keys($patternMap), $jsonString, 1) ?? throw new \InvalidArgumentException('Failed to parse json string');
    }

    /**
     * @phpstan-param class-string<DeviceDto|GroupDto> $targetClass
     *
     * @phpstan-return array<string, string>
     */
    private static function extractPatterns(string $targetClass): array
    {
        Assert::classExists($targetClass);

        return $targetClass::getAttributeReplacePatterns();
    }
}

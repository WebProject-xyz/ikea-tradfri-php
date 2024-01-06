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

namespace IKEA\Tradfri\Util;

use Roave\BetterReflection\BetterReflection;

/**
 * @internal
 */
final class JsonIntTypeNormalizer
{
    /**
     * @psalm-param non-empty-string $jsonString
     * @psalm-param class-string     $targetClass
     *
     * @psalm-return non-empty-string
     */
    public function __invoke(string $jsonString, string $targetClass): string
    {
        $patternMap = $this->extractPatterns($targetClass);

        return \preg_replace($patternMap, \array_keys($patternMap), $jsonString, 1);
    }

    /**
     * @psalm-param class-string $targetClass
     *
     * @psalm-return array<string, string>
     */
    private function extractPatterns(string $targetClass): array
    {
        $classInfo = (new BetterReflection())
            ->reflector()
            ->reflectClass($targetClass);

        return $classInfo->getConstants()['ATTR_MAP']->getValue() ?? [];
    }
}

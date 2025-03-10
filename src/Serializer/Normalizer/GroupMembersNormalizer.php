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

namespace IKEA\Tradfri\Serializer\Normalizer;

use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class GroupMembersNormalizer implements DenormalizerInterface, LoggerAwareInterface, NormalizerInterface
{
    use \Psr\Log\LoggerAwareTrait;

    public function __construct(
        private readonly DenormalizerInterface&NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @phpstan-param array<string, mixed> $context
     *
     * @phpstan-return null|array<mixed>|\ArrayObject<array-key, mixed>|bool|float|int|string
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): null|array|\ArrayObject|bool|float|int|string
    {
        return $this->normalizer->normalize($data, $format, $context);
    }

    /**
     * @phpstan-param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $this->normalizer->supportsNormalization($data, $format, $context);
    }

    /**
     * @phpstan-param array<string, mixed> $context
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (\is_array($data)
            && \is_array($data[GroupDto::KEY_ATTR_GROUP_MEMBERS] ?? false)
            && \is_array($data[GroupDto::KEY_ATTR_GROUP_MEMBERS][GroupDto::KEY_ATTR_GROUP_LIGHTS] ?? false)
        ) {
            $groupMemberIds = $data[GroupDto::KEY_ATTR_GROUP_MEMBERS][GroupDto::KEY_ATTR_GROUP_LIGHTS][9003/* ATTR_GROUP_LIGHTS */] ?? null;
            if (!\is_array($groupMemberIds)) {
                $groupMemberIds = [];
            }

            $data[GroupDto::KEY_ATTR_GROUP_MEMBERS] = $groupMemberIds;
        }

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * @phpstan-param array<string, mixed> $context
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $this->normalizer->supportsDenormalization($data, $type, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return $this->normalizer->getSupportedTypes($format);
    }
}

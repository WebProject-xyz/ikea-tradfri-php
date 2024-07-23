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

namespace IKEA\Tradfri\Serializer\Normalizer;

use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CratedAtNormalizer implements DenormalizerInterface, LoggerAwareInterface, NormalizerInterface
{
    use \Psr\Log\LoggerAwareTrait;

    public function __construct(
        private readonly DenormalizerInterface&NormalizerInterface $normalizer,
    ) {
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): null|array|\ArrayObject|bool|float|int|string
    {
        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $this->normalizer->supportsNormalization($data, $format, $context);
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if ($data['ATTR_CREATED_AT'] ?? null) {
            $data['ATTR_CREATED_AT'] = \date('c', $data['ATTR_CREATED_AT']);
        }

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $this->normalizer->supportsDenormalization($data, $type, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return $this->normalizer->getSupportedTypes($format);
    }
}

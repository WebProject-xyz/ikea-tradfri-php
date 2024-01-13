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

final class ArrayNestingNormalizer implements DenormalizerInterface, LoggerAwareInterface, NormalizerInterface
{
    use \Psr\Log\LoggerAwareTrait;
    final public const ATTR_LIGHT_CONTROL = 'ATTR_LIGHT_CONTROL';
    final public const ATTR_DEVICE_STATE  = 'ATTR_DEVICE_STATE';

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
        if (\is_array($data)
            && \array_key_exists(self::ATTR_LIGHT_CONTROL, $data)
            && !empty($data[self::ATTR_LIGHT_CONTROL])
            && !\array_key_exists('ATTR_DEVICE_STATE', $data[self::ATTR_LIGHT_CONTROL])
        ) {
            // fix unneeded nesting in ATTR_LIGHT_CONTROL
            // array:4 [
            // ...
            //  "ATTR_LIGHT_CONTROL" => array:1 [
            //    0 => array:2 [
            //      "ATTR_LIGHT_DIMMER" => 22
            //      "ATTR_DEVICE_STATE" => 1
            //    ]
            //  ]
            // ]
            $firstItem = \current($data[self::ATTR_LIGHT_CONTROL]);
            if (\is_array($firstItem)) {
                $data[self::ATTR_LIGHT_CONTROL] = $firstItem;
            }
        }

        // todo:
        //        if (is_array($data) && isset($data[self::ATTR_LIGHT_CONTROL][self::ATTR_DEVICE_STATE])) {
        //            $data[self::ATTR_LIGHT_CONTROL][self::ATTR_DEVICE_STATE] = (bool) $data[self::ATTR_LIGHT_CONTROL][self::ATTR_DEVICE_STATE];
        //        }

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

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

namespace IKEA\Tradfri\Serializer;

use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

final class JsonDeviceDataSerializer implements \Symfony\Component\Serializer\SerializerInterface
{
    final public const FORMAT = JsonEncoder::FORMAT;
    private Serializer $serializer;

    public function __construct()
    {
        $this->initSerializer();
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): array|DeviceDto|GroupDto
    {
        return $this->serializer->deserialize(
            $data,
            $type,
            $format,
            [
                JsonEncode::OPTIONS                        => \JSON_PRETTY_PRINT,
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ] + $context,
        );
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize(
            $data,
            $format,
            [
                JsonEncode::OPTIONS                        => \JSON_PRETTY_PRINT,
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ] + $context,
        );
    }

    private function initSerializer(): void
    {
        if (null !== ($this->serializer ?? null)) {
            return;
        }

        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $denormalizer         = new PropertyNormalizer(
            $classMetadataFactory,
            new MetadataAwareNameConverter($classMetadataFactory),
        );
        $serializer = new Serializer(
            [
                $denormalizer,
                new \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer(),
                new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'c']),
            ],
        );
        $denormalizer->setSerializer($serializer);

        $this->serializer = new Serializer(
            [
                new Normalizer\ArrayNestingNormalizer(
                    new \IKEA\Tradfri\Serializer\Normalizer\CratedAtNormalizer(
                        new \IKEA\Tradfri\Serializer\Normalizer\GroupMembersNormalizer(
                            $denormalizer,
                        ),
                    ),
                ),
            ],
            ['json' => new JsonEncoder()],
        );
    }
}

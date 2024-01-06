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
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use const JSON_PRETTY_PRINT;

final class JsonDeviceDataSerializer
{
    private Serializer $serializer;

    public function __construct()
    {
        $this->initSerializer();
    }

    public function deserialize(string $json): DeviceDto
    {
        return $this->serializer->deserialize(
            $json,
            DeviceDto::class,
            JsonEncoder::FORMAT,
            [
                JsonEncode::OPTIONS                        => JSON_PRETTY_PRINT,
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        );
    }

    public function serialize(DeviceDto $deviceDto): string
    {
        return $this->serializer->serialize(
            $deviceDto,
            JsonEncoder::FORMAT,
            [
                JsonEncode::OPTIONS                        => JSON_PRETTY_PRINT,
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
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
        $serializer = new Serializer([$denormalizer, new \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer()]);
        $denormalizer->setSerializer($serializer);

        $denormalizer = new Normalizer\ArrayNestingNormalizer($denormalizer);

        $serializer = new Serializer(
            [
                $denormalizer,
            ],
            ['json' => new JsonEncoder()],
        );
        $this->serializer = $serializer;
    }
}

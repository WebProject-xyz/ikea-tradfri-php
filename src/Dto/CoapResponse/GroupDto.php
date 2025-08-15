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

namespace IKEA\Tradfri\Dto\CoapResponse;

use IKEA\Tradfri\Values\CoapDeviceGroupAttribute;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GroupDto
{
    public const string KEY_ATTR_GROUP_MEMBERS ='ATTR_GROUP_MEMBERS';
    public const string KEY_ATTR_GROUP_LIGHTS  ='ATTR_GROUP_LIGHTS';

    /**
     * {
     *  '9001/ATTR_NAME':'Küche 2',
     *  '9002/ATTR_CREATED_AT':1547309682,
     *  '9003/ATTR_ID':177817,
     *  '5850/ATTR_DEVICE_STATE':0,
     *  '5851/ATTR_LIGHT_DIMMER':0,
     *  '9039/ATTR_MOOD':0,
     *  '9108/ATTR_?':0,
     *  '9018/MEMBERS':{
     *    '15002/ATTR_HS_LINK|ATTR_GROUP_LIGHTS':{
     *      '9003/ATTR_ID':[65588]
     *     }
     *   }
     *  }.
     */
    public function __construct(
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_ID')]
        private int $id,
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_NAME')]
        private string $name,
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_CREATED_AT')]
        private \DateTimeImmutable $createdAt,
        /**
         * @var list<positive-int>
         */
        #[Assert\All([new Assert\NotBlank(), new Assert\Positive()])]
        #[SerializedName(serializedName: 'ATTR_GROUP_MEMBERS')]
        private array $members,
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_DEVICE_STATE')]
        private bool $state,
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_LIGHT_DIMMER')]
        private float $dimmerLevel,
        #[Assert\NotBlank()]
        #[SerializedName(serializedName: 'ATTR_MOOD')]
        private ?int $mood,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @phpstan-return list<positive-int>
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    public function getState(): bool
    {
        return $this->state;
    }

    public function getDimmerLevel(): float
    {
        return $this->dimmerLevel;
    }

    public function getMood(): ?int
    {
        return $this->mood;
    }

    /**
     * @phpstan-return array<non-empty-string, non-empty-string>
     */
    public static function getAttributeReplacePatterns(): array
    {
        return CoapDeviceGroupAttribute::getAttributeReplacePatterns();
    }
}

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

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Dto\CoapResponse\GroupDto as GroupResponseDto;
use IKEA\Tradfri\Group\DeviceGroup;
use IKEA\Tradfri\Service\ServiceInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @template-implements MapperInterface<DeviceGroup, GroupResponseDto|DeviceDto, Groups>
 */
final class GroupData implements LoggerAwareInterface, MapperInterface
{
    use LoggerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function map(
        ServiceInterface $service,
        iterable $dataItems,
        AbstractCollection $collection,
    ): AbstractCollection {
        foreach ($dataItems as $groupDto) {
            if (!$groupDto instanceof GroupResponseDto) {
                $this->logger?->warning('invalid device detected - skipped', ['device' => \serialize($groupDto), 'type' => \get_debug_type($groupDto)]);
                continue;
            }

            $group = new DeviceGroup(
                $groupDto->getId(),
                $service,
            );
            $group->setName($groupDto->getName());
            $group->setDeviceIds($groupDto->getMembers());
            $group->setBrightnessLevel($groupDto->getDimmerLevel());
            $group->setState($groupDto->getState());

            $collection->addGroup($group);
        }

        return $collection;
    }
}

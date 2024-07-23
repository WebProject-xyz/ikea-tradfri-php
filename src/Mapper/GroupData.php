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

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Group\LightGroup;
use IKEA\Tradfri\Service\ServiceInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @template T of Groups
 *
 * @template-implements MapperInterface<T>
 */
final class GroupData implements LoggerAwareInterface, MapperInterface
{
    use LoggerAwareTrait;

    /**
     * @phpstan-param Groups $collection
     *
     * @phpstan-return Groups
     */
    public function map(
        ServiceInterface $service,
        iterable $dataItems,
        AbstractCollection $collection,
    ): AbstractCollection {
        foreach ($dataItems as $groupDto) {
            if (!$groupDto instanceof \IKEA\Tradfri\Dto\CoapResponse\GroupDto) {
                $this->logger?->warning('invalid device detected - skipped', ['device' => \serialize($groupDto), 'type' => \get_debug_type($groupDto)]);
                continue;
            }

            $group = new LightGroup(
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

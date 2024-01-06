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
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Validator\Group\Data as GroupDataValidator;

/**
 * @template T of Groups
 *
 * @template-implements MapperInterface<T>
 */
final class GroupData implements MapperInterface
{
    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @phpstan-param T $collection
     *
     * @phpstan-return T
     */
    public function map(
        ServiceInterface $service,
        array $dataItems,
        AbstractCollection $collection,
    ): AbstractCollection {
        foreach ($dataItems as $device) {
            if (false === $this->isValidData($device)) {
                continue;
            }

            $group = new Light(
                (int) $device->{Keys::ATTR_ID},
                $service,
            );
            $group->setName($device->{Keys::ATTR_NAME});
            $group->setDeviceIds(
                $device
                    ->{Keys::ATTR_GROUP_INFO}
                    ->{Keys::ATTR_GROUP_LIGHTS}
                    ->{Keys::ATTR_ID},
            );
            $group->setBrightness($device->{Keys::ATTR_LIGHT_DIMMER});
            $group->setState((bool) $device->{Keys::ATTR_LIGHT_STATE});

            $collection->addGroup($group);
        }

        return $collection;
    }

    /**
     * @param null|\stdClass $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function isValidData($device): bool
    {
        return (new GroupDataValidator())->isValid($device);
    }
}

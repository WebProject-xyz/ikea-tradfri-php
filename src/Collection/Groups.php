<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Group\DeviceGroup;

/**
 * @extends AbstractCollection<DeviceGroup>
 */
final class Groups extends AbstractCollection
{
    /**
     * @phpstan-param DeviceGroup $group
     */
    public function addGroup(DeviceGroup $group): void
    {
        $this->set($group->getId(), $group);
    }
}

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

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Group\Device;

/**
 * @extends AbstractCollection<\IKEA\Tradfri\Group\Device>
 */
final class Groups extends AbstractCollection
{
    public function addGroup(Device $group): self
    {
        $this->set($group->getId(), $group);

        return $this;
    }
}

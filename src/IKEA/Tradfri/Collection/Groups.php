<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Group\Device;
use IKEA\Tradfri\Group\Light;

/**
 * @extends AbstractCollection<string, \IKEA\Tradfri\Device\Device>
 */
class Groups extends AbstractCollection
{
    public function first(): Light
    {
        return parent::first();
    }

    public function addGroup(Device $group): self
    {
        $this->set($group->getId(), $group);

        return $this;
    }
}

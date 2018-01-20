<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Group\Device;
use IKEA\Tradfri\Group\Light;

/**
 * Class Lightbulbs.
 */
class Groups extends AbstractCollection
{
    /**
     * Get first light.
     *
     * @return Light
     */
    public function first(): Light
    {
        return parent::first();
    }

    /**
     * Add Group.
     *
     * @param Device $group
     *
     * @return $this
     */
    public function addGroup(Device $group): self
    {
        $this->set($group->getId(), $group);

        return $this;
    }
}

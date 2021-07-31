<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Collection;

use IKEA\Tradfri\Group\Device;
use IKEA\Tradfri\Group\Light;

/**
 * Class LightBulbs.
 */
class Groups extends AbstractCollection
{
    /**
     * Get first light.
     */
    public function first() : Light
    {
        return parent::first();
    }

    /**
     * Add Group.
     *
     * @return $this
     */
    public function addGroup(Device $group) : self
    {
        $this->set($group->getId(), $group);

        return $this;
    }
}

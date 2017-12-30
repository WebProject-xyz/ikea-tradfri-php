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
    public function addGroup(Device $group)
    {
        $this->set($group->getId(), $group);

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // @TODO: Implement jsonSerialize() method.
    }
}

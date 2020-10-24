<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Validator\Group\Data as GroupDataValidator;

/**
 * Class GroupData.
 */
class GroupData extends Mapper
{
    /**
     * Map given data to models.
     *
     * @param Api|ServiceInterface $service
     * @param array                $groupDataItems
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return AbstractCollection|Groups
     */
    public function map(
        ServiceInterface $service,
        array $groupDataItems
    ): AbstractCollection {
        $collection = new Groups();
        foreach ($groupDataItems as $device) {
            if (false === $this->_isValidData($device)) {
                continue;
            }

            $group = new Light(
                (int) $device->{Keys::ATTR_ID},
                $service
            );
            $group->setName($device->{Keys::ATTR_NAME});
            $group->setDeviceIds(
                $device
                    ->{Keys::ATTR_GROUP_INFO}
                    ->{Keys::ATTR_GROUP_LIGHTS}
                    ->{Keys::ATTR_ID}
            );
            $group->setBrightness($device->{Keys::ATTR_LIGHT_DIMMER});
            $group->setState((bool) $device->{Keys::ATTR_LIGHT_STATE});

            $collection->addGroup($group);
        }

        return $collection;
    }

    /**
     * Validate device data from api.
     *
     * @param null|\stdClass $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    protected function _isValidData($device): bool
    {
        $validator = new GroupDataValidator();

        return $validator->isValid($device);
    }
}

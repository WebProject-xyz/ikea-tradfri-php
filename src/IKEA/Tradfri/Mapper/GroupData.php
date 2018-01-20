<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Helper\CoapCommandKeys;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class GroupData.
 */
class GroupData extends Mapper
{
    /**
     * Map given data to models.
     *
     * @param ServiceInterface|Api $service
     * @param array                $groupDataItems
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Groups|AbstractCollection
     */
    public function map(ServiceInterface $service,
        array $groupDataItems): AbstractCollection
    {
        $collection = new Groups();
        foreach ($groupDataItems as $device) {
            if (false === $this->_isValidData($device)) {
                continue;
            }

            $group = new Light(
                (int) $device->{CoapCommandKeys::KEY_ID}, $service
            );
            $group->setName($device->{CoapCommandKeys::KEY_NAME});
            $group->setDeviceIds(
                $device
                    ->{CoapCommandKeys::KEY_GROUPS_DATA}
                    ->{CoapCommandKeys::KEY_GET_LIGHTS}
                    ->{CoapCommandKeys::KEY_ID}
            );
            $group->setBrightness($device->{CoapCommandKeys::KEY_DIMMER});
            $group->setState((bool) $device->{CoapCommandKeys::KEY_ONOFF});

            $collection->addGroup($group);
        }

        return $collection;
    }

    /**
     * Validate device data from api.
     *
     * @param $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    protected function _isValidData($device): bool
    {
        $validator = new \IKEA\Tradfri\Validator\Group\Data();

        return $validator->isValid($device);
    }
}

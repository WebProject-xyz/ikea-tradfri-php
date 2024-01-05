<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Validator\Group;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Validator\ValidatorInterface;
use stdClass;
use Throwable;
use function is_object;

class Data implements ValidatorInterface
{
    /**
     * @var array<string>
     */
    protected static array $mustHaves = [
        Keys::ATTR_ID,
        Keys::ATTR_NAME,
        Keys::ATTR_GROUP_INFO,
    ];

    /**
     * @param stdClass|null $data
     *
     * @throws RuntimeException
     */
    public function isValid($data): bool
    {
        try {
            $this->_validateDeviceMustHaves($data);

            $isValid = true;

            $groupData = $data->{Keys::ATTR_GROUP_INFO};
            if (!property_exists(
                $groupData,
                Keys::ATTR_GROUP_LIGHTS
            )) {
                throw new RuntimeException('attribute missing (' . Keys::ATTR_GROUP_LIGHTS . ')');
            }
            $lightData = $groupData->{Keys::ATTR_GROUP_LIGHTS};

            if (!isset($lightData->{Keys::ATTR_ID})) {
                throw new RuntimeException('attribute group data is not an array (' . Keys::ATTR_GROUP_INFO . '->' . Keys::ATTR_GROUP_LIGHTS . '->' . Keys::ATTR_ID . ')');
            }
        } catch (Throwable) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Validate must have properties.
     *
     * @param stdClass|null $device
     *
     * @throws RuntimeException
     */
    protected function _validateDeviceMustHaves($device): bool
    {
        if (false === is_object($device)) {
            throw new TypeException('device is no object');
        }
        foreach (self::$mustHaves as $mustHave) {
            if (!property_exists($device, $mustHave)) {
                throw new RuntimeException('attribute missing (' . $mustHave . ')');
            }
        }

        return true;
    }
}

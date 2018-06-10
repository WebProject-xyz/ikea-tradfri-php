<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Validator\Group;

use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Helper\CoapCommandKeys;
use IKEA\Tradfri\Validator\ValidatorInterface;

/**
 * Class Data.
 */
class Data implements ValidatorInterface
{
    /**
     * @var array
     */
    protected static $_mustHaves = [
        CoapCommandKeys::KEY_ID,
        CoapCommandKeys::KEY_NAME,
        CoapCommandKeys::KEY_GROUPS_DATA,
    ];

    /**
     * Is valid.
     *
     * @param $data
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function isValid($data): bool
    {
        try {
            $this->_validateDeviceMustHaves($data);

            $isValid = true;

            $groupData = $data->{CoapCommandKeys::KEY_GROUPS_DATA};
            if (!\property_exists(
                $groupData,
                CoapCommandKeys::KEY_GET_LIGHTS
            )) {
                throw new RuntimeException(
                    'attribute missing ('.CoapCommandKeys::KEY_GET_LIGHTS.')'
                );
            }
            $lightData = $groupData->{CoapCommandKeys::KEY_GET_LIGHTS};

            if (!isset($lightData->{CoapCommandKeys::KEY_ID})) {
                throw new RuntimeException(
                    'attribute group data is not an array ('.
                    CoapCommandKeys::KEY_GROUPS_DATA.
                    '->'.CoapCommandKeys::KEY_GET_LIGHTS.
                    '->'.CoapCommandKeys::KEY_ID.')'
                );
            }
        } catch (\Throwable $exception) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Validate must have properties.
     *
     * @param \stdClass $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    protected function _validateDeviceMustHaves($device): bool
    {
        if (false === \is_object($device)) {
            throw new TypeException('device is no object');
        }
        foreach (self::$_mustHaves as $mustHave) {
            if (!\property_exists($device, $mustHave)) {
                throw new RuntimeException(
                    'attribute missing ('.$mustHave.')'
                );
            }
        }

        return true;
    }
}

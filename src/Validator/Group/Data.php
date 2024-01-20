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

namespace IKEA\Tradfri\Validator\Group;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Validator\ValidatorInterface;

final class Data implements ValidatorInterface
{
    /**
     * @var list<string>
     */
    private static array $mustHaves = [
        Keys::ATTR_ID,
        Keys::ATTR_NAME,
        Keys::ATTR_GROUP_MEMBERS,
    ];

    /**
     * @throws RuntimeException
     */
    public function isValid(mixed $data): bool
    {
        try {
            $this->_validateDeviceMustHaves($data);

            $isValid = true;

            $groupData = $data->{Keys::ATTR_GROUP_MEMBERS};
            if (!\property_exists(
                $groupData,
                Keys::ATTR_GROUP_LIGHTS,
            )) {
                throw new RuntimeException('attribute missing (' . Keys::ATTR_GROUP_LIGHTS . ')');
            }

            $lightData = $groupData->{Keys::ATTR_GROUP_LIGHTS};

            if (!isset($lightData->{Keys::ATTR_ID})) {
                throw new RuntimeException('attribute group data is not an array (' . Keys::ATTR_GROUP_MEMBERS . '->' . Keys::ATTR_GROUP_LIGHTS . '->' . Keys::ATTR_ID . ')');
            }
        } catch (\Throwable) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Validate must have properties.
     *
     * @throws RuntimeException
     */
    private function _validateDeviceMustHaves(?\stdClass $device): void
    {
        if (false === \is_object($device)) {
            throw new TypeException('device is no object');
        }

        foreach (self::$mustHaves as $mustHave) {
            if (!\property_exists($device, $mustHave)) {
                throw new RuntimeException('attribute missing (' . $mustHave . ')');
            }
        }
    }
}

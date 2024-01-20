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

namespace IKEA\Tradfri\Validator\Device;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Validator\ValidatorInterface;

final class Data implements ValidatorInterface
{
    /**
     * @param null|mixed|\stdClass $device
     */
    public function isValid(mixed $device): bool
    {
        $isValid = true;

        try {
            if (false === \is_object($device)) {
                throw new TypeException('device is no object');
            }

            $this->_hasIdField($device);

            $this->_hasDataField($device);

            $data = $this->_getData($device);

            $this->_hasDataType($data);

            $this->_hasDataManufacturer($data);

            $this->_hasDataVersion($data);
        } catch (\Throwable) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * @throws RuntimeException
     */
    private function _hasIdField(\stdClass $device): void
    {
        if (!\property_exists($device, Keys::ATTR_ID)) {
            throw new RuntimeException('attribute missing (' . Keys::ATTR_ID . ')');
        }
    }

    /**
     * @throws RuntimeException
     */
    private function _hasDataField(\stdClass $device): void
    {
        if (!\property_exists($device, Keys::ATTR_DEVICE_INFO)) {
            throw new RuntimeException('attribute missing (' . Keys::ATTR_DEVICE_INFO . ')');
        }
    }

    /**
     * @throws RuntimeException
     */
    private function _hasDataType(\stdClass $data): void
    {
        if (false === \property_exists($data, Keys::ATTR_DEVICE_MODEL_NUMBER)) {
            throw new RuntimeException('attribute missing type key');
        }
    }

    /**
     * @throws RuntimeException
     */
    private function _hasDataManufacturer(\stdClass $data): void
    {
        if (!\property_exists($data, Keys::ATTR_DEVICE_MANUFACTURER)) {
            throw new RuntimeException('attribute missing type manufacturer');
        }
    }

    /**
     * @throws RuntimeException
     */
    private function _hasDataVersion(\stdClass $data): void
    {
        if (!\property_exists($data, Keys::ATTR_DEVICE_FIRMWARE_VERSION)) {
            throw new RuntimeException('attribute missing type version');
        }
    }

    private function _getData(\stdClass $device): \stdClass
    {
        return $device->{Keys::ATTR_DEVICE_INFO};
    }
}

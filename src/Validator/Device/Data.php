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

/**
 * @deprecated will be gone in v1.0.0
 */
final class Data implements ValidatorInterface
{
    /**
     * @param null|mixed|\stdClass $device
     *
     * @throws RuntimeException
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
        } catch (RuntimeException|\Throwable) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Check id attribute.
     *
     * @throws RuntimeException
     */
    private function _hasIdField(\stdClass $device): bool
    {
        if (!\property_exists($device, Keys::ATTR_ID)) {
            throw new RuntimeException('attribute missing (' . Keys::ATTR_ID . ')');
        }

        return true;
    }

    /**
     * Check data attribute.
     *
     * @throws RuntimeException
     */
    private function _hasDataField(\stdClass $device): bool
    {
        if (!\property_exists($device, Keys::ATTR_DEVICE_INFO)) {
            throw new RuntimeException('attribute missing (' . Keys::ATTR_DEVICE_INFO . ')');
        }

        return true;
    }

    /**
     * Check data type attribute.
     *
     * @throws RuntimeException
     */
    private function _hasDataType(\stdClass $data): bool
    {
        if (false === \property_exists($data, Keys::ATTR_DEVICE_INFO_TYPE)) {
            throw new RuntimeException('attribute missing type key');
        }

        return true;
    }

    /**
     * Check manufacturer attribute.
     *
     * @throws RuntimeException
     */
    private function _hasDataManufacturer(\stdClass $data): bool
    {
        if (!\property_exists($data, Keys::ATTR_DEVICE_INFO_MANUFACTURER)) {
            throw new RuntimeException('attribute missing type manufacturer');
        }

        return true;
    }

    /**
     * Check version attribute.
     *
     * @throws RuntimeException
     */
    private function _hasDataVersion(\stdClass $data): bool
    {
        if (!\property_exists($data, Keys::ATTR_DEVICE_VERSION)) {
            throw new RuntimeException('attribute missing type version');
        }

        return true;
    }

    /**
     * Get Data.
     */
    private function _getData(\stdClass $device)
    {
        return $device->{Keys::ATTR_DEVICE_INFO};
    }
}

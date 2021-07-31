<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Validator\Device;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Validator\ValidatorInterface;

/**
 * Class Data.
 */
class Data implements ValidatorInterface
{
    /**
     * Is valid.
     *
     * @param \stdClass|null $device
     *
     * @throws RuntimeException
     */
    public function isValid($device): bool
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
        } catch (RuntimeException $exception) {
            $isValid = false;
        } catch (\Throwable $exception) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Check id attribute.
     *
     * @throws RuntimeException
     */
    protected function _hasIdField(\stdClass $device): bool
    {
        if (!\property_exists($device, Keys::ATTR_ID)) {
            throw new RuntimeException('attribute missing ('.Keys::ATTR_ID.')');
        }

        return true;
    }

    /**
     * Check data attribute.
     *
     * @throws RuntimeException
     */
    protected function _hasDataField(\stdClass $device): bool
    {
        if (!\property_exists($device, Keys::ATTR_DEVICE_INFO)) {
            throw new RuntimeException('attribute missing ('.Keys::ATTR_DEVICE_INFO.')');
        }

        return true;
    }

    /**
     * Check data type attribute.
     *
     * @throws RuntimeException
     */
    protected function _hasDataType(\stdClass $data): bool
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
    protected function _hasDataManufacturer(\stdClass $data): bool
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
    protected function _hasDataVersion(\stdClass $data): bool
    {
        if (!\property_exists($data, Keys::ATTR_DEVICE_VERSION)) {
            throw new RuntimeException('attribute missing type version');
        }

        return true;
    }

    /**
     * Get Data.
     *
     * @return mixed
     */
    protected function _getData(\stdClass $device)
    {
        return $device->{Keys::ATTR_DEVICE_INFO};
    }
}

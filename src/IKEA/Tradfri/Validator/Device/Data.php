<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Validator\Device;

use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Helper\CoapCommandKeys;
use IKEA\Tradfri\Validator\ValidatorInterface;

/**
 * Class Data
 */
class Data implements ValidatorInterface
{
    /**
     * Is valid.
     *
     * @param $device
     *
     * @return bool
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

            $data = $device->{CoapCommandKeys::KEY_DATA};
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
     * @param $device
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    protected function _hasIdField(\stdClass $device): bool
    {
        if (!\property_exists($device, CoapCommandKeys::KEY_ID)) {
            throw new RuntimeException(
                'attribute missing ('.CoapCommandKeys::KEY_ID
            );
        }

        return true;
    }

    /**
     * @param $device
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    protected function _hasDataField(\stdClass $device): bool
    {
        if (!\property_exists($device, CoapCommandKeys::KEY_DATA)) {
            throw new RuntimeException(
                'attribute missing ('.CoapCommandKeys::KEY_DATA
            );
        }

        return true;
    }

    /**
     * @param $data
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    protected function _hasDataType(\stdClass $data): bool
    {
        if (false === \property_exists($data, CoapCommandKeys::KEY_TYPE)) {
            throw new RuntimeException('attribute missing type key');
        }

        return true;
    }

    /**
     * @param $data
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    protected function _hasDataManufacturer(\stdClass $data): bool
    {
        if (!\property_exists($data, CoapCommandKeys::KEY_MANUFACTURER)) {
            throw new RuntimeException(
                'attribute missing type manufacturer'
            );
        }

        return true;
    }

    /**
     * @param $data
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    protected function _hasDataVersion(\stdClass $data): bool
    {
        if (!\property_exists($data, CoapCommandKeys::KEY_VERSION)) {
            throw new RuntimeException(
                'attribute missing type version'
            );
        }

        return true;
    }
}
